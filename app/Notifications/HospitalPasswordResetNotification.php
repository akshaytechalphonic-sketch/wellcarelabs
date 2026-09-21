<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class HospitalPasswordResetNotification extends Notification
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
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('WellCare Hospital Admin – Reset Password')
            ->view('emails.hospital-reset-password', [
                'name' => $notifiable->name ?? 'Partner',
                'resetUrl' => $resetUrl,
            ]);
    }
}
