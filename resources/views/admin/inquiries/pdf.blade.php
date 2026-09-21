<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Contact Inquiries Report</title>

  <style>
    @page { size: A4; margin: 2mm 2mm 18mm 2mm; }

    @font-face {
      font-family: 'DejaVuSans';
      src: url("{{ public_path('fonts/DejaVuSans.ttf') }}") format('truetype');
      font-weight: normal; font-style: normal;
    }

    html, body {
      font-family: DejaVuSans, Arial, Helvetica, sans-serif;
      color:#0f172a;
    }
    body { font-size:12.2px; line-height:1.45; -webkit-font-smoothing:antialiased; }

    .muted{ color:#64748b; }
    .tiny { font-size:10.8px; }
    .fw-600{ font-weight:600; }
    .text-right{ text-align:right; }
    .text-center{ text-align:center; }
    .nowrap{ white-space:nowrap; }
    td.sr-no{ white-space:nowrap; }

    .brandband{
      background:#fff; color:#0f172a; border-radius:10px;
      padding:8px 12px; border:1px solid #e2e8f0;
      box-shadow:0 1px 2px rgba(0,0,0,.05);
      margin-bottom:8px;
    }
    .brand-top{ display:table; width:100%; border-spacing:0; }
    .brand-ico{
      display:table-cell; width:180px; vertical-align:middle;
      border-radius:16px; background:#fff; border:1px solid #e5e7eb;
      text-align:center; height:65px;
    }
    .brand-ico img{
      width:100%; height:auto; object-fit:contain; display:block;
      background:#fff; border-radius:12px; padding:4px;
    }
    .brand-ttl{
      display:table-cell; vertical-align:middle;
      padding-left:12px; font-size:20px; font-weight:800; letter-spacing:.2px;
      text-align:right; white-space:nowrap;
    }
    .brand-sub{ margin-top:6px; font-size:11.5px; color:#64748b; }

    .card{
      background:#fff; border:1px solid #e2e8f0; border-radius:10px;
      box-shadow:0 1px 4px rgba(0,0,0,.04);
      padding:10px 12px;
    }

    table{ width:100%; border-collapse:collapse; }
    thead{ display:table-header-group; }
    tfoot{ display:table-footer-group; }

    col.sr { width:40px; }
    col.nm { width:18%; }
    col.em { width:20%; }
    col.ph { width:13%; }
    col.dt { width:15%; }
    col.msg{ width:26%; }
    col.st { width:8%;  }

    thead th{
      text-align:left; padding:7px 8px; font-weight:800;
      color:#0b5ed7; background:#f1f5ff; border-bottom:1px solid #dbe3ff;
      font-size:11.2px; letter-spacing:.2px;
    }
    thead th.status { text-align:center; }

    tbody td{
      padding:7px 8px; border-bottom:1px solid #eef2f7; vertical-align:middle;
      overflow-wrap:anywhere; word-break:break-word;
    }
    tbody tr:nth-child(even) td{ background:#fafbff; }

    td.name{ color:#0f172a; font-weight:600; }
    td.status{ text-align:center; }

    .badge{
      display:inline-block; padding:2px 8px; border-radius:999px;
      font-size:10px; font-weight:700; letter-spacing:.2px;
      border:1px solid currentColor; background:transparent;
      white-space:nowrap; min-width:62px; line-height:1; text-align:center;
    }
    .b-pending    { color:#b45309; }
    .b-called     { color:#0b5ed7; }
    .b-interested { color:#166534; }
    .b-booked     { color:#5b21b6; }
    .b-lost       { color:#991b1b; }
    .b-other      { color:#334155; }

    .footerbar{
      display:table; width:100%; border-top:1px solid #e2e8f0;
      margin-top:10px; padding-top:6px; font-size:11px; color:#64748b;
    }
    .footer-row{ display:table-row; }
    .footer-left, .footer-right{ display:table-cell; vertical-align:middle; }
    .footer-right{ text-align:right; }

    .page-no:before{ content: counter(page); }
    .page-total:before{ content: counter(page); }

    .page-num {
      position: fixed;
      bottom: 4mm;
      left: 4mm;
      font-size: 11px;
      color: #64748b;
    }
    .page-num:before { content: "Page " counter(page) " of " counter(page); }
  </style>
</head>
<body>
@php
  use Carbon\Carbon;

  $rows = $inquiries instanceof \Illuminate\Support\Collection ? $inquiries->all() : (array)$inquiries;

  $statusKey = function($row){
      $k = strtolower((string)($row->status ?? 'other'));
      $allowed = ['pending','called','interested','booked','lost'];
      return in_array($k, $allowed) ? $k : 'other';
  };

  // Style B: proper case label
  $statusLabel = function($row) use ($statusKey){
      $raw = (string)($row->status ?? '');
      if ($raw === '') {
          $k = $statusKey($row);
          return ucfirst($k);
      }
      return ucwords(strtolower($raw));
  };

  $badgeClass  = function($row) use ($statusKey){
      return 'b-'.$statusKey($row);
  };

  $fmtDateTime = function($value){
      if (empty($value)) return '';
      try {
          return Carbon::parse($value)
              ->timezone('Asia/Kolkata')
              ->format('d/m/Y h:i A');
      } catch (\Throwable $e) {
          return (string)$value;
      }
  };

  $fmtGenerated = function () {
      return Carbon::now('Asia/Kolkata')->format('d/m/Y h:i A');
  };

  $totalCount = count($rows);

  $logoDataUri = null;
  $logoPath = public_path('assets/images/wellcare_logo.png');
  if (is_readable($logoPath)) {
      try {
          $logoDataUri = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));
      } catch (\Throwable $e) {}
  }
@endphp

  {{-- Header --}}
  <div class="brandband">
    <div class="brand-top">
      <div class="brand-ico">
        @if($logoDataUri)
          <img src="{{ $logoDataUri }}" alt="Wellcare Labs">
        @else
          <span class="tiny fw-600">WL</span>
        @endif
      </div>
      <div class="brand-ttl">
        Contact Inquiries Report
        <div class="brand-sub">
          @if($dateFrom && $dateTo)
            Period: {{ $dateFrom }} – {{ $dateTo }}
          @elseif($dateFrom)
            From: {{ $dateFrom }}
          @elseif($dateTo)
            Upto: {{ $dateTo }}
          @else
            All records
          @endif

          @if(!empty($q))
            &nbsp; | &nbsp; Search: “{{ $q }}”
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Table --}}
  <div class="card">
    <table>
      <colgroup>
        <col class="sr">
        <col class="nm">
        <col class="em">
        <col class="ph">
        <col class="dt">
        <col class="msg">
        <col class="st">
      </colgroup>

      <thead>
        <tr>
          <th class="nowrap">Sr.No</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Date &amp; Time</th>
          <th>Message</th>
          <th class="status">Status</th>
        </tr>
      </thead>

      <tbody>
        @forelse($rows as $row)
          <tr>
            <td class="muted sr-no">{{ $row->sr_no ?? $loop->iteration }}</td>
            <td class="name">{{ $row->name ?? '' }}</td>
            <td class="muted">{{ $row->email ?? '' }}</td>
            <td class="muted nowrap">{{ $row->phone ?? '' }}</td>
            <td class="muted nowrap">
              {{ $fmtDateTime($row->created_at ?? null) }}
            </td>
            <td>{{ $row->message ?? '' }}</td>
            <td class="status">
              <span class="badge {{ $badgeClass($row) }}">
                {{ $statusLabel($row) }}
              </span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center muted" style="padding:18px 8px;">
              No inquiries for selected filters.
            </td>
          </tr>
        @endforelse
      </tbody>

      <tfoot>
        <tr>
          <td colspan="7" class="text-right nowrap" style="padding:10px 8px;">
            <span class="fw-600">Total inquiries</span>:
            <span class="fw-600">{{ $totalCount }}</span>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>

  {{-- Footer --}}
  <div class="footerbar">
    <div class="footer-row">
      <div class="footer-left">
        Page <span class="page-no"></span> of <span class="page-total"></span>
      </div>
      <div class="footer-right">
        Exported by Wellcare Labs • {{ $fmtGenerated() }}
      </div>
    </div>
  </div>

  <div class="page-num"></div>
</body>
</html>
