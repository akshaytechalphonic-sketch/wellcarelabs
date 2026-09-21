<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DeleteOldReports extends Command
{
    protected $signature = 'reports:delete-old';
    protected $description = 'Delete reports older than 60 days (files + database)';

    public function handle(): int
    {
        $cutoff = Carbon::now()->subDays(60);

        $reports = Report::where('created_at', '<', $cutoff)->get();

        $count = 0;

        foreach ($reports as $report) {

            // delete pdf
            if ($report->report_file && Storage::disk('public')->exists($report->report_file)) {
                Storage::disk('public')->delete($report->report_file);
            }

            // delete db row
            $report->delete();
            $count++;
        }

        $this->info("Deleted {$count} old reports.");

        return Command::SUCCESS;
    }
}
