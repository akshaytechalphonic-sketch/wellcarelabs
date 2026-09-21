<?php
// app/Console/Commands/SyncCouponStatuses.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncCouponStatuses extends Command
{
    protected $signature = 'coupons:sync-status {--dry : Show what would change, but don\'t update}';
    protected $description = 'Recompute coupon is_active in LOCAL time (IST) using a single idempotent UPDATE';

    public function handle(): int
    {
        $row = DB::selectOne("SELECT NOW() AS db_now_local, UTC_TIMESTAMP() AS db_now_utc");
        $this->info("DB NOW() (local): {$row->db_now_local} | DB UTC: {$row->db_now_utc}");

        if ($this->option('dry')) {
            $wouldOn = DB::select("
                SELECT id, code, starts_at, expires_at
                FROM coupons
                WHERE (starts_at IS NULL OR starts_at <= NOW())
                  AND (expires_at IS NULL OR expires_at >= NOW())
                ORDER BY id DESC
                LIMIT 200
            ");
            $wouldOff = DB::select("
                SELECT id, code, starts_at, expires_at
                FROM coupons
                WHERE NOT (
                        (starts_at IS NULL OR starts_at <= NOW())
                    AND (expires_at IS NULL OR expires_at >= NOW())
                )
                ORDER BY id DESC
                LIMIT 200
            ");

            $this->line('');
            $this->info('Would set is_active = 1: '.count($wouldOn));
            foreach ($wouldOn as $r) $this->line("  #{$r->id} {$r->code} | {$r->starts_at} → {$r->expires_at}");

            $this->line('');
            $this->info('Would set is_active = 0: '.count($wouldOff));
            foreach ($wouldOff as $r) $this->line("  #{$r->id} {$r->code} | {$r->starts_at} → {$r->expires_at}");

            $this->comment('Dry run: no updates executed.');
            return self::SUCCESS;
        }

        // Idempotent recompute for ALL rows using LOCAL time
        $updated = DB::update("
            UPDATE coupons
            SET
              is_active = CASE
                  WHEN ( (starts_at IS NULL OR starts_at <= NOW())
                         AND (expires_at IS NULL OR expires_at >= NOW()) )
                  THEN 1 ELSE 0
              END,
              updated_at = NOW()
        ");

        $this->info("Rows updated: {$updated}");
        return self::SUCCESS;
    }
}
