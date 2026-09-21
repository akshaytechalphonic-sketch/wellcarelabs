<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class HospitalPasswordCreateNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Welcome to WellCare — Create Your Hospital Admin Password')
            ->view('emails.hospital-create-password', [
                'name' => $notifiable->name ?? 'Partner',
                'resetUrl' => $url,
            ]);
    }
}
