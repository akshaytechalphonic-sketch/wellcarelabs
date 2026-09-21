{{-- resources/views/hospital/appointments/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Hospital - Appointments')

@section('content')
{{-- Font Awesome (for small icons in chips) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

@php
$params = request()->only(['q','date_from','date_to']);

$cleanParams = [];
foreach ($params as $k => $v) {
    if ($v !== null && $v !== '') {
        $cleanParams[$k] = $v;
    }
}

$qp = http_build_query($cleanParams);

$pdfUrl = route('hospital.appointments.export.pdf') . ($qp ? ('?'.$qp) : '');
$excelUrl = route('hospital.appointments.export.excel') . ($qp ? ('?'.$qp) : '');
@endphp

<style>
    :root {
        --content-gap: 22px;
        --wc-bg: #ffffff;
        --wc-panel: rgba(255, 255, 255, 0.98);
        --wc-border: #e7eef2;
        --wc-shadow: 0 10px 30px rgba(15, 38, 34, 0.04);
        --wc-text: #0f1724;
        --wc-muted: #5f6b6b;
        --wc-accent: #0f9d80;
        --wc-accent-2: #1fb28a;
        --wc-pill-pending: #f7e6c9;
        --wc-pill-reschedule: #ede7fb;
        --wc-pill-approved: #e6f5ff;
        --wc-pill-completed: #e9f8f2;
        --wc-pill-cancelled: #ffe7e8;
        --rounded: 12px;
    }

    .admin-page-wrapper {
        margin: 25px 2px;
        box-sizing: border-box;
    }

    @media (max-width: 1200px) {
        .admin-page-wrapper {
            margin-left: var(--content-gap);
        }
    }

    .appointments-card {
        background: var(--wc-panel);
        border-radius: 10px;
        padding: 18px;
        border: 1px solid var(--wc-border);
        box-shadow: var(--wc-shadow);
    }

    .appointments-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }

    .appointments-top .title {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .appointments-top h2 {
        margin: 0;
        font-size: 22px;
        color: var(--wc-text);
        font-weight: 700;
    }

    .appointments-top .subtitle {
        color: var(--wc-muted);
        font-size: 0.95rem;
    }

    /* ===== Top controls (same style as admin) ===== */
    .controls {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-form {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .chip-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* chip-style search */
    .chip-search {
        display: flex;
        align-items: center;
        border-radius: 999px;
        background: #fff;
        border: 1px solid var(--wc-border);
        padding: 2px;
        min-width: 260px;
        max-width: 360px;
    }

    .chip-search input[type="search"] {
        border: none;
        outline: none;
        padding: 8px 10px;
        border-radius: 999px;
        flex: 1;
        font-size: .92rem;
        background: transparent;
        color: var(--wc-text);
    }

    .chip-search button {
        border: none;
        outline: none;
        border-radius: 999px;
        padding: 7px 16px;
        background: linear-gradient(135deg, #0f9d80, #12b981);
        color: #fff;
        font-weight: 600;
        font-size: .86rem;
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        white-space: nowrap;
        box-shadow: 0 8px 18px rgba(16, 185, 129, 0.35);
        transition: transform .15s ease, box-shadow .15s ease, opacity .15s ease;
    }

    .chip-search button i {
        font-size: .9rem;
    }

    .chip-search button:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(16, 185, 129, 0.4);
        opacity: .96;
    }

    /* chip-style Date button */
    .chip-button {
        border-radius: 999px;
        border: 1px solid var(--wc-border);
        background: #fff;
        padding: 7px 14px;
        font-size: .86rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-weight: 500;
        white-space: nowrap;
        color: #374151;
        transition: background .15s ease, border-color .15s ease, color .15s ease,
            box-shadow .15s ease, transform .1s ease;
    }

    .chip-button i {
        font-size: .82rem;
    }

    .chip-button:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.14);
        transform: translateY(-1px);
    }

    .chip-button:active {
        transform: translateY(0);
        box-shadow: none;
    }

    @media (max-width:1100px) {
        .controls {
            justify-content: flex-start;
        }

        .chip-search {
            min-width: 220px;
            max-width: 100%;
        }

        table.wc-table {
            min-width: 720px;
        }
    }

    @media (max-width:820px) {
        .chip-group {
            min-width: 220px;
            gap: 6px;
        }

        .table-scroll {
            overflow-x: auto;
        }

        table.wc-table {
            min-width: 720px;
            font-size: 0.9rem;
        }
    }

    .table-wrapper {
        margin-top: 12px;
        border-radius: 10px;
        background: #fff;
        border: 1px solid var(--wc-border);
        box-shadow: 0 8px 24px rgba(2, 6, 23, 0.03);
        overflow: visible;
        position: relative;
    }

    .table-scroll {
        width: 100%;
        overflow-x: auto;
        overflow-y: visible;
        -webkit-overflow-scrolling: touch;
    }

    table.wc-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 720px;
        font-size: 0.95rem;
    }

    table.wc-table thead th {
        padding: 14px 16px;
        font-weight: 700;
        font-size: 0.95rem;
        text-align: left;
        color: var(--wc-text);
        border-bottom: 1px solid var(--wc-border);
        white-space: nowrap;
    }

    table.wc-table tbody tr {
        transition: background .12s ease;
        border-bottom: 1px solid var(--wc-border);
    }

    table.wc-table tbody tr:hover {
        background: rgba(15, 157, 128, 0.03);
    }

    table.wc-table td {
        padding: 12px 16px;
        vertical-align: middle;
        color: var(--wc-text);
    }

    .status-pill {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.86rem;
        border: 1px solid rgba(0, 0, 0, 0.03);
        background: rgba(255, 255, 255, 0.92);
        color: var(--wc-text);
    }

    .status-pill.pending {
        color: #7a5900;
        background: var(--wc-pill-pending);
        border-color: rgba(122, 89, 0, 0.12);
    }

    .status-pill.approved {
        color: #064d6b;
        background: var(--wc-pill-approved);
        border-color: rgba(6, 77, 107, 0.12);
    }

    .status-pill.completed {
        color: #0b6b4a;
        background: var(--wc-pill-completed);
        border-color: rgba(11, 107, 74, 0.12);
    }

    .status-pill.cancelled {
        color: #7a0e12;
        background: var(--wc-pill-cancelled);
        border-color: rgba(122, 14, 18, 0.12);
    }

    .status-pill.reschedule {
        color: #5a3f9a;
        background: var(--wc-pill-reschedule);
        border-color: rgba(90, 63, 154, 0.12);
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: linear-gradient(135deg, #0f9d80, #12b981);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-view:active {
        transform: translateY(0);
    }

    .btn-view i {
        font-size: 0.8rem;
    }

    .no-results {
        padding: 28px;
        text-align: center;
        color: var(--wc-muted);
    }

    .pagination-wrap {
        margin-top: 12px;
        display: flex;
        justify-content: flex-end;
    }

    /* Date popover */
    .date-wrap {
        position: relative;
    }

    .date-popover {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.16);
        border: 1px solid var(--wc-border);
        padding: 10px 12px;
        min-width: 220px;
        z-index: 11000;
        display: none;
    }

    .date-popover.show {
        display: block;
    }

    .date-popover-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: .85rem;
        font-weight: 600;
        color: var(--wc-text);
    }

    .date-popover-header button {
        border: none;
        background: transparent;
        font-size: .8rem;
        color: #ef4444;
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .date-popover-body {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 8px;
    }

    .date-popover-body label {
        font-size: .8rem;
        color: var(--wc-muted);
        margin-bottom: 2px;
    }

    .date-popover-body input[type="date"] {
        width: 100%;
        border-radius: 8px;
        border: 1px solid var(--wc-border);
        padding: 7px 10px;
        font-size: .86rem;
    }

    .date-popover-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 4px;
    }

    .btn-link-sm {
        border: none;
        background: transparent;
        font-size: .82rem;
        color: var(--wc-muted);
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 999px;
        transition: background .15s ease, color .15s ease;
    }

    .btn-link-sm:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .btn-apply-sm {
        border: none;
        border-radius: 999px;
        padding: 6px 14px;
        background: #12b981;
        color: #fff;
        font-size: .82rem;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.18);
        transition: background .15s ease, box-shadow .15s ease, transform .1s ease;
    }

    .btn-apply-sm:hover {
        background: #0c7c65;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.22);
        transform: translateY(-1px);
    }

    .btn-apply-sm[disabled] {
        background: #e5e7eb;
        color: #9ca3af;
        box-shadow: none;
        cursor: not-allowed;
        transform: none;
    }

    .field-error {
        color: #b91c1c;
        font-size: .78rem;
        margin-top: 4px;
        display: none;
    }

    /* ===== Export popover ===== */
    .export-wrap {
        position: relative;
    }

    .export-menu {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.16);
        border: 1px solid var(--wc-border);
        padding: 10px 12px;
        min-width: 200px;
        z-index: 11000;
        display: none;
    }

    .export-menu.show {
        display: block;
    }

    .export-menu a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 8px;
        font-size: .9rem;
        color: var(--wc-text);
        text-decoration: none;
    }

    .export-menu a:hover {
        background: #f9fafb;
    }
</style>

<div class="admin-page-wrapper">
    <div class="appointments-card">
        {{-- TOP HEADER --}}
        <div class="appointments-top">
            <div class="title">
                <h2>Appointments</h2>
                <div class="subtitle">
                    For hospital: <strong>{{ $hospital->name }}</strong>
                    @if(!empty($hospital->unique_id))
                    <span class="text-muted ms-1">({{ $hospital->unique_id }})</span>
                    @endif
                </div>
            </div>

            {{-- CONTROLS: search + date filter --}}
            <div class="controls" role="region" aria-label="Appointments controls">
                <form
                    id="hospitalAppointmentsFilterForm"
                    method="GET"
                    action="{{ route('hospital.appointments.index') }}"
                    class="filter-form">

                    <div class="chip-group">
                        {{-- Search --}}
                        <div class="chip-search">
                            <input
                                id="hospitalAppointmentsSearchInput"
                                type="search"
                                name="q"
                                placeholder="Search patient, phone, service..."
                                value="{{ request('q') ?? '' }}">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                                <span>Search</span>
                            </button>
                        </div>

                        {{-- Date filter --}}
                        <div class="date-wrap">
                            <button type="button"
                                class="chip-button"
                                id="hospitalDateToggle">
                                <i class="fa-solid fa-calendar-days"></i>
                                <span>Date</span>
                            </button>

                            <div class="date-popover" id="hospitalDatePopover">
                                <div class="date-popover-header">
                                    <span>
                                        <i class="fa fa-filter-circle-xmark" style="margin-right:4px; color:#ef4444;"></i>
                                        Clear date filter
                                    </span>
                                    <button type="button" id="hospitalDateClearBtn">
                                        <i class="fa fa-eraser"></i> Clear
                                    </button>
                                </div>

                                <div class="date-popover-body">
                                    <div>
                                        <label for="appointmentsDateFrom">From</label>
                                        <input
                                            id="appointmentsDateFrom"
                                            type="date"
                                            name="date_from"
                                            value="{{ request('date_from') }}">
                                    </div>
                                    <div>
                                        <label for="appointmentsDateTo">To</label>
                                        <input
                                            id="appointmentsDateTo"
                                            type="date"
                                            name="date_to"
                                            value="{{ request('date_to') }}">
                                    </div>
                                </div>

                                <div id="hospitalDateError" class="field-error"></div>

                                <div class="date-popover-footer">
                                    <button type="button" class="btn-link-sm" id="hospitalDateCloseBtn">Close</button>
                                    <button type="button" class="btn-apply-sm" id="hospitalDateApplyBtn">Apply</button>
                                </div>
                            </div>
                        </div>

                        {{-- Export --}}
                        {{-- <div class="export-wrap">
                            <button type="button"
                                class="chip-button"
                                id="hospitalExportToggle">
                                <i class="fa fa-file-export"></i>
                                <span>Export</span>
                            </button>

                            <div class="export-menu" id="hospitalExportMenu">
                                <a href="{{ $pdfUrl }}" data-export>
                                    <i class="fa fa-file-pdf"></i> PDF
                                </a>
                                <a href="{{ $excelUrl }}" data-export>
                                    <i class="fa fa-file-excel"></i> Excel
                                </a>
                            </div>
                        </div> --}}

                    </div>

                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-wrapper">
            <div class="table-scroll">
                <table class="wc-table" aria-label="Hospital appointments table">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Patient Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $sr = ($appointments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        ? (($appointments->currentPage() - 1) * $appointments->perPage() + 1)
                        : 1;
                        @endphp

                        @forelse($appointments as $appointment)
                        @php
                        $status = strtolower($appointment->status ?? 'pending');
                        $statusClass = 'status-pill ' . $status;
                        @endphp
                        <tr>
                            <td>{{ $sr++ }}</td>
                            <td>{{ $appointment->name ?? '-' }}</td>

                            {{-- Date --}}
                            <td>
                                @if($appointment->date)
                                {{ \Carbon\Carbon::parse($appointment->date)->format('d M Y') }}
                                @else
                                —
                                @endif
                            </td>

                            {{-- Time --}}
                            <td>
                                @if($appointment->time_slot)
                                {{ $appointment->time_slot }}
                                @else
                                —
                                @endif
                            </td>

                            <td>{{ $appointment->service ?? '-' }}</td>

                            <td>
                                <span class="{{ $statusClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('hospital.appointments.show', $appointment->id) }}" 
                                       class="btn-view">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="no-results">
                                No appointments found for the selected filters.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($appointments instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="pagination-wrap">
            {{ $appointments->appends(request()->only('q','date_from','date_to'))->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    (function() {
        const filterForm = document.getElementById('hospitalAppointmentsFilterForm');

        const dateToggle = document.getElementById('hospitalDateToggle');
        const datePopover = document.getElementById('hospitalDatePopover');
        const dateApply = document.getElementById('hospitalDateApplyBtn');
        const dateClose = document.getElementById('hospitalDateCloseBtn');
        const dateClear = document.getElementById('hospitalDateClearBtn');
        const fromInp = document.getElementById('appointmentsDateFrom');
        const toInp = document.getElementById('appointmentsDateTo');
        const dateErrorMsg = document.getElementById('hospitalDateError');

        function closeDatePopover() {
            if (datePopover) datePopover.classList.remove('show');
        }

        function updateApplyState() {
            if (!dateApply || !fromInp || !toInp) return;
            const hasFrom = !!fromInp.value;
            const hasTo = !!toInp.value;

            if ((hasFrom && !hasTo) || (!hasFrom && hasTo)) {
                dateApply.disabled = true;
                if (dateErrorMsg) {
                    dateErrorMsg.textContent = 'Please select both From and To dates.';
                    dateErrorMsg.style.display = 'block';
                }
            } else {
                dateApply.disabled = false;
                if (dateErrorMsg) {
                    dateErrorMsg.textContent = '';
                    dateErrorMsg.style.display = 'none';
                }
            }
        }

        if (fromInp) {
            fromInp.addEventListener('input', updateApplyState);
            fromInp.addEventListener('change', updateApplyState);
        }
        if (toInp) {
            toInp.addEventListener('input', updateApplyState);
            toInp.addEventListener('change', updateApplyState);
        }
        updateApplyState();

        if (dateToggle && datePopover) {
            dateToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const show = !datePopover.classList.contains('show');
                if (show) datePopover.classList.add('show');
                else closeDatePopover();
            });
        }

        if (dateApply && filterForm) {
            dateApply.addEventListener('click', function(e) {
                e.preventDefault();
                if (dateApply.disabled) return;
                filterForm.submit();
            });
        }

        if (dateClose) {
            dateClose.addEventListener('click', function(e) {
                e.preventDefault();
                closeDatePopover();
            });
        }

        if (dateClear && filterForm) {
            dateClear.addEventListener('click', function(e) {
                e.preventDefault();
                if (fromInp) fromInp.value = '';
                if (toInp) toInp.value = '';
                if (dateErrorMsg) {
                    dateErrorMsg.textContent = '';
                    dateErrorMsg.style.display = 'none';
                }
                updateApplyState();
                filterForm.submit();
            });
        }

        // click outside closes popover
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.date-wrap')) {
                closeDatePopover();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDatePopover();
            }
        });
    })();
    /* ===== Export popover ===== */
    (function() {

        const exportToggle = document.getElementById('hospitalExportToggle');
        const exportMenu = document.getElementById('hospitalExportMenu');

        if (!exportToggle || !exportMenu) return;

        function closeExportMenu() {
            exportMenu.classList.remove('show');
        }

        exportToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            exportMenu.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.export-wrap')) {
                closeExportMenu();
            }
        });

        /* Inline export download */
        document.addEventListener('click', async function(e) {
            const link = e.target.closest('[data-export]');
            if (!link) return;

            e.preventDefault();

            const original = link.innerHTML;
            link.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Preparing…';

            try {
                const res = await fetch(link.href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!res.ok) throw new Error();

                const blob = await res.blob();
                const a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = '';
                document.body.appendChild(a);
                a.click();
                a.remove();
            } catch {
                alert('Export failed. Please try again.');
            } finally {
                link.innerHTML = original;
            }
        }, true);

    })();
</script>
@endpush
@endsection