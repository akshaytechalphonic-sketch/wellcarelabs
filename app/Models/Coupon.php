<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'usage_limit',
        'starts_at',
        'expires_at',
        'is_active',
        'show_on_frontend',
    ];

    protected $casts = [
        'starts_at'          => 'datetime:Asia/Kolkata',
        'expires_at'         => 'datetime:Asia/Kolkata',
        'is_active'          => 'boolean',
        'value'              => 'float',
        'min_order_amount'   => 'float',
        'usage_limit'        => 'integer',
        'show_on_frontend'  => 'boolean',
    ];

    protected $appends = ['computed_status'];

    /* -------------------- RELATIONSHIPS -------------------- */

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    /* -------------------- NORMALIZATION -------------------- */

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = trim($value);
    }

    /* -------------------- USAGE HELPERS -------------------- */

    /**
     * Total quantity used across all users/appointments.
     */
    public function totalUsageCount(): int
    {
        return (int) $this->usages()->sum('quantity');
    }

    /**
     * Has the global usage limit been exhausted?
     */
    public function isUsageExhausted(): bool
    {
        if (is_null($this->usage_limit)) {
            return false; // unlimited
        }

        return $this->totalUsageCount() >= $this->usage_limit;
    }

    /* -------------------- STATE & STATUS -------------------- */

    /**
     * Is coupon live at the given time? (start inclusive; end exclusive)
     * Treat a coupon whose global usage_limit is exhausted as not live.
     */
    public function isLive(?Carbon $now = null): bool
    {
        $now ??= now('Asia/Kolkata');

        if (!$this->is_active) return false;

        // If global usage limit exhausted, treat as not live (effectively expired until increased).
        if ($this->isUsageExhausted()) {
            return false;
        }

        $starts  = $this->starts_at;
        $expires = $this->expires_at;

        if ($starts && $now->lt($starts)) return false;
        if ($expires && $now->gte($expires)) return false;

        return true;
    }

    /**
     * Status: expired | inactive | scheduled | live
     * If usage_limit exhausted → considered 'expired'.
     */
    public function getComputedStatusAttribute(): string
    {
        $now = now('Asia/Kolkata');
        $starts  = $this->starts_at;
        $expires = $this->expires_at;

        // Global usage exhausted -> show expired
        if ($this->isUsageExhausted()) {
            return 'expired';
        }

        if ($expires && $now->gte($expires)) return 'expired';
        if (!$this->is_active) return 'inactive';
        if ($starts && $now->lt($starts)) return 'scheduled';
        return 'live';
    }

    /* -------------------- SCOPES -------------------- */

    public function scopeLive(Builder $query): Builder
    {
        $now = now('Asia/Kolkata');

        // Only include coupons that are active, within date range, and not exhausted.
        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', $now);
            })
            // Exclude coupons whose usage_limit is reached.
            ->where(function ($q) {
                $q->whereNull('usage_limit') // unlimited
                    ->orWhereRaw('(SELECT COALESCE(SUM(quantity),0) FROM coupon_usages WHERE coupon_usages.coupon_id = coupons.id) < coupons.usage_limit');
            });
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->whereNotNull('starts_at')
            ->where('starts_at', '>', now('Asia/Kolkata'));
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where(function ($q) {
            // expired by date OR exhausted by usage limit
            $q->whereNotNull('expires_at')->where('expires_at', '<=', now('Asia/Kolkata'))
                ->orWhereRaw('(SELECT COALESCE(SUM(quantity),0) FROM coupon_usages WHERE coupon_usages.coupon_id = coupons.id) >= coupons.usage_limit');
        });
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    public function scopeByCode(Builder $query, string $code): Builder
    {
        return $query->whereRaw('BINARY `code` = ?', [trim($code)]);
    }

    public function scopeVisible(Builder $query): Builder
    {
        $now = now('Asia/Kolkata');

        return $query->where('show_on_frontend', 1)   // ✅ ONLY SHOW THESE ON FRONTEND
            ->where('is_active', true)
            ->where(function ($q) use ($now) {
                // not expired
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', $now);
            })
            // usage limit not reached
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                    ->orWhereRaw('(SELECT COALESCE(SUM(quantity),0) FROM coupon_usages WHERE coupon_usages.coupon_id = coupons.id) < coupons.usage_limit');
            });
    }


    /* -------------------- RULES / CHECKS -------------------- */

    public function usedByUser(?int $userId): int
    {
        if (!$userId) return 0;
        return (int) $this->usages()
            ->where('user_id', $userId)
            ->sum('quantity');
    }

    public function remainingForUser(?int $userId): ?int
    {
        if (is_null($this->per_user_limit) || !$userId) return null;
        $used = $this->usedByUser($userId);
        return max(0, $this->per_user_limit - $used);
    }

    /**
     * Determine whether the coupon allows the provided package id.
     * If allowed_package_ids is null or empty -> it applies to all packages.
     */
    public function allowsPackage(?int $packageId = null): bool
    {
        // no restriction
        if (empty($this->allowed_package_ids)) return true;

        // if packageId is null, treat as not allowed when specific list exists
        if (is_null($packageId)) return false;

        return in_array((int) $packageId, $this->allowed_package_ids, true);
    }

    /**
     * Check if coupon can be seen by user (shows coupon even if min amount not met)
     * Returns [ok(bool), reasonKey|null]
     */
    public function canSee(float $subtotal, ?int $packageId = null, ?int $userId = null, ?Carbon $now = null): array
    {
        $now ??= now('Asia/Kolkata');

        // If global usage limit exhausted -> global_limit
        if ($this->isUsageExhausted()) {
            return [false, 'global_limit'];
        }

        // Not live -> try to be specific about why
        if (!$this->isLive($now)) {
            if (!$this->is_active) return [false, 'not_live'];
            if ($this->expires_at && $now->gte($this->expires_at)) return [false, 'expired'];
            if ($this->starts_at && $now->lt($this->starts_at)) return [false, 'scheduled'];
            return [false, 'not_live'];
        }

        // Package applicability
        if (!$this->allowsPackage($packageId)) {
            return [false, 'not_applicable'];
        }

        // Per-user limit
        if (!is_null($this->per_user_limit) && $userId) {
            $remaining = $this->remainingForUser($userId);
            if ($remaining !== null && $remaining <= 0) {
                return [false, 'per_user_limit'];
            }
        }

        // Check minimum amount but don't fail because of it
        $min = (float) ($this->min_order_amount ?? 0.0);
        if ($subtotal < $min) {
            return [true, 'min_order']; // Return true but with min_order reason
        }

        return [true, null];
    }

    /**
     * Check if coupon can actually be applied (checks all conditions including min amount)
     * Returns [ok(bool), reasonKey|null]
     */
    public function canApply(float $subtotal, ?int $packageId = null, ?int $userId = null, ?Carbon $now = null): array
    {
        $now ??= now('Asia/Kolkata');

        // If global usage limit exhausted -> global_limit
        if ($this->isUsageExhausted()) {
            return [false, 'global_limit'];
        }

        // Not live -> try to be specific about why
        if (!$this->isLive($now)) {
            if (!$this->is_active) return [false, 'not_live'];
            if ($this->expires_at && $now->gte($this->expires_at)) return [false, 'expired'];
            if ($this->starts_at && $now->lt($this->starts_at)) return [false, 'scheduled'];
            return [false, 'not_live'];
        }

        // Minimum order amount check
        $min = (float) ($this->min_order_amount ?? 0.0);
        if ($subtotal < $min) {
            return [false, 'min_order'];
        }

        // Package applicability
        if (!$this->allowsPackage($packageId)) {
            return [false, 'not_applicable'];
        }

        // Per-user limit
        if (!is_null($this->per_user_limit) && $userId) {
            $remaining = $this->remainingForUser($userId);
            if ($remaining !== null && $remaining <= 0) {
                return [false, 'per_user_limit'];
            }
        }

        return [true, null];
    }

    /* -------------------- CHECK COUPON METHOD -------------------- */

    /**
     * Check coupon visibility and eligibility
     * Returns: [ visible(bool), applicable(bool), reasonKey(string|null), message(string), amount_needed(float|null) ]
     */
    public function checkCoupon(float $subtotal, ?int $packageId = null, ?int $userId = null): array
    {
        $messages = $this->couponMessages();

        // First check if coupon can be seen
        [$visible, $reasonKey] = $this->canSee($subtotal, $packageId, $userId);

        if (!$visible) {
            $key = $reasonKey ?? 'invalid';
            $msg = $messages[$key] ?? $messages['invalid'];
            return [false, false, $key, $msg, null];
        }

        // Then check if it can be applied
        [$applicable, $applyReasonKey] = $this->canApply($subtotal, $packageId, $userId);

        if ($applicable) {
            return [true, true, 'success', $messages['success'], null];
        }

        // If not applicable, check why
        $key = $applyReasonKey ?? 'invalid';

        if ($key === 'min_order') {
            $minAmount = (float) ($this->min_order_amount ?? 0);
            $amountNeeded = max(0, $minAmount - $subtotal);

            if ($amountNeeded > 0) {
                $amountFormatted = number_format($minAmount, 2);
                $neededFormatted = number_format($amountNeeded, 2);

                $msg = str_replace(
                    [':more', ':amount'],
                    [$neededFormatted, $amountFormatted],
                    "Add ₹:more more to your cart to apply this coupon (minimum order: ₹:amount)."
                );

                return [true, false, 'min_order', $msg, $amountNeeded];
            }
        }

        $msg = $messages[$key] ?? $messages['invalid'];
        return [true, false, $key, $msg, null];
    }

    /* -------------------- DISCOUNT -------------------- */

    public function computeDiscount(float $subtotal): float
    {
        $discount = 0.0;

        if ($this->type === 'percent') {
            $discount = round(($subtotal * (float) $this->value) / 100, 2);
        } else { // fixed
            $discount = round((float) $this->value, 2);
        }

        if (!is_null($this->max_discount)) {
            $discount = min($discount, (float) $this->max_discount);
        }

        return max(0.0, min($discount, $subtotal));
    }

    /* -------------------- USAGE LOG -------------------- */

    public function recordUsage(int $appointmentId, ?int $userId = null, int $qty = 1): void
    {
        DB::table('coupon_usages')->insert([
            'coupon_id'      => $this->id,
            'user_id'        => $userId,
            'appointment_id' => $appointmentId,
            'quantity'       => $qty,
            'used_at'        => now(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    /* -------------------- MESSAGES & APPLY -------------------- */

    public function couponMessages(): array
    {
        return [
            'success'         => "Great! Your coupon has been applied. Discount added to your total.",
            'not_live'        => "This coupon is not active at the moment.",
            'expired'         => "This coupon has expired.",
            'scheduled'       => "This coupon is not yet active. Please try again later.",
            'min_order'       => "This coupon requires a minimum order of ₹:amount.",
            'not_applicable'  => "This coupon is not valid for the selected package.",
            'per_user_limit'  => "You have already used this coupon the maximum number of times.",
            'global_limit'    => "This coupon has reached its maximum usage and is no longer available.",
            'invalid'         => "Invalid coupon code. Please check and try again.",
        ];
    }

    /**
     * Apply coupon to a cart and return:
     *   [ success(bool), reasonKey(string|null), message(string) ]
     */
    public function applyToCart(float $subtotal, ?int $packageId = null, ?int $userId = null): array
    {
        [$ok, $reasonKey] = $this->canApply($subtotal, $packageId, $userId);

        $messages = $this->couponMessages();

        if ($ok) {
            return [true, 'success', $messages['success']];
        }

        $key = $reasonKey ?? 'invalid';

        // inject min amount into string when relevant
        if ($key === 'min_order') {
            $amount = number_format((float) ($this->min_order_amount ?? 0), 2);
            $msg = str_replace(':amount', $amount, $messages['min_order']);
        } else {
            $msg = $messages[$key] ?? $messages['invalid'];
        }

        return [false, $key, $msg];
    }

    /* -------------------- HELPER METHODS -------------------- */

    /**
     * Get the amount needed to reach minimum order
     */
    public function getAmountNeeded(float $subtotal): float
    {
        $minAmount = (float) ($this->min_order_amount ?? 0);
        return max(0, $minAmount - $subtotal);
    }

    /**
     * Get coupon description
     */
    public function getDescription(): string
    {
        if ($this->type === 'percent') {
            return "{$this->value}% off";
        } else {
            return "₹{$this->value} off";
        }
    }
}
