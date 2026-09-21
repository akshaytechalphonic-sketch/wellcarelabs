<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>{{ $hospital->name }} — Hospital Manager Appointments Report</title>

  <style>
    /* ---------- Page & Fonts ---------- */
    @page { size: A4; margin: 2mm 2mm 18mm 2mm; }

    @font-face {
      font-family: 'DejaVuSans';
      src: url("{{ public_path('fonts/DejaVuSans.ttf') }}") format('truetype');
      font-weight: normal;
      font-style: normal;
    }

    html, body {
      font-family: DejaVuSans, Arial, Helvetica, sans-serif;
      color: #0f172a;
    }

    body {
      font-size: 12.2px;
      line-height: 1.45;
      -webkit-font-smoothing: antialiased;
    }

    /* ---------- Colors (MATCH MAIN REPORT) ---------- */
    :root{
      --wc-primary: #0b5ed7;
      --wc-soft: #f1f5ff;
      --wc-soft-row: #fafbff;
      --wc-border: #dbe3ff;
      --wc-muted: #64748b;
      --wc-pending: #ea580c;
    }

    /* ---------- Utilities ---------- */
    .fw-600{ font-weight:600; }
    .muted{ color:var(--wc-muted); }
    .text-right{ text-align:right; }
    .text-center{ text-align:center; }
    .nowrap{ white-space:nowrap; }
    .tiny{ font-size:10.8px; }

    /* ---------- Header Card ---------- */
    .brandband{
      background:#fff;
      border-radius:12px;
      padding:10px 14px;
      border:1px solid var(--wc-border);
      box-shadow:0 2px 6px rgba(0,0,0,.06);
      margin-bottom:8px;
    }

    .brand-top{
      display:table;
      width:100%;
    }

    .brand-ico{
      display:table-cell;
      width:180px;
      vertical-align:middle;
      border-radius:16px;
      border:1px solid #e5e7eb;
      text-align:center;
      height:65px;
    }

    .brand-ico img{
      width:100%;
      height:auto;
      object-fit:contain;
      padding:4px;
    }

    .brand-ttl{
      display:table-cell;
      vertical-align:middle;
      text-align:right;
      font-size:20px;
      font-weight:800;
      white-space:nowrap;
    }

    .brand-sub{
      margin-top:6px;
      font-size:11.5px;
      color:var(--wc-muted);
      font-weight:600;
    }

    /* ---------- Card ---------- */
    .card{
      background:#fff;
      border:1px solid var(--wc-border);
      border-radius:12px;
      box-shadow:0 2px 6px rgba(0,0,0,.05);
      padding:10px 12px;
    }

    /* ---------- Table ---------- */
    table{
      width:100%;
      border-collapse:collapse;
    }

    thead{
      display:table-header-group;
    }

    thead th{
      padding:7px 8px;
      font-size:11.2px;
      font-weight:800;
      color:var(--wc-primary);
      background:var(--wc-soft);
      border-bottom:1px solid var(--wc-border);
      text-align:left;
    }

    tbody td{
      padding:7px 8px;
      border-bottom:1px solid #eef2f7;
      vertical-align:middle;
      word-break:break-word;
    }

    tbody tr:nth-child(even) td{
      background:var(--wc-soft-row);
    }

    /* ---------- Status Badge ---------- */
    .badge{
      display:inline-block;
      padding:2px 10px;
      border-radius:999px;
      font-size:10px;
      font-weight:700;
      border:1px solid currentColor;
      min-width:70px;
      text-align:center;
      background:#fff;
    }

    .b-pending{
      color:var(--wc-pending);
      border-color:var(--wc-pending);
    }

    /* ---------- Footer ---------- */
    .footerbar{
      display:table;
      width:100%;
      border-top:1px solid #e2e8f0;
      margin-top:10px;
      padding-top:6px;
      font-size:11px;
      color:var(--wc-muted);
    }

    .footer-row{ display:table-row; }
    .footer-left,
    .footer-right{ display:table-cell; }
    .footer-right{ text-align:right; }

    /* ---------- Fixed Page Number ---------- */
    .page-num{
      position:fixed;
      bottom:4mm;
      left:4mm;
      font-size:11px;
      color:var(--wc-muted);
    }
    .page-num:before{
      content:"Page " counter(page);
    }
  </style>
</head>

<body>
@php
  use Carbon\Carbon;

  $fmtDate = fn($d) => $d ? Carbon::parse($d)->format('d/m/Y') : '';
  $fmtTime = fn($t) => $t ?? '';
@endphp

<!-- ===== HEADER ===== -->
<div class="brandband">
  <div class="brand-top">
    <div class="brand-ico">
      <img src="{{ public_path('assets/images/wellcare_logo.png') }}" alt="Wellcare Labs">
    </div>
    <div class="brand-ttl">
      Hospital Manager Appointments Report
      <div class="brand-sub">
        Hospital: {{ $hospital->name }}<br>
        For Internal Hospital Management Use
      </div>
    </div>
  </div>
</div>

<!-- ===== TABLE ===== -->
<div class="card">
  <table>
    <thead>
      <tr>
        <th>Patient Name</th>
        <th class="nowrap">Date</th>
        <th class="nowrap">Time</th>
        <th>Service</th>
        <th class="text-center">Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($appointments as $a)
      <tr>
        <td class="fw-600">{{ $a->name }}</td>
        <td class="muted nowrap">{{ $fmtDate($a->date) }}</td>
        <td class="muted nowrap">{{ $fmtTime($a->time_slot) }}</td>
        <td>{{ $a->service }}</td>
        <td class="text-center">
          <span class="badge b-pending">
            {{ ucfirst($a->status) }}
          </span>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5" class="text-center muted" style="padding:18px;">
          No appointments found.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

<!-- ===== FOOTER ===== -->
<div class="footerbar">
  <div class="footer-row">
    <div class="footer-left">
      Hospital Manager Report
    </div>
    <div class="footer-right">
      Exported by Wellcare Labs • {{ now('Asia/Kolkata')->format('d/m/Y h:i A') }}
    </div>
  </div>
</div>

<div class="page-num"></div>

</body>
</html>
