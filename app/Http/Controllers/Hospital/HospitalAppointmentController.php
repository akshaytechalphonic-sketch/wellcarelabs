<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HospitalAppointmentsExport;


class HospitalAppointmentController extends Controller
{
    /**
     * List appointments (with filters)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $hospital = $user->ownedHospital;

        if (! $hospital) {
            abort(403, 'No hospital linked to this account.');
        }

        $appointments = $this->baseQuery($request, $hospital)
            ->latest()
            ->paginate(20);

        return view('hospital.appointments.index', compact('hospital', 'appointments'));
    }

    /**
     * Export appointments as PDF
     */
    public function exportPdf(Request $request)
    {
        $user = $request->user();
        $hospital = $user->ownedHospital;

        if (! $hospital) {
            abort(403);
        }

        $appointments = $this->baseQuery($request, $hospital)
            ->latest()
            ->get();

        $pdf = Pdf::loadView(
            'hospital.appointments.export-pdf',
            compact('appointments', 'hospital')
        )->setPaper('a4', 'portrait');

        return $pdf->download('hospital-appointments.pdf');
    }


    /**
     * Export appointments as Excel (CSV)
     */
    public function exportExcel(Request $request)
    {
        $hospital = $request->user()->ownedHospital;

        if (! $hospital) {
            abort(403);
        }

        $appointments = $this->baseQuery($request, $hospital)
            ->latest()
            ->get();

        return Excel::download(
            new HospitalAppointmentsExport($appointments),
            'hospital-appointments.xlsx'
        );
    }

    /**
     * Shared filtered query
     */
    private function baseQuery(Request $request, $hospital)
    {
        $query = Appointment::where('hospital_id', $hospital->id);

        // Search filter
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($s) use ($q) {
                $s->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('service', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%");
            });
        }

        // Date range filter
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('date', [
                $request->date_from,
                $request->date_to
            ]);
        }

        return $query;
    }
    public function show(Appointment $appointment)
    {
        // Optional: Add authorization check to ensure the appointment belongs to the hospital
        // $hospital = auth()->user()->hospital; // or however you get the hospital

        return view('hospital.appointments.show', [
            'appointment' => $appointment,
            'hospital' => $appointment->hospital, // assuming relationship
        ]);
    }
}
