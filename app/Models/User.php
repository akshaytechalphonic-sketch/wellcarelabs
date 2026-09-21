<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

// Import both notification types 👇
use App\Notifications\ResetPasswordNotification;
use App\Notifications\HospitalPasswordResetNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // admin or hospital_manager
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship: hospital owned/managed by this user.
     */
    public function ownedHospital()
    {
        return $this->hasOne(Hospital::class, 'owners_user_id');
    }

    /**
     * Alias for ownedHospital for consistency.
     */
    public function hospital()
    {
        return $this->ownedHospital();
    }

    /**
     * Send the correct password reset email based on user role.
     */
    public function sendPasswordResetNotification($token): void
    {
        if ($this->role === 'hospital_manager') {
            $this->notify(new HospitalPasswordResetNotification($token)); // Hospital-specific email
        } else {
            $this->notify(new ResetPasswordNotification($token)); // Default admin reset email
        }
    }

    /**
     * Check if the user is currently online.
     * Users are considered online if they were active in the last 5 minutes.
     */
    public function isOnline(): bool
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    /**
     * Get the user's last activity timestamp.
     */
    public function lastSeenAt()
    {
        return Cache::get('user-is-online-' . $this->id);
    }

    /**
     * Mark user as active (online).
     * Call this method on user activity (e.g., in middleware).
     */
    public function markAsActive(): void
    {
        $expiresAt = now()->addMinutes(5);
        Cache::put('user-is-online-' . $this->id, now(), $expiresAt);
    }

    public function isHospitalManager(): bool
    {
        return $this->role === 'hospital_manager';
    }
}
