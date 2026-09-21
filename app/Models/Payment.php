<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'appointment_id', // newly added
        'txnid',
        'status',
        'amount',
        'name',
        'phone',
        'payment_type',
        'addedon',
        // keep other columns you may have (payload/hash/etc) if present
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'addedon' => 'datetime',
    ];

    // Relationship back to appointment (works once appointment_id exists)
    public function appointment()
    {
        return $this->belongsTo(\App\Models\Appointment::class, 'appointment_id');
    }

    // optional: relationship to user if you have users table
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
