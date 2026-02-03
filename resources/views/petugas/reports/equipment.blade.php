@extends('admin.reports.template')

@section('report-title', $title)

@section('report-content')
<div style="margin-bottom: 20px; text-align: center;">
    <p style="font-size: 11pt; margin: 0;"><strong>Periode:</strong> {{ $periodText ?? 'Semua Data' }}</p>
    <p style="font-size: 11pt; margin: 0;"><strong>Total Alat:</strong> {{ $data->count() }} item</p>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 15%;">Kode Aset</th>
            <th style="width: 30%;">Nama Alat</th>
            <th style="width: 25%;">Kategori / Jurusan</th>
            <th style="width: 15%;">Lokasi</th>
            <th style="width: 10%;">Stok</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $equipment)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center;"><strong>{{ $equipment->code }}</strong></td>
                <td>{{ $equipment->name }}</td>
                <td>{{ $equipment->category->name ?? '-' }}</td>
                <td style="text-align: center;">{{ $equipment->location ?? 'Lab/Bengkel' }}</td>
                <td style="text-align: center;">
                    @if($equipment->stock == 0)
                        <span style="color: red; font-weight: bold;">HABIS</span>
                    @elseif($equipment->stock <= 5)
                        <span style="color: orange; font-weight: bold;">{{ $equipment->stock }} Unit</span>
                    @else
                        <strong>{{ $equipment->stock }} Unit</strong>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; color: #666;">
                    Tidak ada data alat inventaris
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr style="background-color: #f0f0f0;">
            <th colspan="5" style="text-align: right; padding: 8px;">Total Stok Keseluruhan:</th>
            <th style="text-align: center;">{{ $data->sum('stock') }} Unit</th>
        </tr>
    </tfoot>
</table>

<div style="margin-top: 25px; padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9;">
    <h6 style="margin-top: 0;">Catatan Penting:</h6>
    <ul style="font-size: 10pt; margin-bottom: 0; line-height: 1.6;">
        <li>Laporan ini menampilkan seluruh inventaris alat yang terdaftar dalam sistem Inventra</li>
        <li>Stok ditampilkan dalam satuan unit (pcs)</li>
        <li>Status "<span style="color: red;">HABIS</span>" menandakan alat perlu segera direstock</li>
        <li>Kode aset mengikuti format: <strong>INV-[JURUSAN]-[TAHUN]-[ID]</strong></li>
        <li>Data valid per tanggal pencetakan tercantum di footer dokumen</li>
    </ul>
</div>
@endsection
