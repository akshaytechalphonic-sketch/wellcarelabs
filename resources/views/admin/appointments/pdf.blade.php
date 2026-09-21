<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Appointments Report — Alt</title>

  <style>
    /* ---------- Page & fonts ---------- */
    @page { size: A4; margin: 2mm 2mm 18mm 2mm; } /* extra bottom for fixed counter */
    @font-face {
      font-family: 'DejaVuSans';
      src: url("{{ public_path('fonts/DejaVuSans.ttf') }}") format('truetype');
      font-weight: normal; font-style: normal;
    }
    html, body { font-family: DejaVuSans, Arial, Helvetica, sans-serif; color:#0f172a; }
    body { font-size:12.2px; line-height:1.45; -webkit-font-smoothing:antialiased; }

    /* ---------- Utilities ---------- */
    .muted{ color:#64748b; }
    .tiny { font-size:10.8px; }
    .fw-600{ font-weight:600; }
    .text-right{ text-align:right; }
    .text-center{ text-align:center; }
    .nowrap{ white-space:nowrap; }
    td.sr-no{ white-space:nowrap; }

    /* ---------- Header (white card) ---------- */
    .brandband{
      background:#fff; color:#0f172a; border-radius:10px;
      padding:8px 12px; border:1px solid #e2e8f0;
      box-shadow:0 1px 2px rgba(0,0,0,.05);
      margin-bottom:8px;
    }
    /* DOMPDF-SAFE: table layout keeps logo + title in the same row */
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
      text-align:right; white-space:nowrap; /* Title to the right, single line */
    }
    .brand-sub{ margin-top:6px; font-size:11.5px; color:#64748b; }

    /* ---------- Table Card ---------- */
    .card{
      background:#fff; border:1px solid #e2e8f0; border-radius:10px;
      box-shadow:0 1px 4px rgba(0,0,0,.04);
      padding:10px 12px;
    }

    /* ---------- Table ---------- */
    table{ width:100%; border-collapse:collapse; }
    thead{ display:table-header-group; }
    tfoot{ display:table-footer-group; }

    /* Column widths */
    col.sr { width:44px; }
    col.nm { width:22%; }
    col.em { width:23%; }
    col.ph { width:14%; }
    col.dt { width:18%; }
    col.it { width:15%; }
    col.pr { width:9%;  }
    col.st { width:12%; }

    /* Headings */
    thead th{
      text-align:left; padding:7px 8px; font-weight:800;
      color:#0b5ed7; background:#f1f5ff; border-bottom:1px solid #dbe3ff;
      font-size:11.2px; letter-spacing:.2px;
    }
    thead th.status { text-align:center; }

    /* Body */
    tbody td{
      padding:7px 8px; border-bottom:1px solid #eef2f7; vertical-align:middle;
      overflow-wrap:anywhere; word-break:break-word;
    }
    tbody tr:nth-child(even) td{ background:#fafbff; }

    td.price{ text-align:right; font-variant-numeric: tabular-nums; white-space:nowrap; }
    td.name{ color:#0f172a; font-weight:600; }
    td.status{ text-align:center; }

    /* Status badges */
    .badge{
      display:inline-block; padding:2px 8px; border-radius:999px;
      font-size:10px; font-weight:700; letter-spacing:.2px;
      border:1px solid currentColor; background:transparent;
      white-space:nowrap; min-width:62px; line-height:1; text-align:center;
    }
    .b-pending    { color:#b45309; }
    .b-approved   { color:#075985; }
    .b-completed  { color:#166534; }
    .b-cancelled  { color:#991b1b; }
    .b-rescheduled{ color:#5b21b6; }
    .b-other      { color:#334155; }

    /* ---------- Your existing split footer (kept as-is) ---------- */
    .footerbar{ display:table; width:100%; border-top:1px solid #e2e8f0; margin-top:10px; padding-top:6px; font-size:11px; color:#64748b; }
    .footer-row{ display:table-row; }
    .footer-left, .footer-right{ display:table-cell; vertical-align:middle; }
    .footer-right{ text-align:right; }
    .page-no:before{ content: counter(page); }
    .page-total:before{ content: counter(page); } /* you can keep or remove this; separate from corner counter */

    /* ---------- NEW: Fixed bottom-left page counter (every page) ---------- */
    .page-num { position: fixed; bottom: 4mm; left: 4mm; font-size: 11px; color: #64748b; }
    .page-num:before { content: "Page " counter(page) " of " counter(page); }
  </style>
</head>
<body>
@php
  use Carbon\Carbon;

  $rows = $appointments instanceof \Illuminate\Support\Collection ? $appointments->all() : (array)$appointments;

  $statusKey = function($row){
    $k = strtolower((string)($row->raw_status ?? $row->status ?? 'other'));
    $allowed = ['pending','approved','completed','cancelled','rescheduled'];
    return in_array($k, $allowed) ? $k : 'other';
  };
  $statusLabel = function($row) use ($statusKey){ return $row->status ?? ucfirst($statusKey($row)); };
  $badgeClass  = function($row) use ($statusKey){ return 'b-'.$statusKey($row); };

  $fmtMoney = function($value){
    $n = is_numeric($value) ? (float)$value : (float)preg_replace('/[^\d.\-]/','',$value ?? '0');
    return '₹'.number_format($n, 2);
  };

  // Format as d/m/Y H:i:s in IST (robust to strings)
  $fmtDateIST = function($value){
    if (empty($value)) return '';
    $v = is_string($value) ? str_replace(['·','•'], ' ', $value) : $value;
    try { return Carbon::parse($v)->setTimezone('Asia/Kolkata')->format('d/m/Y H:i:s'); }
    catch (\Throwable $e) { return (string)$value; }
  };

$fmtGenerated = function() {
    return \Carbon\Carbon::now('Asia/Kolkata')->format('d/m/Y h:i A');
};


  $totalAmount = 0.0;
  $statusCounts = ['pending'=>0,'approved'=>0,'completed'=>0,'cancelled'=>0,'rescheduled'=>0,'other'=>0];
  foreach ($rows as $r){
    $totalAmount += is_numeric($r->price ?? null)
      ? (float)$r->price
      : (float)preg_replace('/[^\d.\-]/','',$r->price ?? '0');
    $statusCounts[$statusKey($r)]++;
  }
  $totalCount = count($rows);

  $logoDataUri = null;
  $logoPath = public_path('assets/images/wellcare_logo.png');
  if (is_readable($logoPath)) {
    try { $logoDataUri = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath)); } catch (\Throwable $e) {}
  }
@endphp

  <!-- Header -->
<div class="brandband">
  <div class="brand-top">
    <div class="brand-ico">
      @if($logoDataUri)
        <img src="{{ $logoDataUri }}" alt="logo">
      @else
        <span class="tiny fw-600">WL</span>
      @endif
    </div>
    <div class="brand-ttl">
      Appointments Report
      @if(!empty($hospitalName))
        <div class="brand-sub">
            Hospital: {{ $hospitalName }}
        </div>
      @endif
    </div>
  </div>
</div>


  <!-- Table -->
  <div class="card">
    <table>
      <colgroup>
        <col class="sr"><col class="nm"><col class="em"><col class="ph">
        <col class="dt"><col class="it"><col class="pr"><col class="st">
      </colgroup>
      <thead>
        <tr>
          <th class="nowrap">Sr.No</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Date &amp; Time</th>
          <th>Item</th>
          <th class="text-right">Price</th>
          <th class="status">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $a)
          <tr>
  <td class="muted sr-no">{{ $a->sr_no ?? $loop->iteration }}</td>
  <td class="name">{{ $a->name ?? '' }}</td>
  <td class="muted">{{ $a->email ?? '' }}</td>
  <td class="muted nowrap">{{ $a->phone ?? '' }}</td>
<td class="muted nowrap">
    {{ $a->date ?? '' }}

    @php
        // Try multiple possible time fields from the row
        $time = $a->time
            ?? $a->time_slot
            ?? $a->appointment_time
            ?? null;
    @endphp

    @if($time)
        <br>
        <span class="tiny">{{ $time }}</span>
    @endif
</td>
  <td>{{ $a->item ?? '' }}</td>
  <td class="price fw-600">{{ $fmtMoney($a->price ?? 0) }}</td>
  <td class="status"><span class="badge {{ $badgeClass($a) }}">{{ $statusLabel($a) }}</span></td>
</tr>
        @empty
          <tr>
            <td colspan="8" class="text-center muted" style="padding:18px 8px;">No data for selected filters.</td>
          </tr>
        @endforelse
      </tbody>
      <tfoot>
        <tr>
          <td colspan="8" class="text-right nowrap" style="padding:10px 8px;">
            <span class="fw-600">Totals</span> &nbsp; | &nbsp;
            Count: <span class="fw-600">{{ $totalCount }}</span> &nbsp; | &nbsp;
            Amount: <span class="fw-600">{{ $fmtMoney($totalAmount) }}</span>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>

  <!-- Your existing footer bar (kept). You can remove the left "Page ..." here if you want only the corner counter -->
   
  <div class="footerbar">
    <div class="footer-row">
      <div class="footer-left">Page <span class="page-no"></span> of <span class="page-total"></span></div>
<div class="footer-right">Exported by Wellcare Labs • {{ $fmtGenerated() }}</div>
    </div>
  </div>

  <!-- NEW: fixed bottom-left page number on every page -->
  <div class="page-num"></div>
</body>
</html>
