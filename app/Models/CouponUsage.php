<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    protected $table = 'coupon_usages';

    protected $fillable = [
        'coupon_id','user_id','appointment_id','quantity','used_at',
    ];

    protected $casts = [
        'coupon_id'      => 'integer',
        'user_id'        => 'integer',
        'appointment_id' => 'integer',
        'quantity'       => 'integer',
        'used_at'        => 'datetime:Asia/Kolkata',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
