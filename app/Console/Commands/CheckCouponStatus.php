<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use App\Models\User;
use App\Notifications\CouponStatusNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckCouponStatus extends Command
{
    /**
     * Run with: php artisan coupons:check-status
     */
    protected $signature = 'coupons:check-status';

    protected $description = 'Send notifications for coupons that go live or are expiring soon';

    public function handle(): int
    {
        $today = Carbon::today('Asia/Kolkata');
        $now = now('Asia/Kolkata');
        $expiringInDays = 3;

        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            $this->warn('No admin users found to notify.');
            return Command::SUCCESS;
        }

        // 1️⃣ Coupons that START today (becoming live)
        $startingToday = Coupon::whereDate('starts_at', $today)
            ->where('is_active', false)   // <-- IMPORTANT
            ->get();

        foreach ($startingToday as $coupon) {
            $coupon->update(['is_active' => true]); // mark live
            foreach ($admins as $admin) {
                $admin->notify(new CouponStatusNotification($coupon, 'live'));
            }
        }

        // 2️⃣ Coupons expiring soon (in 3 days)
        $expiringDate = $today->copy()->addDays($expiringInDays);

        $expiringSoon = Coupon::where('is_active', true)
            ->whereDate('expires_at', $expiringDate)
            ->get();

        foreach ($expiringSoon as $coupon) {
            foreach ($admins as $admin) {
                $admin->notify(new CouponStatusNotification($coupon, 'expiring'));
            }
        }

        $this->info("Coupons activated today: {$startingToday->count()}");
        $this->info("Coupons expiring in {$expiringInDays} days: {$expiringSoon->count()}");

        return Command::SUCCESS;
    }
}
