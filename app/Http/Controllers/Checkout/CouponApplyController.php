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
            'code'          => ['required', 'string', 'max:50'],
            'subtotal'      => ['required', 'numeric', 'min:0'],
            'package_ids'   => ['array'],
            'package_ids.*' => ['integer'],
        ]);

        // Keep exact casing as user typed
        $code      = trim($data['code']);
        $subtotal  = (float) $data['subtotal'];

        $packageId = null;
        if (!empty($data['package_ids'])) {
            $ids = array_values(array_filter(array_map('intval', $data['package_ids'])));
            $packageId = $ids[0] ?? null;
        }

        // Case-sensitive lookup
        $coupon = Coupon::whereRaw('BINARY `code` = ?', [$code])->first();
        // or: $coupon = Coupon::byCode($code)->first();

        if (!$coupon) {
            return response()->json([
                'ok'      => false,
                'message' => 'This coupon code is not valid. Please check the code and try again.',
            ], 422);
        }

        [$ok, $reason] = $coupon->canApply(
            subtotal: $subtotal,
            packageId: $packageId,
            userId: auth()->id()
        );

        if (!$ok) {
            return response()->json([
                'ok'      => false,
                'message' => $reason ?: 'This coupon cannot be applied to your current order. Please review the offer conditions and try again.',
                'status'  => $coupon->computed_status,
            ], 422);
        }

        $discount   = $coupon->computeDiscount($subtotal);
        $totalAfter = max(0, round($subtotal - $discount, 2));

        session([
            'applied_coupon' => [
                'coupon_id'        => $coupon->id,
                'coupon_code'      => $coupon->code,
                'discount_type'    => $coupon->type,
                'discount_value'   => $coupon->value,
                'discount_applied' => $discount,
                'metadata' => [
                    'min_order_amount'  => $coupon->min_order_amount,
                    'allowed_packages'  => $coupon->allowed_package_ids,
                    'evaluated_at'      => now()->toIso8601String(),
                ],
            ]
        ]);

        return response()->json([
            'ok'      => true,
            'message' => 'Coupon has been successfully applied to your order.',
            'coupon'  => [
                'code'             => $coupon->code,
                'type'             => $coupon->type,
                'value'            => (float) $coupon->value,
                'status'           => $coupon->computed_status,
                'min_order_amount' => $coupon->min_order_amount,
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
            'ok'      => true,
            'message' => 'The applied coupon has been removed from your order.',
        ]);
    }
}
