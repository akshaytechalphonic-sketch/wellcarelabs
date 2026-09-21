<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\LabTest;
use App\Models\CustomPackage;

class CustomPackageController extends Controller
{
    protected $sessionKey = 'cart_items';

    /**
     * Show all available tests for custom package selection
     */
    public function index()
    {
        $tests = LabTest::where('status', 'Published')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('custom_package', compact('tests'));
    }

    /**
     * Store the custom package and/or add tests to cart
     *
     * Accepts payload:
     * {
     *   package_name?: string,
     *   tests: [ int | { id, name?, original_price?, discount_percent?, discounted_price?, position? } , ... ],
     *   replace_cart?: bool,
     *   as_package?: bool
     * }
     */
    public function store(Request $request)
    {
        // We validate minimal shape; detailed validation follows below
        $validated = $request->validate([
            'package_name' => 'sometimes|required_if:as_package,true|string|max:255',
            'tests' => 'required|array|min:1',
            'replace_cart' => 'sometimes|boolean',
            'as_package' => 'sometimes|boolean',
        ]);

        $rawTests = $request->input('tests', []);
        if (!is_array($rawTests) || count($rawTests) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid tests payload'
            ], 422);
        }

        // Helper: discount percent for position (1-based)
        $getDiscountPercentForPosition = function(int $pos) {
            if ($pos <= 1) return 0;
            if ($pos === 2) return 20;
            if ($pos === 3) return 30;
            if ($pos === 4) return 40;
            return 50;
        };

        DB::beginTransaction();

        try {
            // Optionally clear existing cart if requested
            if (!empty($validated['replace_cart'])) {
                session()->forget($this->sessionKey);
            }

            // Normalize incoming tests into an ordered array of arrays:
            // [ ['id'=>int, 'name'=>string|null, 'original_price'=>float|null, 'position'=>int], ... ]
            $normalized = [];
            $position = 0;
            $idsToFetch = [];

            foreach ($rawTests as $entry) {
                $position++;

                // If the entry is scalar (id)
                if (is_int($entry) || (is_string($entry) && ctype_digit((string)$entry))) {
                    $id = (int)$entry;
                    $normalized[] = [
                        'id' => $id,
                        'name' => null,
                        'original_price' => null,
                        'provided' => false,
                        'position' => $position,
                    ];
                    $idsToFetch[] = $id;
                    continue;
                }

                // If the entry is an object/array with details
                if (is_array($entry) || is_object($entry)) {
                    $arr = (array) $entry;
                    $id = isset($arr['id']) ? (int)$arr['id'] : null;
                    if (!$id) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Each test entry must include an id.'
                        ], 422);
                    }
                    $normalized[] = [
                        'id' => $id,
                        'name' => isset($arr['name']) ? (string)$arr['name'] : null,
                        'original_price' => isset($arr['original_price']) ? floatval($arr['original_price']) : null,
                        'provided_discount_percent' => isset($arr['discount_percent']) ? floatval($arr['discount_percent']) : null,
                        'provided_discounted_price' => isset($arr['discounted_price']) ? floatval($arr['discounted_price']) : null,
                        'provided' => true,
                        'position' => isset($arr['position']) ? intval($arr['position']) : $position,
                    ];
                    $idsToFetch[] = $id;
                    continue;
                }

                // Unknown entry type
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid test entry type'
                ], 422);
            }

            // Fetch DB records for the involved lab tests to get authoritative names/prices if needed
            $uniqueIds = array_values(array_unique($idsToFetch));
            $testsFromDb = LabTest::whereIn('id', $uniqueIds)->get()->keyBy('id');

            // Build final tests array with server-side computed discounts (by order)
            $finalTests = [];
            foreach ($normalized as $i => $n) {
                $pos = $n['position'] ?? ($i + 1);
                $discountPct = $getDiscountPercentForPosition($pos);

                $dbTest = $testsFromDb->get($n['id']);
                if (!$dbTest) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Test with id {$n['id']} not found"
                    ], 422);
                }

                // Determine authoritative original price:
                // priority: provided original_price (if present and >0) -> db discounted_price/price/mrp
                $authoritativeOriginalPrice = null;
                if (!is_null($n['original_price']) && $n['original_price'] !== '') {
                    $authoritativeOriginalPrice = floatval($n['original_price']);
                } else {
                    // prefer discounted_price column if exists, else price, else mrp
                    $authoritativeOriginalPrice = (float) ($dbTest->discounted_price ?? $dbTest->price ?? $dbTest->mrp ?? 0);
                }

                // Compute discounted price using server-side discountPercent
                $discountedPrice = round($authoritativeOriginalPrice * (1 - ($discountPct / 100)));

                $finalTests[] = [
                    'id' => (int) $n['id'],
                    'name' => $n['name'] ?? $dbTest->test_name,
                    'original_price' => $authoritativeOriginalPrice,
                    'discount_percent' => $discountPct,
                    'discounted_price' => $discountedPrice,
                    'position' => $pos,
                ];
            }

            // If as_package => create a CustomPackage and attach tests (with pivot details)
            if (!empty($validated['as_package'])) {
                $packageName = $validated['package_name'] ?? 'Customize Package';

                // Create package
                $package = CustomPackage::create([
                    'package_name' => $packageName,
                    'user_id' => auth()->id() ?? null,
                ]);

                // Prepare attach payload for pivot: [ test_id => [pivot_fields], ... ]
                $attachPayload = [];
                $totalPrice = 0.0;
                foreach ($finalTests as $t) {
                    $attachPayload[$t['id']] = [
                        'original_price' => $t['original_price'],
                        'discount_percent' => $t['discount_percent'],
                        'discounted_price' => $t['discounted_price'],
                        'position' => $t['position'],
                    ];
                    $totalPrice += (float) $t['discounted_price'];
                }

                // If model provides attachTests helper, call it (compatibility)
                if (method_exists($package, 'attachTests')) {
                    $package->attachTests($attachPayload);
                } else if (method_exists($package, 'tests')) {
                    $package->tests()->attach($attachPayload);
                } else {
                    if (isset($package->details)) {
                        $package->details = json_encode($finalTests);
                        $package->save();
                    } else {
                        $package->meta = json_encode($finalTests);
                        $package->save();
                    }
                }

                if (isset($package->total_price)) {
                    $package->total_price = $totalPrice;
                    $package->save();
                }

                // Add package item to session cart
                $cart = session()->get($this->sessionKey, []);
                $key = 'custom_' . $package->id;

                $cart[$key] = [
                    'key' => $key,
                    'item_type' => 'custom_package',
                    'item_id' => $package->id,
                    'name' => $package->package_name,
                    'price' => (float) $totalPrice,
                    'quantity' => 1,
                    // include details so frontend/cart can render contained tests
                    'details' => $finalTests,
                    // MARKER: this came from Customize Package flow
                    'from_custom_package' => true,
                ];

                session()->put($this->sessionKey, $cart);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Custom package created and added to cart',
                    'custom_package_id' => $package->id,
                    'cart_key' => $key,
                ], 201);
            }

            // Otherwise: add selected tests as individual items into the cart
            $cart = session()->get($this->sessionKey, []);
            foreach ($finalTests as $t) {
                // Use deterministic key so duplicates are replaced
                $key = 'test_' . $t['id'];

                $cart[$key] = [
                    'key' => $key,
                    'item_type' => 'lab_test',
                    'item_id' => $t['id'],
                    'name' => $t['name'],
                    'price' => (float) $t['discounted_price'],
                    'original_price' => (float) $t['original_price'],
                    'discount_percent' => (float) $t['discount_percent'],
                    'quantity' => 1,
                    // MARKER: this item was added via Customize Package
                    'from_custom_package' => true,
                ];
            }

            session()->put($this->sessionKey, $cart);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Selected tests added to cart',
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error in CustomPackageController@store', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error while processing request'
            ], 500);
        }
    }
}
