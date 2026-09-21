<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentCreatedNotification extends Notification
{
    use Queueable;

    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // DB + real-time
    }

    public function toDatabase($notifiable)
    {
        return [
            'title'   => $this->details['title'] ?? 'Notification',
            'message' => $this->details['message'] ?? '',
            'created_at' => now()->toDateTimeString(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new \Illuminate\Notifications\Messages\BroadcastMessage([
            'title' => $this->details['title'] ?? 'Notification',
            'message' => $this->details['message'] ?? '',
            'created_at' => now()->toDateTimeString(),
        ]);
    }
}
