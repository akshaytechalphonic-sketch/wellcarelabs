<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Appointments Report</title>

  <style>
    @page { margin: 18mm 12mm; }
    @font-face {
      font-family: 'DejaVuSans';
      src: url("{{ public_path('fonts/DejaVuSans.ttf') }}") format('truetype');
    }
    body { font-family: DejaVuSans, Arial, Helvetica, sans-serif; font-size:12px; color:#333; -webkit-font-smoothing:antialiased; }
    .container { width:100%; margin:0 auto; }
    .header { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:14px; }
    .brand { display:flex; align-items:center; gap:12px; }
    .brand img { height:50px; width:auto; object-fit:contain; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.06); background:#fff; padding:4px; }
    .title-wrap { flex:1; text-align:center; }
    .title { font-size:18px; font-weight:700; letter-spacing:0.2px; }
    .subtitle { font-size:12px; color:#666; margin-top:2px; }
    .stamp { text-align:right; font-size:11px; color:#666; min-width:160px; }

    .card { background:#fff; border-radius:6px; border:1px solid #eee; box-shadow:0 1px 6px rgba(0,0,0,0.03); padding:10px; }
    table { width:100%; border-collapse:collapse; table-layout:fixed; font-size:12px; }
    thead th { text-align:left; padding:10px 8px; font-weight:700; color:#222; background:#fafafa; border-bottom:1px solid #e9e9e9; }
    tbody td { padding:10px 8px; border-bottom:1px solid #f1f1f1; vertical-align:middle; word-wrap:break-word; }

    th.col-id { width:48px; } th.col-name { width:160px; } th.col-email { width:220px; } th.col-phone { width:110px; }
    th.col-date { width:160px; } th.col-price { width:80px; text-align:right; } th.col-status { width:110px; text-align:center; }
    tbody tr:nth-child(odd) { background:#ffffff; } tbody tr:nth-child(even) { background:#fbfbfb; }
    .muted { color:#777; font-size:11px; }
    .badge { display:inline-block; padding:5px 8px; border-radius:12px; color:#fff; font-size:11px; font-weight:600; box-shadow:0 1px 0 rgba(0,0,0,0.06); }
    .badge.pending   { background:#f0ad4e; } 
    .badge.approved  { background:#17a2b8; } 
    .badge.completed { background:#28a745; }
    .badge.cancelled { background:#dc3545; } 
    .badge.other     { background:#6c757d; }
    .badge.rescheduled { background: #6f42c1; } /* purple */

    .footer { margin-top:12px; font-size:11px; color:#666; text-align:right; }
    .pagenum:before { content: "Page " counter(page); }
    @media print { .header { margin-bottom:8px; } thead th, tbody td { padding:8px 6px; font-size:11px; } }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="brand">
        @php
            $logo = public_path('assets/images/gallery/Welcare_labs.png');
        @endphp

        @if(file_exists($logo))
          <img src="{{ $logo }}" alt="Wellcare Labs logo">
        @else
          <div style="font-weight:700; font-size:16px; color:#0b6b57;">Wellcare Labs</div>
        @endif
      </div>

      <div class="title-wrap">
        <div class="title">Appointments Report</div>
        <div class="subtitle">Exported appointments snapshot</div>
      </div>

      <div class="stamp">
        {{-- Use Asia/Kolkata timezone for consistent timestamp --}}
        Generated: {{ \Carbon\Carbon::now('Asia/Kolkata')->format('d/m/Y H:i:s') }}
      </div>
    </div>

    {{-- Helpers & Safe accessors --}}
    @php
      use Carbon\Carbon;

      $__rowVal = function ($row, $key, $default = null) {
          if (is_array($row)) return $row[$key] ?? $default;
          if (is_object($row)) {
              // direct property
              if (isset($row->{$key})) return $row->{$key};
              // Eloquent attribute
              if (method_exists($row, 'getAttribute')) {
                  try {
                      $val = $row->getAttribute($key);
                      if ($val !== null) return $val;
                  } catch (\Throwable $e) {}
              }
              // fallback
              return $row->{$key} ?? $default;
          }
          return $default;
      };

      // Try to parse/format a date-like value in Asia/Kolkata; return formatted string or empty
      $__formatDateField = function ($row, $key, $dateOnly = false, $default = '') use (&$__rowVal) {
          $val = $__rowVal($row, $key, null);
          if ($val === null || $val === '') return $default;

          // If it's a Carbon / DateTime instance
          if ($val instanceof \DateTimeInterface) {
              $dt = Carbon::instance($val)->setTimezone('Asia/Kolkata');
              return $dateOnly ? $dt->format('d/m/Y') : $dt->format('d/m/Y H:i:s');
          }

          // If it's numeric timestamp
          if (is_numeric($val)) {
              try {
                  $dt = Carbon::createFromTimestamp((int)$val)->setTimezone('Asia/Kolkata');
                  return $dateOnly ? $dt->format('d/m/Y') : $dt->format('d/m/Y H:i:s');
              } catch (\Throwable $e) {}
          }

          // Try parsing common string formats
          try {
              // Some strings might be 'YYYY-MM-DD' or 'YYYY-MM-DD HH:MM:SS'
              $dt = Carbon::parse($val);
              if ($dt) {
                  $dt->setTimezone('Asia/Kolkata');
                  // If original string has no time component (simple check)
                  $hasTime = (bool) preg_match('/\d{1,2}:\d{2}/', (string)$val);
                  return $dateOnly && !$hasTime ? $dt->format('d/m/Y') : $dt->format('d/m/Y H:i:s');
              }
          } catch (\Throwable $e) {
              // fallback: return original string
              return (string)$val;
          }

          return (string)$val;
      };

      // normalize status class
      $__statusClass = function ($row) use (&$__rowVal) {
          $raw = $__rowVal($row, 'raw_status', null) ?? $__rowVal($row, 'status', null);
          if (!$raw) return 'other';
          $s = strtolower(trim((string)$raw));
          if (in_array($s, ['completed','complete','done'])) return 'completed';
          if (in_array($s, ['pending','pending_payment','awaiting'])) return 'pending';
          if (in_array($s, ['approved','approved_payment'])) return 'approved';
          if (in_array($s, ['cancelled','canceled','rejected'])) return 'cancelled';
          if (in_array($s, ['reschedule','rescheduled','rescheduling','rescheduled_by_user','rescheduled_by_admin'])) return 'rescheduled';
          return 'other';
      };

      // smart item getter
      $__itemVal = function ($row) use (&$__rowVal) {
          return $__rowVal($row, 'item', null)
              ?? $__rowVal($row, 'service', null)
              ?? $__rowVal($row, 'test_name', null)
              ?? $__rowVal($row, 'package_name', null)
              ?? $__rowVal($row, 'item_name', null)
              ?? '';
      };

      // smart price getter
      $__priceVal = function ($row) use (&$__rowVal) {
          $p = $__rowVal($row, 'price', null);
          if ($p !== null && $p !== '') return $p;
          $p = $__rowVal($row, 'total_price', null);
          if ($p !== null && $p !== '') return $p;
          $p = $__rowVal($row, 'amount', null);
          if ($p !== null && $p !== '') return $p;
          // If there is a numeric numeric_price field, format it
          $pn = $__rowVal($row, 'numeric_price', null);
          if (is_numeric($pn)) return '₹' . number_format($pn, 2);
          return '₹0.00';
      };
    @endphp

    <div class="card">
      <table>
        <thead>
          <tr>
            <th class="col-id">ID</th>
            <th class="col-name">Name</th>
            <th class="col-email">Email</th>
            <th class="col-phone">Phone</th>
            <th class="col-date">Date (IST)</th>
            <th>Item</th>
            <th class="col-price">Price</th>
            <th>Message</th>
            <th class="col-status">Status</th>
          </tr>
        </thead>

        <tbody>
          @foreach($appointments as $a)
            <tr>
              <td class="muted">{{ $__rowVal($a, 'id', '') }}</td>
              <td>{{ $__rowVal($a, 'name', '') }}</td>
              <td class="muted">{{ $__rowVal($a, 'email', '') }}</td>
              <td class="muted">{{ $__rowVal($a, 'phone', '') }}</td>

              {{-- Prefer 'date' then 'appointment_at' then created_at --}}
              <td class="muted">
                {{ $__formatDateField($a, 'date', false, $__formatDateField($a, 'appointment_at', false, $__formatDateField($a, 'created_at', false, ''))) }}
              </td>

              <td>{{ $__itemVal($a) }}</td>
              <td style="text-align:right; font-weight:600;">{{ $__priceVal($a) }}</td>
              <td class="muted">{{ $__rowVal($a, 'message', '') }}</td>

              @php
                $key = $__statusClass($a);
                $label = $__rowVal($a, 'status', $__rowVal($a, 'raw_status', ''));
                if (!$label) $label = ucfirst($key);
                // Friendly override: ensure "Rescheduled" label for rescheduled keys
                if ($key === 'rescheduled') $label = 'Rescheduled';
              @endphp

              <td style="text-align:center;"><span class="badge {{ $key }}">{{ $label }}</span></td>
            </tr>
          @endforeach

          @if (empty($appointments) || count($appointments) === 0)
            <tr>
              <td colspan="9" class="muted" style="text-align:center; padding:18px 8px;">No appointments found for the selected filters.</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>

    <div class="footer">
      <span class="pagenum"></span>
      &nbsp;•&nbsp; Exported by Wellcare Labs • {{ \Carbon\Carbon::now('Asia/Kolkata')->format('d/m/Y H:i:s') }}
    </div>
  </div>
</body>
</html>
