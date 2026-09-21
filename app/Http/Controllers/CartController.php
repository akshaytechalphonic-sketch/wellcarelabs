<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\LabTest;
use App\Models\CustomPackage;

class CartController extends Controller
{
    protected $sessionKey = 'cart_items';

    /**
     * Show the cart page
     */
    public function index(Request $request)
    {
        $cart = session()->get($this->sessionKey, []);
        $items = array_values($cart);

        $total = collect($items)->sum(fn($it) => ((float)$it['price']) * ((int)$it['quantity']));

        return view('viewcartdetails', compact('items', 'total'));
    }

    /**
     * Get current cart count for navbar badge
     */
    public function count()
    {
        $cart = session()->get($this->sessionKey, []);
        $count = array_sum(array_column($cart, 'quantity')) ?: 0;

        return response()->json(['count' => $count]);
    }

    /**
     * Return cart items and total for frontend refresh
     */
    public function items()
    {
        $cart = session()->get($this->sessionKey, []);
        $items = array_values($cart);

        $total = collect($items)->sum(fn($it) => ((float)$it['price']) * ((int)$it['quantity']));

        return response()->json([
            'items' => $items,
            'total' => number_format($total, 2, '.', '')
        ]);
    }

    /**
     * Add item to cart
     *
     * New behavior: If the deterministic key (item_type_itemId) already exists in the cart,
     * the request is rejected with HTTP 409 and a helpful message. This prevents adding the
     * same item again from any page.
     */
    public function add(Request $request)
    {
        $request->validate([
            'item_type' => 'required|in:test,package,custom_package',
            'item_id' => 'required|integer',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $qty = (int) $request->input('quantity', 1);

        // Load item model based on type
        $item = match($request->item_type) {
            'test' => LabTest::find($request->item_id),
            'package' => Package::find($request->item_id),
            'custom_package' => CustomPackage::find($request->item_id),
        };

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        $cart = session()->get($this->sessionKey, []);
        $key = $request->item_type . '_' . $item->id;

        // NEW: if key exists, reject and do not add or increment
        if (isset($cart[$key])) {
            return response()->json([
                'success' => false,
                'message' => 'This item is already in your cart.'
            ], 409);
        }

        // create new cart entry (only if not present)
        $price = $item->discounted_price ?? $item->price ?? $item->mrp ?? $item->total_price ?? 0;

        $name = match($request->item_type) {
            'test' => $item->test_name ?? 'Test',
            'package' => $item->name ?? $item->title ?? 'Package',
            'custom_package' => $item->package_name ?? 'Custom Package',
        };

        $cart[$key] = [
            'key' => $key,
            'item_type' => $request->item_type,
            'item_id' => $item->id,
            'name' => $name,
            'price' => (float)$price,
            'quantity' => (int)$qty,
        ];

        session()->put($this->sessionKey, $cart);
        $count = array_sum(array_column($cart, 'quantity')) ?: 0;

        return response()->json([
            'success' => true,
            'message' => 'Added to cart',
            'count' => $count,
        ]);
    }

    /**
     * Update item quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'cart_item_key' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get($this->sessionKey, []);
        $key = $request->cart_item_key;

        if (!isset($cart[$key])) {
            return response()->json(['success' => false, 'message' => 'Item not found']);
        }

        $cart[$key]['quantity'] = (int)$request->quantity;
        session()->put($this->sessionKey, $cart);

        $total = collect($cart)->sum(fn($it) => ((float)$it['price']) * ((int)$it['quantity'])) ;

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated',
            'total' => number_format($total, 2, '.', '')
        ]);
    }

    /**
     * Remove an item
     *
     * This endpoint will refuse to remove items that have 'from_custom_package' => true.
     * Returns 403 JSON when removal is forbidden.
     */
    public function remove(Request $request)
    {
        $request->validate(['key' => 'required|string']);

        $cart = session()->get($this->sessionKey, []);
        $key = $request->key;

        if (!isset($cart[$key])) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        $item = $cart[$key];

        // SERVER-SIDE GUARD: Do not allow deletion of items that originated from Customize Package
        if (!empty($item['from_custom_package'])) {
            return response()->json([
                'success' => false,
                'message' => 'This item was added via Customize Package and cannot be removed individually.'
            ], 403);
        }

        // allowed -> remove
        unset($cart[$key]);
        session()->put($this->sessionKey, $cart);

        $count = array_sum(array_column($cart, 'quantity')) ?: 0;
        $total = collect($cart)->sum(fn($it) => ((float)$it['price']) * ((int)$it['quantity']));

        return response()->json([
            'success' => true,
            'message' => 'Item removed',
            'count' => $count,
            'total' => number_format($total, 2, '.', '')
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        session()->forget($this->sessionKey);

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }
}
