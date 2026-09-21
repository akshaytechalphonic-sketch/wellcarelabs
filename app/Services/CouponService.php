<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CouponService
{
    /**
     * Apply coupon by code (manual entry).
     * Hidden coupons (show_on_frontend=0) should still work.
     *
     * @param string $code
     * @param float $cartTotal
     * @param array<int> $packageIds
     * @return array {discount, payable, coupon}
     * @throws ValidationException
     */
    public function apply(string $code, float $cartTotal, array $packageIds = []): array
    {
        $code = trim($code);

        // ✅ Case-sensitive lookup (DO NOT filter show_on_frontend here)
        $coupon = Coupon::query()
            ->whereRaw('BINARY `code` = ?', [$code])
            ->first();

        if (!$coupon) {
            throw ValidationException::withMessages([
                'code' => 'Invalid coupon.',
            ]);
        }

        // pick 1 package id if you want strict single package validation
        $packageId = !empty($packageIds) ? (int) $packageIds[0] : null;

        // ✅ Use model validation (includes live + min_order + package + per-user + usage limit)
        [$ok, $reason] = $coupon->canApply(
            subtotal: (float) $cartTotal,
            packageId: $packageId,
            userId: Auth::id()
        );

        if (!$ok) {
            $messages = $coupon->couponMessages();
            $msg = $messages[$reason] ?? ($reason ?: 'This coupon cannot be applied.');

            // handle min_order message formatting
            if ($reason === 'min_order') {
                $minAmount = number_format((float) ($coupon->min_order_amount ?? 0), 2);
                $msg = str_replace(':amount', $minAmount, $messages['min_order']);
            }

            throw ValidationException::withMessages([
                'code' => $msg,
            ]);
        }

        // ✅ Discount calculation from model
        $discount = $coupon->computeDiscount((float) $cartTotal);
        $payable  = round(max(0, $cartTotal - $discount), 2);

        return [
            'discount' => round($discount, 2),
            'payable'  => $payable,
            'coupon'   => $coupon,
        ];
    }

    /**
     * Record usage after successful order placement
     */
    public function recordUsage(int $couponId, ?int $userId, ?int $orderId, int $qty = 1): void
    {
        CouponUsage::create([
            'coupon_id' => $couponId,
            'user_id'   => $userId,
            'order_id'  => $orderId,
            'quantity'  => $qty,
            'used_at'   => now(),
        ]);
    }
}
