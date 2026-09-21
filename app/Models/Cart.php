<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class Cart extends Model
{
    protected $fillable = [
        'session_id',      // guests only
        'status',          // active | converted | abandoned
        'subtotal',
        'discount_total',
        'tax_total',
        'grand_total',
        'coupon_applied',  // {code, type: fixed|percent, amount}
    ];

    protected $casts = [
        'coupon_applied' => 'array',
    ];

    /* -------------------------
     | Relationships
     * ------------------------*/
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /* -------------------------
     | Scopes
     * ------------------------*/
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('status', 'active');
    }

    public function scopeForSession(Builder $q, string $sessionId): Builder
    {
        return $q->where('session_id', $sessionId);
    }

    /* -------------------------
     | Business helpers
     * ------------------------*/
    public function reprice(): void
    {
        $this->loadMissing('items');

        $subtotal = (float) $this->items->sum('line_total');
        $discount = 0.0;

        if ($c = $this->coupon_applied) {
            $amount = (float) Arr::get($c, 'amount', 0);
            $type   = (string) Arr::get($c, 'type', 'fixed'); // fixed|percent
            $discount = $type === 'percent'
                ? round($subtotal * ($amount / 100), 2)
                : $amount;

            $discount = max(0, min($discount, $subtotal));
        }

        $tax = 0.0; // plug GST if needed
        $grand = $subtotal - $discount + $tax;

        $this->forceFill(compact('subtotal','discount_total','tax_total','grand_total'))->save();
    }

    public function applyCoupon(?array $coupon): void
    {
        $this->coupon_applied = $coupon ?: null;
        $this->save();
        $this->reprice();
    }

    /**
     * Add or merge a cart item.
     * $payload: item_type,item_id,name,qty,unit_price,line_discount,line_total,meta
     * $mergeBy: e.g. ['item_type','item_id','unit_price']
     */
    public function upsertItem(array $payload, array $mergeBy = []): CartItem
    {
        $this->loadMissing('items');

        $match = null;
        if ($mergeBy) {
            $match = $this->items->first(function (CartItem $it) use ($payload, $mergeBy) {
                foreach ($mergeBy as $k) {
                    if (($it->{$k}) != ($payload[$k] ?? null)) return false;
                }
                return true;
            });
        }

        if ($match) {
            $newQty = $match->qty + (int) ($payload['qty'] ?? 1);
            $match->qty = $newQty;
            $match->line_total = ($match->unit_price * $newQty) - $match->line_discount;
            $match->save();
            $this->reprice();
            return $match;
        }

        /** @var CartItem $created */
        $created = $this->items()->create($payload);
        $this->reprice();
        return $created;
    }
}
