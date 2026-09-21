<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PatientAppointmentConfirmationNotification extends Notification
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
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $appointment = $this->appointment;
        $name = $appointment->name ?? 'Valued Customer';
        $date = !empty($appointment->date) ? \Carbon\Carbon::parse($appointment->date)->format('d M, Y') : 'N/A';
        $slot = !empty($appointment->time_slot) ? \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A') : 'N/A';
        $total = number_format((float)($appointment->total_price ?? 0), 2);
        $paymentMethod = ucfirst($appointment->payment_method ?? 'Pending');

        return (new MailMessage)
            ->subject('Appointment Booking Confirmation - Wellcare Labs')
            ->greeting('Dear ' . $name . ',')
            ->line('Thank you for choosing Wellcare Labs! Your appointment has been successfully booked.')
            ->line('### Appointment Summary')
            ->line('• **Appointment ID:** #' . $appointment->id)
            ->line('• **Patient Name:** ' . $name)
            ->line('• **Phone:** ' . ($appointment->phone ?? 'N/A'))
            ->line('• **Test / Package:** ' . ($appointment->service ?? 'Diagnostic Service'))
            ->line('• **Date & Slot:** ' . $date . ' at ' . $slot)
            ->line('• **Payment Method:** ' . $paymentMethod)
            ->line('• **Total Amount:** ₹' . $total)
            ->line('• **Collection Address:** ' . ($appointment->address ? $appointment->address . ', ' . $appointment->city . ' (' . $appointment->pincode . ')' : 'As specified during booking'))
            ->line('Our representative will contact you shortly to confirm sample collection / appointment timing.')
            ->line('If you need to make any changes or have queries, please call us at **+91 91724 37437** or email **support@wellcarelabs.in**.')
            ->salutation('Warm regards,  
Wellcare Labs Team');
    }
}
