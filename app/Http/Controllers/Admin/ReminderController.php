<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AppointmentReminderService;

class ReminderController extends Controller
{
    public function send()
    {
        $sent = app(AppointmentReminderService::class)
                    ->sendTodayReminders();

        return redirect()->back()->with('success', "Reminders sent: $sent");
    }
}