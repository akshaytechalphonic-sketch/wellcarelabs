<?php

namespace App\Exports;

use App\Models\Appointment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $reports;

    /**
     * Row counter for "Sr No" column.
     *
     * @var int
     */
    protected int $rowIndex = 0;

    /**
     * @param \Illuminate\Support\Collection|array $reports
     */
    public function __construct($reports)
    {
        $this->reports = $reports instanceof Collection
            ? $reports
            : collect($reports);
    }

    /**
     * Data source for export.
     */
    public function collection()
    {
        // Just return the raw reports; mapping will format the row.
        return $this->reports->values();
    }

    /**
     * Headings for the first row of the sheet.
     */
    public function headings(): array
    {
        return [
            'Sr No',
            'Patient Name',
            'Test Name',
            'Report Date',
            'Mobile',
        ];
    }

    /**
     * How each row is mapped into the sheet.
     *
     * @param  mixed  $report
     * @return array
     */
    public function map($report): array
    {
        $this->rowIndex++;

        // 🔗 get linked appointment by report_id to pull mobile
        $appointment = Appointment::where('report_id', $report->id)->first();

        $mobile = '';
        if ($appointment) {
            $mobile =
                ($appointment->mobile_with_country ?? null) ?:
                ($appointment->mobile ?? null) ?:
                ($appointment->phone ?? null) ?:
                ($appointment->whatsapp_number ?? null) ?:
                '';
        }

        // Format date as d-m-Y for nicer human reading
        $reportDate = '';
        if (!empty($report->report_date)) {
            try {
                $reportDate = \Carbon\Carbon::parse($report->report_date)->format('d-m-Y');
            } catch (\Throwable $e) {
                $reportDate = (string) $report->report_date;
            }
        }

        return [
            $this->rowIndex,                 // Sr No
            $report->patient_id ?? '',       // Patient ID (if exists on reports table)
            $report->test_name ?? '',        // Test Name
            $reportDate,                     // Report Date
            $mobile,                         // Mobile (from appointments)
        ];
    }
}
