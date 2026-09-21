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
        // Start with all details you passed
        $data = is_array($this->details) ? $this->details : (array) $this->details;

        // Ensure required keys
        $data['title']      = $data['title']   ?? 'Notification';
        $data['message']    = $data['message'] ?? '';
        $data['created_at'] = now()->toDateTimeString();

        return $data;
    }

    public function toBroadcast($notifiable)
    {
        $data = is_array($this->details) ? $this->details : (array) $this->details;

        $data['title']      = $data['title']   ?? 'Notification';
        $data['message']    = $data['message'] ?? '';
        $data['created_at'] = now()->toDateTimeString();

        return new \Illuminate\Notifications\Messages\BroadcastMessage($data);
    }

}
