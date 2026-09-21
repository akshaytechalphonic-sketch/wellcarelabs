<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Lab Reports</title>

  <style>
    /* ---------- Page & fonts ---------- */
    @page { size: A4; margin: 2mm 2mm 18mm 2mm; }

    @font-face {
      font-family: 'DejaVuSans';
      src: url("{{ public_path('fonts/DejaVuSans.ttf') }}") format('truetype');
      font-weight: normal;
      font-style: normal;
    }

    html, body {
      font-family: DejaVuSans, Arial, Helvetica, sans-serif;
      color:#0f172a;
      font-size:12.2px;
      line-height:1.45;
      -webkit-font-smoothing:antialiased;
    }

    .muted { color:#64748b; }
    .fw-600 { font-weight:600; }
    .text-center { text-align:center; }
    .text-right { text-align:right; }
    .nowrap { white-space:nowrap; }

    /* ---------- Header band ---------- */
    .brandband{
      background:#fff;
      color:#0f172a;
      border-radius:10px;
      padding:8px 12px;
      border:1px solid #e2e8f0;
      box-shadow:0 1px 2px rgba(0,0,0,.05);
      margin-bottom:8px;
    }

    .brand-top{ display:table; width:100%; border-spacing:0; }
    .brand-ico{
      display:table-cell;
      width:180px;
      vertical-align:middle;
      border-radius:16px;
      background:#fff;
      border:1px solid #e5e7eb;
      text-align:center;
      height:65px;
    }
    .brand-ico img{
      width:100%;
      height:auto;
      object-fit:contain;
      display:block;
      border-radius:12px;
      padding:4px;
    }
    .brand-ttl{
      display:table-cell;
      vertical-align:middle;
      padding-left:12px;
      font-size:20px;
      font-weight:800;
      letter-spacing:.2px;
      text-align:right;
      white-space:nowrap;
    }

    /* ---------- Card ---------- */
    .card{
      background:#fff;
      border:1px solid #e2e8f0;
      border-radius:10px;
      box-shadow:0 1px 4px rgba(0,0,0,.04);
      padding:10px 12px;
    }

    /* ---------- Table ---------- */
    table{ width:100%; border-collapse:collapse; }
    thead{ display:table-header-group; }
    tfoot{ display:table-footer-group; }

    /* Column widths */
    col.sr   { width:6%; }
    col.pn   { width:34%; }
    col.test { width:32%; }
    col.date { width:14%; }
    col.mob  { width:14%; }

    /* Header cells - CENTERED TITLES */
    thead th{
      padding:7px 8px;
      font-weight:800;
      background:#f1f5ff;
      border-bottom:1px solid #dbe3ff;
      font-size:11.4px;
      color:#0b5ed7;
      text-align:center;      /* ✅ titles centered */
      vertical-align:middle;
      letter-spacing:.2px;
    }

    /* Body cells */
    tbody td{
      padding:6px 8px;
      border-bottom:1px solid #eef2f7;
      vertical-align:middle;
    }
    tbody tr:nth-child(even) td{ background:#fafbff; }

    /* Column alignment - DATA CENTERED UNDER TITLES */
    td.col-sr     { text-align:center; white-space:nowrap; color:#64748b; }
    td.col-name   { text-align:center; font-weight:600; }          /* name centered */
    td.col-test   { text-align:center; }                           /* test centered */
    td.col-date   { text-align:center; white-space:nowrap; color:#64748b; }
    td.col-mobile { text-align:center; white-space:nowrap; color:#64748b; }

    /* ---------- Footer bar ---------- */
    .footerbar{
      display:table;
      width:100%;
      border-top:1px solid #e2e8f0;
      margin-top:10px;
      padding-top:6px;
      font-size:11px;
      color:#64748b;
    }
    .footer-row{ display:table-row; }
    .footer-left, .footer-right{ display:table-cell; vertical-align:middle; }
    .footer-right{ text-align:right; }
    .page-no:before{ content: counter(page); }
    .page-total:before{ content: counter(page); }

    /* ---------- Fixed corner page counter ---------- */
    .page-num {
      position: fixed;
      bottom: 4mm;
      left: 4mm;
      font-size: 11px;
      color: #64748b;
    }
    .page-num:before {
      content: "Page " counter(page) " of " counter(page);
    }
  </style>
</head>
<body>
@php
  use Carbon\Carbon;

  // Normalize reports collection
  $rows = $reports instanceof \Illuminate\Support\Collection ? $reports->all() : (array)$reports;

  $fmtDate = function($value){
      if (empty($value)) return '';
      try {
          return Carbon::parse($value)->format('d/m/Y');
      } catch (\Throwable $e) {
          return (string)$value;
      }
  };

  $fmtGenerated = function() {
      return Carbon::now('Asia/Kolkata')->format('d/m/Y h:i A');
  };

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
        <img src="{{ $logoDataUri }}" alt="Wellcare Logo">
      @else
        <span class="muted fw-600">Wellcare Labs</span>
      @endif
    </div>
    <div class="brand-ttl">
      Lab Reports Export
    </div>
  </div>
</div>

{{-- Table --}}
<div class="card">
  <table>
    <colgroup>
      <col class="sr">
      <col class="pn">
      <col class="test">
      <col class="date">
      <col class="mob">
    </colgroup>

    <thead>
      <tr>
        <th>Sr.No</th>
        <th>Patient Name</th>
        <th>Test Name</th>
        <th>Report Date</th>
        <th>Mobile</th>
      </tr>
    </thead>

    <tbody>
      @forelse($rows as $report)
        @php
          $appt = \App\Models\Appointment::where('report_id', $report->id)->first();
          $mobile =
              $appt->mobile_with_country
              ?? $appt->mobile
              ?? $appt->phone
              ?? $appt->whatsapp_number
              ?? '';
        @endphp
        <tr>
          <td class="col-sr">{{ $loop->iteration }}</td>
          <td class="col-name">{{ $report->patient_name }}</td>
          <td class="col-test">{{ $report->test_name ?? '' }}</td>
          <td class="col-date">{{ $fmtDate($report->report_date ?? null) }}</td>
          <td class="col-mobile">{{ $mobile }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center muted" style="padding:18px 8px;">
            No reports for selected filters.
          </td>
        </tr>
      @endforelse
    </tbody>

    <tfoot>
      <tr>
        <td colspan="5" class="text-right" style="padding:8px 8px; font-weight:bold;">
          Total Reports: {{ count($rows) }}
        </td>
      </tr>
    </tfoot>
  </table>
</div>

{{-- Footer --}}
<div class="footerbar">
  <div class="footer-row">
    <div class="footer-left">
      Page <span class="page-no"></span> / <span class="page-total"></span>
    </div>
    <div class="footer-right">
      Exported by Wellcare Labs • {{ $fmtGenerated() }}
    </div>
  </div>
</div>

<div class="page-num"></div>
</body>
</html>
