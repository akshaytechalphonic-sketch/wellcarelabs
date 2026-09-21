<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(\Illuminate\Foundation\Inspiring::quote());
})->purpose('Display an inspiring quote');


// ✅ Coupon sync (OK as is)
Schedule::command('coupons:sync-status')
    ->everyFifteenMinutes()
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping();


// ✅ Appointment reminders (FIXED for 15-min cron)
Schedule::command('app:send-appointment-reminders')
    ->everyFifteenMinutes()
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping();


// ✅ Delete old reports (safe daily)
Schedule::command('reports:delete-old')
    ->dailyAt('02:00')
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping();


// ✅ Coupon notifications (safe daily)
Schedule::command('coupons:check-status')
    ->dailyAt('00:05')
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping();


// ✅ Delete notifications older than 30 days
Schedule::call(function () {
    DB::table('notifications')
        ->where('created_at', '<', now()->subDays(30))
        ->delete();
})
->daily()
->timezone('Asia/Kolkata');