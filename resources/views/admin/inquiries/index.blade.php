{{-- resources/views/admin/inquiries/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Contact Us')

@section('content')
{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">
{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
$statuses = [
'Pending' => ['key'=>'pending', 'color'=>'#f7e6c9', 'textColor'=>'#7a5900', 'icon'=>'fa-clock'],
'Called' => ['key'=>'called', 'color'=>'#e6f5ff', 'textColor'=>'#064d6b', 'icon'=>'fa-phone'],
'Interested' => ['key'=>'interested', 'color'=>'#e9f8f2', 'textColor'=>'#0b6b4a', 'icon'=>'fa-thumbs-up'],
'Booked' => ['key'=>'booked', 'color'=>'#ede7fb', 'textColor'=>'#5a3f9a', 'icon'=>'fa-calendar-check'],
'Lost' => ['key'=>'lost', 'color'=>'#ffe7e8', 'textColor'=>'#7a0e12', 'icon'=>'fa-ban'],
];
@endphp

<style>
  :root {
    --wc-text: #0f1724;
    --wc-muted: #6b7280;
    --wc-border: #e5e7eb;
    --wc-accent: #0f9d80;
    --wc-accent-soft: #f0fdf9;
    --wc-bg-soft: #f9fafb;
  }

  .admin-page-wrapper {
    margin: 25px 2px;
  }

  .panel {
    background: #fff;
    border-radius: 12px;
    padding: 18px;
    border: 1px solid var(--wc-border);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
  }

  .panel-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 12px;
    flex-wrap: wrap;
  }

  .panel-title {
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--wc-text);
  }

  .panel-sub {
    color: var(--wc-muted);
    font-size: .9rem;
  }

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

  /* chip-style controls like lab reports */
  .chip-group {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
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

  .chip-button-primary {
    border-color: var(--wc-accent);
    background: var(--wc-accent-soft);
    color: #047857;
  }

  .chip-button-icon-only {
    padding: 7px 10px;
  }

  .export-menu,
  .date-popover {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.18);
    border: 1px solid var(--wc-border);
    padding: 10px 12px;
    min-width: 220px;
    z-index: 11000;
    display: none;
  }

  .export-menu.show,
  .date-popover.show {
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
    transition: background .15s ease;
  }

  .export-menu a:hover {
    background: #f9fafb;
  }

  .export-wrap,
  .date-wrap {
    position: relative;
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

  .table-wrap {
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid var(--wc-border);
    box-shadow: 0 8px 24px rgba(2, 6, 23, 0.03);
    background: #fff;
  }

  .table-scroll {
    overflow: auto;
    -webkit-overflow-scrolling: touch;
  }

  table.inq-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 720px;
    font-size: .94rem;
  }

  table.inq-table thead th {
    padding: 12px 14px;
    text-align: left;
    font-weight: 600;
    color: var(--wc-text);
    border-bottom: 1px solid var(--wc-border);
    white-space: nowrap;
    background: #f3f4f6;
  }

  table.inq-table tbody td {
    padding: 10px 14px;
    border-bottom: 1px solid var(--wc-border);
    vertical-align: middle;
    color: var(--wc-text);
  }

  table.inq-table tbody tr:last-child td {
    border-bottom: none;
  }

  table.inq-table tbody tr:hover {
    background: #f9fafb;
  }

  .muted {
    color: var(--wc-muted);
    font-size: .85rem;
    display: block;
  }

  .status-pill {
    display: inline-block;
    padding: 7px 12px;
    border-radius: 999px;
    font-weight: 600;
    font-size: .86rem;
    border: 1px solid rgba(0, 0, 0, 0.03);
    background: rgba(255, 255, 255, 0.92);
  }

  /* Generic icon button (eye) */
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

  .status-action {
    position: relative;
    display: inline-block;
  }

  .status-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 999px;
    border: 1px solid #d1d5db;
    background: #ffffff;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.16);
    transition: background .15s ease, color .15s ease, border-color .15s ease,
      box-shadow .15s ease, transform .1s ease;
  }

  .status-action-btn .fa {
    font-size: 13px;
    color: var(--wc-accent);
  }

  .status-action-btn:hover {
    background: #f9fafb;
    border-color: #9ca3af;
    box-shadow: 0 2px 5px rgba(15, 23, 42, 0.22);
    transform: translateY(-1px);
  }

  .status-action-btn:active {
    transform: translateY(0);
    box-shadow: none;
  }

  .status-menu {
    position: absolute;
    right: 0;
    top: calc(100% + 8px);
    min-width: 180px;
    background: #ffffff;
    border: 1px solid var(--wc-border);
    box-shadow: 0 18px 40px rgba(2, 6, 23, 0.12);
    border-radius: 10px;
    padding: 8px;
    z-index: 11000;
    display: none;
    isolation: isolate;
    will-change: max-height, transform, opacity;
    transform-origin: top right;
    -webkit-overflow-scrolling: touch;
    max-height: 320px;
    overflow: auto;
    padding-right: 4px;
  }

  .status-menu.show {
    display: block;
    opacity: 1;
  }

  .status-menu.up {
    top: auto !important;
    bottom: calc(100% + 8px) !important;
    transform-origin: bottom right;
  }

  .status-item {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 8px;
    border-radius: 8px;
    background: transparent;
    border: none;
    text-align: left;
    cursor: pointer;
    color: var(--wc-text);
    font-weight: 600;
    font-size: .86rem;
    transition: background .15s ease;
  }

  .status-item:hover {
    background: rgba(15, 157, 128, 0.04);
  }

  .status-item .fa {
    width: 20px;
    text-align: center;
  }

  @media (max-width:900px) {
    table.inq-table {
      min-width: 640px;
    }
  }

  @media (max-width:640px) {
    .controls {
      justify-content: flex-start;
    }

    .chip-search {
      width: 100%;
      max-width: none;
    }
  }
</style>

<div class="admin-page-wrapper">
  <div class="panel">
    <div class="panel-head">
      <div>
        <div class="panel-title">Contact Us</div>
        <div class="panel-sub">Recent contact inquiries — manage and update status</div>
      </div>

      {{-- FILTERS + DATE DROPDOWN + EXPORT --}}
      <div class="controls" role="region" aria-label="Controls">
        <form id="inqFilterForm"
          method="get"
          action="{{ route('admin.inquiries.index') }}"
          class="filter-form">

          <div class="chip-group">

            {{-- Search + Search button --}}
            <div class="chip-search">
              <input type="search"
                name="q"
                id="inqSearchQuery"
                value="{{ old('q', $q ?? '') }}"
                placeholder="Search name, email, phone, status or date (e.g. 2025-12-10)">
              <button type="button" id="inqSearchBtn">
                <i class="fa fa-search"></i>
                <span>Search</span>
              </button>
            </div>

            {{-- Date dropdown --}}
            <div class="date-wrap">
              <button type="button"
                class="chip-button"
                id="inqDateToggle">
                <i class="fa fa-calendar"></i>
                <span>Date</span>
              </button>

              <div class="date-popover" id="inqDatePopover">
                <div class="date-popover-header">
                  <span><i class="fa fa-filter-circle-xmark" style="margin-right:4px; color:#ef4444;"></i>Clear Date Filter</span>
                  <button type="button" id="inqDateClearBtn">
                    <i class="fa fa-eraser"></i> Clear
                  </button>
                </div>

                <div class="date-popover-body">
                  <div>
                    <label for="inqDateFrom">From</label>
                    <input id="inqDateFrom"
                      type="date"
                      name="date_from"
                      value="{{ $dateFrom ?? '' }}">
                  </div>
                  <div>
                    <label for="inqDateTo">To</label>
                    <input id="inqDateTo"
                      type="date"
                      name="date_to"
                      value="{{ $dateTo ?? '' }}">
                  </div>
                </div>

                {{-- date validation error --}}
                <div id="inqDateError"
                  class="text-danger small mt-1"
                  style="display:none;">
                </div>

                <div class="date-popover-footer">
                  <button type="button" class="btn-link-sm" id="inqDateCloseBtn">Close</button>
                  <button type="button" class="btn-apply-sm" id="inqDateApplyBtn">Apply</button>
                </div>
              </div>
            </div>

            {{-- Export dropdown --}}
            <div class="export-wrap">
              <button type="button"
                class="chip-button"
                id="inqExportToggle">
                <i class="fa fa-file-export"></i>
                <span>Export</span>
              </button>

              <div class="export-menu" id="inqExportMenu">
                <a href="{{ route('admin.inquiries.export.pdf', [
                          'q'         => $q ?? null,
                          'date_from' => $dateFrom ?: null,
                          'date_to'   => $dateTo ?: null,
                      ]) }}"
                  title="Export filtered inquiries to PDF">
                  <i class="fa fa-file-pdf"></i>
                  <span>PDF</span>
                </a>

                <a href="{{ route('admin.inquiries.export.excel', [
                          'q'         => $q ?? null,
                          'date_from' => $dateFrom ?: null,
                          'date_to'   => $dateTo ?: null,
                      ]) }}"
                  title="Export filtered inquiries to Excel">
                  <i class="fa fa-file-excel"></i>
                  <span>Excel</span>
                </a>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>

    {{-- TABLE --}}
    <div class="table-wrap">
      <div class="table-scroll">
        <table class="inq-table" aria-label="Inquiries table">
          <thead>
            <tr>
              <th style="width:64px;">Sr.No</th>
              <th style="min-width:180px;">Name</th>
              <th style="min-width:220px;">Contact</th>
              <th style="width:160px;">Submitted</th>
              <th style="width:140px; text-align:center;">Status</th>
              <th style="width:140px; text-align:center;">Action</th>
            </tr>
          </thead>

          <tbody>
            @php
            $sr = ($inquiries->currentPage() - 1) * $inquiries->perPage() + 1;
            @endphp

            @forelse($inquiries as $inquiry)
            @php
            $curLabel = ucfirst($inquiry->status ?? 'Pending');
            $curKey = strtolower($curLabel);
            $meta = null;

            foreach ($statuses as $label => $m) {
            if ($m['key'] === $curKey || strtolower($label) === strtolower($curLabel)) {
            $meta = $m;
            break;
            }
            }

            $pillBg = $meta['color'] ?? '#f7e6c9';
            $pillText = $meta['textColor'] ?? '#7a5900';
            $pillIcon = $meta['icon'] ?? 'fa-clock';

            $srNo = $sr++;
            $rawDate = $inquiry->created_at ? $inquiry->created_at->format('Y-m-d') : '';
            $searchName = strtolower($inquiry->name ?? '');
            $searchEmail = strtolower($inquiry->email ?? '');
            $searchPhone = $inquiry->phone ? preg_replace('/\D+/', '', $inquiry->phone) : '';
            $searchPhone = strtolower($searchPhone);
            $searchStatus = strtolower($curLabel);
            @endphp

            <tr
              data-inquiry-id="{{ $inquiry->id }}"
              data-sr="{{ $srNo }}"
              data-name="{{ $searchName }}"
              data-email="{{ $searchEmail }}"
              data-phone="{{ $searchPhone }}"
              data-status="{{ $searchStatus }}"
              data-date="{{ $rawDate }}">
              <td style="font-weight:600;">{{ $srNo }}</td>

              <td>
                <div style="font-weight:600; display:flex; align-items:center; gap:6px;">
                  {{ $inquiry->name ?? '—' }}
                  @if($inquiry->prescription)
                    <i class="fa-solid fa-paperclip" title="Prescription Attached" style="color:#0d6efd;" aria-hidden="true"></i>
                  @endif
                </div>
                <div class="muted">
                  Submitted {{ $inquiry->created_at ? $inquiry->created_at->diffForHumans() : '-' }}
                </div>
              </td>

              <td>
                <div class="muted">
                  @if($inquiry->email) {{ $inquiry->email }} @else — @endif
                </div>
                <div class="muted" style="margin-top:6px;">
                  @if($inquiry->phone) {{ $inquiry->phone }} @else — @endif
                </div>
              </td>

              <td>
                <div style="font-weight:600;">
                  {{ $inquiry->created_at ? $inquiry->created_at->format('d M Y') : '-' }}
                </div>
                <div class="muted">
                  {{ $inquiry->created_at ? $inquiry->created_at->format('h:i A') : '-' }}
                </div>
              </td>

              <td style="text-align:center;">
                <span class="status-pill"
                  style="background:{{ $pillBg }}; color:{{ $pillText }}; border-color: rgba(0,0,0,0.03);">
                  <i class="fa {{ $pillIcon }}" style="margin-right:6px;"></i> {{ $curLabel }}
                </span>
              </td>

              <td style="text-align:center;">
                <div style="display:flex; gap:8px; justify-content:center; align-items:center;">
                  {{-- status icon button + menu --}}
                  <div class="status-action" data-inquiry-id="{{ $inquiry->id }}">
                    <button class="status-action-btn"
                      aria-haspopup="true"
                      aria-expanded="false"
                      title="Change status"
                      data-current-status="{{ $curKey }}">
                      <i class="fa {{ $pillIcon }}"></i>
                    </button>

                    <div class="status-menu" role="menu" aria-hidden="true" aria-label="Status options">
                      @foreach(['Pending','Called','Interested','Booked','Lost'] as $s)
                      @php
                      $k = $statuses[$s]['key'];
                      $ic = $statuses[$s]['icon'];
                      $bg = $statuses[$s]['color'];
                      $tc = $statuses[$s]['textColor'];
                      @endphp
                      <button class="status-item"
                        data-status="{{ $s }}"
                        data-bg="{{ $bg }}"
                        data-text-color="{{ $tc }}"
                        type="button">
                        <i class="fa {{ $ic }} status-icon"
                          style="color:{{ $tc }}"></i>
                        {{ $s }}
                      </button>
                      @endforeach
                    </div>
                  </div>

                  {{-- show (eye) --}}
                  <a href="{{ route('admin.inquiries.show', $inquiry->id) }}"
                    class="icon-btn"
                    title="Show"
                    aria-label="Show inquiry {{ $inquiry->id }}">
                    <i class="fa fa-eye"></i>
                  </a>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="muted text-center p-4">No inquiries found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center; padding-top:12px; flex-wrap:wrap; gap:8px;">
      <div class="muted small">
        @if($inquiries->total())
        Showing {{ $inquiries->firstItem() }} - {{ $inquiries->lastItem() }} of {{ $inquiries->total() }}
        @else
        0 results
        @endif
      </div>
      <div>{{ $inquiries->links() }}</div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // DATE + EXPORT dropdowns
  (function() {
    const dateToggle = document.getElementById('inqDateToggle');
    const datePopover = document.getElementById('inqDatePopover');
    const dateApply = document.getElementById('inqDateApplyBtn');
    const dateClose = document.getElementById('inqDateCloseBtn');
    const dateClear = document.getElementById('inqDateClearBtn');
    const dateFromInp = document.getElementById('inqDateFrom');
    const dateToInp = document.getElementById('inqDateTo');
    const dateErrorMsg = document.getElementById('inqDateError');
    const filterForm = document.getElementById('inqFilterForm');

    const exportToggle = document.getElementById('inqExportToggle');
    const exportMenu = document.getElementById('inqExportMenu');

    function closeDatePopover() {
      if (datePopover) datePopover.classList.remove('show');
    }

    function closeExportMenu() {
      if (exportMenu) exportMenu.classList.remove('show');
    }

    function updateInqApplyState() {
      if (!dateApply || !dateFromInp || !dateToInp) return;

      const fromVal = (dateFromInp.value || '').trim();
      const toVal = (dateToInp.value || '').trim();

      const hasFrom = !!fromVal;
      const hasTo = !!toVal;
      let error = '';

      if (!hasFrom && !hasTo) {
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

    if (dateFromInp) {
      dateFromInp.addEventListener('input', updateInqApplyState);
      dateFromInp.addEventListener('change', updateInqApplyState);
    }
    if (dateToInp) {
      dateToInp.addEventListener('input', updateInqApplyState);
      dateToInp.addEventListener('change', updateInqApplyState);
    }
    updateInqApplyState();

    if (dateToggle && datePopover) {
      dateToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const show = !datePopover.classList.contains('show');
        closeExportMenu();
        if (show) datePopover.classList.add('show');
        else closeDatePopover();
      });
    }

    if (dateApply && filterForm) {
      dateApply.addEventListener('click', function(e) {
        e.preventDefault();
        updateInqApplyState();
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

    if (dateClear && filterForm && dateFromInp && dateToInp) {
      dateClear.addEventListener('click', function(e) {
        e.preventDefault();
        dateFromInp.value = '';
        dateToInp.value = '';
        if (dateErrorMsg) {
          dateErrorMsg.textContent = '';
          dateErrorMsg.style.display = 'none';
        }
        updateInqApplyState();
        filterForm.submit();
      });
    }

    if (exportToggle && exportMenu) {
      exportToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const show = !exportMenu.classList.contains('show');
        closeDatePopover();
        if (show) exportMenu.classList.add('show');
        else closeExportMenu();
      });
    }

    document.addEventListener('click', function(e) {
      if (!e.target.closest('.date-wrap')) closeDatePopover();
      if (!e.target.closest('.export-wrap')) closeExportMenu();
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeDatePopover();
        closeExportMenu();
      }
    });
  })();

  // 🟢 LIVE SEARCH + URL SEARCH (similar to reports)
  (function inquiriesSearchAndFilter() {
    const searchInput = document.getElementById('inqSearchQuery');
    const searchBtn = document.getElementById('inqSearchBtn');
    const baseInqUrl = "{{ url('/admin/inquiries') }}";

    if (!searchInput) return;

    const rows = document.querySelectorAll('.inq-table tbody tr[data-inquiry-id]');

    function normalizeDateInput(input) {
      input = (input || '').trim();
      if (!input) return null;

      // yyyy-mm-dd
      const iso = input.match(/^(\d{4})-(\d{2})-(\d{2})$/);
      if (iso) return iso[0];

      // dd-mm-yyyy / dd/mm/yyyy / dd.mm.yyyy
      const dmy = input.match(/^(\d{1,2})[\/\-.](\d{1,2})[\/\-.](\d{4})$/);
      if (dmy) {
        let day = dmy[1].padStart(2, '0');
        let month = dmy[2].padStart(2, '0');
        let year = dmy[3];
        return `${year}-${month}-${day}`;
      }

      return null;
    }

    function clientFilter() {
      const raw = (searchInput.value || '');
      const q = raw.trim().toLowerCase();
      const isNumeric = /^[0-9]+$/.test(q);
      const dateNormalized = normalizeDateInput(q);

      rows.forEach(row => {
        const sr = (row.dataset.sr || '').toLowerCase();
        const name = (row.dataset.name || '').toLowerCase();
        const email = (row.dataset.email || '').toLowerCase();
        const phone = (row.dataset.phone || '').toLowerCase();
        const status = (row.dataset.status || '').toLowerCase();
        const date = (row.dataset.date || '').toLowerCase();

        let show = true;

        if (!q) {
          show = true;
        } else if (isNumeric) {
          const qDigits = q.replace(/\D+/g, '');
          const phoneDigits = phone.replace(/\D+/g, '');
          show = (sr === q) || (phoneDigits && phoneDigits.includes(qDigits));
        } else if (dateNormalized) {
          show = (date === dateNormalized);
        } else {
          show =
            name.includes(q) ||
            email.includes(q) ||
            phone.includes(q) ||
            status.includes(q);
        }

        row.style.display = show ? '' : 'none';
      });
    }

    // Live filter while typing
    searchInput.addEventListener('input', clientFilter);
    if (searchInput.value && searchInput.value.trim() !== '') {
      clientFilter();
    }

    // URL-based search (keeping date range) like reports
    function doSearch(query) {
      const url = new URL(window.location.href);
      url.pathname = new URL(baseInqUrl, window.location.origin).pathname;

      if (query && query.trim()) url.searchParams.set('q', query.trim());
      else url.searchParams.delete('q');

      const fromEl = document.getElementById('inqDateFrom');
      const toEl = document.getElementById('inqDateTo');

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
      searchBtn.addEventListener('click', function() {
        doSearch(searchInput.value || '');
      });
    }

    searchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        doSearch(searchInput.value || '');
      }
    });
  })();

  // STATUS DROPDOWN (ported from appointments index)
  (function statusMenuFlow() {
    function closeAllStatusMenus() {
      document.querySelectorAll('.status-menu.show').forEach(m => {
        m.classList.remove('show', 'up');
        m.setAttribute('aria-hidden', 'true');
        const parent = m.closest('.status-action');
        if (parent) {
          const btn = parent.querySelector('.status-action-btn');
          btn && btn.setAttribute('aria-expanded', 'false');
        }
        m.style.maxHeight = '';
        m.style.position = '';
        m.style.zIndex = '';
        m.style.background = '';
      });
    }

    function positionMenu(menu, btn) {
      if (!menu || !btn) return;

      menu.classList.remove('up');
      menu.style.top = '';
      menu.style.bottom = '';

      const prevDisplay = menu.style.display;
      const prevVis = menu.style.visibility;
      menu.style.display = 'block';
      menu.style.visibility = 'hidden';

      const menuRect = menu.getBoundingClientRect();
      const btnRect = btn.getBoundingClientRect();

      const spaceBelow = window.innerHeight - btnRect.bottom - 8;
      const spaceAbove = btnRect.top - 8;

      if (menuRect.height > spaceBelow && spaceAbove > spaceBelow) {
        menu.classList.add('up');
      } else {
        menu.classList.remove('up');
      }

      menu.style.display = prevDisplay || '';
      menu.style.visibility = prevVis || '';
    }

    document.addEventListener('click', function(ev) {
      const btn = ev.target.closest('.status-action-btn');
      const item = ev.target.closest('.status-item');

      // Toggle dropdown open/close
      if (btn) {
        ev.preventDefault();
        ev.stopPropagation();

        const wrap = btn.closest('.status-action');
        if (!wrap) return;
        const menu = wrap.querySelector('.status-menu');
        if (!menu) return;

        const isOpen = menu.classList.contains('show');
        closeAllStatusMenus();

        if (!isOpen) {
          menu.classList.add('show');
          menu.setAttribute('aria-hidden', 'false');
          btn.setAttribute('aria-expanded', 'true');

          menu.style.position = 'absolute';
          menu.style.zIndex = '11000';
          menu.style.background = '#fff';

          positionMenu(menu, btn);
        }
        return;
      }

      // Click on a status option
      if (item) {
        ev.preventDefault();
        ev.stopPropagation();

        const actionWrap = item.closest('.status-action');
        if (!actionWrap) return;

        const inquiryId = actionWrap.dataset.inquiryId;
        const newStatus = item.getAttribute('data-status');
        if (!inquiryId || !newStatus) return;

        const swalAvailable = (typeof Swal !== 'undefined');

        const doConfirmThenUpdate = async () => {
          try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const res = await fetch(`/admin/inquiries/${inquiryId}/status`, {
              method: 'PATCH',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
              },
              body: JSON.stringify({
                status: newStatus
              })
            });

            if (!res.ok) {
              const t = await res.text().catch(() => null);
              throw new Error(t || `HTTP ${res.status}`);
            }

            // Update pill + icon in row
            const row = document.querySelector(`tr[data-inquiry-id="${inquiryId}"]`);
            if (row) {
              const pill = row.querySelector('.status-pill');
              if (pill) {
                const iconNode = item.querySelector('i.fa');
                let iconClass = 'fa-circle';
                if (iconNode) {
                  const cls = Array.from(iconNode.classList)
                    .filter(c => c !== 'fa' && c !== 'status-icon');
                  if (cls.length) iconClass = cls[0];
                }

                pill.innerHTML = `<i class="fa ${iconClass}" style="margin-right:6px;"></i> ${newStatus}`;
                const bg = item.dataset.bg || '';
                const text = item.dataset.textColor || '';
                if (bg) pill.style.background = bg;
                if (text) pill.style.color = text;
              }

              const iconBtn = row.querySelector('.status-action-btn .fa');
              if (iconBtn) {
                const iconNode = item.querySelector('i.fa');
                let iconClass = 'fa-circle';
                if (iconNode) {
                  const cls = Array.from(iconNode.classList)
                    .filter(c => c !== 'fa' && c !== 'status-icon');
                  if (cls.length) iconClass = cls[0];
                }
                iconBtn.className = 'fa ' + iconClass;
              }
            }

            if (swalAvailable) {
              Swal.fire({
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false,
                icon: 'success',
                title: `Status updated to ${newStatus}`
              });
            } else {
              alert(`Status updated to ${newStatus}`);
            }
          } catch (err) {
            console.error(err);
            if (swalAvailable) {
              Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: err.message || 'Failed to update status. Please try again.'
              });
            } else {
              alert('Failed to update status. Please try again.');
            }
          } finally {
            closeAllStatusMenus();
          }
        };

        closeAllStatusMenus();

        if (swalAvailable) {
          Swal
            .fire({
              title: `Change status to "${newStatus}"?`,
              icon: 'question',
              showCancelButton: true,
              confirmButtonText: 'Yes, change it',
              cancelButtonText: 'Cancel'
            })
            .then(r => {
              if (r.isConfirmed) {
                Swal.fire({
                  title: 'Updating...',
                  allowOutsideClick: false,
                  didOpen: () => Swal.showLoading()
                });
                doConfirmThenUpdate().then(() => Swal.close());
              }
            });
        } else {
          if (confirm(`Change status to "${newStatus}"?`)) {
            doConfirmThenUpdate();
          }
        }

        return;
      }

      // click outside → close
      if (!ev.target.closest('.status-action')) {
        closeAllStatusMenus();
      }
    });

    document.addEventListener('keydown', function(ev) {
      if (ev.key === 'Escape') {
        closeAllStatusMenus();
      }
    });
  })();
</script>
@endpush

@endsection