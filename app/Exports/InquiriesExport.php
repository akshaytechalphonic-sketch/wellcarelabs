<?php

namespace App\Exports;

use App\Models\Inquiry;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InquiriesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected ?string $q;
    protected ?Carbon $dateFrom;
    protected ?Carbon $dateTo;
    protected int $rowIndex = 0;

    public function __construct(?string $q, ?Carbon $dateFrom, ?Carbon $dateTo)
    {
        $this->q        = $q ? trim((string) $q) : null;
        $this->dateFrom = $dateFrom;
        $this->dateTo   = $dateTo;
    }

    public function collection(): Collection
    {
        $q = $this->q ?? '';

        $statuses = ['Pending', 'Called', 'Interested', 'Booked', 'Lost'];
        $isStatusQuery = $q !== '' && in_array(strtolower($q), array_map('strtolower', $statuses), true);

        $query = Inquiry::query()
            ->when($isStatusQuery, function ($query) use ($q) {
                $query->whereRaw('LOWER(status) = ?', [strtolower($q)]);
            }, function ($query) use ($q) {
                if ($q === '') {
                    return;
                }
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('message', 'like', "%{$q}%")
                        ->orWhere('status', 'like', "%{$q}%");
                });
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->orderBy('id', 'desc');

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Sr.No',
            'Name',
            'Email',
            'Phone',
            'Date',
            'Time',
            'Message',
            'Prescription',
            'Status',
        ];
    }

    public function map($inquiry): array
    {
        $this->rowIndex++;

        $created = $inquiry->created_at instanceof Carbon
            ? $inquiry->created_at->copy()->timezone('Asia/Kolkata')
            : Carbon::parse($inquiry->created_at)->timezone('Asia/Kolkata');

        // Style B: proper case for status
        $statusRaw = (string)($inquiry->status ?? '');
        $status    = $statusRaw !== ''
            ? ucwords(strtolower($statusRaw))
            : 'Pending';

        $prescriptionUrl = $inquiry->prescription 
            ? url('storage/' . $inquiry->prescription) 
            : '';

        return [
            $this->rowIndex,
            $inquiry->name ?? '',
            $inquiry->email ?? '',
            $inquiry->phone ?? '',
            $created->format('d/m/Y'),
            $created->format('h:i A'),
            $inquiry->message ?? '',
            $prescriptionUrl,
            $status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);

        // Auto-width
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Wrap the Message column
        $sheet->getStyle('G')->getAlignment()->setWrapText(true);

        return [];
    }
}
