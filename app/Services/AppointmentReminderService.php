<?php
namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AppointmentReminderService
{
    public function sendTodayReminders()
    {
        $now = Carbon::now();

        $today = $now->toDateString();
        $sent = 0;

        Appointment::whereDate('date', $today)
            ->whereIn('status', ['Pending', 'Approved', 'Reschedule'])
            ->whereNull('reminder_sent_at')
            ->chunk(50, function ($appointments) use (&$sent) {

                foreach ($appointments as $appointment) {

                    if (empty($appointment->phone)) continue;

                    try {
                        $mobile = preg_replace('/\D+/', '', $appointment->phone);
                        if (strlen($mobile) === 10) {
                            $mobile = '91' . $mobile;
                        }

                        $formattedDate = $appointment->date
                            ? \Carbon\Carbon::parse($appointment->date)->format('d/m/Y')
                            : '';

                        $formattedSlot = $appointment->time_slot
                            ? \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A')
                            : '';

                        $slot = trim($formattedDate . ' ' . $formattedSlot);

                        $bodyParams = [
                            $appointment->name ?? '',
                            $slot,
                            (string) ($appointment->service ?? ''),
                            $appointment->name ?? '',
                            (string) ($appointment->age ?? ''),
                            (string) ($appointment->gender ?? ''),
                        ];

                        app(\App\Services\DovesoftService::class)->sendTemplate(
                            $mobile,
                            'reminderupdated',
                            'en',
                            $bodyParams
                        );

                        $appointment->reminder_sent_at = now();
                        $appointment->save();

                        $sent++;

                    } catch (\Throwable $e) {
                        Log::error('Reminder failed', [
                            'appointment_id' => $appointment->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            });

        return $sent;
    }
}