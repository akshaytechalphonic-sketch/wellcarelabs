<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PendingBooking extends Model
{
    protected $table = 'pending_bookings';

    protected $fillable = [
        'txnid', 'payer_email', 'snapshot', 'gateway', 'expires_at',
    ];

    protected $casts = [
        'snapshot' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Create a PendingBooking from snapshot.
     *
     * @param string $txnid
     * @param array $snapshot
     * @param string|null $payerEmail
     * @param int $hoursValid
     * @return self
     */
    public static function createFromSnapshot(string $txnid, array $snapshot, ?string $payerEmail = null, int $hoursValid = 6): self
    {
        return self::create([
            'txnid' => $txnid,
            'payer_email' => $payerEmail,
            'snapshot' => $snapshot,
            'gateway' => 'easebuzz',
            'expires_at' => Carbon::now()->addHours($hoursValid),
        ]);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
