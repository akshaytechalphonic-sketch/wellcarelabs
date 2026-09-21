<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Exports\InquiriesExport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class InquiryController extends Controller
{
    /**
     * Display all contact inquiries with search, date filter and pagination.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        [$dateFrom, $dateTo] = $this->validateFilterDates($request);

        // Define accepted statuses (keep this in sync with updateStatus Rule)
        $statuses = ['Pending', 'Called', 'Interested', 'Booked', 'Lost'];

        // If query exactly matches one of the statuses (case-insensitive),
        // filter by status only. Otherwise perform the multi-column search.
        $isStatusQuery = $q !== '' && in_array(strtolower($q), array_map('strtolower', $statuses), true);

        $inquiries = Inquiry::query()
            ->when($isStatusQuery, function ($query) use ($q) {
                // exact match on status (case-insensitive)
                $query->whereRaw('LOWER(status) = ?', [strtolower($q)]);
            }, function ($query) use ($q) {
                if ($q === '') {
                    return;
                }
                // general search across fields
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('message', 'like', "%{$q}%")
                        ->orWhere('status', 'like', "%{$q}%");
                });
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        // pass date values as Y-m-d strings for the inputs
        $dateFromStr = $dateFrom ? $dateFrom->format('Y-m-d') : '';
        $dateToStr   = $dateTo ? $dateTo->format('Y-m-d') : '';

        return view('admin.inquiries.index', [
            'inquiries' => $inquiries,
            'q'         => $q,
            'dateFrom'  => $dateFromStr,
            'dateTo'    => $dateToStr,
        ]);
    }

    /**
     * Store a new inquiry (public form).
     */
    public function store(Request $request)
    {
     
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'prescription' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'message' => 'nullable|string|max:2000',
        ]);

        if ($request->hasFile('prescription')) {
            $file = $request->file('prescription');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('prescriptions', $filename, 'public');
            $data['prescription'] = $path;
        }

        $inquiry = Inquiry::create($data);

        // Send WhatsApp notification to Admin
        try {
            $adminNumber = config('services.dovesoft.admin_number');
            if ($adminNumber) {
                $digits = preg_replace('/\D+/', '', $adminNumber);
               
                if (strlen($digits) === 10) {
                    $digits = '91' . $digits;
                }
                
                if ($digits) {
                    app(\App\Services\DovesoftService::class)->sendTemplate(
                        $digits,
                        'contact',
                        'en',
                        []
                    );
                }
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to send inquiry WhatsApp notification to admin', [
                'inquiry_id' => $inquiry->id,
                'error'      => $e->getMessage()
            ]);
        }

        return redirect()->back()->with('success', 'Thank you — your inquiry has been submitted.');
    }

    /**
     * Update inquiry status via AJAX.
     */
    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Pending', 'Called', 'Interested', 'Booked', 'Lost'])],
        ]);

        try {
            $inquiry->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
                'status'  => $inquiry->status,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Failed to update inquiry status', [
                'id'    => $inquiry->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status. Please try again.',
            ], 500);
        }
    }

    /**
     * Delete an inquiry.
     */
    public function destroy(Request $request, Inquiry $inquiry)
    {
        try {
            $inquiry->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inquiry deleted successfully.',
                ]);
            }

            return redirect()
                ->route('admin.inquiries.index')
                ->with('success', 'Inquiry deleted successfully.');
        } catch (\Throwable $e) {
            \Log::error('Failed to delete inquiry', [
                'id'    => $inquiry->id,
                'error' => $e->getMessage(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete inquiry. Please try again.',
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to delete inquiry. Please try again.');
        }
    }

    /**
     * Show a single inquiry.
     */
    public function show(Inquiry $inquiry)
    {
        return view('admin.inquiries.show', compact('inquiry'));
    }

    /**
     * Export filtered inquiries to PDF (same UI style as Appointments).
     */
    public function exportPdf(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        [$dateFrom, $dateTo] = $this->validateFilterDates($request);

        $statuses      = ['Pending', 'Called', 'Interested', 'Booked', 'Lost'];
        $isStatusQuery = $q !== '' && in_array(strtolower($q), array_map('strtolower', $statuses), true);

        $inquiries = Inquiry::query()
            ->when($isStatusQuery, function ($query) use ($q) {
                $query->whereRaw('LOWER(status) = ?', [strtolower($q)]);
            }, function ($query) use ($q) {
                if ($q === '') return;
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('message', 'like', "%{$q}%")
                        ->orWhere('status', 'like', "%{$q}%");
                });
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->orderBy('id', 'desc')
            ->get();

        $dateFromStr = $dateFrom ? $dateFrom->format('d-m-Y') : null;
        $dateToStr   = $dateTo ? $dateTo->format('d-m-Y') : null;

        $pdf = Pdf::loadView('admin.inquiries.pdf', [
            'inquiries' => $inquiries,
            'q'         => $q,
            'dateFrom'  => $dateFromStr,
            'dateTo'    => $dateToStr,
        ])->setPaper('A4', 'portrait');

        return $pdf->download('contact-inquiries-' . now()->format('Ymd-His') . '.pdf');
    }

    /**
     * Export filtered inquiries to Excel.
     */
    public function exportExcel(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        [$dateFrom, $dateTo] = $this->validateFilterDates($request);

        return Excel::download(
            new InquiriesExport($q, $dateFrom, $dateTo),
            'contact-inquiries-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    /**
     * Common date validation (same idea as Appointments)
     *
     * Accepts ?date_from=Y-m-d&date_to=Y-m-d
     * Returns [Carbon|null, Carbon|null]
     */
    protected function validateFilterDates(Request $request): array
    {
        $from = $request->query('date_from');
        $to   = $request->query('date_to');

        $dateFrom = $from ? Carbon::createFromFormat('Y-m-d', $from)->startOfDay() : null;
        $dateTo   = $to   ? Carbon::createFromFormat('Y-m-d', $to)->endOfDay()   : null;

        // if only to is set, or they reversed dates, fix order
        if ($dateFrom && $dateTo && $dateFrom->gt($dateTo)) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        return [$dateFrom, $dateTo];
    }
}
