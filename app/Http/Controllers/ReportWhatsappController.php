<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\DovesoftService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportWhatsappController extends Controller
{
    protected DovesoftService $dovesoft;

    public function __construct(DovesoftService $dovesoft)
    {
        $this->dovesoft = $dovesoft;
    }

    /**
     * POST /admin/reports/{report}/resend-whatsapp
     */
    public function resend(Request $request, Report $report): JsonResponse
    {
        // Ask mobile number from SweetAlert input
        $mobile = (string) $request->input('mobile_number');
        $digits = preg_replace('/\D+/', '', $mobile);

        if (strlen($digits) < 10) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid mobile number.',
            ], 422);
        }

        // If only 10 digits => make it +91 automatically
        if (strlen($digits) === 10) {
            $to = '91' . $digits;
        } else {
            $to = $digits;
        }

        // Generate public PDF link using your existing download route
        $fileUrl = route('admin.reports.download', $report->id);

        $filename = 'Report-' . $report->id . '.pdf';
        $patient  = $report->patient_name ?? 'Patient';
        $test     = $report->test_name ?? 'Lab Test';

        $caption = "Hi {$patient}, your report for {$test} is attached.";

        // Send PDF via WhatsApp using Dovesoft
        $result = $this->dovesoft->sendReportDocument(
            $to,
            $fileUrl,
            $filename,
            $caption
        );

        logger()->info('WhatsApp Report Log', [
            'report_id' => $report->id,
            'to'        => $to,
            'result'    => $result,
        ]);

        $success = $result['successful'] ?? false;

        return response()->json([
            'success' => $success,
            'message' => $success
                ? 'Report sent on WhatsApp successfully!'
                : 'Failed to send report on WhatsApp.',
        ], $success ? 200 : 500);
    }
}
