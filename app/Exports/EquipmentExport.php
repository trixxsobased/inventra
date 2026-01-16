<?php

namespace App\Exports;

use App\Models\Equipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EquipmentExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Equipment::with('category')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Alat',
            'Nama Alat',
            'Kategori',
            'Stok',
            'Lokasi',
            'Kondisi',
            'Harga',
            'Tahun Beli',
            'Vendor',
            'Deskripsi',
        ];
    }

    public function map($equipment): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $equipment->code,
            $equipment->name,
            $equipment->category->name ?? '-',
            $equipment->stock,
            $equipment->location ?? '-',
            ucfirst($equipment->condition),
            $equipment->price ? 'Rp ' . number_format($equipment->price, 0, ',', '.') : '-',
            $equipment->purchase_year ?? '-',
            $equipment->vendor ?? '-',
            $equipment->description ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '4F46E5'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
