<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HospitalDashboardController extends Controller
{
    // In your HospitalDashboardController.php
    public function index()
    {
        $user = Auth::user();

        // Find hospital for this user
        $hospital = Hospital::where('owners_user_id', $user->id)->first();

        // Defaults so the view never breaks
        $appointments              = collect();
        $totalAppointments         = 0;
        $todayAppointments         = 0;
        $last7Appointments         = 0;
        $thisMonthAppointments     = 0;
        $latestAppointment         = null;

        // Status counts for donut (last 7 days)
        $statusApproved            = 0;
        $statusPending             = 0;
        $statusCompleted           = 0;

        // NEW: trend chart arrays (last 14 days)
        $appointmentsTrendLabels   = [];
        $appointmentsTrendData     = [];

        if ($hospital) {
            // Base query scoped to this hospital
            $baseQuery = Appointment::where('hospital_id', $hospital->id);

            // Table data: LATEST 10 APPOINTMENTS ONLY (for dashboard)
            $appointments = (clone $baseQuery)
                ->latest()              // uses created_at desc
                ->take(10)              // ONLY 10 RECORDS
                ->get();                // Use get() instead of paginate()

            // Stats (all time, not just current page)
            $totalAppointments = (clone $baseQuery)->count();

            $todayAppointments = (clone $baseQuery)
                ->whereDate('created_at', Carbon::today())
                ->count();

            $last7Appointments = (clone $baseQuery)
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count();

            $thisMonthAppointments = (clone $baseQuery)
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', Carbon::now()->month)
                ->count();

            // Most recent appointment
            $latestAppointment = (clone $baseQuery)
                ->latest()
                ->first();

            // ===== STATUS COUNTS FOR LAST 7 DAYS (FOR DONUT) =====
            $last7StatusQuery = (clone $baseQuery)
                ->where('created_at', '>=', Carbon::now()->subDays(7));

            // Adjust these to match how status is stored in DB
            $statusApproved = (clone $last7StatusQuery)
                ->whereIn('status', ['approved', 'Approved'])
                ->count();

            $statusPending = (clone $last7StatusQuery)
                ->whereIn('status', ['pending', 'Pending'])
                ->count();

            $statusCompleted = (clone $last7StatusQuery)
                ->whereIn('status', ['completed', 'Completed'])
                ->count();

            // ===== NEW: DAILY APPOINTMENT TREND (LAST 14 DAYS) =====
            $trendStart = Carbon::now()->subDays(13)->startOfDay(); // 14 days including today
            $trendEnd   = Carbon::now()->endOfDay();

            $trendRaw = (clone $baseQuery)
                ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
                ->whereBetween('created_at', [$trendStart, $trendEnd])
                ->groupBy('d')
                ->pluck('c', 'd') // ['2025-12-01' => 5, ...]
                ->toArray();

            $period = CarbonPeriod::create($trendStart, $trendEnd);
            foreach ($period as $date) {
                $key = $date->toDateString(); // 'Y-m-d'
                $appointmentsTrendLabels[] = $date->format('d M');
                $appointmentsTrendData[]   = $trendRaw[$key] ?? 0;
            }
        }

        return view('hospital.dashboard', compact(
            'hospital',
            'appointments',
            'totalAppointments',
            'todayAppointments',
            'last7Appointments',
            'thisMonthAppointments',
            'latestAppointment',
            'statusApproved',
            'statusPending',
            'statusCompleted',
            'appointmentsTrendLabels',
            'appointmentsTrendData'
        ));
    }
}
