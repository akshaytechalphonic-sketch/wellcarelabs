<?php

namespace App\Exports;

use App\Models\Appointment;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HospitalAppointmentsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $appointments;

    public function __construct($appointments)
    {
        $this->appointments = $appointments;
    }

    public function collection()
    {
        return $this->appointments->map(function ($a) {
            return [
                'Patient Name' => $a->name,
                'Date'         => $a->date,
                'Time'         => $a->time_slot,
                'Service'      => $a->service,
                'Status'       => ucfirst($a->status),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Patient Name',
            'Date',
            'Time',
            'Service',
            'Status',
        ];
    }
}
