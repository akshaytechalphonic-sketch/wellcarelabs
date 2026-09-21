<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }

    protected function schedule(Schedule $schedule): void
    {
        // ✅ Coupon auto toggle
        $schedule->command('coupons:auto-toggle')
            ->everyFiveMinutes()
            ->timezone('Asia/Kolkata')
            ->withoutOverlapping();

        // ✅ Sync coupon status
        $schedule->command('coupons:sync-status')
            ->everyFifteenMinutes()
            ->timezone('Asia/Kolkata')
            ->withoutOverlapping();

        // ✅ Appointment reminders (FIXED)
        $schedule->command('app:send-appointment-reminders')
            ->everyFifteenMinutes()
            ->timezone('Asia/Kolkata')
            ->withoutOverlapping();

        // ✅ Delete reports
        $schedule->command('reports:delete-old')
            ->dailyAt('02:00')
            ->timezone('Asia/Kolkata')
            ->withoutOverlapping();

        // ✅ Coupon notifications
        $schedule->command('coupons:check-status')
            ->dailyAt('00:05')
            ->timezone('Asia/Kolkata')
            ->withoutOverlapping();
    }
}