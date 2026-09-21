<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentItem extends Model
{
    // Types we’ll use everywhere consistently
    public const TYPE_TEST           = 'test';
    public const TYPE_PACKAGE        = 'package';
    public const TYPE_CUSTOM_PACKAGE = 'custom_package';

    protected $fillable = [
        'appointment_id',
        'item_type',   // 'test' | 'package' | 'custom_package'
        'item_id',
        'item_name',
        'item_price',
        'quantity',
        'meta',        // NEW: JSON snapshot (e.g., components for custom package)
    ];

    protected $casts = [
        'item_price' => 'float',
        'quantity'   => 'integer',
        'meta'       => 'array',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Appointment::class);
    }

    /* ---------- Convenience accessors ---------- */

    // Line total = price × qty
    public function getLineTotalAttribute(): float
    {
        return (float) $this->item_price * (int) ($this->quantity ?? 1);
    }

    // For custom_package rows, quickly get the components array
    public function getComponentsAttribute(): array
    {
        return (array) ($this->meta['components'] ?? []);
    }

    // Human-friendly label for UI badges
    public function getTypeLabelAttribute(): string
    {
        return match ($this->item_type) {
            self::TYPE_TEST           => 'TEST',
            self::TYPE_PACKAGE        => 'PACKAGE',
            self::TYPE_CUSTOM_PACKAGE => 'CUSTOM',
            default                   => strtoupper((string)$this->item_type),
        };
    }
}
