<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CustomPackage extends Model
{
    protected $fillable = [
        'user_id',      // nullable, ignored in no-login flow
        'title',
        'components',   // [{test_id, name, price}, ...]
        'base_price',
        'total_price',
    ];

    protected $casts = [
        'components' => 'array',
    ];

    /** Convenience: components as a collection */
    public function componentsCollection(): Collection
    {
        return collect($this->components ?? []);
    }

    /** Recalculate total from components + base_price */
    public function recalcTotal(?float $baseFee = null): void
    {
        $base = is_null($baseFee) ? (float)$this->base_price : (float)$baseFee;
        $sum  = (float) $this->componentsCollection()->sum('price');
        $this->total_price = $sum + $base;
        if (!is_null($baseFee)) $this->base_price = $base;
        $this->save();
    }
}
