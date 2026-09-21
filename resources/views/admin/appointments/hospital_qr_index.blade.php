{{-- resources/views/admin/appointments/hospital_qr_index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Appointments (Hospital QR)')

@section('content')
{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- External CSS --}}
<link href="{{ asset('Back_end/css/admin/hospital-qr-appointments.css') }}" rel="stylesheet">

@php
// Build export URLs with hospital_qr=1 + existing filters
$params = request()->only(['q','date_from','date_to','sort','per_page']);
$params['hospital_qr'] = 1;

$cleanParams = [];
foreach ($params as $k => $v) {
    if ($v !== null && $v !== '') {
        $cleanParams[$k] = $v;
    }
}

$qp = http_build_query($cleanParams);
$pdfUrl = url('/admin/appointments/export/pdf') . ($qp ? ('?'.$qp) : '');
$excelUrl = url('/admin/appointments/export/excel') . ($qp ? ('?'.$qp) : '');
@endphp

<div class="admin-page-wrapper">
  <div class="appointments-card">
    <div class="appointments-top">
      <div class="title">
        <h2>Appointments (Hospital QR)</h2>
        <div class="subtitle">Showing only bookings that came via hospital QR codes</div>
      </div>

      {{-- FILTERS + DATE + EXPORT --}}
      <div class="controls" role="region" aria-label="Appointments controls">
        <form id="appointmentsFilterForm"
          method="GET"
          action="{{ route('admin.appointments.hospital_qr') }}"
          class="filter-form">

          <div class="chip-group">
            {{-- Search --}}
            <div class="chip-search">
              <input
                id="appointmentsSearchInput"
                type="search"
                name="q"
                placeholder="Search name, email, hospital name, status"
                value="{{ request('q') ?? '' }}">
              <button type="submit">
                <i class="fa fa-search"></i>
                <span>Search</span>
              </button>
            </div>

            {{-- Date dropdown --}}
            <div class="date-wrap">
              <button type="button"
                class="chip-button"
                id="apDateToggle">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Date</span>
              </button>

              <div class="date-popover" id="apDatePopover">
                <div class="date-popover-header">
                  <span>
                    <i class="fa fa-filter-circle-xmark" style="margin-right:4px; color:#ef4444;"></i>
                    Clear Date Filter
                  </span>
                  <button type="button" id="apDateClearBtn">
                    <i class="fa fa-eraser"></i> Clear
                  </button>
                </div>

                <div class="date-popover-body">
                  <div>
                    <label for="apDateFrom">From</label>
                    <input
                      id="apDateFrom"
                      type="date"
                      name="date_from"
                      value="{{ request('date_from') }}">
                  </div>

                  <div>
                    <label for="apDateTo">To</label>
                    <input
                      id="apDateTo"
                      type="date"
                      name="date_to"
                      value="{{ request('date_to') }}">
                  </div>
                </div>

                <div id="apDateError" class="field-error" style="display:none;"></div>

                <div class="date-popover-footer">
                  <button type="button" class="btn-link-sm" id="apDateCloseBtn">Close</button>
                  <button type="button" class="btn-apply-sm" id="apDateApplyBtn">Apply</button>
                </div>
              </div>
            </div>

            {{-- Export dropdown --}}
            <div class="export-wrap">
              <button type="button"
                class="chip-button"
                id="apExportToggle">
                <i class="fa fa-file-export"></i>
                <span>Export</span>
              </button>

              <div class="export-menu" id="apExportMenu">
                <a href="{{ $pdfUrl }}"
                  title="Export filtered appointments to PDF"
                  data-export="pdf"
                  data-mime="application/pdf">
                  <i class="fa fa-file-pdf"></i>
                  <span>PDF</span>
                </a>

                <a href="{{ $excelUrl }}"
                  title="Export filtered appointments to Excel"
                  data-export="excel"
                  data-mime="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                  <i class="fa fa-file-excel"></i>
                  <span>Excel</span>
                </a>
              </div>
            </div>

            {{-- keep sort / per_page / hospital_qr --}}
            @if(request()->has('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            <input type="hidden" name="per_page" value="{{ request('per_page', 20) }}">
            <input type="hidden" name="hospital_qr" value="1">
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
              <th style="min-width:160px;">Hospital</th>
              <th style="min-width:160px;">Item</th>
              <th style="width:120px; text-align:center;">Price</th>
              <th style="width:140px; text-align:center;">Status</th>
              <th style="width:110px; text-align:center;">Action</th>
            </tr>
          </thead>

          <tbody id="appointmentsTableBody">
            @php
            $sr = ($appointments->currentPage() - 1) * $appointments->perPage() + 1;
            @endphp

            @forelse($appointments as $appointment)
            @php
            $st = strtolower($appointment->status ?? 'pending');
            $pill = 'status-pill ' . ($st ?: 'pending');

            $itemsCol = collect($appointment->items ?? []);
            $calcSubtotal = $appointment->subtotal
            ?? $itemsCol->sum(fn($i) => (float)($i->item_price ?? 0) * (int)($i->quantity ?? 1));
            $calcDiscount = (float)($appointment->discount_amount ?? 0);
            $calcTotal = $appointment->total_price ?? max(0, (float)$calcSubtotal - (float)$calcDiscount);

            $fallbackName = $appointment->items_summary
            ?? ($appointment->package_name
            ?? ($appointment->service
            ?? (optional($appointment->test)->test_name ?? 'Item')));

            if ($itemsCol->isEmpty()) {
                $itemPayload = collect([[
                    'item_type' => $appointment->package_name
                    ? 'PACKAGE'
                    : ($appointment->service
                    ? 'SERVICE'
                    : (optional($appointment->test)->test_name ? 'TEST' : 'ITEM')),
                    'item_name' => (string) $fallbackName,
                    'item_price' => (float) ($calcSubtotal ?: $calcTotal ?: 0),
                    'quantity' => 1,
                    'components' => [],
                ]]);

                $itemsLabel = $fallbackName;
            } else {
                $itemPayload = $itemsCol->map(function($i){
                    return [
                        'item_type' => (string)($i->item_type ?? ''),
                        'item_name' => (string)($i->item_name ?? ''),
                        'item_price' => (float)($i->item_price ?? 0),
                        'quantity' => (int)($i->quantity ?? 1),
                        'components' => is_array($i->components ?? null)
                        ? array_values($i->components)
                        : [],
                    ];
                })->values();

                $itemsLabel = $appointment->items_summary
                ?? ($itemsCol->first()->item_name
                . ($itemsCol->count() > 1
                ? ' (+' . ($itemsCol->count()-1) . ' more)'
                : ''));
            }
            @endphp

            <tr data-appointment-id="{{ $appointment->id }}">
              <td style="font-weight:700;" data-label="Sr.">{{ $sr++ }}</td>

              <td data-label="Patient">
                <div class="patient-info">
                  <div class="patient-name">{{ $appointment->name ?? '-' }}</div>
                  <div class="patient-meta">
                    Booked {{ \Carbon\Carbon::parse($appointment->created_at ?? now())->diffForHumans() }}
                  </div>
                </div>
              </td>

              <td class="contact-col" data-label="Contact">
                <span class="muted">{{ $appointment->email ?? '-' }}</span>
                <span class="muted" style="display:block; margin-top:6px;">
                  {{ $appointment->phone ?? '-' }}
                </span>
              </td>

              <td class="date-col" data-label="Date & time">
                @if($appointment->date)
                {{ \Carbon\Carbon::parse($appointment->date)->format('d M Y') }}
                <div class="time">{{ $appointment->time_slot ?: '-' }}</div>
                @else
                —
                @endif
              </td>

              <td class="hospital-col" data-label="Hospital">
                <span class="muted">
                  @if(!empty($appointment->hospital_name))
                  {{ $appointment->hospital_name }}
                  @elseif(!empty($appointment->hospital?->name))
                  {{ $appointment->hospital->name }}
                  @else
                  —
                  @endif
                </span>
              </td>

              {{-- ITEM COLUMN --}}
              <td class="item-col" data-label="Item">
                <div class="muted">{{ $itemsLabel }}</div>

                <button
                  type="button"
                  class="btn btn-sm btn-outline-success mt-2 view-items-btn"
                  title="View items"
                  data-bs-toggle="modal"
                  data-bs-target="#itemsModal"
                  data-appointment-id="{{ $appointment->id }}"
                  data-appointment-name="{{ $appointment->name ?? '' }}"
                  data-items='@json($itemPayload)'
                  data-subtotal="{{ number_format((float)$calcSubtotal, 2, '.', '') }}"
                  data-discount="{{ number_format((float)$calcDiscount, 2, '.', '') }}"
                  data-total="{{ number_format((float)$calcTotal, 2, '.', '') }}"
                  data-coupon="{{ $appointment->coupon_code ?? '' }}">
                  <i class="fa-solid fa-list me-1"></i>
                  View items
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

                @if($discount > 0)
                <div style="font-size:12px; color:#0f9d80;">
                  - ₹{{ number_format($discount, 2) }}
                  {{ $appointment->coupon_code ? '(' . $appointment->coupon_code . ')' : '' }}
                </div>
                @endif

                <div style="font-size:11px; color:#64748b;">
                  Subtotal: ₹{{ number_format($subtotal, 2) }}
                </div>
              </td>

              <td style="text-align:center;" data-label="Status">
                <span class="{{ $pill }}">{{ ucfirst($st) }}</span>
              </td>

              <td style="text-align:center;" data-label="Action">
                <div style="align-items:center;">
                  <a
                    href="{{ route('admin.appointments.show', $appointment->id) }}"
                    title="View"
                    class="icon-btn"
                    style="text-decoration:none;">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="9" class="no-results">No appointments found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Pagination --}}
    <div class="pagination-wrap">
      {{ $appointments->appends(request()->only('q','date_from','date_to','sort','per_page','hospital_qr'))->links() }}
    </div>
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

{{-- External JS --}}
<script src="{{ asset('Back_end/js/admin/hospital-qr-appointments.js') }}"></script>
@endsection