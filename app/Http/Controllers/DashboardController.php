<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Package;
use App\Models\LabTest;
use Illuminate\Support\Facades\Schema;
use App\Models\Payment; // Make sure to import Payment model if exists

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index(Request $request)
    {
        $now = Carbon::now();

        // Date windows
        $startOfThisMonth = $now->copy()->startOfMonth();
        $endOfThisMonth   = $now->copy()->endOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth   = $now->copy()->subMonth()->endOfMonth();

        // Check if payment_method column exists
        $hasPaymentMethodColumn = Schema::hasColumn('appointments', 'payment_method');

        // --- Basic counts (overall) - Only count successful/cash appointments ---
        $totalAppointments = $this->countAppointmentsWithValidPayment();

        $totalThisMonthAppointments = $this->countAppointmentsWithValidPayment($startOfThisMonth, $endOfThisMonth);

        $lastMonthAppointments = $this->countAppointmentsWithValidPayment($startOfLastMonth, $endOfLastMonth);

        $appointmentsChangePercent = $lastMonthAppointments > 0
            ? round((($totalThisMonthAppointments - $lastMonthAppointments) / $lastMonthAppointments) * 100)
            : ($totalThisMonthAppointments > 0 ? 100 : 0);

        // Status counts for valid payment appointments only
        $totalRescheduleAppointments = $this->countAppointmentsWithValidPayment(null, null, 'Reschedule');

        // Status counts for valid payment appointments only
        $totalApprovedAppointments  = $this->countAppointmentsWithValidPayment(null, null, 'Approved');
        $totalCompletedAppointments = $this->countAppointmentsWithValidPayment(null, null, 'Completed');
        $totalPendingAppointments   = $this->countAppointmentsWithValidPayment(null, null, 'Pending');

        // --- Packages & Tests ---
        $totalPublishedPackages  = $this->safeWhereCount(Package::class, ['status' => 'published']);
        $packagesAddedLast30 = Package::where('status', 'published')
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->count();

        $packagesRemovedLast30 = Package::where('status', '!=', 'published')
            ->where('updated_at', '>=', $now->copy()->subDays(30))
            ->count();

        $packagesChangePercent = $packagesAddedLast30 - $packagesRemovedLast30;

        $totalPublishedTests = $this->safeWhereCount(LabTest::class, ['status' => 'published']);

        // Published tests THIS month (for "Total Tests" KPI if you use that)
        $totalPublishedThisMonth = $this->countForPeriod(
            LabTest::class,
            $startOfThisMonth,
            $endOfThisMonth,
            ['status' => 'published']
        );

        // Published tests last month for change calculation
        $testsPublishedLastMonth = $this->countForPeriod(
            LabTest::class,
            $startOfLastMonth,
            $endOfLastMonth,
            ['status' => 'published']
        );

        $testsChangePercent = $totalPublishedThisMonth - $testsPublishedLastMonth;

        // --- Appointment status for last 7 days (for donut + insights) - Only valid payment appointments ---
        try {
            $sevenDaysAgo = $now->copy()->subDays(7);

            $totalApprovedLast7 = Appointment::where('status', 'Approved')
                ->where('created_at', '>=', $sevenDaysAgo)
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->count();

            $totalCompletedLast7 = Appointment::where('status', 'Completed')
                ->where('created_at', '>=', $sevenDaysAgo)
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->count();

            $totalRescheduleLast7 = Appointment::whereIn('status', ['Reschedule'])
                ->where('created_at', '>=', $sevenDaysAgo)
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->count();

            $totalPendingLast7 = Appointment::where('status', 'Pending')
                ->where('created_at', '>=', $sevenDaysAgo)
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->count();
        } catch (\Throwable $e) {
            $totalApprovedLast7   = 0;
            $totalCompletedLast7  = 0;
            $totalRescheduleLast7 = 0;
            $totalPendingLast7    = 0;
        }

        // --- Recent Appointments (for list at bottom) - Only valid payment appointments ---
        $recentAppointments = Appointment::where(function ($query) use ($hasPaymentMethodColumn) {
            // Cash appointments
            if ($hasPaymentMethodColumn) {
                $query->orWhere('payment_method', 'cash');
            }

            // Or appointments with success/failed payments
            $query->orWhereHas('payments', function ($q) {
                $q->whereIn('status', ['success', 'failed']); // Both success and failed
            });
        })
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // --- Revenue Overview (Today / This Month / % change vs last month) - Only valid payment appointments ---
        try {
            $revenueToday = Appointment::where('status', 'Completed')
                ->whereDate('created_at', $now->toDateString())
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->sum('total_price');

            $revenueThisMonth = Appointment::where('status', 'Completed')
                ->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->sum('total_price');

            $revenueLastMonth = Appointment::where('status', 'Completed')
                ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->sum('total_price');

            if ($revenueLastMonth > 0) {
                $revenueChangePercent = round(
                    (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100
                );
            } else {
                $revenueChangePercent = 0;
            }
        } catch (\Throwable $e) {
            $revenueToday         = 0;
            $revenueThisMonth     = 0;
            $revenueLastMonth     = 0;
            $revenueChangePercent = 0;
        }

        // --- Revenue Trend (last 30 days) - Only valid payment appointments ---
        try {
            $fromDate = $now->copy()->subDays(29)->startOfDay();

            $revenueByDate = Appointment::where('status', 'Completed')
                ->where('created_at', '>=', $fromDate)
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->selectRaw('DATE(created_at) as d, SUM(total_price) as total')
                ->groupBy('d')
                ->orderBy('d')
                ->pluck('total', 'd');

            $period = new \Carbon\CarbonPeriod($fromDate, $now->copy()->startOfDay());

            $revenueTrendLabels = [];
            $revenueTrendValues = [];

            foreach ($period as $date) {
                $key = $date->toDateString();
                $revenueTrendLabels[] = $date->format('d M');
                $revenueTrendValues[] = (float) ($revenueByDate[$key] ?? 0);
            }
        } catch (\Throwable $e) {
            $revenueTrendLabels = [];
            $revenueTrendValues = [];
        }

        return view('dashboard', compact(
            // basic counts
            'totalAppointments',
            'totalThisMonthAppointments',
            'totalApprovedAppointments',
            'totalCompletedAppointments',
            'totalPendingAppointments',
            'totalRescheduleAppointments',

            // packages & tests
            'totalPublishedPackages',
            'totalPublishedTests',
            'totalPublishedThisMonth',

            // appointment donut + insights - LAST 7 DAYS DATA
            'totalApprovedLast7',
            'totalCompletedLast7',
            'totalPendingLast7',
            'totalRescheduleLast7',

            // recent appointments list
            'recentAppointments',

            // revenue overview + trend
            'revenueToday',
            'revenueThisMonth',
            'revenueChangePercent',
            'revenueTrendLabels',
            'revenueTrendValues',

            // KPI change percents (for badges)
            'appointmentsChangePercent',
            'packagesChangePercent',
            'testsChangePercent'
        ));
    }

    /**
     * JSON endpoint for dashboard polling (chart + KPIs + revenue).
     * Used by route('dashboard.counts') in the JS.
     */
    public function getCounts()
    {
        $now = Carbon::now();

        $startOfThisMonth = $now->copy()->startOfMonth();
        $endOfThisMonth   = $now->copy()->endOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth   = $now->copy()->subMonth()->endOfMonth();

        // Check if payment_method column exists
        $hasPaymentMethodColumn = Schema::hasColumn('appointments', 'payment_method');

        // --- appointment KPIs - Only valid payment appointments ---
        $totalThisMonthAppointments = $this->countAppointmentsWithValidPayment($startOfThisMonth, $endOfThisMonth);

        $lastMonthAppointments = $this->countAppointmentsWithValidPayment($startOfLastMonth, $endOfLastMonth);

        $appointmentsChangePercent = $lastMonthAppointments > 0
            ? round((($totalThisMonthAppointments - $lastMonthAppointments) / $lastMonthAppointments) * 100)
            : ($totalThisMonthAppointments > 0 ? 100 : 0);

        $approved  = $this->countAppointmentsWithValidPayment(null, null, 'Approved');
        $completed = $this->countAppointmentsWithValidPayment(null, null, 'Completed');
        $pending   = $this->countAppointmentsWithValidPayment(null, null, 'Pending');
        $reschedule = $this->countAppointmentsWithValidPayment(null, null, 'Reschedule');

        // last 7 days breakdown for donut - Only valid payment appointments
        try {
            $sevenDaysAgo = $now->copy()->subDays(7);

            $totalApprovedLast7 = Appointment::where('status', 'Approved')
                ->where('created_at', '>=', $sevenDaysAgo)
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->count();

            $totalCompletedLast7 = Appointment::where('status', 'Completed')
                ->where('created_at', '>=', $sevenDaysAgo)
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->count();

            $totalRescheduleLast7 = Appointment::whereIn('status', ['Reschedule'])
                ->where('created_at', '>=', $sevenDaysAgo)
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->count();

            $totalPendingLast7 = Appointment::where('status', 'Pending')
                ->where('created_at', '>=', $sevenDaysAgo)
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->count();
        } catch (\Throwable $e) {
            $totalApprovedLast7   = 0;
            $totalCompletedLast7  = 0;
            $totalRescheduleLast7 = 0;
            $totalPendingLast7    = 0;
        }

        // --- Packages & Tests ---
        $totalPublishedPackages = $this->safeWhereCount(Package::class, ['status' => 'published']);
        $packagesAddedLast30 = Package::where('status', 'published')
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->count();

        $packagesRemovedLast30 = Package::where('status', '!=', 'published')
            ->where('updated_at', '>=', $now->copy()->subDays(30))
            ->count();

        $packagesChangePercent = $packagesAddedLast30 - $packagesRemovedLast30;

        $totalPublishedTests     = $this->safeWhereCount(LabTest::class, ['status' => 'published']);
        $totalPublishedThisMonth = $this->countForPeriod(
            LabTest::class,
            $startOfThisMonth,
            $endOfThisMonth,
            ['status' => 'published']
        );

        $testsPublishedLastMonth = $this->countForPeriod(
            LabTest::class,
            $startOfLastMonth,
            $endOfLastMonth,
            ['status' => 'published']
        );

        $testsChangePercent = $totalPublishedThisMonth - $testsPublishedLastMonth;

        // --- Revenue overview - Only valid payment appointments ---
        try {
            $revenueToday = Appointment::where('status', 'Completed')
                ->whereDate('created_at', $now->toDateString())
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->sum('total_price');

            $revenueThisMonth = Appointment::where('status', 'Completed')
                ->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->sum('total_price');

            $revenueLastMonth = Appointment::where('status', 'Completed')
                ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->sum('total_price');

            if ($revenueLastMonth > 0) {
                $revenueChangePercent = round(
                    (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100
                );
            } else {
                $revenueChangePercent = 0;
            }
        } catch (\Throwable $e) {
            $revenueToday         = 0;
            $revenueThisMonth     = 0;
            $revenueLastMonth     = 0;
            $revenueChangePercent = 0;
        }

        // --- Revenue trend (last 30 days) - Only valid payment appointments ---
        try {
            $fromDate = $now->copy()->subDays(29)->startOfDay();

            $revenueByDate = Appointment::where('status', 'Completed')
                ->where('created_at', '>=', $fromDate)
                ->where(function ($query) use ($hasPaymentMethodColumn) {
                    // Cash appointments
                    if ($hasPaymentMethodColumn) {
                        $query->orWhere('payment_method', 'cash');
                    }

                    // Or appointments with success/failed payments
                    $query->orWhereHas('payments', function ($q) {
                        $q->whereIn('status', ['success', 'failed']); // Both success and failed
                    });
                })
                ->selectRaw('DATE(created_at) as d, SUM(total_price) as total')
                ->groupBy('d')
                ->orderBy('d')
                ->pluck('total', 'd');

            $period = new \Carbon\CarbonPeriod($fromDate, $now->copy()->startOfDay());

            $revenueTrendLabels = [];
            $revenueTrendValues = [];

            foreach ($period as $date) {
                $key = $date->toDateString();
                $revenueTrendLabels[] = $date->format('d M');
                $revenueTrendValues[] = (float) ($revenueByDate[$key] ?? 0);
            }
        } catch (\Throwable $e) {
            $revenueTrendLabels = [];
            $revenueTrendValues = [];
        }

        return response()->json([
            // for KPIs
            'totalThisMonthAppointments' => $totalThisMonthAppointments,
            'totalPublishedPackages'     => $totalPublishedPackages,
            'totalPublishedTests'        => $totalPublishedTests,
            'published_tests'            => $totalPublishedTests,

            // donut + legend - LAST 7 DAYS DATA (only valid payment)
            'totalApprovedLast7'         => $totalApprovedLast7,
            'totalCompletedLast7'        => $totalCompletedLast7,
            'totalRescheduleLast7'       => $totalRescheduleLast7,
            'totalPendingLast7'          => $totalPendingLast7,

            // All-time counts (for fallbacks - only valid payment)
            'approved'                   => $approved,
            'completed'                  => $completed,
            'pending'                    => $pending,
            'totalPendingAppointments'   => $pending,
            'reschedule'                 => $reschedule,

            // KPI change badges
            'appointmentsChangePercent'  => $appointmentsChangePercent,
            'packagesChangePercent'      => $packagesChangePercent,
            'testsChangePercent'         => $testsChangePercent,

            // revenue overview (only valid payment)
            'revenueToday'              => $revenueToday,
            'revenueThisMonth'          => $revenueThisMonth,
            'revenueChangePercent'      => $revenueChangePercent,

            // revenue trend (only valid payment)
            'revenueTrendLabels'        => $revenueTrendLabels,
            'revenueTrendValues'        => $revenueTrendValues,

            // debug/timestamp
            'timestamp'                 => now()->toDateTimeString(),
        ]);
    }

    /**
     * Helper functions
     */

    /**
     * Count appointments with valid payment (cash or success/failed payments)
     */
    protected function countAppointmentsWithValidPayment($startDate = null, $endDate = null, $status = null)
    {
        try {
            $query = Appointment::query();

            // Add date range if provided
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where('created_at', '>=', $startDate);
            } elseif ($endDate) {
                $query->where('created_at', '<=', $endDate);
            }

            // Add status filter if provided
            if ($status) {
                $query->where('status', $status);
            }

            // Check if payment_method column exists
            $hasPaymentMethodColumn = Schema::hasColumn('appointments', 'payment_method');

            // Apply payment filter - cash appointments OR appointments with success/failed payments
            $query->where(function ($q) use ($hasPaymentMethodColumn) {
                // Cash appointments
                if ($hasPaymentMethodColumn) {
                    $q->orWhere('payment_method', 'cash');
                }

                // Or appointments with success/failed payments
                $q->orWhereHas('payments', function ($paymentQuery) {
                    $paymentQuery->whereIn('status', ['success', 'failed']);
                });
            });

            return $query->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    protected function countForPeriod($modelClass, $start, $end, array $where = [], $dateColumn = 'created_at'): int
    {
        try {
            $query = (is_string($modelClass) ? $modelClass::query() : $modelClass->newQuery());
            foreach ($where as $col => $val) {
                $query->where($col, $val);
            }
            return $query->whereBetween($dateColumn, [$start, $end])->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    protected function safeTotalCount($modelClass): int
    {
        try {
            return is_string($modelClass) ? $modelClass::count() : $modelClass->newQuery()->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    protected function safeWhereCount($modelClass, array $where = []): int
    {
        try {
            $query = (is_string($modelClass) ? $modelClass::query() : $modelClass->newQuery());
            foreach ($where as $col => $val) {
                $query->where($col, $val);
            }
            return $query->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
