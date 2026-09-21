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
use setasign\Fpdi\Tcpdf\Fpdi;
use App\Mail\ReportMail;
use Illuminate\Support\Str;

// ⬇️ Export deps
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * List reports in admin (UI table).
     */
    public function index(Request $request)
    {
        // Check what columns exist on appointments to avoid 42S22
        $hasMobileWithCountry = Schema::hasColumn('appointments', 'mobile_with_country');
        $hasMobile            = Schema::hasColumn('appointments', 'mobile');
        $hasPhone             = Schema::hasColumn('appointments', 'phone');

        $query = Report::query()
            ->leftJoin('appointments', 'appointments.report_id', '=', 'reports.id')
            ->select('reports.*');

        // 🔍 Search
        if ($search = trim($request->get('q', ''))) {
            $query->where(function ($q) use ($search, $hasMobileWithCountry, $hasMobile, $hasPhone) {
                $q->where('reports.patient_name', 'like', "%{$search}%")
                    ->orWhere('reports.test_name', 'like', "%{$search}%")
                    ->orWhereDate('reports.report_date', $search);

                if ($hasMobileWithCountry) {
                    $q->orWhere('appointments.mobile_with_country', 'like', "%{$search}%");
                }
                if ($hasMobile) {
                    $q->orWhere('appointments.mobile', 'like', "%{$search}%");
                }
                if ($hasPhone) {
                    $q->orWhere('appointments.phone', 'like', "%{$search}%");
                }
            });
        }

        // 📅 Date range filter
        if ($from = $request->get('date_from')) {
            $query->whereDate('reports.report_date', '>=', $from);
        }
        if ($to = $request->get('date_to')) {
            $query->whereDate('reports.report_date', '<=', $to);
        }

        // ⬇️ ALWAYS latest first (no user sorting)
        $query->orderByDesc('reports.report_date')
            ->orderByDesc('reports.created_at');

        $reports = $query->paginate(20)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Used ONLY for export (no joins, so no column-not-found issues).
     * Filters: q (patient/test/date) + date_from/date_to
     */
    protected function buildExportQuery(Request $request)
    {
        $query = Report::query();

        if ($search = trim($request->get('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                    ->orWhere('test_name', 'like', "%{$search}%")
                    ->orWhereDate('report_date', $search);
            });
        }

        if ($from = $request->get('date_from')) {
            $query->whereDate('report_date', '>=', $from);
        }
        if ($to = $request->get('date_to')) {
            $query->whereDate('report_date', '<=', $to);
        }

        return $query->orderByDesc('report_date')
            ->orderByDesc('created_at');
    }

    /**
     * Export reports with current filters to PDF or Excel.
     * /admin/reports/export?format=pdf|excel&q=...&date_from=...&date_to=...
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel'); // default: excel

        // Same filter logic as UI list, but without pagination
        $reports = $this->buildExportQuery($request)->get();

        if ($reports->isEmpty()) {
            return back()->with('error', 'No reports found to export.');
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.export-pdf', [
                'reports' => $reports,
            ])->setPaper('a4', 'portrait');

            return $pdf->download('reports-' . now()->format('Ymd-His') . '.pdf');
        }

        // Excel (default)
        return Excel::download(
            new ReportsExport($reports),
            'reports-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name'    => 'required|string|max:255',
            'test_name'       => 'required|string|max:255',
            'report_file'     => 'required|mimes:pdf|max:10240',
            'report_date'     => 'required|date',
            'appointment_id'  => 'nullable|integer|exists:appointments,id',
            'mobile_number'   => 'nullable|string|max:64',
            'prefill_message' => 'nullable|string|max:1000',
        ]);

        $appointmentId = $request->input('appointment_id');

        /* -------------------------------------------------
       1️⃣ Ensure reports directory
    ------------------------------------------------- */
        $reportsDir = storage_path('app/public/reports');
        if (!is_dir($reportsDir)) {
            @mkdir($reportsDir, 0755, true);
        }

        /* -------------------------------------------------
       2️⃣ Prepare filename (version-safe)
    ------------------------------------------------- */
        $patientSlug = Str::slug($request->patient_name);
        $reportDate  = date('Y-m-d', strtotime($request->report_date));

        $baseFileName = "{$patientSlug}_{$reportDate}.pdf";
        $fileName = $baseFileName;
        $counter = 1;

        while (file_exists(storage_path('app/public/reports/' . $fileName))) {
            $fileName = str_replace('.pdf', "_v{$counter}.pdf", $baseFileName);
            $counter++;
        }

        $publicRelative = 'reports/' . $fileName;
        $outputPath = storage_path('app/public/' . $publicRelative);

        /* -------------------------------------------------
       3️⃣ Stamp header/footer if possible
    ------------------------------------------------- */
        $uploaded = $request->file('report_file');
        $headerImage = public_path('assets/images/wellcare_header.jpg');

        try {
            $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetAutoPageBreak(false);
            $pdf->SetCreator('Wellcare Labs');
            $pdf->SetTitle('Wellcare Report - ' . $request->patient_name);

            $pageCount = $pdf->setSourceFile($uploaded->getPathname());
            $bannerHeight = 26;

            for ($i = 1; $i <= $pageCount; $i++) {
                $tplId = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tplId);
                $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';

                $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                $pdf->useTemplate($tplId, 0, 0, $size['width'], $size['height'], true);

                if (file_exists($headerImage)) {
                    $pdf->Image($headerImage, 0, 0, $size['width'], $bannerHeight);
                }
            }

            $pdf->Output($outputPath, 'F');
        } catch (\Throwable $e) {
            // fallback: save raw PDF
            $uploaded->move(dirname($outputPath), basename($outputPath));
        }

        /* -------------------------------------------------
       4️⃣ Create Report
    ------------------------------------------------- */
        $report = Report::create([
            'patient_name' => $request->patient_name,
            'test_name'    => $request->test_name,
            'report_file'  => $publicRelative,
            'report_date'  => $request->report_date,
        ]);

        /* -------------------------------------------------
       5️⃣ Update appointment (VERY IMPORTANT)
    ------------------------------------------------- */
        if ($appointmentId) {
            $appointment = Appointment::find($appointmentId);
            if ($appointment) {
                $appointment->status = 'completed';
                if (Schema::hasColumn('appointments', 'report_id')) {
                    $appointment->report_id = $report->id;
                }
                $appointment->save();
            }
        }

        /* -------------------------------------------------
       6️⃣ Prepare response data
    ------------------------------------------------- */
        $reportUrl = route('admin.reports.view', $report->id);

        /* -------------------------------------------------
       7️⃣ AJAX vs normal submit (🔥 KEY FIX)
    ------------------------------------------------- */
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'        => true,
                'report_id'      => $report->id,
                'appointment_id' => $appointmentId,
                'report_url'     => $reportUrl,
                'message'        => 'Report uploaded successfully.',
            ]);
        }

        /* -------------------------------------------------
       8️⃣ Fallback: normal redirect
    ------------------------------------------------- */
        return redirect()
            ->route('admin.reports.index')
            ->with('success', 'Report uploaded successfully!');
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
     * 📧 Share via Email
     */
    public function shareEmail(Request $request, Report $report)
    {
        $data = $request->validate([
            'email'   => 'required|email',
            'message' => 'nullable|string',
        ]);

        if (!$report->report_file) {
            return response()->json([
                'message' => 'Report file not found.',
            ], 404);
        }

        $filePath = storage_path('app/public/' . $report->report_file);

        if (!file_exists($filePath)) {
            return response()->json([
                'message' => 'Report file missing on server.',
            ], 404);
        }

        $messageBody = $data['message']
            ?: "Please find attached the report for {$report->patient_name} ({$report->test_name}).";

        try {
            Mail::to($data['email'])->send(
                new ReportMail(
                    $report->patient_name,
                    $report->test_name,
                    $messageBody,
                    $filePath
                )
            );

            return response()->json([
                'message' => 'Report emailed successfully.',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to send email.',
            ], 500);
        }
    }
}
