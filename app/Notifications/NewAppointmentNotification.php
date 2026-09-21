<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewAppointmentNotification extends Notification
{
    use Queueable;

    /**
     * @var \App\Models\Appointment
     */
    protected $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Data stored in `notifications.data` (for database channel).
     */
    public function toDatabase($notifiable): array
    {
        $appointment = $this->appointment;

        $formattedDate = null;
        if (!empty($appointment->date)) {
            try {
                $formattedDate = \Carbon\Carbon::parse($appointment->date)->format('Y-m-d');
            } catch (\Throwable $e) {
                $formattedDate = (string)$appointment->date;
            }
        }

        return [
            'type'    => 'appointment_created',
            'title'   => 'New Appointment Booked',
            'message' => "New appointment booked by " . ($appointment->name ?? 'Patient'),

            'appointment' => [
                'id'        => $appointment->id,
                'name'      => $appointment->name ?? null,
                'phone'     => $appointment->phone ?? null,
                'service'   => $appointment->service ?? null,
                'date'      => $formattedDate,
                'time_slot' => $appointment->time_slot ?? null,
            ],

            'url' => route('admin.appointments.show', $appointment->id),
        ];
    }

    /**
     * Mail notification representation for admin.
     */
    public function toMail($notifiable): MailMessage
    {
        $appointment = $this->appointment;
        $date = !empty($appointment->date) ? \Carbon\Carbon::parse($appointment->date)->format('d M, Y') : 'N/A';
        $slot = !empty($appointment->time_slot) ? \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A') : 'N/A';
        $total = number_format((float)($appointment->total_price ?? 0), 2);

        return (new MailMessage)
            ->subject('New Appointment Booked - #' . $appointment->id . ' - Wellcare Labs')
            ->greeting('Hello Admin,')
            ->line('A new appointment has been booked on Wellcare Labs.')
            ->line('• **Patient Name:** ' . ($appointment->name ?? 'N/A'))
            ->line('• **Phone:** ' . ($appointment->phone ?? 'N/A'))
            ->line('• **Email:** ' . ($appointment->email ?? 'N/A'))
            ->line('• **Test / Package:** ' . ($appointment->service ?? 'N/A'))
            ->line('• **Date & Slot:** ' . $date . ' at ' . $slot)
            ->line('• **Total Price:** ₹' . $total)
            ->line('• **Address:** ' . ($appointment->address ? $appointment->address . ', ' . $appointment->city . ' (' . $appointment->pincode . ')' : 'N/A'))
            ->action('View Appointment Details', route('admin.appointments.show', $appointment->id))
            ->line('Thank you for managing Wellcare Labs!');
    }

    /**
     * Array form (used if you call `toArray` somewhere).
     */
    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
