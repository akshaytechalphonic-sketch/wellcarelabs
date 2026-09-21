<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Report;
use App\Services\DovesoftService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppointmentWhatsappController extends Controller
{
    protected DovesoftService $dovesoft;

    public function __construct(DovesoftService $dovesoft)
    {
        $this->dovesoft = $dovesoft;
    }

    public function send(Request $request, Appointment $appointment)
    {
        /* -------------------------------------------------
           1️⃣ Resolve WhatsApp number
        ------------------------------------------------- */
        $to = $appointment->mobile_with_country
            ?? $appointment->mobile
            ?? $appointment->phone
            ?? null;

        if (!$to) {
            return back()->with('error', 'No mobile number found.');
        }

        // Normalize Indian numbers
        $digits = preg_replace('/\D+/', '', $to);
        if (strlen($digits) === 10) {
            $to = '91' . $digits;
        }

        /* -------------------------------------------------
           2️⃣ Resolve Report
        ------------------------------------------------- */
        $report = $appointment->report
            ?? Report::find($appointment->report_id);

        if (!$report || empty($report->report_file)) {
            return back()->with('error', 'No report PDF found.');
        }

        /* -------------------------------------------------
           3️⃣ Prepare public PDF
        ------------------------------------------------- */
        $publicUrl = route('whatsapp.report.media', $report->id);
        $filename  = basename($report->report_file)
            ?: "Report-{$appointment->id}.pdf";

        /* -------------------------------------------------
           4️⃣ Template variables
        ------------------------------------------------- */
        $patientName = $appointment->name ?? 'Patient';

        $testOrPackage =
            $appointment->service
            ?? $appointment->package_name
            ?? 'Lab Test';

        /* -------------------------------------------------
           5️⃣ Send WhatsApp Template
        ------------------------------------------------- */
        $result = $this->dovesoft->sendTemplate(
            $to,
            'reportupdatednew',
            'en',
            [
                $patientName,      // {{1}}
                $testOrPackage,    // {{2}}
            ],
            [
                'link'     => $publicUrl,
                'filename' => $filename,
            ]
        );

        Log::info('WhatsApp appointment report sent', [
            'appointment_id' => $appointment->id,
            'to'             => $to,
            'response'       => $result,
        ]);

        if (!($result['successful'] ?? false)) {
            return back()->with('error', 'Failed to send report on WhatsApp.');
        }

        return back()->with('success', 'Report sent on WhatsApp successfully!');
    }
}
