{{-- resources/views/hospital/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Hospital Dashboard - Wellcare Labs')

@section('content')
<div class="container-fluid dashboard-shell">
    <div class="py-4">

        {{-- If no hospital is linked, show a simple message --}}
        @if(!$hospital)
        <div class="alert alert-warning animate__animated animate__shakeX">
            <div class="d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" class="me-2">
                    <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2m1 15h-2v-2h2zm0-4h-2V7h2z" />
                </svg>
                <span>No hospital linked to this account. Please contact admin to link your hospital profile.</span>
            </div>
        </div>
        @else

        {{-- HEADER with animated gradient --}}
        <div class="mb-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                <div>
                    <h2 class="fw-bold mb-1 dashboard-title animate__animated animate__fadeIn">
                        <span class="gradient-text">Hospital Dashboard</span>
                    </h2>
                    <small class="text-muted animate__animated animate__fadeIn animate__delay-1s">
                        Managing: <strong class="hospital-name">{{ $hospital->name }}</strong>
                    </small>
                </div>
                <div class="hospital-badge animate__animated animate__zoomIn animate__delay-2s">
                    <span class="badge bg-white text-primary border border-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" class="me-1">
                            <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5l1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                        </svg>
                        Active
                    </span>
                </div>
            </div>
        </div>

        {{-- TOP KPIS (APPOINTMENTS ONLY) with hover effects --}}
        <div class="row g-3 mb-5">
            <div class="col-12">
                <div class="d-flex align-items-stretch gap-3 flex-column flex-sm-row">

                    {{-- KPI: Total Appointments --}}
                    <div class="card flex-fill kpi-card animate-on-scroll" data-animation="fade-up" data-delay="0">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="kpi-icon-wrap bg-success-subtle border border-success border-opacity-10 me-3 pulse-animation">
                                    <div class="kpi-icon-circle bg-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                            <path fill="#ffffff" d="M17 12a5 5 0 1 1-10 0a5 5 0 0 1 10 0m-5 9a9 9 0 1 0-9-9a9 9 0 0 0 9 9" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-1 text-dark fs-14 text-uppercase small text-muted">Total appointments</p>
                                    <h3 class="mb-0 fs-22 text-black counter" data-target="{{ $totalAppointments ?? 0 }}">
                                        0
                                    </h3>
                                    <div class="small text-muted mt-1">
                                        All time for this hospital
                                    </div>
                                </div>
                            </div>
                            <div class="kpi-sparkline" id="sparkline-total"></div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <div class="progress thin-progress">
                                <div class="progress-bar bg-success" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- KPI: Today --}}
                    <div class="card flex-fill kpi-card animate-on-scroll" data-animation="fade-up" data-delay="100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="kpi-icon-wrap bg-warning-subtle border border-warning border-opacity-10 me-3">
                                    <div class="kpi-icon-circle bg-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                            <path fill="#ffffff" d="M7 2v2H5a2 2 0 0 0-2 2v1h18V6a2 2 0 0 0-2-2h-2V2h-2v2H9V2zM3 9v11a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9zm4 3h3v3H7zm5 0h3v3h-3zm5 0h3v3h-3z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-1 text-dark fs-14 text-uppercase small text-muted">Today</p>
                                    <h3 class="mb-0 fs-22 text-black counter" data-target="{{ $todayAppointments ?? 0 }}">
                                        0
                                    </h3>
                                    <div class="small text-muted mt-1">
                                        Booked today
                                    </div>
                                </div>
                            </div>
                            <div class="kpi-sparkline" id="sparkline-today"></div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <div class="progress thin-progress">
                                <div class="progress-bar bg-warning" style="width: {{ min(($todayAppointments ?? 0) * 10, 100) }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- KPI: Last 7 days --}}
                    <div class="card flex-fill kpi-card animate-on-scroll" data-animation="fade-up" data-delay="200">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="kpi-icon-wrap bg-secondary-subtle border border-secondary border-opacity-10 me-3">
                                    <div class="kpi-icon-circle bg-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                            <path fill="#ffffff" d="M3 4h18v2H3zm2 4h14v2H5zm0 4h10v2H5zm0 4h6v2H5z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-1 text-dark fs-14 text-uppercase small text-muted">Last 7 days</p>
                                    <h3 class="mb-0 fs-22 text-black counter" data-target="{{ $last7Appointments ?? 0 }}">
                                        0
                                    </h3>
                                    <div class="small text-muted mt-1">
                                        New bookings in last week
                                    </div>
                                </div>
                            </div>
                            <div class="kpi-sparkline" id="sparkline-week"></div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <div class="progress thin-progress">
                                <div class="progress-bar bg-secondary"
                                    style="width: {{ min(($last7Appointments ?? 0) * 5, 100) }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- KPI: This month --}}
                    <div class="card flex-fill kpi-card animate-on-scroll" data-animation="fade-up" data-delay="300">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="kpi-icon-wrap bg-info-subtle border border-info border-opacity-10 me-3">
                                    <div class="kpi-icon-circle bg-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                            <path fill="#ffffff" d="M12 3a9 9 0 1 0 9 9a9 9 0 0 0-9-9m1 9V7h-2v7h6v-2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-1 text-dark fs-14 text-uppercase small text-muted">This month</p>
                                    <h3 class="mb-0 fs-22 text-black counter" data-target="{{ $thisMonthAppointments ?? 0 }}">
                                        0
                                    </h3>
                                    <div class="small text-muted mt-1">
                                        Appointments created this month
                                    </div>
                                </div>
                            </div>
                            <div class="kpi-sparkline" id="sparkline-month"></div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <div class="progress thin-progress">
                                <div class="progress-bar bg-info"
                                    style="width: {{ min(($thisMonthAppointments ?? 0) * 3, 100) }}%"></div>
                            </div>
                        </div>
                    </div>

                </div> {{-- flex container --}}
            </div>
        </div>

        {{-- STATUS DONUT + QUICK INSIGHTS with floating animation --}}
        <div class="row g-3 mb-5">
            <div class="col-12 col-xl-8">
                <div class="card analytics-card h-100 floating-card animate-on-scroll" data-animation="fade-up" data-delay="100">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0">
                                        <span class="gradient-text">Appointment Status</span>
                                        <small class="text-muted ms-2">(Last 7 days)</small>
                                    </h6>
                                    <small class="text-muted">
                                        Approved / Pending / Completed
                                    </small>
                                </div>
                                <p class="text-muted small mb-3">
                                    Snapshot of status mix for recent appointments in this hospital.
                                </p>

                                <div class="d-flex align-items-center justify-content-center justify-content-lg-start">
                                    <div class="chart-wrapper">
                                        <canvas id="appointmentsStatusDonut" aria-label="Appointment status chart"></canvas>
                                        <div class="chart-center">
                                            <span class="total-count">{{ $statusApproved + $statusPending + $statusCompleted }}</span>
                                            <small>Total</small>
                                        </div>
                                    </div>

                                    <div class="ms-4">
                                        <ul class="list-unstyled mb-0 status-legend">
                                            <li class="d-flex align-items-center mb-2 animate-on-scroll" data-animation="slide-right" data-delay="0">
                                                <span class="legend-dot legend-dot-approved"></span>
                                                <small>Approved
                                                    <span id="legend-approved-count" class="text-muted">
                                                        ({{ $statusApproved ?? 0 }})
                                                    </span>
                                                </small>
                                            </li>

                                            <li class="d-flex align-items-center mb-2 animate-on-scroll" data-animation="slide-right" data-delay="100">
                                                <span class="legend-dot legend-dot-pending"></span>
                                                <small>Pending
                                                    <span id="legend-pending-count" class="text-muted">
                                                        ({{ $statusPending ?? 0 }})
                                                    </span>
                                                </small>
                                            </li>

                                            <li class="d-flex align-items-center animate-on-scroll" data-animation="slide-right" data-delay="200">
                                                <span class="legend-dot legend-dot-completed"></span>
                                                <small>Completed
                                                    <span id="legend-completed-count" class="text-muted">
                                                        ({{ $statusCompleted ?? 0 }})
                                                    </span>
                                                </small>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- INSIGHTS PANEL --}}
                            <div class="insights-column d-none d-xl-block animate-on-scroll" data-animation="slide-left" data-delay="300">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="tiny text-muted text-uppercase">Backlog health</span>
                                    <span id="health-badge" class="health-badge health-good pulse">Good</span>
                                </div>
                                <p id="insight-primary-line" class="insight-description mb-2">
                                    Backlog healthy: only 0% pending right now.
                                </p>
                                <p id="health-subline" class="tiny mb-3 mb-lg-4">
                                    Backlog looks healthy. Most appointments are flowing through on time.
                                </p>

                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="tiny text-muted text-uppercase">Status funnel (last 7 days)</span>
                                        <span class="tiny text-muted">Pending: <span id="pending-share-label">0%</span></span>
                                    </div>
                                    <div class="funnel-bar mb-1">
                                        <div id="funnel-approved" class="funnel-segment funnel-approved" style="width: 40%;"></div>
                                        <div id="funnel-pending" class="funnel-segment funnel-pending" style="width: 30%;"></div>
                                        <div id="funnel-completed" class="funnel-segment funnel-completed" style="width: 30%;"></div>
                                    </div>
                                    <div class="d-flex justify-content-between funnel-legend">
                                        <small><span class="funnel-dot funnel-approved"></span>Approved</small>
                                        <small><span class="funnel-dot funnel-pending"></span>Pending</small>
                                        <small><span class="funnel-dot funnel-completed"></span>Completed</small>
                                    </div>
                                </div>

                                {{-- Additional stats with animations --}}
                                <div class="mt-4 pt-3 border-top">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="tiny text-muted text-uppercase">Completion Rate</span>
                                        <span class="text-success fw-bold" id="completion-rate">0%</span>
                                    </div>
                                    <div class="progress thin-progress">
                                        <div id="completion-bar" class="progress-bar bg-success" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>

                        </div> {{-- flex --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- DAILY APPOINTMENT TREND with interactive chart --}}
        <div class="row g-3 mb-5">
            <div class="col-12">
                <div class="card analytics-card h-100 animate-on-scroll" data-animation="fade-up" data-delay="200">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2 gap-2">
                            <div>
                                <h6 class="mb-0">
                                    <span class="gradient-text">Daily Appointment Trend</span>
                                    <small class="text-muted">(Last 14 days)</small>
                                </h6>
                                <p class="text-muted small mb-0">
                                    See how appointment volume is moving day-by-day for this hospital.
                                </p>
                            </div>
                            <div class="text-md-end tiny text-muted animate-on-scroll" data-animation="fade-in" data-delay="300">
                                <span class="me-3">
                                    Avg / day:
                                    <strong id="avg-day" class="text-primary">
                                        {{ isset($appointmentsTrendData) && count($appointmentsTrendData) 
                                            ? number_format(array_sum($appointmentsTrendData) / max(count($appointmentsTrendData),1), 1)
                                            : '0.0' }}
                                    </strong>
                                </span>
                                <span>
                                    Total (last 14 days):
                                    <strong id="total-14" class="text-primary">
                                        {{ isset($appointmentsTrendData) ? array_sum($appointmentsTrendData) : 0 }}
                                    </strong>
                                </span>
                            </div>
                        </div>

                        <div class="trend-chart-wrapper">
                            <canvas id="appointmentsTrendChart" aria-label="Daily appointments trend"></canvas>
                        </div>

                        <div class="tiny text-muted mt-2">
                            <span class="me-3">
                                <span class="trend-dot trend-dot-main"></span>Appointments per day
                            </span>
                            <span>
                                Use this to spot quiet days and spikes for staff planning.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ALL APPOINTMENTS TABLE with hover effects --}}
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card secondary-card animate-on-scroll" data-animation="fade-up" data-delay="300">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 gradient-text">Latest Appointments</h6>
                            <div class="table-actions">
                                <button class="btn btn-sm btn-outline-primary me-2" id="refresh-table">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M17.65 6.35A7.958 7.958 0 0 0 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0 1 12 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4z" />
                                    </svg>
                                </button>
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ $appointments->count() ?? 0 }} Latest
                                </span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0 table-hover">
                                <thead class="table-light">
                                    <tr class="small text-muted">
                                        <th class="ps-3">#</th>
                                        <th>Patient</th>
                                        <th>Test / Package</th>
                                        <th>Status</th>
                                        <th>Booked On</th>
                                        <th class="pe-3 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="appointments-table-body">
                                    @forelse ($appointments as $index => $appt)
                                    <tr class="animate-on-scroll" data-animation="fade-in" data-delay="{{ $index * 50 }}">
                                        <td class="ps-3">{{ $index + 1 }}</td>

                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-circle bg-primary-subtle text-primary">
                                                        {{ strtoupper(substr($appt->name ?? $appt->patient_name ?? 'N/A', 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $appt->name ?? $appt->patient_name ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ $appt->patient_phone ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            @if(!empty($appt->package_name))
                                            <span class="badge bg-info-subtle text-info border border-info border-opacity-25">
                                                {{ $appt->package_name }}
                                            </span>
                                            @elseif(!empty($appt->service))
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary border-opacity-25">
                                                {{ $appt->service }}
                                            </span>
                                            @else
                                            <span class="text-muted">N/A</span>
                                            @endif
                                        </td>

                                        <td>
                                            @php
                                            $status = $appt->status ?? 'Pending';
                                            $key = strtolower($status);
                                            $statusClass = 'status-badge-other';
                                            if ($key === 'pending') $statusClass = 'status-badge-pending';
                                            elseif ($key === 'approved') $statusClass = 'status-badge-approved';
                                            elseif ($key === 'completed') $statusClass = 'status-badge-completed';
                                            elseif ($key === 'reschedule' || $key === 'rescheduled') $statusClass = 'status-badge-reschedule';
                                            @endphp
                                            <span class="badge rounded-pill status-badge {{ $statusClass }} status-animation">
                                                {{ $status }}
                                            </span>
                                        </td>

                                        <td class="small text-muted">
                                            <div>{{ optional($appt->created_at)->format('d M, Y') }}</div>
                                            <div>{{ optional($appt->created_at)->format('h:i A') }}</div>
                                        </td>

                                        <td class="pe-3 text-end">
                                            @if(Route::has('hospital.appointments.show'))
                                            <a href="{{ route('hospital.appointments.show', $appt->id) }}"
                                                class="btn btn-sm btn-soft-primary btn-action">
                                                View
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted small py-5">
                                            <div class="empty-state">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" class="mb-2">
                                                    <path fill="#d1d5db" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5l1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                                </svg>
                                                <p class="mb-0">No appointments to display.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- View All Button -- Only show if there are more than 0 appointments --}}
                        @if($totalAppointments > 0)
                        <div class="mt-3 text-center animate-on-scroll" data-animation="fade-up" data-delay="400">
                            <a href="{{ route('hospital.appointments.index') }}" class="btn btn-sm btn-outline-primary">
                                View All Appointments ({{ $totalAppointments }} total)
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @endif {{-- /$hospital --}}
    </div>
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
        background: linear-gradient(135deg, #f4f7fb 0%, #ffffff 50%, #f0f9ff 100%);
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
        background: linear-gradient(135deg, rgba(178, 191, 213, 0.05) 0%, rgba(234, 225, 241, 0.05) 100%);
        z-index: 0;
        clip-path: polygon(0 0, 100% 0, 100% 70%, 0 100%);
    }

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

    .kpi-sparkline {
        width: 60px;
        height: 30px;
        opacity: 0.3;
        transition: opacity 0.3s ease;
    }

    .kpi-card:hover .kpi-sparkline {
        opacity: 0.8;
    }

    .analytics-card,
    .health-card,
    .secondary-card {
        border: none;
        border-radius: 1.5rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: var(--shadow-soft);
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .analytics-card::before,
    .secondary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, transparent 0%, rgba(59, 130, 246, 0.03) 100%);
        z-index: 0;
    }

    .floating-card {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    .chart-wrapper {
        width: 280px;
        height: 280px;
        position: relative;
        margin: 0 auto;
    }

    .chart-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        pointer-events: none;
    }

    .total-count {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2933;
        display: block;
        line-height: 1;
    }

    .chart-center small {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .chart-wrapper canvas {
        width: 100% !important;
        height: 100% !important;
        transition: transform 0.3s ease;
    }

    .chart-wrapper:hover canvas {
        transform: scale(1.05);
    }

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

    .insights-column {
        min-width: 280px;
        max-width: 350px;
        border-radius: 1.25rem;
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
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
        transition: all 0.3s ease;
    }

    .insight-tile:hover .insight-value {
        color: #3b82f6;
    }

    .insight-description {
        font-size: .875rem;
        color: #4b5563;
        line-height: 1.5;
    }

    .health-badge {
        font-size: .75rem;
        border-radius: 999px;
        padding: .35rem .85rem;
        border: 2px solid transparent;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .health-badge.pulse {
        animation: pulse-badge 2s infinite;
    }

    @keyframes pulse-badge {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
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

    .funnel-completed {
        background: var(--success-gradient);
    }

    .funnel-legend small {
        font-size: .75rem;
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

    .tiny {
        font-size: .75rem;
    }

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

    /* Trend chart styles */
    .trend-chart-wrapper {
        width: 100%;
        height: 340px;
        position: relative;
    }

    .trend-chart-wrapper canvas {
        width: 100% !important;
        height: 100% !important;
        transition: all 0.3s ease;
    }

    .trend-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 999px;
        margin-right: 8px;
        background: var(--success-gradient);
        animation: pulse 2s infinite;
    }

    .avatar-sm {
        width: 40px;
        height: 40px;
    }

    .avatar-circle {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    tr:hover .avatar-circle {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-action {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-action::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-action:hover::before {
        width: 300px;
        height: 300px;
    }

    .empty-state {
        opacity: 0.5;
        transition: opacity 0.3s ease;
    }

    .empty-state:hover {
        opacity: 1;
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

    @media (max-width: 991px) {
        .insights-column {
            max-width: 100%;
            width: 100%;
            margin-top: 2rem;
        }

        .chart-wrapper {
            width: 240px;
            height: 240px;
        }

        .trend-chart-wrapper {
            height: 280px;
        }
    }

    @media (max-width: 575px) {
        .card .fs-22 {
            font-size: 1.25rem;
        }

        .dashboard-shell {
            padding-left: 0;
            padding-right: 0;
        }

        .kpi-card {
            margin-bottom: 1rem;
        }

        .dashboard-title {
            font-size: 1.75rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize counters animation
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 1500;
            const step = target / (duration / 16);
            let current = 0;

            const updateCounter = () => {
                current += step;
                if (current < target) {
                    counter.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            };

            setTimeout(updateCounter, 300);
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
                        element.classList.add('animated', animation);
                    }, parseInt(delay));

                    observer.unobserve(element);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));

        // Refresh button animation
        const refreshBtn = document.getElementById('refresh-table');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                this.classList.add('rotating');

                // Simulate refresh
                const tableBody = document.getElementById('appointments-table-body');
                if (tableBody) {
                    tableBody.style.opacity = '0.5';
                    setTimeout(() => {
                        tableBody.style.opacity = '1';
                        this.classList.remove('rotating');

                        // Add a subtle success animation
                        const successAlert = document.createElement('div');
                        successAlert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                        successAlert.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle me-2" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                            </svg>
                            Table refreshed successfully
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.body.appendChild(successAlert);

                        setTimeout(() => {
                            successAlert.remove();
                        }, 3000);
                    }, 800);
                }
            });
        }

        // Chart data
        const approved = Number("{{ $statusApproved ?? 0 }}");
        const pending = Number("{{ $statusPending ?? 0 }}");
        const completed = Number("{{ $statusCompleted ?? 0 }}");
        const total = approved + pending + completed;

        const colors = {
            approved: '#3b82f6',
            pending: '#f6a623',
            completed: '#34d399'
        };

        // Donut Chart
        const donutCtx = document.getElementById('appointmentsStatusDonut')?.getContext('2d');
        if (donutCtx) {
            const data = [approved, pending, completed];

            const gradientApproved = donutCtx.createLinearGradient(0, 0, 0, 300);
            gradientApproved.addColorStop(0, '#60a5fa');
            gradientApproved.addColorStop(1, '#3b82f6');

            const gradientPending = donutCtx.createLinearGradient(0, 0, 0, 300);
            gradientPending.addColorStop(0, '#f8c471');
            gradientPending.addColorStop(1, '#f6a623');

            const gradientCompleted = donutCtx.createLinearGradient(0, 0, 0, 300);
            gradientCompleted.addColorStop(0, '#68d391');
            gradientCompleted.addColorStop(1, '#34d399');

            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Approved', 'Pending', 'Completed'],
                    datasets: [{
                        data: data,
                        backgroundColor: [gradientApproved, gradientPending, gradientCompleted],
                        hoverBackgroundColor: [gradientApproved, gradientPending, gradientCompleted],
                        hoverOffset: 12,
                        borderWidth: 0,
                        borderRadius: 10,
                        spacing: 2
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutout: '70%',
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
                                label: function(ctx) {
                                    const idx = ctx.dataIndex;
                                    const val = ctx.dataset.data[idx] || 0;
                                    const totalLocal = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = totalLocal ? ((val / totalLocal) * 100).toFixed(1) + '%' : '0%';
                                    return `${ctx.label}: ${val} (${pct})`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        // Trend Chart
        const trendLabels = @json($appointmentsTrendLabels ?? []);
        const trendData = @json($appointmentsTrendData ?? []);

        const trendCtx = document.getElementById('appointmentsTrendChart')?.getContext('2d');
        if (trendCtx && Array.isArray(trendLabels) && trendLabels.length) {
            const gradient = trendCtx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(15,157,128,0.3)');
            gradient.addColorStop(1, 'rgba(15,157,128,0.05)');

            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Appointments',
                        data: trendData,
                        tension: 0.4,
                        fill: true,
                        borderColor: '#0f9d80',
                        backgroundColor: gradient,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointHitRadius: 10,
                        pointBackgroundColor: '#0f9d80',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        borderWidth: 3
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 8,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 13
                            },
                            bodyFont: {
                                size: 12
                            },
                            mode: 'index',
                            intersect: false
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        // Update insights and health
        function updateHealth() {
            if (!total) {
                return;
            }

            const pendingPct = Math.round((pending / total) * 100);
            const completedPct = Math.round((completed / total) * 100);
            const approvedPct = Math.round((approved / total) * 100);

            // Update completion rate
            const completionRateEl = document.getElementById('completion-rate');
            const completionBar = document.getElementById('completion-bar');
            if (completionRateEl) {
                completionRateEl.textContent = completedPct + '%';
            }
            if (completionBar) {
                completionBar.style.width = completedPct + '%';
            }

            // Animate funnel segments
            const segApproved = document.getElementById('funnel-approved');
            const segPending = document.getElementById('funnel-pending');
            const segCompleted = document.getElementById('funnel-completed');

            if (segApproved) segApproved.style.width = approvedPct + '%';
            if (segPending) segPending.style.width = pendingPct + '%';
            if (segCompleted) segCompleted.style.width = completedPct + '%';
        }

        updateHealth();
    });
</script>
@endpush