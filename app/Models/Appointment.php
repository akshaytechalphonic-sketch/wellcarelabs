<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'date',
        'time_slot',

        'package_id',
        'package_name',
        'test_id',
        'service',
        'message',
        'total_price',
        'status',

        // hospital attribution
        'hospital_id',
        'hospital_unique_id',

        // 💸 coupon/discount snapshots (added by new migrations)
        'subtotal',          // before discount/tax
        'discount_amount',   // rupees off
        'coupon_code',       // snapshot of code used
        'discount_type',     // 'percent' | 'fixed'
        'discount_value',    // 10 for 10% or 200.00 for fixed
        'coupon_id',         // FK to coupons (nullable)

        // 🧍 patient / address fields
        'gender',
        'dob',
        'age',
        'address',
        'city',
        'pincode',
        'landmark',
    ];

    protected $casts = [
        'date'           => 'date',
        'dob'            => 'date',
        'time_slot'      => 'string',
        'total_price'    => 'float',
        'hospital_id'    => 'integer',

        // 💸 casts for discount fields
        'subtotal'        => 'float',
        'discount_amount' => 'float',
        'discount_value'  => 'float',
        'coupon_id'       => 'integer',

        'age'             => 'integer',
    ];

    protected $appends = [
        'items_summary',
        'total_price_display',
        'net_total', // computed below: subtotal - discount (fallbacks to total_price)
    ];

    /**************************
     * Model Boot Method - PREVENT STATUS CHANGES FOR COMPLETED APPOINTMENTS
     **************************/
    protected static function boot()
    {
        parent::boot();
        
        static::updating(function ($appointment) {
            // Check if the appointment is already completed
            $originalStatus = strtolower($appointment->getOriginal('status') ?? '');
            
            if ($originalStatus === 'completed') {
                // Prevent any status change from completed to anything else
                if ($appointment->isDirty('status') && 
                    strtolower($appointment->status) !== 'completed') {
                    // Keep the original completed status
                    $appointment->status = $appointment->getOriginal('status');
                    
                    // Log this attempt (optional)
                    \Log::warning('Attempted to change status of completed appointment', [
                        'appointment_id' => $appointment->id,
                        'original_status' => $appointment->getOriginal('status'),
                        'attempted_status' => $appointment->status,
                    ]);
                }
                
                // Also prevent other updates if you want (optional)
                // You can uncomment this to lock the entire record after completion:
                // return false;
            }
        });
    }

    /**************************
     * Helper Methods
     **************************/
    
    /**
     * Check if appointment is completed
     */
    public function isCompleted(): bool
    {
        return strtolower($this->status ?? '') === 'completed';
    }
    
    /**
     * Scope to get completed appointments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    /**
     * Scope to get non-completed appointments
     */
    public function scopeNotCompleted($query)
    {
        return $query->where('status', '!=', 'completed');
    }

    /**************************
     * Relationships
     **************************/

    public function items(): HasMany
    {
        return $this->hasMany(AppointmentItem::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Package::class, 'package_id');
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(\App\Models\LabTest::class, 'test_id');
    }

    /**
     * Hospital relation (nullable)
     */
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Hospital::class, 'hospital_id');
    }

    /**
     * Coupon snapshot FK (nullable)
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Coupon::class, 'coupon_id');
    }

    /**
     * Audit rows if you created `appointment_coupons` table (stacking-ready)
     */
    public function appliedCoupons(): HasMany
    {
        return $this->hasMany(\App\Models\AppointmentCoupon::class);
    }

    /**
     * Link to uploaded report (if exists)
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Report::class, 'report_id');
    }

    /**
     * Payments relation
     * NOTE: in your DB, appointment id is stored in payments.user_id
     */
    public function payments(): HasMany
    {
        // foreign key on payments table = user_id
        // local key on appointments table = id
        return $this->hasMany(\App\Models\Payment::class, 'user_id', 'id');
    }

    /**************************
     * Additional Boot Events
     **************************/
    protected static function booted()
    {
        static::saving(function ($model) {
            // if hospital_id is set but hospital_unique_id empty, try to fill it
            if (!empty($model->hospital_id) && empty($model->hospital_unique_id)) {
                $hospital = \App\Models\Hospital::find($model->hospital_id);
                if ($hospital && !empty($hospital->unique_id)) {
                    $model->hospital_unique_id = $hospital->unique_id;
                }
            }

            // if hospital_unique_id is present but hospital_id missing, try to resolve it
            if (empty($model->hospital_id) && !empty($model->hospital_unique_id)) {
                $hospital = \App\Models\Hospital::where('unique_id', $model->hospital_unique_id)->first();
                if ($hospital) {
                    $model->hospital_id = $hospital->id;
                }
            }
        });
    }

    /**************************
     * Scopes
     **************************/

    /**
     * Scope to filter by hospital id or unique id.
     * Usage: Appointment::byHospital(123) or Appointment::byHospital('UNIQUE123')
     */
    public function scopeByHospital($query, $hospitalIdOrUnique)
    {
        if (is_numeric($hospitalIdOrUnique)) {
            return $query->where('hospital_id', (int) $hospitalIdOrUnique);
        }

        return $query->where(function ($q) use ($hospitalIdOrUnique) {
            $q->where('hospital_unique_id', $hospitalIdOrUnique)
                ->orWhereHas('hospital', function ($h) use ($hospitalIdOrUnique) {
                    $h->where('unique_id', $hospitalIdOrUnique);
                });
        });
    }

    /**
     * Scope: filter by coupon code
     */
    public function scopeWithCoupon($query, ?string $code)
    {
        if (!$code) return $query;
        return $query->where('coupon_code', $code);
    }

    /**************************
     * Accessors
     **************************/

    public function getItemsSummaryAttribute(): ?string
    {
        if ($this->relationLoaded('items')) {
            $items = $this->items;
            if ($items->isEmpty()) return null;
            $first = $items->first()->item_name ?? null;
            $count = $items->count();
        } else {
            $firstItem = $this->items()->limit(1)->first();
            $first = $firstItem->item_name ?? null;
            $count = $this->items()->count();
        }

        if (empty($first)) return null;
        if ($count <= 1) return $first;
        return $first . ' (+ ' . ($count - 1) . ' more)';
    }

    public function getTotalPriceDisplayAttribute(): ?float
    {
        if (!is_null($this->total_price) && $this->total_price !== '') {
            return (float) $this->total_price;
        }

        if ($this->relationLoaded('package') && $this->package && isset($this->package->price)) {
            return (float) $this->package->price;
        }

        if (! $this->relationLoaded('package') && $this->package_id) {
            $pkg = $this->package()->first(['id', 'price']);
            if ($pkg && isset($pkg->price)) return (float) $pkg->price;
        }

        if ($this->relationLoaded('test') && $this->test && isset($this->test->mrp)) {
            return (float) $this->test->mrp;
        }

        if (! $this->relationLoaded('test') && $this->test_id) {
            $t = $this->test()->first(['id', 'mrp']);
            if ($t && isset($t->mrp)) return (float) $t->mrp;
        }

        if ($this->relationLoaded('items')) {
            return (float) $this->items->sum(function ($it) {
                return ($it->item_price * ($it->quantity ?? 1));
            });
        }

        $items = $this->items()->get();
        if ($items->isEmpty()) return null;

        return (float) $items->sum(function ($it) {
            return ($it->item_price * ($it->quantity ?? 1));
        });
    }

    /**
     * Computed net total:
     * - If `subtotal` is present, returns subtotal - discount_amount (never below 0).
     * - Else falls back to `total_price`.
     */
    public function getNetTotalAttribute(): ?float
    {
        if (!is_null($this->subtotal)) {
            $net = (float)$this->subtotal - (float)($this->discount_amount ?? 0);
            return $net > 0 ? $net : 0.0;
        }
        return $this->total_price !== null ? (float)$this->total_price : null;
    }
}