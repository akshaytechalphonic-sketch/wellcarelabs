<?php

namespace App\Exports;

use App\Models\Appointment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AppointmentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $packageCache = [];
    protected $testCache = [];

    protected $appointmentPriceColumn = 'total_price';
    protected $appointmentTimeSlotColumn = 'time_slot';

    /** @var array */
    protected $filters = [];

    /** @var string[] base appointment columns (dynamic, ordered alpha-first then numeric-last) */
    protected array $baseCols = [];

    /** columns to drop from export */
    protected array $dropCols = [
        'items_json','items_count','user_name','test_name','package_title',
        'resolved_item_price_formatted','resolved_item_name',
        'hospital_inique_id','hospital_unique_id',
        'report_id','paid_at','coupon_id','discount_value',
        'service','test_id','package_name','package_id',
    ];

    /** @var array<string,string> Doctrine types per column (if available) */
    protected array $doctrineTypes = [];

    public function __construct(array $filters = [])
    {
        $this->filters  = $filters;

        $all = Schema::getColumnListing('appointments') ?? [];
        // Remove hidden columns
        $drop = array_map('strtolower', $this->dropCols);
        $all  = array_values(array_filter($all, fn($c) => !in_array(strtolower($c), $drop, true)));

        // Load doctrine types once (if DBAL installed)
        $this->doctrineTypes = $this->getDoctrineTypesFor('appointments');

        // Order: alpha/date first, numeric last
        [$alpha, $numeric] = $this->partitionAlphaNumeric($all);
        $this->baseCols = array_values(array_unique(array_merge($alpha, $numeric)));
    }

    public function collection()
    {
        $query = Appointment::query();

        // Eager-load relations safely
        $relCandidates = ['items','package','test','hospital','user'];
        $rels = [];
        foreach ($relCandidates as $rel) {
            if (method_exists(new Appointment, $rel)) $rels[] = $rel;
        }
        if ($rels) $query->with($rels);

        // Search
        $q = isset($this->filters['q']) ? trim((string)$this->filters['q']) : '';
        if ($q !== '') {
            $query->where(function ($builder) use ($q) {
                if (ctype_digit($q)) $builder->orWhere('id', intval($q));
                $builder->orWhere('name','like',"%{$q}%")
                        ->orWhere('email','like',"%{$q}%")
                        ->orWhere('phone','like',"%{$q}%")
                        ->orWhere('message','like',"%{$q}%");
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $q)) $builder->orWhereDate('date', $q);

                $builder->orWhereHas('items', function ($b) use ($q) {
                    $b->where('item_name','like',"%{$q}%")
                      ->orWhere('item_type','like',"%{$q}%");
                });
                if (method_exists(new Appointment,'package')) {
                    $builder->orWhereHas('package', fn($b)=>$b->where('title','like',"%{$q}%")->orWhere('name','like',"%{$q}%"));
                }
                if (method_exists(new Appointment,'test')) {
                    $builder->orWhereHas('test', fn($b)=>$b->where('test_name','like',"%{$q}%")->orWhere('name','like',"%{$q}%"));
                }
                if (method_exists(new Appointment,'hospital')) {
                    $builder->orWhereHas('hospital', fn($b)=>$b->where('name','like',"%{$q}%"));
                }
            });
        }

        // Date filters
        $dateFrom = $this->filters['date_from'] ?? null;
        $dateTo   = $this->filters['date_to'] ?? null;
        if (!empty($dateFrom) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) $query->whereDate('date', '>=', $dateFrom);
        if (!empty($dateTo)   && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo))   $query->whereDate('date', '<=', $dateTo);

        // Optional hospital_qr filter (broadened to include hospital_name-only rows)
        if (!empty($this->filters['hospital_qr'])) {
            $query->where(function ($qrb) {
                $qrb->whereNotNull('hospital_id')
                    ->orWhere(function ($q2) {
                        $q2->whereNotNull('hospital_unique_id')
                           ->where('hospital_unique_id','<>','');
                    })
                    ->orWhere(function ($q3) {
                        $q3->whereNotNull('hospital_name')
                           ->where('hospital_name','<>','');
                    });
            });
        }

        // Sort
        $sort = strtolower($this->filters['sort'] ?? '');
        if (in_array($sort, ['asc','desc'], true)) $query->orderByRaw('COALESCE(date, created_at) '.$sort);
        else $query->orderByDesc('id');

        return $this->baseCols ? $query->get($this->baseCols) : $query->get();
    }

    public function headings(): array
    {
        return array_merge(
            $this->baseCols,
            [
                // text/date extras (12-hour)
                'date_time_display',
                'hospital_name',
                'first_item_name',
                'tests',
                'packages_list',

                // numeric extras
                'items_total',
                'computed_subtotal',
                'computed_coupon',
                'computed_grand_total',

                // meta (IST 12-hour)
                'generated_at_ist',
            ]
        );
    }

    public function map($a): array
    {
        // -------- Format base columns with 12-hour times where applicable --------
        $baseValues = [];
        $attrs = $a->getAttributes();

        foreach ($this->baseCols as $col) {
            $val  = $attrs[$col] ?? null;
            $type = strtolower((string)($this->doctrineTypes[$col] ?? ''));
            $baseValues[] = $this->formatColumnValue($col, $val, $type);
        }

        // -------- date_time_display in 12-hour --------
        $dateStr = '';
        if (!empty($a->date)) {
            try {
                if (!empty($a->{$this->appointmentTimeSlotColumn})) {
                    $datePart    = $a->date instanceof \DateTimeInterface ? $a->date->format('Y-m-d') : Carbon::parse($a->date)->format('Y-m-d');
                    $timeSlotRaw = trim((string)$a->{$this->appointmentTimeSlotColumn});
                    $dt          = Carbon::parse("{$datePart} {$timeSlotRaw}");
                } else {
                    $dt = $a->date instanceof \DateTimeInterface ? $a->date : Carbon::parse($a->date);
                }
                $dateStr = $dt->format('d/m/Y h:i a');
            } catch (\Exception $e) {
                $dateStr = (string)$a->date;
            }
        }

        // -------- Hospital name --------
        $hospitalName = null;
        try {
            $hospitalName = $a->relationLoaded('hospital') && $a->hospital ? ($a->hospital->name ?? null) : ($a->hospital_name ?? null);
        } catch (\Throwable $e) {}

        // -------- Items + tests/packages --------
        $itemsArr     = [];
        $itemsTotal   = 0.0;
        $itemsRel     = [];
        $tests        = [];
        $packages     = [];

        try { $itemsRel = $a->relationLoaded('items') ? ($a->items ?? []) : []; } catch (\Throwable $e) {}

        foreach ($itemsRel as $it) {
            $qty  = max(1, (int)($it->quantity ?? 1));
            $unit = (float)($it->item_price ?? 0);
            $sum  = $qty * $unit;
            $itemsTotal += $sum;

            $name = $it->item_name ?? $it->name ?? 'Item';
            $type = strtolower((string)($it->item_type ?? ''));

            if ($type !== '') {
                if (strpos($type, 'test') !== false)     { $tests[] = $name; }
                if (strpos($type, 'package') !== false)  { $packages[] = $name; }
            } else {
                $lname = strtolower($name);
                if (preg_match('/\btest\b/', $lname))    { $tests[] = $name; }
                if (preg_match('/\bpackage\b/', $lname)) { $packages[] = $name; }
            }

            $itemsArr[] = [
                'name' => $name,
                'qty'  => $qty,
                'unit' => $unit,
                'sum'  => $sum,
            ];
        }

        $testsList    = implode(', ', array_values(array_unique(array_filter($tests))));
        $packagesList = implode(' | ', array_values(array_unique(array_filter($packages))));

        // -------- Totals --------
        $discountType  = strtolower(trim((string)($a->discount_type ?? '')));
        $discountValue = (float)($a->discount_value ?? 0);
        $couponAmount  = 0.0;

        if ($discountType === 'percent' && $discountValue > 0) {
            $couponAmount = round(($itemsTotal * $discountValue) / 100, 2);
        } elseif ($discountType === 'fixed' && $discountValue > 0) {
            $couponAmount = round($discountValue, 2);
        } else {
            $couponAmount = (float)($a->discount_amount
                            ?? $a->coupon_amount
                            ?? $a->coupon_discount
                            ?? 0);
        }
        $couponAmount = max(0, min($couponAmount, $itemsTotal));

        $extraDiscount = (float)($a->extra_discount ?? 0);
        $taxAmount     = (float)($a->tax_amount ?? 0);
        $collectionFee = (float)($a->collection_charges ?? $a->home_collection_fee ?? 0);

        $grandTotalComputed = isset($a->total_price) ? (float)$a->total_price
            : (isset($a->grand_total) ? (float)$a->grand_total
            : (isset($a->total_price_display) ? (float)$a->total_price_display
            : max(0, $itemsTotal - $couponAmount - $extraDiscount + $taxAmount + $collectionFee)));

        // -------- Append extras --------
        $extras = [
            $dateStr,
            $hospitalName,
            $itemsArr[0]['name'] ?? null,
            $testsList,
            $packagesList,
            round($itemsTotal, 2),
            round($itemsTotal, 2),
            round($couponAmount, 2),
            round($grandTotalComputed, 2),
            now('Asia/Kolkata')->format('d/m/Y h:i a'),
        ];

        return array_merge($baseValues, $extras);
    }

    // ----------------- Formatting helpers -----------------

    /**
     * Format a single column's value based on doctrine type or name heuristics.
     * Ensures all time/datetime fields are 12-hour with am/pm (but keeps free-text slots like "Morning").
     */
    protected function formatColumnValue(string $col, $val, string $doctrineType)
    {
        if ($val === null || $val === '') {
            return $val;
        }

        $lcCol = strtolower($col);
        $type  = $doctrineType;

        // If doctrine tells us the type, prefer it
        if ($type !== '') {
            if ($this->isTimeType($type)) {
                return $this->formatAsTime12($val);
            }
            if ($this->isDateTimeType($type)) {
                return $this->formatAsDateTime12($val);
            }
            if ($this->isDateType($type)) {
                return $this->formatAsDate($val);
            }
        }

        // Heuristics by column name
        if ($lcCol === 'time_slot' || str_ends_with($lcCol, '_time') || str_contains($lcCol, 'time')) {
            return $this->formatAsTime12($val);
        }
        if (str_ends_with($lcCol, '_at') || str_contains($lcCol, 'datetime')) {
            return $this->formatAsDateTime12($val);
        }
        if ($lcCol === 'date' || str_ends_with($lcCol, '_date') || $lcCol === 'dob') {
            // could be date-only or datetime; try datetime parse first
            $dt = $this->tryParseDateTime($val);
            if ($dt && !($dt->hour === 0 && $dt->minute === 0 && $dt->second === 0)) {
                return $dt->format('d/m/Y h:i a');
            }
            return $this->formatAsDate($val);
        }

        return $val;
    }

    /**
     * Convert only “time-looking” values to 12h; keep words like "Morning" as-is.
     */
    protected function formatAsTime12($value): string
    {
        try {
            if ($value instanceof \DateTimeInterface) {
                return Carbon::instance($value)->format('h:i a');
            }
            $v = trim((string)$value);

            // Accept: "H:i" / "H:i:s" 24h, "h:i am/pm" 12h
            $looks24h = preg_match('/^(2[0-3]|[01]?\d):[0-5]\d(?::[0-5]\d)?$/', $v);
            $looks12h = preg_match('/^(1[0-2]|0?\d):[0-5]\d(?::[0-5]\d)?\s*(am|pm)$/i', $v);

            if (!$looks24h && !$looks12h) {
                // Not a strict time; keep original (e.g., "Morning", "Evening")
                return $v;
            }

            $dt = Carbon::parse('1970-01-01 '.$v);
            return $dt->format('h:i a');
        } catch (\Throwable $e) {
            return (string)$value;
        }
    }

    protected function formatAsDateTime12($value): string
    {
        try {
            $dt = $this->tryParseDateTime($value);
            if ($dt) return $dt->format('d/m/Y h:i a');
            return (string)$value;
        } catch (\Throwable $e) {
            return (string)$value;
        }
    }

    protected function formatAsDate($value): string
    {
        try {
            $dt = $value instanceof \DateTimeInterface ? Carbon::instance($value) : Carbon::parse($value);
            return $dt->format('d/m/Y');
        } catch (\Throwable $e) {
            return (string)$value;
        }
    }

    protected function tryParseDateTime($value): ?Carbon
    {
        try {
            if ($value instanceof \DateTimeInterface) {
                return Carbon::instance($value);
            }
            return Carbon::parse((string)$value);
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function isTimeType(string $t): bool
    {
        return in_array($t, ['time','time_immutable'], true);
    }
    protected function isDateTimeType(string $t): bool
    {
        return in_array($t, ['datetime','datetimetz','datetime_immutable'], true);
    }
    protected function isDateType(string $t): bool
    {
        return in_array($t, ['date','date_immutable'], true);
    }

    // ----------------- Existing helpers -----------------

    protected function formatPrice($value): string
    {
        $num = is_numeric($value) ? (float)$value : 0.0;
        return 'Rs. ' . number_format($num, 2);
    }

    protected function normalizeStatus($status): string
    {
        if (!$status) return '';
        $s = strtolower(trim((string)$status));
        if (in_array($s, ['complete','completed','done'])) return 'Completed';
        if (in_array($s, ['pending','pending_payment','awaiting'])) return 'Pending';
        return Str::title(str_replace('_',' ',$s));
    }

    protected function formatDateTimeDisplay($value): string
    {
        if (!$value) return '';
        try {
            $dt = $value instanceof \DateTimeInterface ? $value : Carbon::parse($value);
            return $dt->format('d/m/Y h:i a'); // 12-hour
        } catch (\Exception $e) {
            return (string)$value;
        }
    }

    protected function resolveService(?string $service, $a = null): array
    {
        if ($a) {
            if (isset($a->package_id) && $a->package_id) {
                $pkg = $this->getPackageById((int)$a->package_id);
                if ($pkg) return [$pkg->name, $pkg->price];
            }
            if (isset($a->test_id) && $a->test_id) {
                $t = $this->getTestById((int)$a->test_id);
                if ($t) return [$t->name, $t->price];
            }
        }
        if ($service && preg_match('/^package_(\d+)$/i', $service, $m)) {
            $id = (int)$m[1];
            $pkg = $this->getPackageById($id);
            if ($pkg) return [$pkg->name, $pkg->price];
            return ["Package #{$id}", 0.0];
        }
        if ($service && preg_match('/^test_(\d+)$/i', $service, $m)) {
            $id = (int)$m[1];
            $t = $this->getTestById($id);
            if ($t) return [$t->name, $t->price];
            return ["Test #{$id}", 0.0];
        }
        if ($service) {
            $t = $this->getTestBySlugOrName($service);
            if ($t) return [$t->name, $t->price];
            $p = $this->getPackageByName($service);
            if ($p) return [$p->name, $p->price];
            return [Str::title(str_replace('_',' ',$service)), 0.0];
        }
        return ['', 0.0];
    }

    protected function getPackageById(int $id)
    {
        if ($id <= 0) return null;
        if (isset($this->packageCache[$id])) return $this->packageCache[$id];

        if (class_exists(\App\Models\Package::class)) {
            try { $pkg = \App\Models\Package::find($id); if ($pkg) return $this->packageCache[$id] = $pkg; } catch (\Throwable $e) {}
        }
        try {
            if (Schema::hasTable('packages')) {
                $row = DB::table('packages')->where('id',$id)->first();
                if ($row) return $this->packageCache[$id] = $this->normalizeRecordToNamePrice($row,'packages');
            }
        } catch (\Throwable $e) {}
        return $this->packageCache[$id] = null;
    }

    protected function getTestById(int $id)
    {
        if ($id <= 0) return null;
        if (isset($this->testCache[$id])) return $this->testCache[$id];

        if (class_exists(\App\Models\Test::class)) {
            try { $t = \App\Models\Test::find($id); if ($t) return $this->testCache[$id] = $t; } catch (\Throwable $e) {}
        }
        if (class_exists(\App\Models\LabTest::class)) {
            try { $t = \App\Models\LabTest::find($id); if ($t) return $this->testCache[$id] = $t; } catch (\Throwable $e) {}
        }
        try {
            if (Schema::hasTable('tests')) {
                $row = DB::table('tests')->where('id',$id)->first();
                if ($row) return $this->testCache[$id] = $this->normalizeRecordToNamePrice($row,'tests');
            }
            if (Schema::hasTable('lab_tests')) {
                $row = DB::table('lab_tests')->where('id',$id)->first();
                if ($row) return $this->testCache[$id] = $this->normalizeRecordToNamePrice($row,'lab_tests');
            }
        } catch (\Throwable $e) {}
        return $this->testCache[$id] = null;
    }

    protected function getTestBySlugOrName(string $service)
    {
        $key = 'slugname:' . strtolower($service);
        if (isset($this->testCache[$key])) return $this->testCache[$key];
        $svc = trim(strtolower($service));
        if (class_exists(\App\Models\Test::class)) {
            try { $t = \App\Models\Test::whereRaw('LOWER(slug)=?',[$svc])->orWhereRaw('LOWER(name)=?',[$svc])->first(); if ($t) return $this->testCache[$key] = $t; } catch (\Throwable $e) {}
        }
        if (class_exists(\App\Models\LabTest::class)) {
            try { $t = \App\Models\LabTest::whereRaw('LOWER(slug)=?',[$svc])->orWhereRaw('LOWER(name)=?',[$svc])->first(); if ($t) return $this->testCache[$key] = $t; } catch (\Throwable $e) {}
        }
        try {
            if (Schema::hasTable('tests')) {
                $row = DB::table('tests')->whereRaw('LOWER(slug)=?',[$svc])->orWhereRaw('LOWER(name)=?',[$svc])->first();
                if ($row) return $this->testCache[$key] = $this->normalizeRecordToNamePrice($row,'tests');
            }
            if (Schema::hasTable('lab_tests')) {
                $row = DB::table('lab_tests')->whereRaw('LOWER(slug)=?',[$svc])->orWhereRaw('LOWER(name)=?',[$svc])->first();
                if ($row) return $this->testCache[$key] = $this->normalizeRecordToNamePrice($row,'lab_tests');
            }
        } catch (\Throwable $e) {}
        return $this->testCache[$key] = null;
    }

    protected function getPackageByName(string $service)
    {
        $key = 'pkgname:' . strtolower($service);
        if (isset($this->packageCache[$key])) return $this->packageCache[$key];
        $svc = trim(strtolower($service));
        if (class_exists(\App\Models\Package::class)) {
            try { $p = \App\Models\Package::whereRaw('LOWER(slug)=?',[$svc])->orWhereRaw('LOWER(name)=?',[$svc])->first(); if ($p) return $this->packageCache[$key] = $p; } catch (\Throwable $e) {}
        }
        try {
            if (Schema::hasTable('packages')) {
                $row = DB::table('packages')->whereRaw('LOWER(slug)=?',[$svc])->orWhereRaw('LOWER(name)=?',[$svc])->first();
                if ($row) return $this->packageCache[$key] = $this->normalizeRecordToNamePrice($row,'packages');
            }
        } catch (\Throwable $e) {}
        return $this->packageCache[$key] = null;
    }

    protected function normalizeRecordToNamePrice($row, $table = null)
    {
        if (!$row) return null;
        if (is_object($row) && (property_exists($row,'name') || isset($row->name))) {
            $name  = $row->name ?? ($row->title ?? null);
            $price = $this->extractPriceFromRow($row);
            $obj   = new \stdClass();
            $obj->name  = $name ?? ('#' . ($row->id ?? ''));
            $obj->price = $price;
            return $obj;
        }
        if (is_array($row)) {
            $name  = $row['name'] ?? $row['title'] ?? null;
            $price = $this->extractPriceFromRow((object)$row);
            $obj   = new \stdClass();
            $obj->name  = $name ?? ('#' . ($row['id'] ?? ''));
            $obj->price = $price;
            return $obj;
        }
        $obj = new \stdClass();
        $obj->name  = $row->name ?? $row->title ?? ('#' . ($row->id ?? ''));
        $obj->price = $this->extractPriceFromRow($row);
        return $obj;
    }

    protected function extractPriceFromRow($row)
    {
        $candidates = ['price','amount','test_price','package_price','cost','fee','mrp','discounted_price','total_price'];
        foreach ($candidates as $col) {
            if (is_object($row) && (isset($row->{$col}) || property_exists($row,$col))) {
                $v = $row->{$col} ?? null;
                if (is_numeric($v)) return (float)$v;
            } elseif (is_array($row) && array_key_exists($col,$row) && is_numeric($row[$col])) {
                return (float)$row[$col];
            }
        }
        return 0.0;
    }

    // ---------- partition & doctrine helpers ----------

    protected function partitionAlphaNumeric(array $cols): array
    {
        $numericCols = [];
        $alphaCols   = [];

        foreach ($cols as $c) {
            $type = strtolower((string)($this->doctrineTypes[$c] ?? ''));
            $isNumeric =
                ($type !== '' ? $this->isNumericDoctrineType($type) : $this->isLikelyNumericByName($c));

            // Dates/times should stay in alpha group (not "numbers")
            if (in_array($type, ['date','datetime','datetimetz','time','date_immutable','datetime_immutable','time_immutable'], true)) {
                $isNumeric = false;
            }

            if ($isNumeric) $numericCols[] = $c;
            else            $alphaCols[]   = $c;
        }

        return [$alphaCols, $numericCols];
    }

    protected function getDoctrineTypesFor(string $table): array
    {
        $out = [];
        try {
            if (!method_exists(Schema::getConnection(), 'getDoctrineSchemaManager')) {
                return $out;
            }
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            if (method_exists(Schema::getConnection()->getDoctrineConnection(), 'getDatabasePlatform')) {
                Schema::getConnection()->getDoctrineConnection()->getDatabasePlatform();
            }
            $columns = $sm->listTableColumns($table);
            foreach ($columns as $name => $col) {
                $out[$col->getName()] = $col->getType()->getName();
            }
        } catch (\Throwable $e) {
            // silently ignore; heuristics will be used
        }
        return $out;
    }

    protected function isNumericDoctrineType(string $type): bool
    {
        return in_array($type, [
            'integer','bigint','smallint','decimal','float','double','real','boolean'
        ], true);
    }

    protected function isLikelyNumericByName(string $col): bool
    {
        $c = strtolower($col);
        $numericHints = [
            'id','_id','price','amount','total','subtotal','grand_total','discount','fee','charge','mrp',
            'qty','quantity','count','number','score','rate','percent','percentage','tax','age','year',
            'otp','pincode','zipcode','zip','lat','lng','latitude','longitude','weight','height',
            'phone','mobile','contact'
        ];
        foreach ($numericHints as $hint) {
            if ($c === $hint || str_ends_with($c, $hint) || str_contains($c, $hint)) {
                return true;
            }
        }
        return false;
    }
}
