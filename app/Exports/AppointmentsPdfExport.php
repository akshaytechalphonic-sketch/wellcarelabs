<?php

namespace App\Exports;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AppointmentsPdfExport
{
    /** Optional filters: q, date_from, date_to, sort, hospital_qr */
    protected array $filters;

    /** Column names that may or may not exist */
    protected string $priceCol     = 'total_price';
    protected string $timeSlotCol  = 'time_slot';

    /** small caches for DB lookups */
    protected array $packageCache = [];
    protected array $testCache    = [];

    /** appointment_id => array<item> from appointment_items (authoritative when available) */
    protected array $relItemsByAppt = [];

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Return mapped appointments data ready for the PDF view.
     * Keys per row:
     *   sr_no, name, email, phone, date, item, price (formatted), numeric_price, status, raw_status
     */
    public function getData(): Collection
    {
        $query = $this->baseQuery();

        // columns to select defensively
        $cols = ['id', 'name', 'email', 'phone', 'date', 'service', 'status', 'created_at', 'updated_at'];
        foreach ([$this->priceCol, $this->timeSlotCol, 'package_id', 'test_id', 'items', 'items_summary', 'package_name'] as $c) {
            if (Schema::hasColumn('appointments', $c)) $cols[] = $c;
        }
        // optional hospital bits (used in QR view, harmless elsewhere)
        foreach (['hospital_id','hospital_name','source','via'] as $c) {
            if (Schema::hasColumn('appointments', $c)) $cols[] = $c;
        }

        $rows = $query->get($cols);

        // Preload relational items in one shot if the table exists
        $this->hydrateRelItems($rows);

        // in app/Exports/AppointmentsPdfExport.php -> getData()

        $sr = 1;
        return $rows->map(function ($a) use (&$sr) {
            [$label, $numeric] = $this->resolveItemAndPrice($a);
            $dateStr = $this->formatAppointmentDate($a);

            return (object) [
                'id'           => $a->id ?? null,                // <— added, so $a->id works if view expects it
                'sr_no'        => $sr++,                         // starts from 1
                'name'         => $a->name ?? '',
                'email'        => $a->email ?? '',
                'phone'        => $a->phone ?? '',
                'date'         => $dateStr,                      // d/m/Y · H:i:s IST
                'item'         => $label,                        // proper item/test/package/cart summary
                'price'        => '₹' . number_format((float)$numeric, 2),
                'numeric_price'=> (float)$numeric,
                'status'       => $this->normalizeStatus($a->status),
                'raw_status'   => $this->rawStatusKey($a->status),
                'created_at'   => $this->formatStamp($a->created_at),
                'updated_at'   => $this->formatStamp($a->updated_at),
            ];
        });

    }

    /* =========================== Query & Filters =========================== */

    protected function baseQuery(): Builder
    {
        $q = Appointment::query();

        // Quick search
        if ($term = trim((string)($this->filters['q'] ?? ''))) {
            $q->where(function ($sub) use ($term) {
                $sub->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%");
                if (Schema::hasColumn('appointments','hospital_name')) {
                    $sub->orWhere('hospital_name', 'like', "%{$term}%");
                }
            });
        }

        // Date range (inclusive) using 'date' if present, else created_at
        $from = $this->filters['date_from'] ?? null;
        $to   = $this->filters['date_to']   ?? null;
        if ($from || $to) {
            $toEnd = $to ? Carbon::parse($to)->endOfDay() : null;

            if (Schema::hasColumn('appointments','date')) {
                $q->when($from, fn($qq)=>$qq->whereDate('date','>=',$from))
                  ->when($toEnd, fn($qq)=>$qq->whereDate('date','<=',$to));
            } else {
                $q->when($from, fn($qq)=>$qq->where('created_at','>=',Carbon::parse($from)->startOfDay()))
                  ->when($toEnd, fn($qq)=>$qq->where('created_at','<=',$toEnd));
            }
        }

        // Hospital QR only
        if (!empty($this->filters['hospital_qr'])) {
            if (Schema::hasColumn('appointments','hospital_id')) {
                $q->whereNotNull('hospital_id');
            } elseif (Schema::hasColumn('appointments','source')) {
                $q->where('source','hospital_qr');
            } elseif (Schema::hasColumn('appointments','via')) {
                $q->where('via','hospital_qr');
            }
        }

        // Sort (default newest first)
        $sort = strtolower((string)($this->filters['sort'] ?? 'desc'));
        $q->orderBy('id', $sort === 'asc' ? 'asc' : 'desc');

        return $q;
    }

    /* ======================= Appointment Items (relation) ======================= */

    /**
     * Load appointment_items grouped by appointment_id for all given appointments.
     */
    protected function hydrateRelItems(Collection $appointments): void
    {
        $this->relItemsByAppt = [];

        if (!Schema::hasTable('appointment_items')) {
            return;
        }

        $ids = $appointments->pluck('id')->filter()->unique()->values();
        if ($ids->isEmpty()) {
            return;
        }

        // Build selectable columns based on existence
        $cols = ['appointment_id','item_type','item_id','item_name','item_price','quantity'];
        if (Schema::hasColumn('appointment_items', 'code')) {
            $cols[] = 'code';
        }

        $rows = DB::table('appointment_items')
            ->select($cols)
            ->whereIn('appointment_id', $ids)
            ->orderBy('appointment_id')
            ->get();

        foreach ($rows as $r) {
            $aid = (int)$r->appointment_id;
            $this->relItemsByAppt[$aid] ??= [];
            $this->relItemsByAppt[$aid][] = [
                'item_type'  => $r->item_type,
                'item_id'    => $r->item_id,
                'item_name'  => $r->item_name,
                'item_price' => (float)($r->item_price ?? 0),
                'quantity'   => max(1, (int)($r->quantity ?? 1)),
                // 'code' may not exist; add only if present on the row
                'code'       => property_exists($r, 'code') ? $r->code : null,
            ];
        }
    }

    /* =========================== Item & Price =========================== */

    /**
     * Resolve final item label and numeric price.
     * Priority:
     *  - appointment total_price if set
     *  - relational appointment_items (sum + summary label)
     *  - appointments.items JSON (sum + summary label)
     *  - package_id/test_id lookup (name/price)
     *  - service field decoding (package_#, test_#, slug/name)
     *  - package_name fallback
     */
    protected function resolveItemAndPrice($a): array
    {
        $price = 0.0;

        // 1) Prefer appointment price column if present
        if (Schema::hasColumn('appointments',$this->priceCol)) {
            $val = $a->{$this->priceCol} ?? null;
            if (is_numeric($val) && (float)$val > 0) {
                $price = (float)$val;
            }
        }

        // 2) appointment_items (authoritative when present)
        [$relLabel, $relTotal] = $this->deriveFromRelItems($a->id ?? null);
        if ($relLabel !== null) {
            if ($price <= 0 && $relTotal > 0) $price = $relTotal;
            return [$relLabel, max(0, (float)$price)];
        }

        // 3) appointments.items JSON (legacy)
        [$jsonLabel, $jsonTotal] = $this->deriveFromItemsJson($a);
        if ($jsonLabel !== null) {
            if ($price <= 0 && $jsonTotal > 0) $price = $jsonTotal;
            return [$jsonLabel, max(0, (float)$price)];
        }

        // 4) package_id/test_id lookup
        if (Schema::hasColumn('appointments','package_id') && !empty($a->package_id)) {
            if ($pkg = $this->getPackageById((int)$a->package_id)) {
                if ($price <= 0 && is_numeric($pkg->price)) $price = (float)$pkg->price;
                return [$pkg->name, max(0, (float)$price)];
            }
        }
        if (Schema::hasColumn('appointments','test_id') && !empty($a->test_id)) {
            if ($t = $this->getTestById((int)$a->test_id)) {
                if ($price <= 0 && is_numeric($t->price)) $price = (float)$t->price;
                return [$t->name, max(0, (float)$price)];
            }
        }

        // 5) service field decoding
        $service = (string)($a->service ?? '');
        if ($service !== '') {
            // package_#
            if (preg_match('/^package_(\d+)$/i', $service, $m)) {
                $id  = (int)$m[1];
                $pkg = $this->getPackageById($id);
                if ($pkg) {
                    if ($price <= 0 && is_numeric($pkg->price)) $price = (float)$pkg->price;
                    return [$pkg->name, max(0, (float)$price)];
                }
            }
            // test_#
            if (preg_match('/^test_(\d+)$/i', $service, $m)) {
                $id = (int)$m[1];
                $t  = $this->getTestById($id);
                if ($t) {
                    if ($price <= 0 && is_numeric($t->price)) $price = (float)$t->price;
                    return [$t->name, max(0, (float)$price)];
                }
            }
            // slug/name lookup in tests first, then packages
            if ($t = $this->getTestBySlugOrName($service)) {
                if ($price <= 0 && is_numeric($t->price)) $price = (float)$t->price;
                return [$t->name, max(0, (float)$price)];
            }
            if ($p = $this->getPackageByName($service)) {
                if ($price <= 0 && is_numeric($p->price)) $price = (float)$p->price;
                return [$p->name, max(0, (float)$price)];
            }

            // ultimately, prettify service text
            return [Str::title(str_replace('_',' ',$service)), max(0, (float)$price)];
        }

        // 6) package_name fallback
        if (Schema::hasColumn('appointments','package_name') && !empty($a->package_name)) {
            return [(string)$a->package_name, max(0,(float)$price)];
        }

        return ['Item', max(0,(float)$price)];
    }

    /**
     * Prefer appointment_items rows when available.
     * Returns [label|null, numericTotal]
     */
    protected function deriveFromRelItems($appointmentId): array
    {
        if (!$appointmentId) return [null, 0.0];
        if (!isset($this->relItemsByAppt[$appointmentId]) || empty($this->relItemsByAppt[$appointmentId])) {
            return [null, 0.0];
        }

        $items = $this->relItemsByAppt[$appointmentId];

        // enrich names from DB if missing and compute total
        $total = 0.0;
        foreach ($items as &$it) {
            $qty   = max(1, (int)($it['quantity'] ?? 1));
            $price = (float)($it['item_price'] ?? 0);
            $name  = trim((string)($it['item_name'] ?? ''));
            $type  = strtoupper((string)($it['item_type'] ?? ''));

            if ($name === '' && $type === 'PACKAGE') {
                $pid = $it['item_id'] ?? null;
                if (is_numeric($pid) && ($pkg = $this->getPackageById((int)$pid))) {
                    $name  = $pkg->name;
                    $price = $price > 0 ? $price : (float)$pkg->price;
                }
            }

            if ($name === '' && in_array($type, ['TEST','LABTEST','LAB_TEST'])) {
                $tid = $it['item_id'] ?? null;
                if (is_numeric($tid) && ($t = $this->getTestById((int)$tid))) {
                    $name  = $t->name;
                    $price = $price > 0 ? $price : (float)$t->price;
                }
            }

            if ($name === '') {
                $name = ($type ?: 'ITEM');
            }

            $it['item_name']  = $name;
            $it['item_price'] = $price;
            $it['quantity']   = $qty;

            $total += $price * $qty;
        }

        // label like: "First Item (+N more)"
        $first = $items[0]['item_name'] ?? 'Item';
        $label = $first . (count($items) > 1 ? ' (+' . (count($items)-1) . ' more)' : '');

        return [$label, $total];
    }

    /**
     * Parse appointments.items JSON/array into a friendly label and sum.
     * Returns [label|null, numericTotal]
     */
    protected function deriveFromItemsJson($a): array
    {
        // items_summary (already prepared label)
        if (Schema::hasColumn('appointments','items_summary') && !empty($a->items_summary)) {
            $sum = $this->sumItems($a->items ?? null);
            return [(string)$a->items_summary, $sum];
        }

        // parse items
        $items = $a->items ?? null;
        if (is_string($items)) {
            $decoded = json_decode($items, true);
            if (json_last_error() === JSON_ERROR_NONE) $items = $decoded;
        }
        if (!is_array($items) || empty($items)) {
            return [null, 0.0];
        }

        $items = array_values($items);
        $total = 0.0;
        foreach ($items as &$it) {
            $qty   = (int)($it['quantity'] ?? 1);
            $price = (float)($it['item_price'] ?? 0);

            // fill missing names from DB if we have ids encoded inside
            $name = trim((string)($it['item_name'] ?? ''));
            $type = strtoupper((string)($it['item_type'] ?? ''));

            if ($name === '' && $type === 'PACKAGE') {
                $pid = $it['item_id'] ?? $it['package_id'] ?? null;
                if (!$pid && !empty($it['code']) && preg_match('/^package_(\d+)$/i',$it['code'],$m)) $pid = (int)$m[1];
                if (is_numeric($pid) && ($pkg = $this->getPackageById((int)$pid))) {
                    $name  = $pkg->name;
                    $price = $price > 0 ? $price : (float)$pkg->price;
                }
            }

            if ($name === '' && in_array($type, ['TEST','LABTEST','LAB_TEST'])) {
                $tid = $it['item_id'] ?? $it['test_id'] ?? null;
                if (!$tid && !empty($it['code']) && preg_match('/^test_(\d+)$/i',$it['code'],$m)) $tid = (int)$m[1];
                if (is_numeric($tid) && ($t = $this->getTestById((int)$tid))) {
                    $name  = $t->name;
                    $price = $price > 0 ? $price : (float)$t->price;
                }
            }

            if ($name === '') {
                $name = ($type ?: 'ITEM');
            }

            $it['item_name']  = $name;
            $it['item_price'] = $price;
            $it['quantity']   = $qty;

            $total += $price * max(1,$qty);
        }

        $first = $items[0]['item_name'] ?? 'Item';
        $label = $first . (count($items) > 1 ? ' (+' . (count($items)-1) . ' more)' : '');

        return [$label, $total];
    }

    protected function sumItems($items): float
    {
        if (is_string($items)) {
            $decoded = json_decode($items, true);
            if (json_last_error() === JSON_ERROR_NONE) $items = $decoded;
        }
        if (!is_array($items) || !$items) return 0.0;

        $sum = 0.0;
        foreach ($items as $i) {
            $sum += (float)($i['item_price'] ?? 0) * (int)($i['quantity'] ?? 1);
        }
        return $sum;
    }

    /* =========================== Date/Time =========================== */

    protected function formatAppointmentDate($a): string
    {
        if (empty($a->date)) return '';

        try {
            // Build a Carbon from date + optional time_slot
            if (!empty($a->{$this->timeSlotCol})) {
                $datePart = $a->date instanceof \DateTimeInterface
                    ? $a->date->format('Y-m-d')             // DB stores Y-m-d
                    : Carbon::parse($a->date)->format('Y-m-d');

                $dt = Carbon::parse($datePart.' '.trim((string)$a->{$this->timeSlotCol}));
            } else {
                $dt = $a->date instanceof \DateTimeInterface
                    ? Carbon::instance($a->date)
                    : Carbon::parse($a->date);
            }

            // Convert to IST for display
            $dtIst = $dt->setTimezone('Asia/Kolkata');

            // If there's no explicit time (e.g., DB only had Y-m-d), show only the date
            $hasTimeSlot = !empty($a->{$this->timeSlotCol});
            $isMidnight  = $dtIst->format('H:i:s') === '00:00:00';

            if ($hasTimeSlot || !$isMidnight) {
                return $dtIst->format('d/m/Y h:i A');      // 12-hour with AM/PM
            }

            return $dtIst->format('d/m/Y');                // date only
        } catch (\Throwable $e) {
            return (string)$a->date;
        }
    }

    protected function formatStamp($v): string
    {
        if (!$v) return '';
        try {
            $dt = $v instanceof \DateTimeInterface ? Carbon::instance($v) : Carbon::parse($v);
            return $dt->setTimezone('Asia/Kolkata')->format('d/m/Y h:i A'); // 12-hour with AM/PM
        } catch (\Throwable $e) {
            return (string)$v;
        }
    }

    /* =========================== Lookups =========================== */

    protected function getPackageById(int $id): ?object
    {
        if (isset($this->packageCache[$id])) return $this->packageCache[$id];

        if (!Schema::hasTable('packages')) return $this->packageCache[$id] = null;

        $row = DB::table('packages')->where('id',$id)->first();
        return $this->packageCache[$id] = $row ? $this->normalizeRowToNamePrice($row, "Package #{$id}") : null;
    }

    protected function getPackageByName(string $name): ?object
    {
        $key = 'pkg_name:' . $name;
        if (isset($this->packageCache[$key])) return $this->packageCache[$key];

        if (!Schema::hasTable('packages')) return $this->packageCache[$key] = null;

        $q = DB::table('packages');
        if (Schema::hasColumn('packages','name')) {
            $q->where(function($qq) use ($name){ $qq->where('name',$name)->orWhere('name','like',"%{$name}%"); });
        } elseif (Schema::hasColumn('packages','title')) {
            $q->where(function($qq) use ($name){ $qq->where('title',$name)->orWhere('title','like',"%{$name}%"); });
        } else {
            return $this->packageCache[$key] = null;
        }

        $row = $q->first();
        return $this->packageCache[$key] = $row ? $this->normalizeRowToNamePrice($row, null) : null;
    }

    protected function getTestById(int $id): ?object
    {
        $key = "test_id:{$id}";
        if (isset($this->testCache[$key])) return $this->testCache[$key];

        $row = null;
        if (Schema::hasTable('lab_tests')) {
            $row = DB::table('lab_tests')->where('id',$id)->first();
        }
        if (!$row && Schema::hasTable('tests')) {
            $row = DB::table('tests')->where('id',$id)->first();
        }

        return $this->testCache[$key] = $row ? $this->normalizeRowToNamePrice($row, "Test #{$id}") : null;
    }

    protected function getTestBySlugOrName(string $slug): ?object
    {
        $key = "test_slug:{$slug}";
        if (isset($this->testCache[$key])) return $this->testCache[$key];

        $row = null;
        foreach (['lab_tests','tests'] as $tbl) {
            if (!Schema::hasTable($tbl)) continue;

            $cols = collect(['name','code','slug','title','test_name'])->filter(fn($c)=>Schema::hasColumn($tbl,$c))->all();
            if (!$cols) continue;

            $q = DB::table($tbl);
            $first = array_shift($cols);
            $q->where($first, $slug);
            foreach ($cols as $c) $q->orWhere($c, $slug);
            $row = $q->first();

            if ($row) break;
        }

        return $this->testCache[$key] = $row ? $this->normalizeRowToNamePrice($row, null) : null;
    }

    protected function normalizeRowToNamePrice($row, ?string $fallback): object
    {
        $nameCols  = ['name','title','display_name','label','test_name','package_name','package_title','package_title_en'];
        $priceCols = ['price','amount','total_price','cost','fees','value','price_inr','mrp','discounted_price'];

        $name = $fallback ?? 'Item';
        foreach ($nameCols as $c) {
            if (isset($row->{$c}) && $row->{$c} !== '') { $name = (string)$row->{$c}; break; }
        }

        $price = 0.0;
        foreach ($priceCols as $c) {
            if (isset($row->{$c}) && is_numeric($row->{$c})) { $price = (float)$row->{$c}; break; }
        }
        if ($price === 0.0) {
            foreach ((array)$row as $v) {
                if (is_numeric($v)) { $price = (float)$v; break; }
            }
        }

        return (object)['name'=>$name,'price'=>$price,'raw'=>$row];
    }

    /* =========================== Status =========================== */

    protected function normalizeStatus($status): string
    {
        if (!$status) return '';
        $s = strtolower(trim((string)$status));
        return match (true) {
            in_array($s, ['complete','completed','done'])              => 'Completed',
            in_array($s, ['approved','approve','approved_payment'])    => 'Approved',
            in_array($s, ['pending','pending_payment','awaiting'])     => 'Pending',
            in_array($s, ['cancel','cancelled','canceled'])            => 'Cancelled',
            in_array($s, ['reschedule','rescheduled'])                 => 'Rescheduled',
            default => Str::title(str_replace('_',' ',$s)),
        };
    }

    protected function rawStatusKey($status): string
    {
        if (!$status) return 'other';
        $s = strtolower(trim((string)$status));
        return match (true) {
            in_array($s, ['complete','completed','done'])           => 'completed',
            in_array($s, ['approved','approve','approved_payment']) => 'approved',
            in_array($s, ['pending','pending_payment','awaiting'])  => 'pending',
            in_array($s, ['cancel','cancelled','canceled'])         => 'cancelled',
            in_array($s, ['reschedule','rescheduled'])              => 'rescheduled',
            default => 'other',
        };
    }
}
