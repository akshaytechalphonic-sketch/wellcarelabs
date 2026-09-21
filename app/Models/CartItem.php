<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'item_type',     // lab_test | package | custom_package
        'item_id',       // master id or custom_package id (for audit)
        'name',          // snapshot display name
        'qty',
        'unit_price',
        'line_discount',
        'line_total',
        'meta',          // JSON snapshot (see notes)
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /* Keep totals consistent when values change */
    public function setQtyAttribute($value): void
    {
        $qty  = (int) $value;
        $unit = (float) ($this->attributes['unit_price'] ?? 0);
        $disc = (float) ($this->attributes['line_discount'] ?? 0);

        $this->attributes['qty'] = $qty;
        $this->attributes['line_total'] = ($unit * $qty) - $disc;
    }

    public function setUnitPriceAttribute($value): void
    {
        $unit = (float) $value;
        $qty  = (int) ($this->attributes['qty'] ?? 1);
        $disc = (float) ($this->attributes['line_discount'] ?? 0);

        $this->attributes['unit_price'] = $unit;
        $this->attributes['line_total'] = ($unit * $qty) - $disc;
    }

    public function setLineDiscountAttribute($value): void
    {
        $disc = (float) $value;
        $qty  = (int) ($this->attributes['qty'] ?? 1);
        $unit = (float) ($this->attributes['unit_price'] ?? 0);

        $this->attributes['line_discount'] = $disc;
        $this->attributes['line_total'] = ($unit * $qty) - $disc;
    }
}
