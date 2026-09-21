<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patientName;
    public $testName;
    public $messageBody;
    public $filePath;

    public function __construct($patientName, $testName, $messageBody, $filePath)
    {
        $this->patientName = $patientName;
        $this->testName = $testName;
        $this->messageBody = $messageBody;
        $this->filePath = $filePath;
    }

    public function build()
    {
        $from = config('mail.report_address');
        return $this->from($from['address'], $from['name'])
                    ->subject("Wellcare Labs Report - {$this->patientName}")
                    ->view('emails.report')
                    ->with([
                        'patient' => $this->patientName,
                        'test'    => $this->testName,
                        'body'    => $this->messageBody,
                    ])
                    ->attach($this->filePath, [
                        'as'   => basename($this->filePath),
                        'mime' => 'application/pdf',
                    ]);
    }
}
