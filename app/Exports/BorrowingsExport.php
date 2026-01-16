<?php

namespace App\Exports;

use App\Models\Borrowing;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BorrowingsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $dateFrom;
    protected $dateTo;
    protected $status;

    public function __construct($dateFrom = null, $dateTo = null, $status = null)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->status = $status;
    }

    public function query()
    {
        $query = Borrowing::with(['user', 'equipment', 'verifiedBy']);

        if ($this->dateFrom) {
            $query->whereDate('borrow_date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('borrow_date', '<=', $this->dateTo);
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Pinjam',
            'Tanggal Kembali (Rencana)',
            'Tanggal Kembali (Aktual)',
            'Peminjam',
            'Alat',
            'Kode Alat',
            'Tujuan',
            'Status',
            'Kondisi Kembali',
            'Diverifikasi Oleh',
            'Catatan',
        ];
    }

    public function map($borrowing): array
    {
        static $no = 0;
        $no++;

        $statusLabels = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'borrowed' => 'Dipinjam',
            'returned' => 'Dikembalikan',
        ];

        return [
            $no,
            $borrowing->borrow_date->format('d/m/Y'),
            $borrowing->planned_return_date->format('d/m/Y'),
            $borrowing->actual_return_date ? $borrowing->actual_return_date->format('d/m/Y') : '-',
            $borrowing->user->name ?? '-',
            $borrowing->equipment->name ?? '-',
            $borrowing->equipment->code ?? '-',
            $borrowing->purpose ?? '-',
            $statusLabels[$borrowing->status] ?? $borrowing->status,
            $borrowing->return_condition ? ucfirst($borrowing->return_condition) : '-',
            $borrowing->verifiedBy->name ?? '-',
            $borrowing->notes ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '059669'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
