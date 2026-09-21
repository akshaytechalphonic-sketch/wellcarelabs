<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HospitalDeleteOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $hospitalName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otp, string $hospitalName)
    {
        $this->otp = $otp;
        $this->hospitalName = $hospitalName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $from = config('mail.info_address');
        return new Envelope(
            from: new Address($from['address'], $from['name']),
            subject: 'OTP for Hospital Deletion'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.hospital-delete-otp',
            with: [
                'otp' => $this->otp,
                'hospitalName' => $this->hospitalName,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
