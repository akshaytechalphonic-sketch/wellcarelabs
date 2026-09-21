{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Reports')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
use App\Models\Appointment;
use Illuminate\Support\Facades\Storage;

$reportsCount = $reports->count();

// Build export URLs with current filters (q, date_from, date_to)
$params = request()->only(['q','date_from','date_to']);
$cleanParams = [];
foreach ($params as $k => $v) {
if ($v !== null && $v !== '') {
$cleanParams[$k] = $v;
}
}

// route('admin.reports.export', ['format' => 'pdf|excel', ...query...])
$pdfUrl = route('admin.reports.export', array_merge(['format' => 'pdf'], $cleanParams));
$excelUrl = route('admin.reports.export', array_merge(['format' => 'excel'], $cleanParams));
@endphp

<style>
  :root {
    --wc-text: #0f1724;
    --wc-muted: #6b7280;
    --wc-border: #e5e7eb;
    --wc-accent: #0f9d80;
    --wc-bg-soft: #f9fafb;
  }

  .admin-page-wrapper {
    margin: 20px 22px 25px 22px;
  }

  .admin-page-card-flush {
    margin-left: -18px;
    margin-right: -18px;
    width: calc(100% + 36px);
    border-radius: 12px;
    border: 1px solid var(--wc-border);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
    background: #ffffff;
  }

  @media (max-width: 768px) {
    .admin-page-wrapper {
      margin: 14px 12px 18px 12px;
    }

    .admin-page-card-flush {
      margin-left: 0;
      margin-right: 0;
      width: 100%;
    }
  }

  .panel-title {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: var(--wc-text);
    line-height: 1.1;
  }

  .panel-sub {
    color: var(--wc-muted);
    font-size: 0.9rem;
  }

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
    font-size: .9rem;
  }

  .chip-search button {
    border: 1px solid transparent;
    outline: none;
    border-radius: 999px;
    padding: 7px 16px;
    background: var(--wc-accent);
    color: #fff;
    font-weight: 500;
    font-size: .85rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    white-space: nowrap;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.16);
    transition: background .15s ease, box-shadow .15s ease, transform .1s ease, opacity .15s ease;
  }

  .chip-search button i {
    font-size: .9rem;
  }

  .chip-search button:hover {
    background: #0c7c65;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.2);
    transform: translateY(-1px);
  }

  .chip-search button:active {
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.18);
  }

  @media (max-width:640px) {
    .chip-search {
      width: 100%;
      max-width: none;
    }
  }

  .table-wrap {
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid var(--wc-border);
    background: #fff;
  }

  .table-scroll {
    overflow: auto;
    -webkit-overflow-scrolling: touch;
  }

  table.reports-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 860px;
    font-size: 0.92rem;
  }

  table.reports-table thead th {
    padding: 12px 14px;
    text-align: left;
    font-weight: 600;
    color: var(--wc-text);
    border-bottom: 1px solid var(--wc-border);
    white-space: nowrap;
    background-color: #f3f4f6;
  }

  table.reports-table tbody td {
    padding: 10px 14px;
    border-bottom: 1px solid #edf0f3;
    vertical-align: middle;
    color: var(--wc-text);
  }

  table.reports-table tbody tr:last-child td {
    border-bottom: none;
  }

  table.reports-table tbody tr:hover {
    background: #f9fafb;
  }

  .muted {
    color: var(--wc-muted);
    font-size: .85rem;
    display: block;
  }

  /* Generic icon buttons (View / Download / Delete) */
  .icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 999px;
    border: 1px solid #d1d5db;
    background: #ffffff;
    text-decoration: none;
    color: #4b5563;
    transition: background .15s ease, color .15s ease, border-color .15s ease,
      box-shadow .15s ease, transform .1s ease;
    font-size: .85rem;
    padding: 0;
    cursor: pointer;
  }

  .icon-btn i {
    font-size: 0.85rem;
    pointer-events: none;
  }

  .icon-btn:hover {
    background: #f9fafb;
    border-color: #9ca3af;
    color: #111827;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.18);
    transform: translateY(-1px);
  }

  .icon-btn:active {
    transform: translateY(0);
    box-shadow: none;
  }

  .btn-download {
    color: var(--wc-accent);
    border-color: rgba(15, 157, 128, 0.55);
    background: #f0fdf9;
  }

  .btn-download:hover {
    background: #ecfdf5;
    border-color: var(--wc-accent);
    color: var(--wc-accent);
  }

  .btn-delete {
    color: #b91c1c;
    border-color: rgba(248, 113, 113, 0.7);
    background: #fef2f2;
  }

  .btn-delete:hover {
    background: #fee2e2;
    border-color: #dc2626;
    color: #b91c1c;
  }

  .action-group,
  .share-actions {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
  }

  /* Date filter chip */
  .chip-button {
    border-radius: 999px;
    border: 1px solid var(--wc-border);
    background: #ffffff;
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

  .date-wrap {
    position: relative;
  }

  .date-popover {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.18);
    border: 1px solid var(--wc-border);
    padding: 10px 12px;
    min-width: 260px;
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
    font-size: .82rem;
    font-weight: 500;
    color: var(--wc-text);
  }

  .date-popover-header button {
    border: none;
    background: transparent;
    font-size: .78rem;
    color: #b91c1c;
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
    font-size: .78rem;
    color: var(--wc-muted);
    margin-bottom: 2px;
  }

  .date-popover-body input[type="date"] {
    width: 100%;
    border-radius: 8px;
    border: 1px solid var(--wc-border);
    padding: 6px 9px;
    font-size: .84rem;
  }

  .date-popover-body input[type="date"]:focus {
    outline: none;
    border-color: var(--wc-accent);
    box-shadow: 0 0 0 1px rgba(15, 157, 128, 0.3);
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
    font-size: .8rem;
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
    background: var(--wc-accent);
    color: #fff;
    font-size: .8rem;
    font-weight: 500;
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

  /* Round share button (Telegram-style, but in brand color) */
  .btn-telegram-icon {
    background: var(--wc-accent);
    color: #ffffff;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.22);
    transition: background .15s ease, box-shadow .15s ease, transform .1s ease, opacity .12s ease;
  }

  .btn-telegram-icon i {
    margin: 0;
  }

  .btn-telegram-icon:hover {
    background: #0c7c65;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.26);
    transform: translateY(-1px);
  }

  .btn-telegram-icon:active {
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.2);
  }

  /* Modal buttons */
  .admin-form .btn-sm.btn-secondary {
    border-radius: 999px;
    padding: 6px 14px;
    font-size: .8rem;
    border-color: #d1d5db;
    background: #f9fafb;
    color: #374151;
  }

  .admin-form .btn-sm.btn-secondary:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
  }

  .admin-form .btn-sm.btn-primary {
    border-radius: 999px;
    padding: 6px 16px;
    font-size: .8rem;
    background: var(--wc-accent);
    border-color: var(--wc-accent);
    font-weight: 500;
  }

  .admin-form .btn-sm.btn-primary:hover {
    background: #0c7c65;
    border-color: #0c7c65;
  }

  /* Export dropdown (same pattern as hospital_qr) */
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
    padding: 8px 10px;
    min-width: 190px;
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
    padding: 7px 9px;
    border-radius: 8px;
    font-size: .88rem;
    color: var(--wc-text);
    text-decoration: none;
    transition: background .15s ease, color .15s ease;
  }

  .export-menu a i {
    font-size: .9rem;
  }

  .export-menu a:hover {
    background: #f9fafb;
    color: #111827;
  }

  .btn-looks-disabled {
    opacity: .55;
    cursor: not-allowed;
  }

  /* Mobile layout: Date & Export BELOW search bar */
@media (max-width: 640px) {

  /* Allow toolbar to wrap */
  .admin-fragment-toolbar {
    flex-wrap: wrap;
    row-gap: 8px;
  }

  /* Left side (results text) full width */
  .admin-fragment-toolbar .toolbar-left {
    width: 100%;
  }

  /* Right controls stack */
  .admin-fragment-toolbar .toolbar-right {
    width: 100%;
    margin-top: 4px;
    flex-wrap: wrap;
    justify-content: flex-start;
  }

  /* Search row full width */
  .admin-fragment-toolbar .toolbar-right form {
    width: 100%;
  }

  .admin-fragment-toolbar .chip-search {
    width: 100%;
    max-width: none;
  }

  /* Date + Export move to next line */
  .admin-fragment-toolbar .date-wrap,
  .admin-fragment-toolbar .export-wrap {
    margin-top: 6px;
  }
}

</style>

<div class="admin-page-wrapper">
  <div class="card shadow-sm admin-page-card-flush">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom bg-white">
      <div>
        <h5 class="panel-title mb-0">Lab Reports</h5>
        <small class="panel-sub">Manage and share uploaded reports</small>
      </div>
    </div>

    <div class="card-body p-2 pt-1" style="margin-top:-6px;">
      <div id="reportsAdminBody" class="admin-fragment p-3" data-loaded-url="{{ url()->current() }}">

        <div class="d-flex justify-content-between align-items-center mb-2 admin-fragment-toolbar"
          style="border-bottom:1px solid #eef2f6;padding-bottom:8px;">
          <div class="toolbar-left">
            <div class="results-text text-muted small">
              Showing results for:
              <strong>{{ request('q') ? e(request('q')) : 'All Reports' }}</strong>
            </div>
          </div>

          <div class="toolbar-right ms-auto d-flex align-items-center gap-2">
            <form id="reportsSearchForm"
              action="{{ route('admin.reports.index') }}"
              method="GET"
              class="d-flex align-items-center gap-2"
              role="search"
              aria-label="Search lab reports">

              <div class="chip-search">
                <input
                  type="search"
                  name="q"
                  id="searchQuery"
                  value="{{ request('q') }}"
                  placeholder="Search name, test, Sr.No, mobile or date (e.g. 2025-10-10)">
                <button type="button" id="reportsSearchBtn">
                  <i class="fa fa-search"></i>
                  <span>Search</span>
                </button>
              </div>
            </form>

            {{-- Date filter (like appointments) --}}
            <div class="date-wrap">
              <button type="button"
                class="chip-button"
                id="reportsDateToggle">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Date</span>
              </button>

              <div class="date-popover" id="reportsDatePopover">
                <div class="date-popover-header">
                  <span>
                    <i class="fa fa-filter-circle-xmark" style="margin-right:4px; color:#ef4444;"></i>
                    Clear Date Filter
                  </span>
                  <button type="button" id="reportsDateClearBtn">
                    <i class="fa fa-eraser"></i> Clear
                  </button>
                </div>

                <div class="date-popover-body">
                  <div>
                    <label for="reportsDateFrom">From</label>
                    <input id="reportsDateFrom"
                      type="date"
                      name="date_from"
                      value="{{ request('date_from') }}">
                  </div>
                  <div>
                    <label for="reportsDateTo">To</label>
                    <input id="reportsDateTo"
                      type="date"
                      name="date_to"
                      value="{{ request('date_to') }}">
                  </div>
                </div>

                <div id="reportsDateError"
                  class="text-danger small mt-1"
                  style="display:none;">
                </div>

                <div class="date-popover-footer">
                  <button type="button" class="btn-link-sm" id="reportsDateCloseBtn">Close</button>
                  <button type="button" class="btn-apply-sm" id="reportsDateApplyBtn">Apply</button>
                </div>
              </div>
            </div>

            {{-- Export dropdown (same UX as hospital_qr) --}}
            <div class="export-wrap">
              <button type="button"
                class="chip-button"
                id="reportsExportToggle">
                <i class="fa fa-file-export"></i>
                <span>Export</span>
              </button>

              <div class="export-menu" id="reportsExportMenu">
                <a href="{{ $pdfUrl }}"
                  title="Export filtered reports to PDF"
                  data-export-pdf
                  data-mime="application/pdf">
                  <i class="fa fa-file-pdf"></i>
                  <span>PDF</span>
                </a>

                <a href="{{ $excelUrl }}"
                  title="Export filtered reports to Excel"
                  data-export-excel
                  data-mime="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                  <i class="fa fa-file-excel"></i>
                  <span>Excel</span>
                </a>
              </div>
            </div>

          </div>
        </div>

        <div class="table-wrap">
          <div class="table-scroll">
            @if($reportsCount)
            <table class="reports-table" id="reportsTable" aria-label="Reports table">
              <thead>
                <tr>
                  <th style="width:72px;">Sr.No</th>
                  <th>Patient Name</th>
                  <th>Test</th>
                  {{-- 🆕 Simple header: always latest first --}}
                  <th style="width:140px;">
                    Date
                    <span class="text-muted small">(latest first)</span>
                  </th>
                  <th style="width:150px;">Mobile</th>
                  <th style="width:170px; text-align:center;">Actions</th>
                  <th style="width:140px; text-align:center;">Share</th>
                </tr>
              </thead>
              <tbody>
                @foreach($reports as $index => $report)
                @php
                $srNo = $reports->firstItem() + $index;
                $downloadUrl = route('admin.reports.download', $report->id);

                // Find linked appointment to get mobile + WhatsApp route
                $linkedAppointment = Appointment::where('report_id', $report->id)->first();
                $mobile = $linkedAppointment->mobile_with_country
                ?? $linkedAppointment->phone
                ?? null;

                $rawDate = $report->report_date ? \Carbon\Carbon::parse($report->report_date)->format('Y-m-d') : '';

                // URL to final stored PDF used by ReportController
                $reportPdfUrl = Storage::disk('public')->url($report->report_file);

                // Backend WhatsApp route (AppointmentWhatsappController@send)
                $shareAction = $linkedAppointment
                ? route('appointments.share-whatsapp', $linkedAppointment->id)
                : null;

                // Normalized mobile for search (remove spaces, lowercase)
                $mobileForSearch = $mobile ? strtolower(preg_replace('/\s+/', '', $mobile)) : '';
                @endphp
                <tr
                  data-sr="{{ $srNo }}"
                  data-name="{{ strtolower($report->patient_name) }}"
                  data-test="{{ strtolower($report->test_name) }}"
                  data-date="{{ $rawDate }}"
                  data-mobile="{{ $mobileForSearch }}">
                  <td style="font-weight:600;">{{ $srNo }}</td>
                  <td>
                    <div style="font-weight:600;">{{ $report->patient_name }}</div>
                    {{-- patient_id intentionally not shown in UI now (kept if you want later) --}}
                  </td>
                  <td>
                    <div style="font-weight:600;">{{ $report->test_name }}</div>
                  </td>
                  <td>
                    {{ $report->report_date ? \Carbon\Carbon::parse($report->report_date)->format('d M Y') : '-' }}
                  </td>

                  <td>
                    @if($mobile)
                    <span>{{ $mobile }}</span>
                    @else
                    <span class="muted">Not linked</span>
                    @endif
                  </td>

                  <td style="text-align:center;">
                    <div class="action-group">
                      <a href="{{ route('admin.reports.view', $report->id) }}"
                        class="icon-btn"
                        title="View"
                        target="_blank"
                        rel="noopener">
                        <i class="fa fa-eye"></i>
                      </a>
                      <a href="{{ $downloadUrl }}"
                        class="icon-btn btn-download"
                        title="Download">
                        <i class="fa fa-download"></i>
                      </a>

                      <form action="{{ route('admin.reports.destroy', $report->id) }}"
                        method="POST"
                        class="d-inline delete-form"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                          class="icon-btn btn-delete"
                          title="Delete">
                          <i class="fa fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>

                  <td style="text-align:center;">
                    <div class="share-actions">
                      {{-- WhatsApp via backend + shared modal --}}
                      @if($shareAction)
                      <button
                        type="button"
                        class="btn-telegram-icon btn-share-modal"
                        data-share-url="{{ $reportPdfUrl }}"
                        data-share-action="{{ $shareAction }}"
                        data-share-message="Hi {{ $linkedAppointment->patient_name ?? 'there' }}, your report is ready."
                        data-report-id="{{ $report->id }}"
                        data-email="{{ $linkedAppointment->email ?? $linkedAppointment->patient_email ?? '' }}"
                        data-phone="{{ $linkedAppointment->whatsapp_number ?? $mobile ?? '' }}"
                        title="Share report">
                        <i class="fa fa-paper-plane"></i>
                      </button>
                      @else
                      <span class="muted small">No phone</span>
                      @endif
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @else
            <div style="padding:28px; text-align:center; color:var(--wc-muted);">
              No reports uploaded yet.
            </div>
            @endif
          </div>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; padding-top:5px;">
          <div class="muted small">
            @if($reports->total())
            Showing {{ $reports->firstItem() }} – {{ $reports->lastItem() }} of {{ $reports->total() }} results
            @else
            0 results
            @endif
          </div>
          <div>{{ $reports->withQueryString()->links() }}</div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Email Share Modal --}}
<div class="modal fade" id="emailShareModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="emailShareForm" class="modal-content admin-form">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Share Report via Email</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="emailReportId" name="report_id">
        <div class="mb-3">
          <label for="shareEmail">Recipient Email</label>
          <input id="shareEmail" type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="shareEmailMessage">Message (optional)</label>
          <textarea id="shareEmailMessage" name="message" class="form-control" rows="3"></textarea>
        </div>
        <div id="emailShareAlert" class="alert d-none" role="alert"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-sm btn-primary">Send Email</button>
      </div>
    </form>
  </div>
</div>

{{-- Reusable share modal for WhatsApp/link/email --}}
@include('partials.share-modal')

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    document.querySelectorAll('.btn-delete').forEach((btn) => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.delete-form');
        Swal.fire({
          title: 'Are you sure?',
          text: "This report will be permanently deleted!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) form.submit();
        });
      });
    });

    // Download confirmation
    document.querySelectorAll('.btn-download').forEach((btn) => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const downloadUrl = this.getAttribute('href');
        Swal.fire({
          title: 'Download Report?',
          text: "Do you want to download this report PDF?",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#198754',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, download'
        }).then((result) => {
          if (result.isConfirmed) window.location.href = downloadUrl;
        });
      });
    });

    // Email share modal prefill
    document.querySelectorAll('.btn-server-email').forEach(btn => {
      btn.addEventListener('click', function() {
        const reportId = this.dataset.reportId;
        const reportName = this.dataset.reportName || '';
        document.getElementById('emailReportId').value = reportId;
        document.getElementById('shareEmail').value = '';
        document.getElementById('shareEmailMessage').value = `Please find attached the report for ${reportName}.`;
        const alertBox = document.getElementById('emailShareAlert');
        alertBox.classList.add('d-none');
        alertBox.innerText = '';
      });
    });

    // Email form AJAX
    document.getElementById('emailShareForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const reportId = document.getElementById('emailReportId').value;
      const email = document.getElementById('shareEmail').value.trim();
      const message = document.getElementById('shareEmailMessage').value.trim();
      const alertBox = document.getElementById('emailShareAlert');

      alertBox.classList.add('d-none');
      alertBox.classList.remove('alert-danger', 'alert-success');
      alertBox.innerText = '';

      if (!email) {
        alertBox.classList.remove('d-none');
        alertBox.classList.add('alert-danger');
        alertBox.innerText = 'Recipient email is required.';
        return;
      }
      const submitBtn = this.querySelector('button[type="submit"]');
      submitBtn.disabled = true;
      submitBtn.innerHTML = 'Sending...';

      fetch("{{ url('admin/reports') }}/" + reportId + "/share-email", {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            email: email,
            message: message
          })
        })
        .then(async (res) => {
          const data = await res.json().catch(() => ({}));
          if (!res.ok) throw new Error(data.message || 'Request failed');
          return data;
        })
        .then((data) => {
          const modalEl = document.getElementById('emailShareModal');
          const modal = bootstrap.Modal.getInstance(modalEl);
          if (modal) modal.hide();
          Swal.fire({
            icon: 'success',
            title: 'Sent!',
            text: data.message || 'Report emailed successfully.',
            timer: 1800,
            showConfirmButton: false
          });
        })
        .catch((err) => {
          alertBox.classList.remove('d-none');
          alertBox.classList.add('alert-danger');
          alertBox.innerText = err.message || 'Failed to send email.';
        })
        .finally(() => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = 'Send Email';
        });
    });

    // Client-side simple filter (live) with mobile support
    const searchInput = document.getElementById('searchQuery');

    function normalizeDateInput(input) {
      input = input.trim();
      if (!input) return null;
      const isoMatch = input.match(/^(\d{4})-(\d{2})-(\d{2})$/);
      if (isoMatch) return isoMatch[0];
      const dmyMatch = input.match(/^(\d{1,2})[\/\-.](\d{1,2})[\/\-.](\d{4})$/);
      if (dmyMatch) {
        let day = dmyMatch[1].padStart(2, '0');
        let month = dmyMatch[2].padStart(2, '0');
        let year = dmyMatch[3];
        return `${year}-${month}-${day}`;
      }
      return null;
    }

    function clientFilter() {
      const raw = (searchInput.value || '');
      const q = raw.trim().toLowerCase();
      const numeric = /^[0-9]+$/.test(q);
      const dateNormalized = normalizeDateInput(q);

      document.querySelectorAll('#reportsTable tbody tr').forEach(row => {
        let show = true;

        if (!q) {
          show = true;
        } else if (numeric) {
          const sr = (row.dataset.sr || '').toLowerCase();
          const mobile = (row.dataset.mobile || '').toLowerCase();
          const qDigits = q.replace(/\D+/g, '');
          const mobileDigits = mobile.replace(/\D+/g, '');
          show = (sr === q) || (mobileDigits && mobileDigits.includes(qDigits));
        } else if (dateNormalized) {
          show = (row.dataset.date === dateNormalized);
        } else {
          const name = row.dataset.name || '';
          const test = row.dataset.test || '';
          const mobile = row.dataset.mobile || '';
          show = (name.includes(q) || test.includes(q) || mobile.includes(q));
        }

        row.style.display = show ? '' : 'none';
      });
    }

    if (searchInput) {
      searchInput.addEventListener('input', clientFilter);
      if (searchInput.value && searchInput.value.trim() !== '') clientFilter();
    }

    // === Search + Date filter URL handling ===
    const searchBtn = document.getElementById('reportsSearchBtn');
    const baseReportsUrl = "{{ url('/admin/reports') }}";

    function doSearch(query) {
      const url = new URL(window.location.href);
      url.pathname = new URL(baseReportsUrl, window.location.origin).pathname;

      if (query && query.trim()) url.searchParams.set('q', query.trim());
      else url.searchParams.delete('q');

      const fromEl = document.getElementById('reportsDateFrom');
      const toEl = document.getElementById('reportsDateTo');

      const curUrl = new URL(window.location.href);
      const existingFrom = curUrl.searchParams.get('date_from');
      const existingTo = curUrl.searchParams.get('date_to');

      if (fromEl && fromEl.value) url.searchParams.set('date_from', fromEl.value);
      else if (existingFrom) url.searchParams.set('date_from', existingFrom);
      else url.searchParams.delete('date_from');

      if (toEl && toEl.value) url.searchParams.set('date_to', toEl.value);
      else if (existingTo) url.searchParams.set('date_to', existingTo);
      else url.searchParams.delete('date_to');

      url.searchParams.delete('page');
      window.location.assign(url.toString());
    }

    if (searchBtn) {
      searchBtn.addEventListener('click', () =>
        doSearch(searchInput?.value || '')
      );
    }

    if (searchInput) {
      searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
          e.preventDefault();
          doSearch(searchInput.value || '');
        }
      });
    }

    // === Export dropdown + PDF inline (same pattern as hospital_qr) ===
    const exportToggle = document.getElementById('reportsExportToggle');
    const exportMenu = document.getElementById('reportsExportMenu');

    function closeExportMenu() {
      if (exportMenu) exportMenu.classList.remove('show');
    }

    if (exportToggle && exportMenu) {
      exportToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const willShow = !exportMenu.classList.contains('show');
        if (willShow) exportMenu.classList.add('show');
        else closeExportMenu();
      });
    }

    // Helper: parse filename from Content-Disposition
    function filenameFromDisposition(disposition) {
      if (!disposition) return null;
      const m1 = disposition.match(/filename\*=UTF-8''([^;]+)/i);
      if (m1 && m1[1]) return decodeURIComponent(m1[1]);
      const m2 = disposition.match(/filename="([^"]+)"/i);
      if (m2 && m2[1]) return m2[1];
      return null;
    }

    async function fetchAndDownload(url, mimeFallback) {
      const res = await fetch(url, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': '*/*'
        }
      });
      if (!res.ok) {
        const txt = await res.text().catch(() => null);
        throw new Error('Export failed — ' + res.status + (txt ? (': ' + txt) : ''));
      }
      const disp = res.headers.get('Content-Disposition') || '';
      let filename = filenameFromDisposition(disp);
      if (!filename) {
        const ct = (res.headers.get('Content-Type') || mimeFallback || '').toLowerCase();
        const ext = ct.includes('pdf') ? 'pdf' : (ct.includes('excel') || ct.includes('sheet') ? 'xlsx' : 'pdf');
        filename = 'reports_' + (new Date()).toISOString().slice(0, 19).replace(/[:T]/g, '-') + '.' + ext;
      }

      const blob = await res.blob();
      const blobUrl = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = blobUrl;
      a.download = filename;
      document.body.appendChild(a);
      a.click();
      a.remove();
      setTimeout(() => URL.revokeObjectURL(blobUrl), 2000);
    }

    // Global click handler for export PDF + Excel links in dropdown
    document.addEventListener('click', async function(ev) {
      const link = ev.target.closest('[data-export-pdf],[data-export-excel]');
      if (!link) return;

      ev.preventDefault();
      ev.stopPropagation();

      const href = link.getAttribute('href');
      if (!href) return;

      const original = link.innerHTML;
      link.setAttribute('aria-disabled', 'true');
      link.classList.add('btn-looks-disabled');
      link.innerHTML = `
        <i class="fa-solid fa-spinner fa-spin me-2"></i>
        <span>Preparing…</span>
      `;

      const mime = link.getAttribute('data-mime') ||
        (link.hasAttribute('data-export-pdf') ?
          'application/pdf' :
          'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

      try {
        await fetchAndDownload(href, mime);
      } catch (err) {
        console.error(err);
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'error',
            title: 'Export failed',
            text: err.message || 'Please try again.'
          });
        } else {
          alert(err.message || 'Export failed. Please try again.');
        }
      } finally {
        link.innerHTML = original;
        link.removeAttribute('aria-disabled');
        link.classList.remove('btn-looks-disabled');
        closeExportMenu();
      }
    }, {
      capture: true
    });

    // === Date popover logic for reports ===
    (function reportsDatePopover() {
      const dateToggle = document.getElementById('reportsDateToggle');
      const datePopover = document.getElementById('reportsDatePopover');
      const dateApply = document.getElementById('reportsDateApplyBtn');
      const dateClose = document.getElementById('reportsDateCloseBtn');
      const dateClear = document.getElementById('reportsDateClearBtn');
      const fromInp = document.getElementById('reportsDateFrom');
      const toInp = document.getElementById('reportsDateTo');
      const dateErrorMsg = document.getElementById('reportsDateError');

      function closePopover() {
        if (datePopover) datePopover.classList.remove('show');
      }

      function updateApplyState() {
        if (!dateApply || !fromInp || !toInp) return;

        const fromVal = (fromInp.value || '').trim();
        const toVal = (toInp.value || '').trim();

        const hasFrom = !!fromVal;
        const hasTo = !!toVal;
        let error = '';

        if (!hasFrom && !hasTo) {
          // both empty -> don't allow Apply
          error = 'Please select From and To dates, or use Clear to remove the filter.';
        } else if ((hasFrom && !hasTo) || (!hasFrom && hasTo)) {
          error = 'Please select both From and To dates.';
        } else if (hasFrom && hasTo && toVal < fromVal) {
          error = '"To" date cannot be earlier than "From" date.';
        }

        if (error) {
          dateApply.disabled = true;
          if (dateErrorMsg) {
            dateErrorMsg.textContent = error;
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
          else closePopover();
        });
      }

      // Apply date filter
      if (dateApply) {
        dateApply.addEventListener('click', function(e) {
          e.preventDefault();

          // re-validate before submit
          updateApplyState();
          if (dateApply.disabled) return;

          const base = new URL("{{ url('/admin/reports') }}", window.location.origin);
          const cur = new URL(window.location.href);

          const qFromUrl = cur.searchParams.get('q');
          const qFromInput = (document.getElementById('searchQuery')?.value || '').trim();
          const q = qFromInput || qFromUrl;
          if (q) base.searchParams.set('q', q);

          if (fromInp && fromInp.value) base.searchParams.set('date_from', fromInp.value);
          else base.searchParams.delete('date_from');

          if (toInp && toInp.value) base.searchParams.set('date_to', toInp.value);
          else base.searchParams.delete('date_to');

          base.searchParams.delete('page');

          window.location.assign(base.toString());
        });
      }


      // Close button
      if (dateClose) {
        dateClose.addEventListener('click', function(e) {
          e.preventDefault();
          closePopover();
        });
      }

      // Clear date filter (keep q)
      if (dateClear) {
        dateClear.addEventListener('click', function(e) {
          e.preventDefault();

          if (fromInp) fromInp.value = '';
          if (toInp) toInp.value = '';

          if (dateErrorMsg) {
            dateErrorMsg.textContent = '';
            dateErrorMsg.style.display = 'none';
          }
          updateApplyState();

          const base = new URL("{{ url('/admin/reports') }}", window.location.origin);
          const cur = new URL(window.location.href);

          const q = cur.searchParams.get('q') ||
            (document.getElementById('searchQuery')?.value || '').trim();
          if (q) base.searchParams.set('q', q);

          base.searchParams.delete('date_from');
          base.searchParams.delete('date_to');
          base.searchParams.delete('page');

          window.location.assign(base.toString());
        });
      }

      // Click outside -> close date + export
      document.addEventListener('click', function(e) {
        if (!e.target.closest('.date-wrap')) closePopover();
        if (!e.target.closest('.export-wrap')) closeExportMenu();
      });

      // Esc -> close
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          closePopover();
          closeExportMenu();
        }
      });
    })();
  });
</script>
@endpush

@endsection