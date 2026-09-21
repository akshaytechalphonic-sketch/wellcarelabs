<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewAppointmentNotification;

use App\Models\Appointment;
use App\Models\AppointmentItem;
use App\Models\CustomPackage;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentBookedMail;
use App\Mail\NewAppointmentAdminMail;

class CheckoutController extends Controller
{
    /**
     * GET /checkout – show page
     */
    public function index()
    {
        // 🔒 If checkout already completed, block back navigation
        if (session()->get('checkout_completed')) {
            return redirect('/')->with('info', 'Your session has ended.');
        }

        $items = array_values(session()->get('cart_items', []));

        if (empty($items)) {
            return redirect('/')->with('error', 'Your cart is empty.');
        }

        $total = collect($items)->sum(fn($it) => (float)($it['price'] ?? 0));

        $now = Carbon::now();

        // Get ALL active coupons (don't filter by min_order_amount)
        $availableCoupons = Coupon::visible()
            ->orderBy('min_order_amount', 'asc')
            ->orderBy('value', 'desc')
            ->get();

        return view('checkout', [
            'items' => $items,
            'subtotalAmount' => $total,
            'availableCoupons' => $availableCoupons,
            'packageId' => null,
            'userId' => auth()->id(),
        ]);
    }

    /**
     * POST /checkout – place order
     */
    public function store(Request $request)
    {
        // 🔒 Prevent duplicate submission / back-button abuse
        if (session()->get('checkout_completed')) {
            return redirect('/')->with('info', 'Checkout already completed.');
        }

        // Multiple items booking: service is not required anymore
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['nullable', 'email', 'max:255'],
            'phone'     => ['required', 'string', 'max:20'],
            'date'      => ['required', 'date'],
            'time_slot' => ['nullable', 'string', 'max:20'],
            'service'   => ['nullable', 'string', 'max:255'],
            'message'   => ['nullable', 'string', 'max:1000'],
            'address'   => ['nullable', 'string', 'max:500'],
            'city'      => ['nullable', 'string', 'max:100'],
            'pincode'   => ['nullable', 'string', 'max:10'],
            'landmark'  => ['nullable', 'string', 'max:200'],

            // legacy single-selection (kept nullable)
            'package_id'   => ['nullable', 'integer'],
            'package_name' => ['nullable', 'string', 'max:255'],
            'test_id'      => ['nullable', 'integer'],

            // hospital attribution (optional)
            'hospital_id'        => ['nullable', 'integer'],
            'hospital_unique_id' => ['nullable', 'string', 'max:64'],

            // coupon fields
            'applied_coupon_code' => ['nullable', 'string', 'max:50'],
            'applied_discount'    => ['nullable', 'numeric', 'min:0'],
        ]);

        // 1) Read session cart
        $sessionItems = array_values(session('cart_items', []));
        if (empty($sessionItems)) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        // 2) Normalize items for insert (qty = 1; normalize type)
        $cartItems = collect($sessionItems)->map(function ($row) {
            $type = $row['item_type'] ?? $row['type'] ?? 'package';
            if ($type === 'lab_test') $type = 'test'; // normalize to your DB semantics

            return [
                'item_type'  => (string) $type,                                      // test | package | custom_package
                'item_id'    => (int) ($row['item_id'] ?? $row['id'] ?? 0),
                'item_name'  => (string) ($row['name'] ?? $row['item_name'] ?? ''),
                'item_price' => (float) ($row['price'] ?? $row['item_price'] ?? 0),
                'quantity'   => 1,                                                  // fixed
            ];
        });

        // 3) Subtotal (sum of prices only)
        $subtotal = (float) $cartItems->sum('item_price');

        // 4) Coupon handling
        $couponCode = $request->input('applied_coupon_code');
        $discount = (float) $request->input('applied_discount', 0.0);

        // Validate coupon if applied
        if ($couponCode) {
            $coupon = Coupon::byCode($couponCode)->first();
            if (!$coupon) {
                return back()->withErrors(['coupon' => 'Invalid coupon code.']);
            }

            // Verify coupon can still be applied
            [$canApply, $reason] = $coupon->canApply($subtotal, null, null);
            if (!$canApply) {
                return back()->withErrors(['coupon' => 'Coupon is no longer applicable.']);
            }

            // Verify discount amount is correct
            $calculatedDiscount = $coupon->computeDiscount($subtotal);
            if (abs($discount - $calculatedDiscount) > 0.01) {
                return back()->withErrors(['coupon' => 'Discount amount mismatch. Please reapply coupon.']);
            }

            $couponData = [
                'coupon_id'       => $coupon->id,
                'coupon_code'     => $coupon->code,
                'discount_type'   => $coupon->type,
                'discount_value'  => $coupon->value,
                'discount_applied' => $discount,
            ];
        } else {
            $couponData = null;
            $discount = 0.0;
        }

        $grandTotal = max(0, round($subtotal - $discount, 2));

        // 5) Persist everything
        $appointment = DB::transaction(function () use ($data, $cartItems, $subtotal, $discount, $grandTotal, $couponData) {

            /** @var Appointment $appointment */
            $appointment = Appointment::create([
                'name'                => $data['name'],
                'email'               => $data['email'] ?? null,
                'phone'               => $data['phone'],
                'date'                => $data['date'],
                'time_slot'           => $data['time_slot'] ?? null,
                'service'             => $data['service'] ?? 'Cart',
                'message'             => $data['message'] ?? null,
                'address'             => $data['address'] ?? null,
                'city'                => $data['city'] ?? null,
                'pincode'             => $data['pincode'] ?? null,
                'landmark'            => $data['landmark'] ?? null,

                // legacy single selection
                'package_id'          => $data['package_id']   ?? null,
                'package_name'        => $data['package_name'] ?? null,
                'test_id'             => $data['test_id']      ?? null,

                // hospital attribution
                'hospital_id'         => $data['hospital_id']        ?? null,
                'hospital_unique_id'  => $data['hospital_unique_id'] ?? null,

                // pricing snapshots
                'subtotal'            => $subtotal,
                'discount_amount'     => $discount,
                'coupon_code'         => $couponData['coupon_code']   ?? null,
                'discount_type'       => $couponData['discount_type'] ?? null,   // 'percent' | 'fixed'
                'discount_value'      => $couponData['discount_value'] ?? null,   // 10 or 200.00
                'coupon_id'           => $couponData['coupon_id']     ?? null,
                'total_price'         => $grandTotal,
            ]);

            // Build items payload; include meta for custom packages
            $rows = $cartItems->map(function ($it) {
                $meta = null;

                if ($it['item_type'] === 'custom_package' && $it['item_id']) {
                    if ($cp = CustomPackage::find($it['item_id'])) {
                        $meta = [
                            'base_fee'   => (float) ($cp->base_price ?? 0),
                            'components' => (array) ($cp->components ?? []),
                        ];
                    }
                }

                return [
                    'appointment_id' => null,
                    'item_type'      => $it['item_type'],
                    'item_id'        => $it['item_id'] ?: null,
                    'item_name'      => $it['item_name'],
                    'item_price'     => $it['item_price'],
                    'quantity'       => 1,
                    'meta'           => $meta,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            })->all();

            foreach ($rows as &$row) {
                $row['appointment_id'] = $appointment->id;
            }
            unset($row);

            AppointmentItem::insert($rows);

            // Record coupon usage if applicable
            if ($couponData && $couponData['coupon_id']) {
                $coupon = Coupon::find($couponData['coupon_id']);
                if ($coupon) {
                    $coupon->recordUsage($appointment->id, null);
                }
            }

            return $appointment;
        });

        // ✅ Send Admin Email Notification
        try {
            $adminEmail = config('mail.admin_email');
            if (!empty($adminEmail) && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                Mail::to($adminEmail)->send(new NewAppointmentAdminMail($appointment));
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to send admin appointment email notification (CheckoutController)', [
                'appointment_id' => $appointment->id ?? null,
                'error'          => $e->getMessage()
            ]);
        }

        // ✅ Send Patient/Customer Email Notification
        try {
            if (!empty($appointment->email) && filter_var($appointment->email, FILTER_VALIDATE_EMAIL) && !str_contains($appointment->email, 'guest@wellcare')) {
                Mail::to($appointment->email)->send(new AppointmentBookedMail($appointment));
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to send patient appointment email notification (CheckoutController)', [
                'appointment_id' => $appointment->id ?? null,
                'error'          => $e->getMessage()
            ]);
        }

        // Send WhatsApp notification to Admin for new booking
        try {
            $adminNumber = config('services.dovesoft.admin_number');
            if ($adminNumber) {
                $digits = preg_replace('/\D+/', '', $adminNumber);
                if (strlen($digits) === 10) {
                    $digits = '91' . $digits;
                }
                if ($digits) {
                    app(\App\Services\DovesoftService::class)->sendTemplate(
                        $digits,
                        'appointment',
                        'en',
                        []
                    );
                }
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to send appointment WhatsApp notification to admin (CheckoutController)', [
                'appointment_id' => $appointment->id ?? null,
                'error'          => $e->getMessage()
            ]);
        }

        // Send WhatsApp notification to Customer for new booking
        try {
            if (!empty($appointment?->phone)) {
                $mobile = preg_replace('/\D+/', '', $appointment->phone);
                if (strlen($mobile) === 10) {
                    $mobile = '91' . $mobile;
                }
                try {
                    $formattedDate = !empty($appointment->date)
                        ? \Carbon\Carbon::parse($appointment->date)->format('d/m/Y')
                        : '';
                } catch (\Throwable $e) {
                    $formattedDate = (string) ($appointment->date ?? '');
                }
                try {
                    $formattedSlot = !empty($appointment->time_slot)
                        ? \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A')
                        : '';
                } catch (\Throwable $e) {
                    $formattedSlot = (string) ($appointment->time_slot ?? '');
                }
                $bodyParams = [
                    $appointment->name ?? '',                 // {{1}} Name
                    $formattedDate,                            // {{2}} Date
                    $formattedSlot,                            // {{3}} Slot
                    (string) ($appointment->service ?? 'Lab Test'), // {{4}} Test details
                    $appointment->name ?? '',                 // {{5}} Name
                    (string) ($appointment->age ?? ''),        // {{6}} Age
                    (string) ($appointment->gender ?? ''),     // {{7}} Gender
                ];

                app(\App\Services\DovesoftService::class)->sendTemplate(
                    $mobile,
                    'bookingupdatednew',
                    'en',
                    $bodyParams
                );
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to send appointment WhatsApp notification to customer (CheckoutController)', [
                'appointment_id' => $appointment->id ?? null,
                'error'          => $e->getMessage()
            ]);
        }

        // 7) Clear session cart + coupon
        session()->put('checkout_completed', true);
        session()->forget([
            'cart_items',
            'applied_coupon',
        ]);
        session()->invalidate();
        session()->regenerateToken();

        // 8) Respond
        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Appointment booked successfully.',
                'appointment_id' => $appointment->id,
                'pricing' => [
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total'    => $grandTotal,
                ],
            ]);
        }

        return redirect()->route('checkout.success')
            ->with('success', 'Appointment booked successfully.');
    }

    /**
     * Apply coupon via AJAX
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::byCode($request->code)
            ->where('show_on_frontend', 1)
            ->first();

        if (!$coupon) {
            return response()->json([
                'ok' => false,
                'message' => 'Invalid coupon code.',
            ], 404);
        }

        // Check if coupon can be applied
        [$canApply, $reason] = $coupon->canApply($request->subtotal, null, null);

        if (!$canApply) {
            $messages = $coupon->couponMessages();
            $message = $messages[$reason] ?? $messages['invalid'];

            // Special handling for min_order
            if ($reason === 'min_order') {
                $amountNeeded = max(0, $coupon->min_order_amount - $request->subtotal);
                $message = str_replace(':amount', number_format($coupon->min_order_amount, 2), $message);
                return response()->json([
                    'ok' => false,
                    'message' => $message,
                    'reason' => $reason,
                    'amount_needed' => $amountNeeded,
                ]);
            }

            return response()->json([
                'ok' => false,
                'message' => $message,
                'reason' => $reason,
            ]);
        }

        // Calculate discount
        $discount = $coupon->computeDiscount($request->subtotal);
        $total = max(0, $request->subtotal - $discount);

        // Store coupon in session for later use
        session()->put('applied_coupon', [
            'coupon_id' => $coupon->id,
            'coupon_code' => $coupon->code,
            'discount_type' => $coupon->type,
            'discount_value' => $coupon->value,
            'discount_applied' => $discount,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Coupon applied successfully!',
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'min_order_amount' => $coupon->min_order_amount,
            ],
            'calc' => [
                'subtotal' => $request->subtotal,
                'discount' => $discount,
                'total' => $total,
            ],
        ]);
    }

    /**
     * Remove coupon via AJAX
     */
    public function removeCoupon(Request $request)
    {
        session()->forget('applied_coupon');

        return response()->json([
            'ok' => true,
            'message' => 'Coupon removed successfully.',
        ]);
    }

    /**
     * Success page
     */
    public function success()
    {
        if (!session()->get('checkout_completed')) {
            return redirect('/');
        }

        return view('checkout-success');
    }
}
