<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{

    protected $signature = 'app:send-appointment-reminders';
    protected $description = 'Send WhatsApp reminders';
    
    public function handle()
    {
        $now = now();

        $start = $now->copy()->setTime(5, 0);
        $end   = $now->copy()->setTime(5, 20);

        if ($now->lt($start) || $now->gt($end)) {
            $this->info("Skipped: Outside reminder window");
            return 0;
        }

        $sent = app(\App\Services\AppointmentReminderService::class)
            ->sendTodayReminders();

        $this->info("Reminders sent: {$sent}");

        return 0;
    }
}
