<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WhatsappController;
use App\Models\Report;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Tcpdf\Fpdi;
use App\Mail\ReportMail;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::latest()->paginate(10);
        return view('admin.reports.index', compact('reports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name'   => 'required|string|max:255',
            'test_name'      => 'required|string|max:255',
            'report_file'    => 'required|mimes:pdf|max:10240',
            'report_date'    => 'required|date',
            'appointment_id' => 'nullable|integer|exists:appointments,id',
            'mobile_number'  => 'nullable|string|max:64', // optional, used for WhatsApp
            'prefill_message'=> 'nullable|string|max:1000',
        ], [
            'patient_name.required' => 'Please enter the patient name.',
            'test_name.required'    => 'Please enter the test name.',
            'report_file.required'  => 'Please upload a PDF report.',
            'report_file.mimes'     => 'Only PDF files are allowed.',
        ]);

        $appointmentId = $request->input('appointment_id');

        // ensure reports folder exists
        $reportsDir = storage_path('app/public/reports');
        if (!is_dir($reportsDir)) {
            @mkdir($reportsDir, 0755, true);
        }

        // choose final filename
        $fileName = 'report_' . time() . '_' . uniqid() . '.pdf';
        $publicRelative = 'reports/' . $fileName;
        $outputPath = storage_path('app/public/' . $publicRelative);

        $uploaded = $request->file('report_file');

        // Only header stamping (full-width banner at top), thinner vertically
        $headerImage = public_path('assets/images/wellcare_header.png');

        try {
            $uploadedPdfPath = $uploaded->getPathname();

            $pdf = new Fpdi();
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetAutoPageBreak(false);
            $pdf->SetCreator('Wellcare Labs');
            $pdf->SetTitle('Wellcare Report - ' . $request->patient_name);

            $pageCount = $pdf->setSourceFile($uploadedPdfPath);

            // Controls how THIN the header appears (vertical thickness)
            $bannerHeight = 26;

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $tplId = $pdf->importPage($pageNo);
                $tplSize = $pdf->getTemplateSize($tplId);
                $orientation = ($tplSize['width'] > $tplSize['height']) ? 'L' : 'P';

                // Keep original page size
                $pdf->AddPage($orientation, [$tplSize['width'], $tplSize['height']]);

                // Place original page content
                $pdf->useTemplate($tplId, 0, 0, $tplSize['width'], $tplSize['height'], true);

                // Draw header (slim): full width, but vertically clipped to $bannerHeight
                if (file_exists($headerImage)) {
                    if (method_exists($pdf, 'ClippingRect') && method_exists($pdf, 'UnsetClipping')) {
                        $pdf->ClippingRect(0, 0, $tplSize['width'], $bannerHeight, true);
                        $pdf->Image(
                            $headerImage,
                            0,
                            0,
                            $tplSize['width'],
                            0,
                            '',
                            '',
                            '',
                            false,
                            300,
                            '',
                            false,
                            false,
                            0
                        );
                        $pdf->UnsetClipping();
                    } else {
                        $pdf->Image(
                            $headerImage,
                            0,
                            0,
                            $tplSize['width'],
                            $bannerHeight,
                            '',
                            '',
                            '',
                            false,
                            300,
                            '',
                            false,
                            false,
                            0
                        );
                    }
                }
            }

            // Save modified PDF
            $pdf->Output($outputPath, 'F');
        } catch (\Exception $e) {
            // Fallback: save original upload as-is if stamping fails
            Log::error('PDF stamping failed: ' . $e->getMessage());
            $uploaded->move(dirname($outputPath), basename($outputPath));
        }

        // Save record
        $report = Report::create([
            'patient_name' => $request->patient_name,
            'test_name'    => $request->test_name,
            'report_file'  => $publicRelative,
            'report_date'  => $request->report_date,
        ]);

        // If appointment_id provided, mark appointment completed and optionally link report_id
        if ($appointmentId) {
            try {
                $appointment = Appointment::find($appointmentId);
                if ($appointment) {
                    $appointment->status = 'completed';
                    if (Schema::hasColumn('appointments', 'report_id')) {
                        $appointment->report_id = $report->id;
                    }
                    $appointment->save();
                }
            } catch (\Exception $e) {
                Log::warning("Failed to update appointment #{$appointmentId}: " . $e->getMessage());
            }
        }

        // Build public URL for the stored report
        try {
            $reportUrl = Storage::disk('public')->url($publicRelative);
        } catch (\Exception $e) {
            Log::warning('Failed to build public URL for report: ' . $e->getMessage());
            // fallback to local path
            $reportUrl = url('/storage/' . $publicRelative);
        }

        $shareMessage = $request->input('prefill_message') ?: ("Wellcare Labs - Report for " . ($report->patient_name ?? $request->patient_name) . " (" . ($report->test_name ?? 'Report') . ").");

        //
        // === WhatsApp sending (optional) ===
        // Trigger when either mobile_number provided in request OR appointment has phone
        //
        $whatsappResult = null;
        $mobileRaw = $request->input('mobile_number') ?: null;

        // if not provided, fall back to appointment phone
        if (!$mobileRaw && $appointmentId) {
            try {
                $appt = Appointment::find($appointmentId);
                if ($appt && !empty($appt->phone)) {
                    $mobileRaw = $appt->phone;
                }
            } catch (\Exception $e) {
                Log::warning("Failed to load appointment #{$appointmentId} for whatsapp fallback: " . $e->getMessage());
            }
        }

        if ($mobileRaw) {
            // Normalize digits only
            $phoneDigits = preg_replace('/\D+/', '', $mobileRaw);

            // If only local 10-digit number given, prepend default country code (change as needed)
            if (strlen($phoneDigits) <= 10) {
                $phoneDigits = '91' . ltrim($phoneDigits, '0'); // <-- change '91' if required
            }

            // Ensure file URL is absolute and accessible - we try to use the public disk URL above
            $fileUrl = $reportUrl;

            try {
                /** @var WhatsappController $waClient */
                $waClient = app(WhatsappController::class);

                // Use the filename from stored path
                $filename = basename($publicRelative);

                // Attempt to send document (controller handles fallback to text)
                $response = $waClient->sendDocument($phoneDigits, $fileUrl, $filename, $shareMessage);

                // Log and prepare flash info (response may be \Illuminate\Http\Client\Response)
                if (is_object($response) && method_exists($response, 'status')) {
                    $status = $response->status();
                    $body = (method_exists($response, 'body') ? $response->body() : null);

                    Log::info('WhatsApp send attempted', [
                        'to' => $phoneDigits,
                        'report_id' => $report->id,
                        'status' => $status,
                        'response' => Str::limit($body, 1000),
                    ]);

                    $whatsappResult = [
                        'ok' => $response->ok(),
                        'status' => $status,
                        'body' => $body,
                    ];
                } else {
                    // If the WhatsappController returned a non-Http response, just log generic success
                    Log::info('WhatsApp send attempted (non-http response)', [
                        'to' => $phoneDigits,
                        'report_id' => $report->id,
                        'response' => (string) $response,
                    ]);
                    $whatsappResult = ['ok' => true, 'status' => null, 'body' => (string)$response];
                }
            } catch (\Throwable $e) {
                Log::error('WhatsApp send failed: ' . $e->getMessage(), [
                    'to' => $phoneDigits,
                    'report_id' => $report->id,
                ]);
                $whatsappResult = ['ok' => false, 'error' => $e->getMessage()];
            }
        }

        // Prepare redirect with flash info
        $flash = [
            'report_uploaded' => true,
            'report_url' => $reportUrl,
            'share_message' => $shareMessage,
        ];
        if (!is_null($whatsappResult)) {
            $flash['whatsapp_result'] = $whatsappResult;
        }

        if ($appointmentId) {
            return redirect()->route('admin.appointments.show', $appointmentId)
                            ->with($flash)
                            ->with('success', 'Report uploaded and formatted successfully!');
        }

        return redirect()->route('admin.reports.index')
                        ->with($flash)
                        ->with('success', 'Report uploaded and formatted successfully!');
    }

    public function download(Report $report)
    {
        return Storage::disk('public')->download($report->report_file);
    }

    public function view(Report $report)
    {
        return response()->file(storage_path('app/public/' . $report->report_file));
    }

    public function destroy(Report $report)
    {
        if ($report->report_file && Storage::disk('public')->exists($report->report_file)) {
            Storage::disk('public')->delete($report->report_file);
        }

        $report->delete();

        return redirect()->route('admin.reports.index')
                         ->with('danger', 'Report deleted successfully.');
    }

    /**
     * Share the report via email with PDF attachment
     */
    public function shareByEmail(Request $request, Report $report)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'nullable|string'
        ]);

        $filePath = storage_path('app/public/' . $report->report_file);

        if (!file_exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'Report file not found.'], 404);
        }

        $messageBody = $request->message ?: "Please find attached the report for {$report->patient_name} ({$report->test_name}).";

        try {
            Mail::to($request->email)->send(
                new ReportMail($report->patient_name, $report->test_name, $messageBody, $filePath)
            );
            return response()->json(['success' => true, 'message' => 'Report emailed successfully.']);
        } catch (\Exception $e) {
            Log::error('Report email failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
