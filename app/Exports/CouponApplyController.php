<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponApplyController extends Controller
{
    /**
     * POST /checkout/coupon/apply
     */
    public function apply(Request $request)
    {
        $data = $request->validate([
            'code'         => ['required','string','max:50'],
            'subtotal'     => ['required','numeric','min:0'],
            // Optional: IDs of packages in cart (enforce allow-list if you use it)
            'package_ids'  => ['array'],
            'package_ids.*'=> ['integer'],
        ]);

        $code      = strtoupper(trim($data['code']));
        $subtotal  = (float) $data['subtotal'];
        $packageId = null; // pick one “primary” package if present
        if (!empty($data['package_ids'])) {
            $ids = array_values(array_filter(array_map('intval', $data['package_ids'])));
            $packageId = $ids[0] ?? null;
        }

        $coupon = Coupon::byCode($code)->first();
        if (!$coupon) {
            return response()->json(['ok' => false, 'message' => 'Invalid coupon code.'], 422);
        }

        // Centralized checks (live window, min amount, allow-list, per-user)
        [$ok, $reason] = $coupon->canApply(
            subtotal: $subtotal,
            packageId: $packageId,
            userId: auth()->id()
        );
        if (!$ok) {
            return response()->json(['ok' => false, 'message' => $reason, 'status' => $coupon->computed_status], 422);
        }

        // Compute discount (with cap); never exceeds subtotal
        $discount   = $coupon->computeDiscount($subtotal);
        $totalAfter = max(0, round($subtotal - $discount, 2));

        // Persist a normalized snapshot for the place-order step
        session([
            'applied_coupon' => [
                'coupon_id'        => $coupon->id,
                'coupon_code'      => $coupon->code,
                'discount_type'    => $coupon->type,      // 'percent' | 'fixed'
                'discount_value'   => $coupon->value,     // 10 for 10% or 200.00 for fixed
                'discount_applied' => $discount,          // actual ₹ off
                'metadata' => [
                    'max_discount'      => $coupon->max_discount,
                    'min_order_amount'  => $coupon->min_order_amount,
                    'allowed_packages'  => $coupon->allowed_package_ids, // array|null
                    'evaluated_at'      => now()->toIso8601String(),
                ],
            ]
        ]);

        return response()->json([
            'ok'      => true,
            'message' => 'Coupon applied.',
            'coupon'  => [
                'code'              => $coupon->code,
                'type'              => $coupon->type,
                'value'             => (float) $coupon->value,
                'status'            => $coupon->computed_status,
                'min_order_amount'  => $coupon->min_order_amount,
                'max_discount'      => $coupon->max_discount,
            ],
            'calc'    => [
                'subtotal' => round($subtotal, 2),
                'discount' => round($discount, 2),
                'total'    => $totalAfter,
            ],
        ]);
    }

    /**
     * POST /checkout/coupon/remove
     */
    public function remove(Request $request)
    {
        session()->forget('applied_coupon');

        return response()->json([
            'ok' => true,
            'message' => 'Coupon removed.',
        ]);
    }
}
