<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentStatusNotification extends Notification
{
    use Queueable;

    protected Appointment $appointment;
    protected string $event; // e.g. 'status_changed', 'rescheduled'

    public function __construct(Appointment $appointment, string $event = 'status_changed')
    {
        $this->appointment = $appointment;
        $this->event       = $event;
    }

    public function via($notifiable): array
    {
        // Using only database for now (bell + dropdown)
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $a = $this->appointment;

        $patient = $a->name ?? 'Patient';
        $service = $a->service
            ?? $a->package_name
            ?? optional($a->test)->test_name
            ?? 'Service';

        $date = $a->date ?? null;
        $time = $a->time_slot ?? null;

        // ---------- Title ----------
        if ($this->event === 'rescheduled') {
            $title = 'Appointment Rescheduled';
        } else {
            $title = 'Appointment Status Updated';
        }

        // ---------- Message ----------
        if ($this->event === 'rescheduled') {
            $message = "Appointment  for {$patient} has been rescheduled";
        } else {
            $status  = $a->status ?? 'Updated';
            $message = "Appointment for {$patient} marked as {$status}";
        }

        if ($date) {
            $message .= " on {$date}";
        }
        if ($time) {
            $message .= " at {$time}";
        }

        return [
            'title'          => $title,
            'message'        => $message,
            'event'          => $this->event,          // 'status_changed' | 'rescheduled'
            'status'         => $a->status,
            'patient_name'   => $patient,
            'phone'          => $a->phone,
            'service'        => $service,
            'date'           => $date,
            'time'           => $time,
            'url'            => route('admin.appointments.show', $a->id),
        ];
    }
}