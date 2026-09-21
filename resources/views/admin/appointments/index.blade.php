{{-- resources/views/admin/appointments/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Appointments')

@section('content')
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- External CSS --}}
    <link href="{{ asset('Back_end/css/admin/appointments.css') }}" rel="stylesheet">

    @php
        // build query params for exports
        $params = request()->only(['q', 'date_from', 'date_to', 'sort', 'hospital_qr']);

        $cleanParams = [];
        foreach ($params as $k => $v) {
            if ($v !== null && $v !== '') {
                $cleanParams[$k] = $v;
            }
        }

        $qp = http_build_query($cleanParams);
        $pdfUrl = url('/admin/appointments/export/pdf') . ($qp ? '?' . $qp : '');
        $excelUrl = url('/admin/appointments/export/excel') . ($qp ? '?' . $qp : '');
    @endphp

    <div class="admin-page-wrapper">
        <div class="appointments-card">
            <div class="appointments-top">
                <div class="title">
                    <h2>Appointments</h2>
                    <div class="subtitle">Recent bookings — manage and update status</div>
                </div>

                {{-- Controls (search + date + export) --}}
                <div class="controls" role="region" aria-label="Appointments controls">
                    <form id="appointmentsFilterForm" method="GET" action="{{ route('admin.appointments.index') }}"
                        class="filter-form">

                        <div class="chip-group">

                            <a href="{{ route('admin.appointments.create') }}" class="wc-btn-primary">
                                <i class="fa fa-plus"></i> Create Booking
                            </a>

                            {{-- Search --}}
                            <div class="chip-search">
                                <input id="appointmentsSearchInput" type="search" name="q"
                                    placeholder="Search name, email, phone, status, hospital"
                                    value="{{ request('q') ?? '' }}">
                                <button type="submit">
                                    <i class="fa fa-search"></i>
                                    <span>Search</span>
                                </button>
                            </div>

                            {{-- Date dropdown --}}
                            <div class="date-wrap">
                                <button type="button" class="chip-button" id="apDateToggle">
                                    <i class="fa-solid fa-calendar-days"></i>
                                    <span>Date</span>
                                </button>

                                <div class="date-popover" id="apDatePopover">
                                    <div class="date-popover-header">
                                        <span>
                                            <i class="fa fa-filter-circle-xmark"
                                                style="margin-right:4px; color:#ef4444;"></i>
                                            Clear Date Filter
                                        </span>
                                        <button type="button" id="apDateClearBtn">
                                            <i class="fa fa-eraser"></i> Clear
                                        </button>
                                    </div>

                                    <div class="date-popover-body">
                                        <div>
                                            <label for="appointmentsDateFrom">From</label>
                                            <input id="appointmentsDateFrom" type="date" name="date_from"
                                                value="{{ request('date_from') }}">
                                        </div>
                                        <div>
                                            <label for="appointmentsDateTo">To</label>
                                            <input id="appointmentsDateTo" type="date" name="date_to"
                                                value="{{ request('date_to') }}">
                                        </div>
                                    </div>

                                    <div id="apDateError" class="text-danger small mt-1" style="display:none;">
                                    </div>

                                    <div class="date-popover-footer">
                                        <button type="button" class="btn-link-sm" id="apDateCloseBtn">Close</button>
                                        <button type="button" class="btn-apply-sm" id="apDateApplyBtn">Apply</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Export dropdown --}}
                            <div class="export-wrap">
                                <button type="button" class="chip-button" id="apExportToggle">
                                    <i class="fa fa-file-export"></i>
                                    <span>Export</span>
                                </button>

                                <div class="export-menu" id="apExportMenu">
                                    <a href="{{ $pdfUrl }}" title="Export filtered appointments to PDF"
                                        data-export="pdf" data-mime="application/pdf">
                                        <i class="fa fa-file-pdf"></i>
                                        <span>PDF</span>
                                    </a>

                                    <a href="{{ $excelUrl }}" title="Export filtered appointments to Excel"
                                        data-export="excel"
                                        data-mime="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                        <i class="fa fa-file-excel"></i>
                                        <span>Excel</span>
                                    </a>
                                </div>
                            </div>

                            @if (request()->has('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            @if (request()->has('hospital_qr'))
                                <input type="hidden" name="hospital_qr" value="{{ request('hospital_qr') }}">
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-wrapper">
                <div class="table-scroll">
                    <table class="wc-table" aria-label="Appointments table">
                        <thead>
                            <tr>
                                <th style="width:center;">Sr.No</th>
                                <th style="min-width:140px;">Patient</th>
                                <th style="min-width:180px;">Contact</th>
                                <th style="width:150px;">Date & time</th>
                                <th style="min-width:160px;">Item</th>
                                <th style="width:120px; text-align:center;">Price</th>
                                <th style="width:140px; text-align:center;">Status</th>
                                <th style="width:110px; text-align:center;">Action</th>
                            </tr>
                        </thead>

                        <tbody id="appointmentsTableBody">
                            @php $sr = ($appointments->currentPage() - 1) * $appointments->perPage() + 1; @endphp

                            @forelse($appointments as $appointment)
                                @php
                                    $latestPayment = $appointment->payments()->latest('id')->first();
                                    $paymentMode = $appointment->payment_method;
                                    $st = strtolower($appointment->status ?? 'pending');
                                    $pill = 'status-pill ' . ($st ?: 'pending');
                                    $statusIcon = match ($st) {
                                        'approved' => 'fa-check',
                                        'completed' => 'fa-circle-check',
                                        'cancelled' => 'fa-xmark',
                                        'reschedule' => 'fa-calendar-alt',
                                        default => 'fa-clock',
                                    };
                                @endphp

                                <tr data-appointment-id="{{ $appointment->id }}">
                                    <td style="font-weight:700;" data-label="Sr.">{{ $sr++ }}</td>

                                    <td data-label="Patient">
                                        <div class="patient-info">
                                            <div class="patient-name">{{ $appointment->name ?? '-' }}</div>
                                            <div class="patient-meta">
                                                {{ $appointment->hospital->name ?? '' }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="contact-col" data-label="Contact">
                                        <span class="muted">{{ $appointment->email ?? '-' }}</span>
                                        <span class="muted"
                                            style="display:block; margin-top:6px;">{{ $appointment->phone ?? '-' }}</span>
                                        <span class="muted" style="display:block; margin-top:6px;">Payment:
                                            {{ $paymentMode ? ucfirst($paymentMode) : '-' }}</span>
                                        <span style="font-size: smaller;">
                                            {{ $latestPayment?->transaction_id ?? ($latestPayment?->txnid ?? '-') }}
                                        </span>
                                    </td>

                                    <td class="date-col" data-label="Date & time">
                                        @if ($appointment->date)
                                            {{ \Carbon\Carbon::parse($appointment->date)->format('d M Y') }}
                                            @php
                                                $t = $appointment->time_slot;
                                                try {
                                                    $formatted =
                                                        $t && preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', $t)
                                                            ? \Carbon\Carbon::createFromFormat(
                                                                strlen($t) === 5 ? 'H:i' : 'H:i:s',
                                                                $t,
                                                            )->format('g:i A')
                                                            : $t;
                                                } catch (\Throwable $e) {
                                                    $formatted = $t;
                                                }
                                            @endphp
                                            <div class="time">{{ $formatted ?: '-' }}</div>
                                        @else
                                            —
                                        @endif
                                    </td>

                                    {{-- ITEM COLUMN --}}
                                    <td class="item-col" data-label="Item">
                                        @php
                                            $itemsCol = collect($appointment->items ?? []);
                                            $calcSubtotal =
                                                $appointment->subtotal ??
                                                $itemsCol->sum(
                                                    fn($i) => (float) ($i->item_price ?? 0) * (int) ($i->quantity ?? 1),
                                                );
                                            $calcDiscount = (float) ($appointment->discount_amount ?? 0);
                                            $calcTotal =
                                                $appointment->total_price ??
                                                max(0, (float) $calcSubtotal - (float) $calcDiscount);

                                            $fallbackName =
                                                $appointment->items_summary ??
                                                ($appointment->package_name ??
                                                    ($appointment->service ??
                                                        (optional($appointment->test)->test_name ?? 'Item')));

                                            if ($itemsCol->isEmpty()) {
                                                $itemPayload = collect([
                                                    [
                                                        'item_type' => $appointment->package_name
                                                            ? 'PACKAGE'
                                                            : ($appointment->service
                                                                ? 'SERVICE'
                                                                : (optional($appointment->test)->test_name
                                                                    ? 'TEST'
                                                                    : 'ITEM')),
                                                        'item_name' => (string) $fallbackName,
                                                        'item_price' => (float) ($calcSubtotal ?: $calcTotal ?: 0),
                                                        'quantity' => 1,
                                                        'components' => [],
                                                    ],
                                                ]);
                                                $itemsLabel = $fallbackName;
                                            } else {
                                                $itemPayload = $itemsCol
                                                    ->map(function ($i) {
                                                        return [
                                                            'item_type' => (string) ($i->item_type ?? ''),
                                                            'item_name' => (string) ($i->item_name ?? ''),
                                                            'item_price' => (float) ($i->item_price ?? 0),
                                                            'quantity' => (int) ($i->quantity ?? 1),
                                                            'components' => is_array($i->components ?? null)
                                                                ? array_values($i->components)
                                                                : [],
                                                        ];
                                                    })
                                                    ->values();

                                                $itemsLabel =
                                                    $appointment->items_summary ??
                                                    $itemsCol->first()->item_name .
                                                        ($itemsCol->count() > 1
                                                            ? ' (+' . ($itemsCol->count() - 1) . ' more)'
                                                            : '');
                                            }
                                        @endphp

                                        <div class="muted">{{ $itemsLabel }}</div>

                                        <button type="button" class="btn btn-sm btn-outline-success mt-2 view-items-btn"
                                            title="View items" data-bs-toggle="modal" data-bs-target="#itemsModal"
                                            data-appointment-id="{{ $appointment->id }}"
                                            data-appointment-name="{{ $appointment->name ?? '' }}"
                                            data-items='@json($itemPayload)'
                                            data-subtotal="{{ number_format((float) $calcSubtotal, 2, '.', '') }}"
                                            data-discount="{{ number_format((float) $calcDiscount, 2, '.', '') }}"
                                            data-total="{{ number_format((float) $calcTotal, 2, '.', '') }}"
                                            data-coupon="{{ $appointment->coupon_code ?? '' }}">
                                            <i class="fa-solid fa-list me-1"></i> View items
                                        </button>
                                    </td>

                                    {{-- PRICE COLUMN --}}
                                    <td class="price-col" data-label="Price">
                                        @php
                                            $subtotal = $calcSubtotal;
                                            $discount = $calcDiscount;
                                            $total = $calcTotal;
                                        @endphp

                                        <div><b>₹{{ number_format($total, 2) }}</b></div>
                                        @if ($discount > 0)
                                            <div style="font-size:12px; color:#0f9d80;">-
                                                ₹{{ number_format($discount, 2) }}
                                                {{ $appointment->coupon_code ? '(' . $appointment->coupon_code . ')' : '' }}
                                            </div>
                                        @endif
                                        <div style="font-size:11px; color:#64748b;">Subtotal:
                                            ₹{{ number_format($subtotal, 2) }}</div>
                                    </td>

                                    <td style="text-align:center;" data-label="Status">
                                        <span class="{{ $pill }}">{{ ucfirst($st) }}</span>
                                    </td>

                                    <td style="text-align:right;" data-label="Action">
                                        <div style="display:flex; gap:8px; justify-content:flex-end; align-items:center;">
                                            {{-- status icon button + menu --}}
                                            <div class="status-action" data-appointment-id="{{ $appointment->id }}">
                                                <button class="status-action-btn" aria-haspopup="true"
                                                    aria-expanded="false" title="Change status"
                                                    data-current-status="{{ $st }}">
                                                    <i class="fa {{ $statusIcon }}"></i>
                                                </button>

                                                <div class="status-menu" role="menu" aria-hidden="true"
                                                    aria-label="Status options">
                                                    <button class="status-item" data-status="Pending" type="button">
                                                        <i class="fa fa-clock status-icon pending"></i> Pending
                                                    </button>
                                                    <button class="status-item" data-status="Approved" type="button">
                                                        <i class="fa fa-check status-icon approved"></i> Approved
                                                    </button>
                                                    <button class="status-item" data-status="Reschedule" type="button">
                                                        <i class="fa fa-calendar-alt status-icon reschedule"></i>
                                                        Reschedule
                                                    </button>
                                                    <button class="status-item" data-status="Completed" type="button">
                                                        <i class="fa fa-circle-check status-icon completed"></i> Completed
                                                    </button>
                                                    <button class="status-item" data-status="Cancelled" type="button">
                                                        <i class="fa fa-xmark status-icon cancelled"></i> Cancelled
                                                    </button>
                                                </div>
                                            </div>

                                            <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                                                title="View" class="icon-btn">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                           @if($appointment->status === 'Completed')
    <a href="javascript:void(0);"
       class="icon-btn disabled"
       title="Cannot edit completed appointment"
       style="opacity:0.5; pointer-events:none; cursor:not-allowed;">
        <i class="fa-solid fa-lock"></i>
    </a>
@else
    <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
       class="icon-btn"
       title="Edit Booking">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
@endif


                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="no-results">No appointments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="pagination-wrap">
                {{ $appointments->appends(request()->only('q', 'date_from', 'date_to', 'sort', 'hospital_qr'))->links() }}
            </div>
        </div>
    </div>

    {{-- Reschedule Modal --}}
    <div class="modal fade" id="adminRescheduleModal" tabindex="-1" aria-labelledby="adminRescheduleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content wc-modal-card">
                <form id="adminRescheduleForm" method="POST" action="" novalidate>
                    @csrf
                    @method('PATCH')

                    <div class="modal-header wc-modal-header-primary">
                        <div class="wc-modal-header-main">
                            <div class="wc-modal-title-pill">
                                <span class="wc-modal-title-pill-icon">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>
                                <div>
                                    <h5 class="wc-modal-title-main" id="adminRescheduleModalLabel">Reschedule Appointment
                                    </h5>
                                    <div class="wc-modal-header-sub">
                                        <i class="fa-regular fa-clock"></i>
                                        <span>Pick a new date & time and mark as rescheduled.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body wc-modal-body">
                        <input type="hidden" id="rescheduleAppointmentId" name="appointment_id" value="">

                        <div class="mb-3">
                            <label for="rescheduleDate" class="form-label">New date</label>
                            <input id="rescheduleDate" name="date" type="date" class="form-control">
                            <div id="dateError" class="field-error text-danger mt-1" style="display:none;"></div>
                        </div>

                        <div class="mb-3">
                            <label for="rescheduleTime" class="form-label">Time / slot</label>
                            <select id="rescheduleTime" name="time_slot" class="form-select">
                                <option value="">Select time slot</option>
                                @php
                                    // Generate hourly slots like "10:30 AM to 11:30 AM"
                                    // while storing only the START time (24h) in the DB.
                                    $start = \Carbon\Carbon::createFromTime(5, 30); // 05:30
                                    $lastStart = \Carbon\Carbon::createFromTime(14, 30); // 14:30 => last slot 14:30–15:30
                                    $timeSlots = [];

                                    while ($start->lte($lastStart)) {
                                        $from = $start->copy();
                                        $to = $start->copy()->addHour();

                                        $timeSlots[] = [
                                            // this goes into appointments.time_slot (e.g. "10:30")
                                            'value' => $from->format('H:i'),
                                            // this is what admin sees in dropdown (e.g. "10:30 AM to 11:30 AM")
                                            'label' => $from->format('h:i A') . ' to ' . $to->format('h:i A'),
                                        ];

                                        $start->addHour();
                                    }
                                @endphp

                                @foreach ($timeSlots as $slot)
                                    <option value="{{ $slot['value'] }}">{{ $slot['label'] }}</option>
                                @endforeach>
                                <option value="custom">Custom…</option>
                            </select>
                            <div class="form-text">Select a slot or choose "Custom…" to type your own.</div>

                            <input id="customTimeInput" type="text" class="form-control mt-2"
                                placeholder="e.g. 10:15 AM, 15:30, or Morning" style="display:none;">
                            <div id="timeError" class="field-error text-danger mt-1" style="display:none;"></div>
                        </div>

                        <div id="rescheduleError" class="field-error text-danger mt-1" style="display:none;"></div>
                    </div>

                    <div class="modal-footer wc-modal-footer">
                        <button type="button" class="wc-btn-outline" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark"></i>
                            <span>Cancel</span>
                        </button>
                        <button type="submit" id="rescheduleSubmitBtn" class="wc-btn-primary">
                            <i class="fa-regular fa-floppy-disk"></i>
                            <span>Save & Mark Rescheduled</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Upload Report Modal --}}
    <div class="modal fade" id="uploadReportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form id="uploadReportForm" method="POST" action="{{ route('admin.reports.store') }}"
                enctype="multipart/form-data" class="modal-content wc-modal-card" novalidate
                data-share-action-base="{{ route('appointments.share-whatsapp', '__ID__') }}">
                @csrf
                <input type="hidden" name="appointment_id" id="uploadAppointmentId" value="">

                <div class="modal-header wc-modal-header-primary">
                    <div class="wc-modal-header-main">
                        <div class="wc-modal-title-pill">
                            <span class="wc-modal-title-pill-icon">
                                <i class="fa-regular fa-file-pdf"></i>
                            </span>
                            <div>
                                <h5 class="wc-modal-title-main">Upload & Complete</h5>
                                <div class="wc-modal-header-sub">
                                    <i class="fa-regular fa-circle-check"></i>
                                    <span>Upload the patient report PDF and mark appointment as completed.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body wc-modal-body">
                    <div class="mb-3">
                        <label class="form-label">Patient</label>
                        <input id="patient_name_display" type="text" class="form-control" disabled value="">
                        <input type="hidden" name="patient_name" id="uploadPatientName" value="">
                        <div class="field-error" data-for="patient_name" style="display:none;"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mobile Number (with country code)</label>
                        <input type="text" name="mobile_number" id="mobileNumberInput" class="form-control" required>
                        <div class="field-error" data-for="mobile_number" style="display:none;"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Test / Item</label>
                        <input id="test_name_display" type="text" class="form-control" disabled value="">
                        <input type="hidden" name="test_name" id="uploadTestName" value="">
                        <div class="field-error" data-for="test_name" style="display:none;"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Report Date</label>
                        <input type="date" name="report_date" id="reportDate" class="form-control" required>
                        <div class="field-error" data-for="report_date" style="display:none;"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">PDF file</label>
                        <input id="reportFileInput" type="file" name="report_file" accept="application/pdf"
                            class="form-control" required>
                        <small class="text-muted">Max 10MB. PDF only.</small>
                        <div class="field-error" data-for="report_file" style="display:none;"></div>
                    </div>

                    <input type="hidden" name="stamp_header_footer" value="0">
                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" name="stamp_header_footer" value="1" type="checkbox"
                            id="stampHeader" checked>
                        <label class="form-check-label" for="stampHeader">Stamp header & footer (applied by
                            default)</label>
                        <div class="field-error" data-for="stamp_header_footer" style="display:none;"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prefill share message</label>
                        <textarea name="prefill_message" id="postUploadShareMessage" class="form-control" rows="2" required></textarea>
                        <div class="field-error" data-for="prefill_message" style="display:none;"></div>
                    </div>

                    <div id="uploadReportAlert" class="alert d-none" role="alert"></div>
                </div>

                <div class="modal-footer wc-modal-footer">
                    <button type="button" id="uploadReportCancelBtn" class="wc-btn-outline" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                        <span>Cancel</span>
                    </button>
                    <button id="uploadReportSubmitBtn" type="submit" class="wc-btn-primary">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Upload & Mark Complete</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Items Modal --}}
    <div class="modal fade" id="itemsModal" tabindex="-1" aria-labelledby="itemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="itemsModalLabel" class="modal-title">Appointment Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div id="itemsModalMeta" class="mb-3">
                        <small class="text-muted" id="modalPatientName"></small>
                    </div>

                    <div id="itemsModalList" class="mb-4">
                        <!-- Items will be populated here by JavaScript -->
                    </div>

                    <hr>

                    <div id="itemsModalSummary" class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="itemsSubtotal" class="fw-bold">₹0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount: <span id="itemsCouponWrap" class="text-muted"></span></span>
                            <span id="itemsDiscount" class="text-danger">-₹0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3 pt-2 border-top">
                            <span class="fw-bold">Total:</span>
                            <span id="itemsTotal" class="fw-bold fs-5">₹0.00</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Shared Share Modal Partial --}}
    @include('partials.share-modal')

    {{-- Hidden launcher: used to trigger share modal from JS after upload --}}
    <button type="button" id="autoShareLauncher" data-share-url="" data-share-action="" data-share-message=""
        style="display:none;"></button>

    {{-- External JS --}}
    <script src="{{ asset('Back_end/js/admin/appointments.js') }}"></script>
@endsection
