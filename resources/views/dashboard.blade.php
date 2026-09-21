{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Wellcare Labs')

@section('content')
    <div class="container-fluid dashboard-shell">
        <div class="py-4">

            {{-- HEADER with animated gradient --}}
            <div class="mb-5">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">

                    <!-- LEFT -->
                    <div>
                        <h2 class="fw-bold mb-1 dashboard-title animate__animated animate__fadeIn">
                            <span
                                style="
                    background: linear-gradient(90deg, #436aa3, #00b431);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                        ">
                                Wellcare Labs Dashboard
                            </span>
                        </h2>

                        <div
                            class="d-flex align-items-center flex-wrap gap-2 animate__animated animate__fadeIn animate__delay-1s">

                            <small class="text-muted">
                                Logged in as <strong class="hospital-name">Admin</strong>
                            </small>

                            <!-- REMINDER BUTTON -->
                        </div>
                    </div>

                    <!-- RIGHT -->
                    <div class="hospital-badge animate__animated animate__zoomIn animate__delay-2s">
                       <a href="{{ route('admin.send.reminders') }}" onclick="return confirm('Send reminders now?')"
                                style="
                        background:#25D366;
                        color:#fff;
                        font-size:13px;
                        padding:6px 14px;
                        border-radius:20px;
                        text-decoration:none;
                        font-weight:500;
                        display:inline-flex;
                        align-items:center;
                        transition:all 0.25s ease;
                        box-shadow:0 4px 10px rgba(37, 211, 102, 0.3);
                   "
                                onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 14px rgba(37,211,102,0.4)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(37,211,102,0.3)'">

                                <i class="fa-brands fa-whatsapp me-1"></i>
                                Send Reminders
                            </a>

                    </div>

                </div>
            </div>

            {{-- TOP KPIS with enhanced effects --}}
            <div class="row g-3 mb-5" id="topKpis">
                <div class="col-12">
                    <div class="d-flex align-items-stretch gap-3 flex-column flex-sm-row">

                        {{-- KPI: Appointments --}}
                        <div class="card flex-fill kpi-card animate-on-scroll" data-animation="fade-up" data-delay="0"
                            onclick="window.location='{{ route('admin.appointments.index') }}'">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div
                                        class="kpi-icon-wrap bg-success-subtle border border-success border-opacity-10 me-3 pulse-animation">
                                        <div class="kpi-icon-circle bg-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                viewBox="0 0 24 24">
                                                <path fill="#ffffff"
                                                    d="M17 12a5 5 0 1 1-10 0a5 5 0 0 1 10 0m-5 9a9 9 0 1 0-9-9a9 9 0 0 0 9 9" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-1 text-dark fs-14 text-uppercase small text-muted">Appointments</p>
                                        <h3 class="mb-0 fs-22 text-black counter"
                                            data-target="{{ $totalThisMonthAppointments ?? ($totalAppointments ?? 0) }}">
                                            0
                                        </h3>
                                        <div class="small text-muted mt-1">
                                            This month
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <div class="mb-2">
                                        <small class="badge period-badge">This Month</small>
                                    </div>
                                    <div>
                                        <small id="badge-appointments-change" class="badge change-badge badge-positive">
                                            {{ $appointmentsChangePercent ?? '' }}
                                        </small>
                                        <div class="mt-1 small text-muted kpi-subchange" id="badge-appointments-subline">
                                            vs last month
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 pt-0">
                                <div class="progress thin-progress">
                                    <div class="progress-bar bg-success"
                                        style="width: {{ min(($totalThisMonthAppointments ?? 0) * 2, 100) }}%"></div>
                                </div>
                            </div>
                        </div>

                        {{-- KPI: Packages --}}
                        <div class="card flex-fill kpi-card animate-on-scroll" data-animation="fade-up" data-delay="100"
                            onclick="window.location='{{ route('admin.packages.index') }}'">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div
                                        class="kpi-icon-wrap bg-warning-subtle border border-warning border-opacity-10 me-3">
                                        <div class="kpi-icon-circle bg-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24">
                                                <path fill="#ffffff" d="M12 2l7 6v14H5V8z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-1 text-dark fs-14 text-uppercase small text-muted">Packages</p>
                                        <h3 class="mb-0 fs-22 text-black counter"
                                            data-target="{{ $totalPublishedPackages ?? ($totalPublishedPackages ?? 0) }}">
                                            0
                                        </h3>
                                        <div class="small text-muted mt-1">
                                            Published packages (live)
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 pt-0">
                                <div class="progress thin-progress">
                                    <div class="progress-bar bg-warning"
                                        style="width: {{ min(($totalPublishedPackages ?? 0) * 20, 100) }}%"></div>
                                </div>
                            </div>
                        </div>

                        {{-- KPI: Tests (TOTAL AVAILABLE) --}}
                        <div class="card flex-fill kpi-card animate-on-scroll" data-animation="fade-up" data-delay="200"
                            onclick="window.location='{{ route('admin.labtests.index') }}'">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div
                                        class="kpi-icon-wrap bg-secondary-subtle border border-secondary border-opacity-10 me-3">
                                        <div class="kpi-icon-circle bg-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24">
                                                <path fill="#ffffff"
                                                    d="M19 3H5a2 2 0 0 0-2 2v14l4-4h12a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-1 text-dark fs-14 text-uppercase small text-muted">Tests</p>
                                        <h3 class="mb-0 fs-22 text-black counter"
                                            data-target="{{ $totalPublishedTests ?? 0 }}">
                                            0
                                        </h3>
                                        <div class="small text-muted mt-1">
                                            Total available lab tests
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 pt-0">
                                <div class="progress thin-progress">
                                    <div class="progress-bar bg-secondary"
                                        style="width: {{ min(($totalPublishedTests ?? 0) * 1, 100) }}%"></div>
                                </div>
                            </div>
                        </div>

                        {{-- KPI: Revenue (New) --}}
                        <div class="card flex-fill kpi-card animate-on-scroll" data-animation="fade-up" data-delay="300"
                            onclick="window.location='#'">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="kpi-icon-wrap bg-info-subtle border border-info border-opacity-10 me-3">
                                        <div class="kpi-icon-circle bg-info">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24">
                                                <path fill="#ffffff"
                                                    d="M12 3a9 9 0 1 0 9 9a9 9 0 0 0-9-9m1 9V7h-2v7h6v-2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-1 text-dark fs-14 text-uppercase small text-muted">Revenue</p>
                                        <h3 class="mb-0 fs-22 text-black counter"
                                            data-target="{{ $revenueThisMonth ?? 0 }}">
                                            0
                                        </h3>
                                        <div class="small text-muted mt-1">
                                            This month (₹)
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="mb-2">
                                        <small class="badge period-badge">This Month</small>
                                    </div>
                                    <div>
                                        <small id="badge-revenue-change"
                                            class="badge change-badge 
                                        @if (isset($revenueChangePercent)) {{ $revenueChangePercent > 0 ? 'badge-positive' : ($revenueChangePercent < 0 ? 'badge-negative' : 'badge-neutral') }}
                                        @else
                                            badge-neutral @endif">
                                            {{ $revenueChangePercent ?? '0%' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 pt-0">
                                <div class="progress thin-progress">
                                    <div class="progress-bar bg-info"
                                        style="width: {{ min(($revenueThisMonth ?? 0) / 1000, 100) }}%"></div>
                                </div>
                            </div>
                        </div>

                    </div> {{-- flex container --}}
                </div>
            </div>

            {{-- REVENUE ROW --}}
            <div class="row g-3 mb-4">
                {{-- Revenue overview card --}}
                <div class="col-12 col-xl-4">
                    <div class="card revenue-card h-100 animate-on-load" data-animation="slide-right" data-delay="0.5">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Revenue Overview</h6>
                                <span class="badge bg-light text-muted border small animate-currency">
                                    ₹
                                </span>
                            </div>
                            <p class="text-muted tiny mb-3">
                                Billing based on completed / billed appointments.
                            </p>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center revenue-item">
                                    <span class="text-muted small">Today</span>
                                    <span class="fw-semibold animate-currency" id="revenue-today">
                                        ₹<span class="revenue-value">{{ number_format($revenueToday ?? 0) }}</span>
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1 revenue-item">
                                    <span class="text-muted small">This month</span>
                                    <span class="fw-semibold animate-currency" id="revenue-month">
                                        ₹<span class="revenue-value">{{ number_format($revenueThisMonth ?? 0) }}</span>
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="tiny text-muted d-block">vs last month</span>
                                    <span id="revenue-change" class="badge change-badge badge-neutral animate-change">
                                        {{ $revenueChangePercent ?? '0%' }}
                                    </span>
                                </div>
                                <div class="text-end tiny text-muted">
                                    <div id="revenue-health-label" class="fw-semibold">Stable</div>
                                    <div id="revenue-health-subline">
                                        Watching month-on-month movement.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Revenue trend chart --}}
                <div class="col-12 col-xl-8">
                    <div class="card secondary-card h-100 animate-on-load" data-animation="slide-left" data-delay="0.6">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Revenue Trend</h6>
                                <small class="text-muted tiny">
                                    Last 30 days (₹)
                                </small>
                            </div>
                            <p class="text-muted tiny mb-3">
                                Track how daily revenue is moving over the last month.
                            </p>
                            <div class="chart-wrapper-wide">
                                <div class="chart-pulse"></div>
                                <canvas id="revenueTrendChart" aria-label="Revenue trend chart"
                                    data-labels='@json($revenueTrendLabels ?? [])'
                                    data-values='@json($revenueTrendValues ?? [])'></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MAIN ANALYTICS ROW: HOSPITAL-STYLE DONUT + INSIGHTS + HEALTH --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-xl-8">
                    <div class="card analytics-card h-100 animate-on-load" data-animation="slide-up" data-delay="0.7">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0">
                                            <span class="gradient-text">Appointment Status</span>
                                            <small class="text-muted ms-2">(Last 7 days)</small>
                                        </h6>
                                        <small class="text-muted">
                                            Approved / Pending / Reschedule / Completed
                                        </small>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        Snapshot of status mix for recent appointments.
                                    </p>

                                    <div
                                        class="d-flex align-items-center justify-content-center justify-content-lg-start flex-wrap">
                                        <div class="chart-wrapper">
                                            <canvas id="appointmentsStatusDonut"
                                                aria-label="Appointment status chart"></canvas>
                                            <div class="chart-center">
                                                <span
                                                    class="total-count">{{ $totalApprovedLast7 + $totalPendingLast7 + $totalRescheduleLast7 + $totalCompletedLast7 }}</span>
                                                <small>Total</small>
                                            </div>
                                        </div>

                                        <div class="ms-lg-4 mt-3 mt-lg-0">
                                            <ul class="list-unstyled mb-0 status-legend">
                                                <li class="d-flex align-items-center justify-content-between mb-2 animate-legend"
                                                    data-delay="0.8">
                                                    <div class="d-flex align-items-center">
                                                        <span class="legend-dot legend-dot-approved pulse-dot"></span>
                                                        <small>Approved</small>
                                                    </div>
                                                    <span class="badge bg-light text-dark ms-2 fw-normal">
                                                        {{ $totalApprovedLast7 ?? ($totalApprovedAppointments ?? 0) }}
                                                    </span>
                                                </li>

                                                <li class="d-flex align-items-center justify-content-between mb-2 animate-legend"
                                                    data-delay="0.85">
                                                    <div class="d-flex align-items-center">
                                                        <span class="legend-dot legend-dot-pending pulse-dot"></span>
                                                        <small>Pending</small>
                                                    </div>
                                                    <span class="badge bg-light text-dark ms-2 fw-normal">
                                                        {{ $totalPendingLast7 ?? ($totalPendingAppointments ?? 0) }}
                                                    </span>
                                                </li>

                                                <li class="d-flex align-items-center justify-content-between mb-2 animate-legend"
                                                    data-delay="0.9">
                                                    <div class="d-flex align-items-center">
                                                        <span class="legend-dot legend-dot-rescheduled pulse-dot"></span>
                                                        <small>Reschedule</small>
                                                    </div>
                                                    <span class="badge bg-light text-dark ms-2 fw-normal">
                                                        {{ $totalRescheduleLast7 ?? 0 }}
                                                    </span>
                                                </li>

                                                <li class="d-flex align-items-center justify-content-between animate-legend"
                                                    data-delay="0.95">
                                                    <div class="d-flex align-items-center">
                                                        <span class="legend-dot legend-dot-completed pulse-dot"></span>
                                                        <small>Completed</small>
                                                    </div>
                                                    <span class="badge bg-light text-dark ms-2 fw-normal">
                                                        {{ $totalCompletedLast7 ?? ($totalCompletedAppointments ?? 0) }}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                {{-- QUICK INSIGHTS COLUMN --}}
                                <div class="insights-column mt-3 mt-lg-0 animate-on-load" data-animation="fade-in"
                                    data-delay="1">
                                    <h6 class="mb-2 text-muted small text-uppercase fw-bold">Quick insights</h6>
                                    <div class="insight-tile mb-2 animate-insight" data-delay="1.1">
                                        <span class="insight-label small text-muted">Today's focus</span>
                                        <p class="insight-value mb-0 small" id="insight-primary-line">
                                            Monitoring pending vs completed load…
                                        </p>
                                    </div>

                                    <div class="insight-tile mb-2 animate-insight" data-delay="1.2">
                                        <span class="insight-label small text-muted">Status mix</span>
                                        <p class="insight-description mb-0 small" id="insight-status-mix">
                                            Live calculation will appear here based on the chart data.
                                        </p>
                                    </div>

                                    <div class="insight-tile animate-insight" data-delay="1.3">
                                        <span class="insight-label small text-muted">Completion rate</span>
                                        <p class="insight-description mb-0 small" id="insight-completion-rate">
                                            Calculating...
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- HEALTH + FUNNEL --}}
                <div class="col-12 col-xl-4">
                    <div class="card health-card h-100 animate-on-load" data-animation="slide-up" data-delay="1.4">
                        <div class="card-body d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0">Operations health</h6>
                                <span id="health-badge" class="badge health-badge health-good animate-health">Good</span>
                            </div>
                            <p class="text-muted small mb-2" id="health-subline">
                                Backlog looks healthy based on pending vs completed ratio.
                            </p>

                            <div class="funnel-bar">
                                <div id="funnel-approved" class="funnel-segment funnel-approved animate-funnel"
                                    style="width: 0%;" data-delay="1.5"></div>
                                <div id="funnel-pending" class="funnel-segment funnel-pending animate-funnel"
                                    style="width: 0%;" data-delay="1.6"></div>
                                <div id="funnel-rescheduled" class="funnel-segment funnel-rescheduled animate-funnel"
                                    style="width: 0%;" data-delay="1.7"></div>
                                <div id="funnel-completed" class="funnel-segment funnel-completed animate-funnel"
                                    style="width: 0%;" data-delay="1.8"></div>
                            </div>

                            <div class="d-flex justify-content-between mt-1 funnel-legend">
                                <small class="animate-legend-dot" data-delay="1.9">
                                    <span class="funnel-dot funnel-approved pulse-dot"></span>Approved
                                </small>
                                <small class="animate-legend-dot" data-delay="2">
                                    <span class="funnel-dot funnel-pending pulse-dot"></span>Pending
                                </small>
                                <small class="animate-legend-dot" data-delay="2.1">
                                    <span class="funnel-dot funnel-rescheduled pulse-dot"></span>Rescheduled
                                </small>
                                <small class="animate-legend-dot" data-delay="2.2">
                                    <span class="funnel-dot funnel-completed pulse-dot"></span>Completed
                                </small>
                            </div>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="tiny text-muted text-uppercase">Completion Rate</span>
                                    <span class="text-success fw-bold" id="completion-rate">0%</span>
                                </div>
                                <div class="progress thin-progress">
                                    <div id="completion-bar" class="progress-bar bg-success" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ALERTS & EXCEPTIONS --}}
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="card alerts-card animate-on-load" data-animation="fade-in" data-delay="2.3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Alerts & Exceptions</h6>
                                <div class="alert-pulse"></div>
                            </div>
                            <p class="text-muted tiny mb-3">
                                We highlight unusual patterns in your appointments and revenue so you can act quickly.
                            </p>
                            <div id="alerts-empty" class="tiny text-muted">
                                ✅ Everything looks good right now. No alerts to show.
                            </div>
                            <ul id="alerts-list" class="list-unstyled mb-0 mt-1"></ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECONDARY ROW: RECENT ITEMS --}}
            <div class="row g-2">
                @isset($recentAppointments)
                    <div class="col-12">
                        <div class="card secondary-card h-100 animate-on-load" data-animation="slide-up" data-delay="2.4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Recent Appointments</h6>
                                    <a href="{{ route('admin.appointments.index') }}" class="wc-icon-btn animate-icon"
                                        title="View all">
                                        <i data-feather="arrow-right"></i>
                                    </a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead>
                                            <tr class="small text-muted">
                                                <th>Sr.No</th>
                                                <th>Patient</th>
                                                <th>Test / Package</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($recentAppointments as $appt)
                                                <tr class="animate-table-row" data-delay="{{ $loop->index * 0.05 + 2.5 }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $appt->name ?? 'N/A' }}</td>
                                                    <td>
                                                        @if (!empty($appt->package_name))
                                                            {{ $appt->package_name }}
                                                        @elseif(!empty($appt->service))
                                                            {{ $appt->service }}
                                                        @elseif(!empty($appt->test_id))
                                                            Test #{{ $appt->test_id }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $status = $appt->status ?? 'Pending';
                                                            $key = strtolower($status);
                                                            $statusClass = 'status-badge-other';
                                                            if ($key === 'pending') {
                                                                $statusClass = 'status-badge-pending';
                                                            } elseif ($key === 'approved') {
                                                                $statusClass = 'status-badge-approved';
                                                            } elseif ($key === 'completed') {
                                                                $statusClass = 'status-badge-completed';
                                                            } elseif ($key === 'reschedule' || $key === 'reschedule') {
                                                                $statusClass = 'status-badge-reschedule';
                                                            }
                                                        @endphp
                                                        <span
                                                            class="badge rounded-pill status-badge {{ $statusClass }} animate-status">
                                                            {{ $status }}
                                                        </span>
                                                    </td>
                                                    <td class="small text-muted">
                                                        {{ optional($appt->created_at)->format('d M, Y h:i A') }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted small py-3">
                                                        No recent appointments to display.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endisset
            </div>

        </div>
    </div>

    {{-- Floating animation elements --}}
    <div class="floating-shapes">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
    </div>
@endsection

@push('styles')
    <style>
        /* Import animations */
        @import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #0f9d80 0%, #2ec6a5 100%);
            --warning-gradient: linear-gradient(135deg, #f6a623 0%, #f8c471 100%);
            --info-gradient: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            --shadow-soft: 0 8px 30px rgba(0, 0, 0, 0.08);
            --shadow-hard: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .dashboard-shell {
            background: linear-gradient(#f4f7fb 0%, #eef2ff 50%, #f0f9ff 50%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        .dashboard-shell::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 300px;
            z-index: 0;
            clip-path: polygon(0 0, 100% 0, 100% 0%, 0 100%);
        }

        /* Dashboard header animation */
        .dashboard-header {
            position: relative;
            z-index: 1;
            padding: 2rem 0;
        }

        @keyframes gradient-shift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #8CC63F 0%, #32689B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .dashboard-title {
            color: transparent;
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .hospital-name {
            color: #1f2933;
            position: relative;
            display: inline-block;
        }

        .hospital-name::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--success-gradient);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }

        .hospital-name:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        /* KPI Card enhancements */
        .kpi-card {
            border: none;
            border-radius: 1.25rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-soft);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            z-index: 1;
            cursor: pointer;
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
            z-index: 2;
        }

        .kpi-card:hover::before {
            transform: scaleX(1);
        }

        .kpi-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-hard);
            z-index: 10;
        }

        .kpi-icon-wrap {
            padding: 0.4rem;
            border-radius: 1rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .kpi-card:hover .kpi-icon-wrap {
            transform: scale(1.1) rotate(5deg);
        }

        .kpi-icon-circle {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            transition: all 0.3s ease;
        }

        .kpi-card:hover .kpi-icon-circle {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .thin-progress {
            height: 4px;
            border-radius: 2px;
            overflow: hidden;
        }

        .thin-progress .progress-bar {
            transition: width 1s ease-in-out;
        }

        .period-badge {
            background: rgba(15, 23, 42, 0.03);
            color: #374151;
            padding: .25rem .45rem;
            border-radius: 999px;
            font-size: .75rem;
        }

        .change-badge {
            padding: .25rem .6rem;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 600;
            display: inline-block;
            min-width: 56px;
            text-align: center;
        }

        .badge-positive {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.18);
        }

        .badge-negative {
            background: rgba(239, 68, 68, 0.08);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.12);
        }

        .badge-neutral {
            background: rgba(107, 114, 128, 0.08);
            color: #6b7280;
            border: 1px solid rgba(107, 114, 128, 0.10);
        }

        .kpi-subchange {
            font-size: 0.7rem;
        }

        /* Revenue card */
        .revenue-card {
            border: none;
            border-radius: 1.1rem;
            box-shadow: var(--shadow-soft);
            background: radial-gradient(circle at top left, #ecfdf5 0, #ffffff 50%, #f9fafb 100%);
        }

        /* HOSPITAL-STYLE DONUT CHART */
        .chart-wrapper {
            position: relative;
            width: 280px;
            height: 280px;
            margin: 0 auto;
        }

        .chart-wrapper canvas {
            width: 100% !important;
            height: 100% !important;
            transition: transform 0.3s ease;
        }

        .chart-wrapper:hover canvas {
            transform: scale(1.05);
        }

        .chart-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            pointer-events: none;
            z-index: 2;
        }

        .total-count {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2933;
            display: block;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }

        .chart-center small {
            font-size: 0.875rem;
            color: #6b7280;
            display: block;
        }

        .chart-wrapper-wide {
            position: relative;
            width: 100%;
            max-width: 100%;
            height: 260px;
        }

        .chart-wrapper-wide canvas {
            width: 100% !important;
            height: 100% !important;
        }

        /* STATUS LEGEND */
        .status-legend .legend-dot {
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            margin-right: 12px;
            transition: all 0.3s ease;
            position: relative;
        }

        .status-legend li:hover .legend-dot {
            transform: scale(1.3);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .legend-dot-approved {
            background: var(--info-gradient);
        }

        .legend-dot-pending {
            background: var(--warning-gradient);
        }

        .legend-dot-completed {
            background: var(--success-gradient);
        }

        .legend-dot-rescheduled {
            background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
        }

        /* INSIGHTS COLUMN */
        .insights-column {
            width: 100%;
            max-width: 320px;
            border-radius: 1rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, rgba(249, 250, 251, 0.9), rgba(238, 242, 255, 0.9));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(148, 163, 184, 0.2);
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .insights-column:hover {
            transform: translateX(-5px);
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .insight-tile {
            padding: .5rem .75rem;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .insight-tile:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .insight-label {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #6b7280;
            font-weight: 600;
        }

        .insight-value {
            font-size: .92rem;
            font-weight: 600;
            color: #111827;
            transition: all 0.3s ease;
        }

        .insight-tile:hover .insight-value {
            color: #3b82f6;
        }

        .insight-description {
            font-size: .85rem;
            color: #4b5563;
            line-height: 1.5;
        }

        /* Health + funnel */
        .health-card {
            background: radial-gradient(circle at top left, #e0f2fe 0, #eef2ff 32%, #ffffff 100%);
        }

        .health-badge {
            font-size: .75rem;
            border-radius: 999px;
            padding: .35rem .85rem;
            border: 2px solid transparent;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .health-good {
            background: var(--success-gradient);
            color: white;
            border-color: rgba(16, 185, 129, 0.3);
        }

        .health-watch {
            background: var(--warning-gradient);
            color: white;
            border-color: rgba(245, 158, 11, 0.3);
        }

        .health-critical {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .funnel-bar {
            height: 16px;
            border-radius: 999px;
            overflow: hidden;
            display: flex;
            background: rgba(226, 232, 240, 0.5);
            position: relative;
        }

        .funnel-segment {
            height: 100%;
            transition: width 0.8s ease-in-out;
            position: relative;
            overflow: hidden;
        }

        .funnel-segment::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg,
                    transparent 0%,
                    rgba(255, 255, 255, 0.2) 50%,
                    transparent 100%);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .funnel-approved {
            background: var(--info-gradient);
        }

        .funnel-pending {
            background: var(--warning-gradient);
        }

        .funnel-rescheduled {
            background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
        }

        .funnel-completed {
            background: var(--success-gradient);
        }

        .funnel-legend small {
            font-size: .72rem;
            color: #4b5563;
            transition: all 0.3s ease;
        }

        .funnel-legend small:hover {
            color: #1f2933;
            transform: translateY(-1px);
        }

        .funnel-dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 999px;
            margin-right: 6px;
            transition: transform 0.3s ease;
        }

        .funnel-legend small:hover .funnel-dot {
            transform: scale(1.3);
        }

        .funnel-dot.funnel-approved {
            background: var(--info-gradient);
        }

        .funnel-dot.funnel-pending {
            background: var(--warning-gradient);
        }

        .funnel-dot.funnel-rescheduled {
            background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
        }

        .funnel-dot.funnel-completed {
            background: var(--success-gradient);
        }

        .tiny {
            font-size: .72rem;
        }

        /* Status badge colors for Recent Appointments */
        .status-badge {
            font-size: .75rem;
            padding: .4rem .85rem;
            border-radius: 999px;
            border: 2px solid transparent;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .status-animation::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .status-animation:hover::before {
            left: 100%;
        }

        .status-badge-pending {
            background: var(--warning-gradient);
            color: white;
            border-color: rgba(245, 158, 11, 0.3);
        }

        .status-badge-approved {
            background: var(--info-gradient);
            color: white;
            border-color: rgba(59, 130, 246, 0.3);
        }

        .status-badge-completed {
            background: var(--success-gradient);
            color: white;
            border-color: rgba(16, 185, 129, 0.3);
        }

        .status-badge-reschedule {
            background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
            color: white;
            border-color: rgba(168, 85, 247, 0.3);
        }

        .status-badge-other {
            background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
            color: white;
            border-color: rgba(239, 68, 68, 0.3);
        }

        /* Alerts & Exceptions */
        .alerts-card {
            border: none;
            border-radius: 1.2rem;
            box-shadow: var(--shadow-soft);
        }

        .alert-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.55rem 0.25rem;
            border-left: 2px solid transparent;
        }

        .alert-pill-icon {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            margin-top: 0.35rem;
            flex-shrink: 0;
        }

        .alert-text {
            flex: 1;
        }

        .alert-title {
            font-size: .82rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.05rem;
        }

        .alert-body {
            font-size: .78rem;
            color: #4b5563;
        }

        .alert-critical {
            border-left-color: rgba(220, 38, 38, 0.8);
        }

        .alert-critical .alert-pill-icon {
            background: #dc2626;
        }

        .alert-warning {
            border-left-color: rgba(245, 158, 11, 0.9);
        }

        .alert-warning .alert-pill-icon {
            background: #f59e0b;
        }

        .alert-positive {
            border-left-color: rgba(34, 197, 94, 0.9);
        }

        .alert-positive .alert-pill-icon {
            background: #22c55e;
        }

        /* Scroll animations */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }

        .animate-on-load {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .animate-on-load.animated {
            opacity: 1;
            transform: translateY(0);
        }

        /* Pulse dots */
        .pulse-dot {
            animation: pulseDot 2s infinite;
        }

        @keyframes pulseDot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.7;
                transform: scale(1.2);
            }
        }

        /* Chart animations */
        .chart-pulse {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(14, 165, 233, 0.05) 0%, transparent 70%);
            animation: chartPulse 3s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes chartPulse {

            0%,
            100% {
                opacity: 0.3;
                transform: translate(-50%, -50%) scale(1);
            }

            50% {
                opacity: 0.1;
                transform: translate(-50%, -50%) scale(1.05);
            }
        }

        /* Alert pulse */
        .alert-pulse {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ef4444;
            animation: alertPulse 1.5s infinite;
        }

        @keyframes alertPulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.3);
            }
        }

        /* Animated health badge */
        .animate-health {
            animation: healthPulse 3s infinite;
        }

        @keyframes healthPulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
        }

        /* Funnel animation */
        .animate-funnel {
            transition: width 1s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* Currency animation */
        .animate-currency {
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .revenue-item:hover .animate-currency {
            animation: currencyBounce 0.5s ease;
        }

        @keyframes currencyBounce {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Badge animation */
        .animate-badge {
            display: inline-block;
            animation: badgeFloat 3s ease-in-out infinite;
        }

        @keyframes badgeFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-3px);
            }
        }

        /* Change badge animation */
        .animate-change {
            transition: all 0.3s ease;
        }

        .animate-change.positive {
            animation: positiveGlow 2s infinite;
        }

        @keyframes positiveGlow {

            0%,
            100% {
                box-shadow: 0 0 5px rgba(16, 185, 129, 0.5);
            }

            50% {
                box-shadow: 0 0 15px rgba(16, 185, 129, 0.8);
            }
        }

        /* Icon animation */
        .animate-icon {
            transition: all 0.3s ease;
        }

        .animate-icon:hover {
            animation: iconSpin 0.6s ease;
        }

        @keyframes iconSpin {
            0% {
                transform: rotate(0);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Status animation */
        .animate-status {
            transition: all 0.3s ease;
        }

        .animate-status:hover {
            transform: scale(1.1);
            animation: statusShake 0.5s ease;
        }

        @keyframes statusShake {

            0%,
            100% {
                transform: translateX(0) scale(1.1);
            }

            25% {
                transform: translateX(-2px) scale(1.1);
            }

            75% {
                transform: translateX(2px) scale(1.1);
            }
        }

        /* Table row animation */
        .animate-table-row {
            opacity: 0;
            transform: translateX(-20px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .animate-table-row.animated {
            opacity: 1;
            transform: translateX(0);
        }

        /* List item animation */
        .animate-list-item {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .animate-list-item.animated {
            opacity: 1;
            transform: translateY(0);
        }

        /* Legend dot animation */
        .animate-legend-dot {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .animate-legend-dot.animated {
            opacity: 1;
            transform: translateY(0);
        }

        /* Insight animation */
        .animate-insight {
            opacity: 0;
            transform: translateX(-20px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .animate-insight.animated {
            opacity: 1;
            transform: translateX(0);
        }

        /* Legend animation */
        .animate-legend {
            opacity: 0;
            transform: translateX(-10px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .animate-legend.animated {
            opacity: 1;
            transform: translateX(0);
        }

        /* Floating shapes */
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.05;
            filter: blur(40px);
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            top: 10%;
            left: 10%;
            animation: floatShape 20s ease-in-out infinite;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #10b981, #0ea5e9);
            bottom: 20%;
            right: 15%;
            animation: floatShape 15s ease-in-out infinite reverse;
        }

        .shape-3 {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            top: 50%;
            left: 5%;
            animation: floatShape 25s ease-in-out infinite;
        }

        @keyframes floatShape {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            33% {
                transform: translate(30px, -50px) rotate(120deg);
            }

            66% {
                transform: translate(-20px, 20px) rotate(240deg);
            }
        }

        /* Animation performance preferences */
        @media (prefers-reduced-motion: reduce) {

            .animate-on-load,
            .animate-on-scroll,
            .counter,
            .pulse-animation,
            .chart-pulse,
            .alert-pulse,
            .animate-health,
            .animate-funnel,
            .animate-currency,
            .animate-badge,
            .animate-change,
            .animate-icon,
            .animate-status,
            .animate-table-row,
            .animate-list-item,
            .animate-legend-dot,
            .animate-insight,
            .animate-legend,
            .floating-shape,
            .kpi-card::before,
            .pulse-dot {
                animation: none !important;
                transition: none !important;
            }

            .animate-on-load,
            .animate-on-scroll {
                opacity: 1;
                transform: none;
            }

            .kpi-card:hover {
                transform: none;
            }
        }

        /* Responsive tweaks */
        @media (max-width: 991px) {
            .insights-column {
                max-width: 100%;
                width: 100%;
            }

            .chart-wrapper {
                width: 240px;
                height: 240px;
            }

            .chart-wrapper-wide {
                height: 220px;
            }
        }

        @media (max-width: 768px) {
            .chart-wrapper-wide {
                height: 200px;
            }
        }

        @media (max-width: 575px) {
            #topKpis .card {
                min-width: 0;
            }

            .card .fs-22 {
                font-size: 1.25rem;
            }

            .dashboard-shell {
                padding-left: 0;
                padding-right: 0;
            }

            .chart-wrapper {
                width: 200px;
                height: 200px;
            }

            .chart-wrapper-wide {
                height: 180px;
            }
        }

        .wc-icon-btn {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid #dbeafe;
            background: #ffffff;
            color: #2563eb;
            transition: all .25s ease;
        }

        .wc-icon-btn:hover {
            background: #2563eb;
            color: #ffffff;
            transform: translateX(3px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, .25);
        }

        .wc-icon-btn svg {
            pointer-events: none;
            position: relative;
            z-index: 10;
            pointer-events: auto;
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <script>
        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize feather icons
            feather.replace();

            // Counter animation for all KPI cards
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target')) || 0;
                const duration = 1500;
                const step = target / (duration / 16);
                let current = 0;

                const updateCounter = () => {
                    current += step;
                    if (current < target) {
                        counter.textContent = Math.floor(current).toLocaleString();
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target.toLocaleString();
                    }
                };

                setTimeout(updateCounter, 300);
            });

            // KPI hover effects
            document.querySelectorAll('.kpi-card').forEach(card => {
                // Add hover effects
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                    this.style.zIndex = '10';

                    const iconCircle = this.querySelector('.kpi-icon-circle');
                    if (iconCircle) {
                        iconCircle.style.transform = 'scale(1.05)';
                        iconCircle.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.1)';
                    }

                    const iconWrap = this.querySelector('.kpi-icon-wrap');
                    if (iconWrap) {
                        iconWrap.style.transform = 'scale(1.1) rotate(5deg)';
                    }
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                    this.style.zIndex = '';

                    const iconCircle = this.querySelector('.kpi-icon-circle');
                    if (iconCircle) {
                        iconCircle.style.transform = '';
                        iconCircle.style.boxShadow = '';
                    }

                    const iconWrap = this.querySelector('.kpi-icon-wrap');
                    if (iconWrap) {
                        iconWrap.style.transform = '';
                    }
                });

                // Add click handler
                const url = card.getAttribute('onclick');
                if (url && url.includes('window.location')) {
                    const cleanUrl = url.match(/'([^']+)'/)[1];
                    card.style.cursor = 'pointer';
                    card.addEventListener('click', function() {
                        window.location.href = cleanUrl;
                    });
                }
            });

            // Scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const animation = element.getAttribute('data-animation');
                        const delay = element.getAttribute('data-delay') || 0;

                        setTimeout(() => {
                            element.classList.add('animated');
                            if (animation) {
                                element.classList.add(animation);
                            }
                        }, parseInt(delay));

                        observer.unobserve(element);
                    }
                });
            }, observerOptions);

            // Observe all animate-on-scroll elements
            document.querySelectorAll('.animate-on-scroll, .animate-on-load').forEach(el => {
                observer.observe(el);
            });

            // Observe other animated elements
            document.querySelectorAll(
                '.animate-table-row, .animate-list-item, .animate-legend-dot, .animate-insight, .animate-legend, .animate-legend'
            ).forEach(el => {
                observer.observe(el);
            });

            // Animation controller
            window.animationsEnabled = true;
            const toggleBtn = document.getElementById('toggleAnimations');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    window.animationsEnabled = !window.animationsEnabled;
                    document.body.classList.toggle('animations-disabled', !window.animationsEnabled);
                    toggleBtn.innerHTML = window.animationsEnabled ?
                        '<i data-feather="pause-circle" class="me-1"></i> Pause Animations' :
                        '<i data-feather="play-circle" class="me-1"></i> Resume Animations';
                    feather.replace();
                });
            }

            // Your existing dashboard functionality
            const countsUrl = "{{ route('dashboard.counts') }}";

            let statusChart = null;
            let revenueChart = null;

            // --- HOSPITAL-STYLE DONUT CHART ---
            function buildOrUpdateChart(approved, pending, reschedule, completed) {
                const data = [approved, pending, reschedule, completed];
                const ctx = document.getElementById('appointmentsStatusDonut')?.getContext('2d');

                if (!ctx) return;

                // Destroy existing chart if exists
                if (statusChart) {
                    statusChart.destroy();
                }

                // Create gradient colors for hospital-style donut
                const gradientApproved = ctx.createLinearGradient(0, 0, 0, 300);
                gradientApproved.addColorStop(0, '#60a5fa');
                gradientApproved.addColorStop(1, '#3b82f6');

                const gradientPending = ctx.createLinearGradient(0, 0, 0, 300);
                gradientPending.addColorStop(0, '#f8c471');
                gradientPending.addColorStop(1, '#f6a623');

                const gradientRescheduled = ctx.createLinearGradient(0, 0, 0, 300);
                gradientRescheduled.addColorStop(0, '#c4b5fd');
                gradientRescheduled.addColorStop(1, '#8b5cf6');

                const gradientCompleted = ctx.createLinearGradient(0, 0, 0, 300);
                gradientCompleted.addColorStop(0, '#68d391');
                gradientCompleted.addColorStop(1, '#34d399');

                statusChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Approved', 'Pending', 'Rescheduled', 'Completed'],
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                gradientApproved,
                                gradientPending,
                                gradientRescheduled,
                                gradientCompleted
                            ],
                            hoverBackgroundColor: [
                                gradientApproved,
                                gradientPending,
                                gradientRescheduled,
                                gradientCompleted
                            ],
                            hoverOffset: 12,
                            borderWidth: 0,
                            borderRadius: 10,
                            spacing: 2
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        cutout: '70%',
                        animation: {
                            animateRotate: window.animationsEnabled,
                            animateScale: window.animationsEnabled,
                            duration: window.animationsEnabled ? 2000 : 0,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleFont: {
                                    size: 14
                                },
                                bodyFont: {
                                    size: 13
                                },
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total ? ((value / total) * 100).toFixed(1) +
                                            '%' : '0%';
                                        return `${label}: ${value} (${percentage})`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Update total count in chart center
            function updateTotalCount(approved, pending, reschedule, completed) {
                const totalCount = document.querySelector('.total-count');
                if (totalCount) {
                    const total = approved + pending + reschedule + completed;

                    if (window.animationsEnabled && typeof anime !== 'undefined') {
                        anime({
                            targets: totalCount,
                            innerHTML: [0, total],
                            round: 1,
                            easing: 'easeOutQuart',
                            duration: 1500
                        });
                    } else {
                        totalCount.textContent = total;
                    }
                }
            }

            // Update completion rate
            function updateCompletionRate(approved, pending, reschedule, completed) {
                const total = approved + pending + reschedule + completed;
                const completionRateEl = document.getElementById('completion-rate');
                const completionBar = document.getElementById('completion-bar');
                const insightCompletion = document.getElementById('insight-completion-rate');

                if (!total) {
                    if (completionRateEl) completionRateEl.textContent = '0%';
                    if (completionBar) completionBar.style.width = '0%';
                    if (insightCompletion) insightCompletion.textContent = 'No completed appointments yet';
                    return;
                }

                const completionPct = Math.round((completed / total) * 100);

                if (completionRateEl) completionRateEl.textContent = completionPct + '%';
                if (completionBar) completionBar.style.width = completionPct + '%';
                if (insightCompletion) {
                    insightCompletion.textContent = completionPct +
                        '% of appointments have been completed (last 7 days)';
                }
            }

            function updateInsights(approved, pending, reschedule, completed) {
                const total = approved + pending + reschedule + completed;
                const primaryLine = document.getElementById('insight-primary-line');
                const statusMix = document.getElementById('insight-status-mix');

                if (!total) {
                    if (primaryLine) primaryLine.textContent = 'No appointments in the current window.';
                    if (statusMix) statusMix.textContent =
                        'Once data comes in, you\'ll see pending and completed ratios here.';
                    return;
                }

                const approvedPct = Math.round((approved / total) * 100);
                const pendingPct = Math.round((pending / total) * 100);
                const reschedulePct = Math.round((reschedule / total) * 100);
                const completedPct = Math.round((completed / total) * 100);

                if (primaryLine) {
                    const backlogPct = pendingPct + reschedulePct;
                    if (backlogPct >= 40) {
                        primaryLine.textContent = backlogPct +
                            '% of appointments are pending or need rescheduling. Consider clearing today\'s backlog.';
                    } else {
                        primaryLine.textContent = 'Backlog healthy: only ' + backlogPct +
                            '% pending/reschedule right now.';
                    }
                }

                if (statusMix) {
                    statusMix.textContent =
                        approvedPct + '% approved · ' +
                        pendingPct + '% pending · ' +
                        reschedulePct + '% reschedule · ' +
                        completedPct + '% completed (last 7 days).';
                }
            }

            function updateHealthAndFunnel(approved, pending, rescheduled, completed) {
                const total = approved + pending + rescheduled + completed;

                const healthBadge = document.getElementById('health-badge');
                const healthSubline = document.getElementById('health-subline');

                const segApproved = document.getElementById('funnel-approved');
                const segPending = document.getElementById('funnel-pending');
                const segRescheduled = document.getElementById('funnel-rescheduled');
                const segCompleted = document.getElementById('funnel-completed');

                if (!total) {
                    [segApproved, segPending, segRescheduled, segCompleted].forEach(el => {
                        if (el) el.style.width = '0%';
                    });
                    return;
                }

                const approvedPct = (approved / total) * 100;
                const pendingPct = (pending / total) * 100;
                const rescheduledPct = (rescheduled / total) * 100;
                const completedPct = (completed / total) * 100;

                // Animate funnel segments
                if (window.animationsEnabled) {
                    setTimeout(() => {
                        if (segApproved) segApproved.style.width = approvedPct + '%';
                    }, 100);
                    setTimeout(() => {
                        if (segPending) segPending.style.width = pendingPct + '%';
                    }, 300);
                    setTimeout(() => {
                        if (segRescheduled) segRescheduled.style.width = rescheduledPct + '%';
                    }, 500);
                    setTimeout(() => {
                        if (segCompleted) segCompleted.style.width = completedPct + '%';
                    }, 700);
                } else {
                    if (segApproved) segApproved.style.width = approvedPct + '%';
                    if (segPending) segPending.style.width = pendingPct + '%';
                    if (segRescheduled) segRescheduled.style.width = rescheduledPct + '%';
                    if (segCompleted) segCompleted.style.width = completedPct + '%';
                }

                /* Health calculation: pending + rescheduled = backlog */
                const backlogPct = pendingPct + rescheduledPct;

                healthBadge.classList.remove('health-good', 'health-watch', 'health-critical');

                if (backlogPct < 35) {
                    healthBadge.textContent = 'Good';
                    healthBadge.classList.add('health-good');
                    healthSubline.textContent = 'Backlog looks healthy including reschedules.';
                } else if (backlogPct <= 50) {
                    healthBadge.textContent = 'Watch';
                    healthBadge.classList.add('health-watch');
                    healthSubline.textContent = 'Pending + rescheduled workload is rising.';
                } else {
                    healthBadge.textContent = 'Critical';
                    healthBadge.classList.add('health-critical');
                    healthSubline.textContent = 'High backlog including rescheduled appointments.';
                }
            }

            // --- Alerts & Exceptions ---
            function updateAlerts(approved, pending, completed, payload) {
                const listEl = document.getElementById('alerts-list');
                const emptyEl = document.getElementById('alerts-empty');
                if (!listEl || !emptyEl) return;

                const alerts = [];
                const total = approved + pending + completed;
                const pendingPct = total ? (pending / total) * 100 : 0;

                const revChange = Number(payload.revenueChangePercent ?? 0);
                const revMonth = Number(payload.revenueThisMonth ?? 0);
                const revToday = Number(payload.revenueToday ?? 0);
                const totalThisMonth = Number(payload.totalThisMonthAppointments ?? 0);

                // Backlog alerts
                if (total >= 10 && pendingPct > 50) {
                    alerts.push({
                        type: 'critical',
                        title: 'High pending backlog',
                        text: 'More than half of recent appointments are still pending. Consider prioritising approvals and completions today.'
                    });
                } else if (total >= 10 && pendingPct >= 35) {
                    alerts.push({
                        type: 'warning',
                        title: 'Backlog building up',
                        text: Math.round(pendingPct) +
                            '% of recent appointments are pending. You may want to clear today\'s queue.'
                    });
                }

                // Revenue trend alerts
                if (!isNaN(revChange) && revChange < -15 && revMonth > 0) {
                    alerts.push({
                        type: 'critical',
                        title: 'Revenue trending down',
                        text: 'Revenue is down ' + revChange +
                            '% vs last month. Review high-value packages and booking sources.'
                    });
                } else if (!isNaN(revChange) && revChange > 25) {
                    alerts.push({
                        type: 'positive',
                        title: 'Strong revenue growth',
                        text: 'Revenue is up ' + revChange +
                            '% vs last month. Consider reinforcing campaigns that are working.'
                    });
                }

                // Average ticket size hint
                if (totalThisMonth > 0 && revMonth > 0) {
                    const avgTicket = revMonth / totalThisMonth;
                    if (avgTicket < 300 && totalThisMonth > 50) {
                        alerts.push({
                            type: 'warning',
                            title: 'Low average ticket size',
                            text: 'Average revenue per appointment is about ₹' + Math.round(avgTicket) +
                                '. Upselling packages could improve profitability.'
                        });
                    }
                }

                listEl.innerHTML = '';

                if (!alerts.length) {
                    emptyEl.classList.remove('d-none');
                    return;
                }

                emptyEl.classList.add('d-none');

                alerts.forEach(a => {
                    const li = document.createElement('li');
                    li.className = 'alert-item alert-' + a.type;

                    li.innerHTML =
                        '<div class="alert-pill-icon"></div>' +
                        '<div class="alert-text">' +
                        '<div class="alert-title">' + a.title + '</div>' +
                        '<div class="alert-body">' + a.text + '</div>' +
                        '</div>';

                    listEl.appendChild(li);
                });
            }

            // --- Revenue Trend Chart ---
            function buildOrUpdateRevenueChart(labels, values) {
                const canvas = document.getElementById('revenueTrendChart');
                if (!canvas) return;

                const ctxRev = canvas.getContext('2d');

                if (revenueChart) {
                    revenueChart.data.labels = labels;
                    revenueChart.data.datasets[0].data = values;
                    revenueChart.update();
                    return;
                }

                revenueChart = new Chart(ctxRev, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Revenue',
                            data: values,
                            tension: 0.35,
                            fill: true,
                            borderWidth: 2,
                            borderColor: '#0ea5e9',
                            backgroundColor: 'rgba(14,165,233,0.10)',
                            pointRadius: 2.5,
                            pointHoverRadius: 4,
                            pointBorderWidth: 1,
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        animation: {
                            duration: window.animationsEnabled ? 1000 : 0
                        },
                        scales: {
                            x: {
                                ticks: {
                                    maxTicksLimit: 6
                                },
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '₹' + value;
                                    }
                                },
                                grid: {
                                    color: 'rgba(148,163,184,0.2)'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        return '₹' + ctx.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function initRevenueFromServer() {
                const canvas = document.getElementById('revenueTrendChart');
                if (!canvas) return;
                try {
                    const labels = JSON.parse(canvas.dataset.labels || '[]');
                    const values = JSON.parse(canvas.dataset.values || '[]');
                    if (labels.length && values.length) {
                        buildOrUpdateRevenueChart(labels, values);
                    }
                } catch (e) {
                    console.warn('Error parsing initial revenue trend data', e);
                }
            }

            function updateRevenueFromPayload(payload) {
                const revTodayEl = document.getElementById('revenue-today');
                const revMonthEl = document.getElementById('revenue-month');
                const revChangeEl = document.getElementById('revenue-change');
                const healthLabel = document.getElementById('revenue-health-label');
                const healthSub = document.getElementById('revenue-health-subline');

                // Store original value before updating
                const originalChange = revChangeEl?.textContent?.trim() || '0%';

                if (payload.revenueToday !== undefined && revTodayEl) {
                    const value = Number(payload.revenueToday);
                    const span = revTodayEl.querySelector('.revenue-value');
                    if (span) span.textContent = value.toLocaleString();
                    revTodayEl.textContent = '₹' + value.toLocaleString();
                }
                if (payload.revenueThisMonth !== undefined && revMonthEl) {
                    const value = Number(payload.revenueThisMonth);
                    const span = revMonthEl.querySelector('.revenue-value');
                    if (span) span.textContent = value.toLocaleString();
                    revMonthEl.textContent = '₹' + value.toLocaleString();
                }

                if (revChangeEl && payload.revenueChangePercent !== undefined) {
                    const pct = Number(payload.revenueChangePercent);

                    // FIX: Handle 0% correctly
                    if (!isNaN(pct)) {
                        let txt = '';
                        if (pct > 0) {
                            txt = '+' + pct + '%';
                        } else if (pct < 0) {
                            txt = pct + '%'; // Negative numbers already have '-'
                        } else {
                            txt = '0%';
                        }
                        revChangeEl.textContent = txt;
                    } else {
                        // If invalid number, keep original value
                        revChangeEl.textContent = originalChange;
                    }

                    revChangeEl.classList.remove('badge-positive', 'badge-negative', 'badge-neutral');
                    if (!isNaN(pct)) {
                        if (pct > 0) revChangeEl.classList.add('badge-positive');
                        else if (pct < 0) revChangeEl.classList.add('badge-negative');
                        else revChangeEl.classList.add('badge-neutral');
                    } else {
                        revChangeEl.classList.add('badge-neutral');
                    }

                    if (healthLabel && healthSub && !isNaN(pct)) {
                        if (pct > 5) {
                            healthLabel.textContent = 'Growing';
                            healthSub.textContent = 'Revenue is trending up compared to last month.';
                        } else if (pct < -5) {
                            healthLabel.textContent = 'Declining';
                            healthSub.textContent = 'Revenue is down. Check high value packages and bookings.';
                        } else {
                            healthLabel.textContent = 'Stable';
                            healthSub.textContent = 'Minor change month-on-month.';
                        }
                    }
                } else if (revChangeEl) {
                    // If API doesn't return revenueChangePercent, keep the original value
                    revChangeEl.textContent = originalChange;
                }

                if (payload.revenueTrendLabels && payload.revenueTrendValues) {
                    buildOrUpdateRevenueChart(payload.revenueTrendLabels, payload.revenueTrendValues);
                }
            }

            // --- Counters / badges ---
            function applyCounts(payload) {
                const appointmentsEl = document.getElementById('kpi-appointments');
                const packagesEl = document.getElementById('kpi-packages');
                const testsEl = document.getElementById('kpi-tests');

                if (appointmentsEl && payload.totalThisMonthAppointments !== undefined) {
                    const target = payload.totalThisMonthAppointments ?? appointmentsEl.textContent;
                    animateCounter(appointmentsEl, target);
                }

                if (packagesEl && payload.totalPublishedPackages !== undefined) {
                    const target = payload.totalPublishedPackages ?? packagesEl.textContent;
                    animateCounter(packagesEl, target);
                }

                // TESTS: use total available published tests
                if (testsEl && (payload.published_tests !== undefined || payload.totalPublishedTests !==
                        undefined)) {
                    const target = (payload.published_tests ?? payload.totalPublishedTests ?? testsEl.textContent);
                    animateCounter(testsEl, target);
                }

                // FIXED: Get the correct LAST 7 DAYS data from API
                const approvedCount = Number(payload.totalApprovedLast7 ?? payload.approved ?? 0);
                const pendingCount = Number(payload.totalPendingLast7 ?? payload.pending ?? payload
                    .totalPendingAppointments ?? 0);
                const rescheduleCount = Number(payload.totalRescheduleLast7 ?? payload.reschedule ?? 0);
                const completedCount = Number(payload.totalCompletedLast7 ?? payload.completed ?? 0);

                buildOrUpdateChart(approvedCount, pendingCount, rescheduleCount, completedCount);
                updateTotalCount(approvedCount, pendingCount, rescheduleCount, completedCount);
                updateCompletionRate(approvedCount, pendingCount, rescheduleCount, completedCount);

                // FIXED: Pass ALL FOUR parameters to updateInsights
                updateInsights(approvedCount, pendingCount, rescheduleCount, completedCount);

                updateHealthAndFunnel(
                    approvedCount,
                    pendingCount,
                    rescheduleCount,
                    completedCount
                );

                updateChangeBadge('appointments', payload.appointmentsChangePercent, payload.appointmentsDelta);
                updateChangeBadge('packages', payload.packagesChangePercent, payload.packagesDelta);
                updateChangeBadge('tests', payload.testsChangePercent, payload.testsDelta);

                // Revenue extras if backend sends them
                updateRevenueFromPayload(payload);

                // Alerts from live payload - FIXED: Include reschedule in pending for backlog calculation
                updateAlerts(
                    approvedCount,
                    pendingCount + rescheduleCount, // Include reschedule in backlog
                    completedCount,
                    payload
                );
            }

            function updateChangeBadge(key, percent, delta) {
                const el = document.getElementById('badge-' + key + '-change');
                if (!el) return;

                let text = '';
                let positive = null;

                if (percent !== undefined && percent !== null && percent !== '') {
                    const pct = Number(percent);
                    if (!isNaN(pct)) {
                        text = (pct > 0 ? '+' : '') + pct + '%';
                        positive = pct > 0 ? true : (pct < 0 ? false : null);
                    } else {
                        text = String(percent);
                    }
                } else if (delta !== undefined && delta !== null && delta !== '') {
                    const d = Number(delta);
                    if (!isNaN(d)) {
                        text = (d > 0 ? '+' : '') + d;
                        positive = d > 0 ? true : (d < 0 ? false : null);
                    } else {
                        text = String(delta);
                    }
                } else {
                    el.style.display = 'none';
                    return;
                }

                el.style.display = 'inline-block';
                el.textContent = text;

                el.classList.remove('badge-positive', 'badge-negative', 'badge-neutral');
                if (positive === true) el.classList.add('badge-positive');
                else if (positive === false) el.classList.add('badge-negative');
                else el.classList.add('badge-neutral');
            }

            // Counter animation function
            function animateCounter(element, target) {
                if (!window.animationsEnabled) {
                    element.textContent = target.toLocaleString();
                    return;
                }

                const duration = 1500;
                const start = 0;
                const startTime = performance.now();

                function update(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    // Easing function
                    const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                    const currentValue = Math.floor(start + (target - start) * easeOutQuart);

                    element.textContent = currentValue.toLocaleString();

                    if (progress < 1) {
                        requestAnimationFrame(update);
                    } else {
                        element.textContent = target.toLocaleString();
                        element.classList.add('animating');
                        setTimeout(() => element.classList.remove('animating'), 500);
                    }
                }

                requestAnimationFrame(update);
            }

            async function fetchAndApplyCounts() {
                try {
                    const res = await fetch(countsUrl, {
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) {
                        console.warn('Dashboard counts fetch failed', res.status);
                        if (!statusChart) buildOrUpdateChart(0, 0, 0, 0);
                        return;
                    }
                    const json = await res.json();
                    applyCounts(json);
                } catch (err) {
                    console.error('Error fetching dashboard counts', err);
                    if (!statusChart) buildOrUpdateChart(0, 0, 0, 0);
                }
            }

            (function initFromServer() {
                // FIXED: Get all 4 parameters from server for initial load
                const approvedServer = Number(
                    "{{ $totalApprovedLast7 ?? ($totalApprovedAppointments ?? 0) }}");
                const pendingServer = Number(
                    "{{ $totalPendingLast7 ?? ($totalPendingAppointments ?? 0) }}");
                const completedServer = Number(
                    "{{ $totalCompletedLast7 ?? ($totalCompletedAppointments ?? 0) }}");
                const rescheduleServer = Number("{{ $totalRescheduleLast7 ?? 0 }}");

                buildOrUpdateChart(
                    approvedServer,
                    pendingServer,
                    rescheduleServer,
                    completedServer
                );

                updateTotalCount(approvedServer, pendingServer, rescheduleServer, completedServer);
                updateCompletionRate(approvedServer, pendingServer, rescheduleServer, completedServer);
                updateInsights(approvedServer, pendingServer, rescheduleServer, completedServer);
                updateHealthAndFunnel(
                    approvedServer,
                    pendingServer,
                    rescheduleServer,
                    completedServer
                );

                // Initial alerts from PHP-side values
                updateAlerts(
                    approvedServer,
                    pendingServer + rescheduleServer, // Include reschedule
                    completedServer, {
                        revenueChangePercent: "{{ $revenueChangePercent ?? 0 }}",
                        revenueThisMonth: "{{ $revenueThisMonth ?? 0 }}",
                        revenueToday: "{{ $revenueToday ?? 0 }}",
                        totalThisMonthAppointments: "{{ $totalThisMonthAppointments ?? 0 }}"
                    }
                );

                // Revenue trend from PHP variables
                initRevenueFromServer();

                (function seedBadges() {
                    updateChangeBadge('appointments', "{{ $appointmentsChangePercent ?? '' }}",
                        "{{ $appointmentsDelta ?? '' }}");
                    updateChangeBadge('packages', "{{ $packagesChangePercent ?? '' }}",
                        "{{ $packagesDelta ?? '' }}");
                    updateChangeBadge('tests', "{{ $testsChangePercent ?? '' }}",
                        "{{ $testsDelta ?? '' }}");
                })();
            })();

            fetchAndApplyCounts();

            // Add keyboard shortcut to toggle animations (Ctrl+Alt+A)
            document.addEventListener('keydown', (e) => {
                if (e.ctrlKey && e.altKey && e.key === 'a') {
                    e.preventDefault();
                    if (toggleBtn) toggleBtn.click();
                }
            });

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: window.animationsEnabled ? 'smooth' : 'auto',
                            block: 'start'
                        });
                    }
                });
            });

            // Add parallax effect to floating shapes on scroll
            if (window.animationsEnabled) {
                window.addEventListener('scroll', () => {
                    const scrolled = window.pageYOffset;
                    const shapes = document.querySelectorAll('.floating-shape');

                    shapes.forEach((shape, index) => {
                        const speed = 0.5 + (index * 0.1);
                        const yPos = -(scrolled * speed * 0.1);
                        shape.style.transform = `translateY(${yPos}px)`;
                    });
                });
            }
        });
    </script>
@endpush
{{-- End of dashboard.blade.php --}}
