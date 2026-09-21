<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $fillable = [
        'name',
        'unique_id',
        'email',
        'owners_user_id',   // <-- Added so we can assign owner user ID
        'phone',
        'address',
        'owner_name',
        'owner_mobile',
        'doctor_name',
        'doctor_mobile',
    ];

    /**
     * Relationship: hospital belongs to one owner user (hospital manager).
     */
    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owners_user_id');
    }

    /**
     * (Optional alias) A hospital "manager" is the same as owner user.
     */
    public function manager()
    {
        return $this->ownerUser();
    }

    /**
     * Relationship: a hospital can have many appointments.
     */
    public function appointments()
    {
        return $this->hasMany(\App\Models\Appointment::class);
    }
}
