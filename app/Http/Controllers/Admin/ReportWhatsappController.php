<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\DovesoftService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportWhatsappController extends Controller
{
    protected DovesoftService $dovesoft;

    public function __construct(DovesoftService $dovesoft)
    {
        $this->dovesoft = $dovesoft;
    }

    public function resend(Request $request, Report $report): JsonResponse
    {
        /* -------------------------------------------------
           1️⃣ Validate & normalize mobile number
        ------------------------------------------------- */
        $mobile = (string) $request->input('mobile_number');
        $digits = preg_replace('/\D+/', '', $mobile);

        if (strlen($digits) < 10) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid mobile number.',
            ], 422);
        }

        $to = strlen($digits) === 10 ? '91' . $digits : $digits;

        /* -------------------------------------------------
           2️⃣ Prepare PDF
        ------------------------------------------------- */
        $fileUrl  = route('whatsapp.report.media', $report->id);
        $filename = 'Report-' . $report->id . '.pdf';

        /* -------------------------------------------------
           3️⃣ Template variables
        ------------------------------------------------- */
        $patientName = $report->patient_name ?? 'Patient';

        $testOrPackage =
            $report->test_name
            ?? $report->package_name
            ?? 'Lab Test';

        /* -------------------------------------------------
           4️⃣ Send WhatsApp Template
        ------------------------------------------------- */
        $result = $this->dovesoft->sendTemplate(
            $to,
            'wellcare_reportsss',
            'en',
            [
                $patientName,      // {{1}}
                $testOrPackage,    // {{2}}
            ],
            [
                'link'     => $fileUrl,
                'filename' => $filename,
            ]
        );

        Log::info('WhatsApp report resent', [
            'report_id' => $report->id,
            'to'        => $to,
            'response'  => $result,
        ]);

        if (!($result['successful'] ?? false)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send report on WhatsApp.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Report sent on WhatsApp successfully!',
        ]);
    }
}
