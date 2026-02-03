@extends('admin.reports.template')

@section('report-title', $title)

@section('report-content')
<div class="mb-4">
    <h5>Laporan Data Peminjaman</h5>
    <p class="text-muted mb-0">
        Periode: <strong>{{ $periodText ?? 'Semua Data' }}</strong>
    </p>
    <p class="text-muted">Total Peminjaman: <strong>{{ $data->count() }}</strong></p>
</div>

<table class="table table-bordered table-sm">
    <thead class="table-light">
        <tr>
            <th width="5%">No</th>
            <th width="12%">Tanggal Pinjam</th>
            <th width="18%">Peminjam</th>
            <th width="22%">Alat</th>
            <th width="12%">Tgl Rencana Kembali</th>
            <th width="12%">Tgl Kembali</th>
            <th width="10%">Status</th>
            <th width="9%">Denda</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $borrowing)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}</td>
                <td>
                    {{ $borrowing->user->name }}<br>
                    <small class="text-muted">{{ $borrowing->user->email }}</small>
                </td>
                <td>
                    {{ $borrowing->equipment->name }}<br>
                    <small class="text-muted"><code>{{ $borrowing->equipment->code }}</code></small>
                </td>
                <td>{{ $borrowing->planned_return_date->format('d/m/Y') }}</td>
                <td>
                    @if($borrowing->actual_return_date)
                        {{ $borrowing->actual_return_date->format('d/m/Y') }}
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($borrowing->status === 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($borrowing->status === 'borrowed')
                        <span class="badge bg-info">Dipinjam</span>
                    @elseif($borrowing->status === 'returned')
                        <span class="badge bg-success">Dikembalikan</span>
                    @else
                        <span class="badge bg-danger">Ditolak</span>
                    @endif
                </td>
                <td class="text-end">
                    @if($borrowing->fine)
                        <span class="text-danger">Rp {{ number_format($borrowing->fine->amount, 0, ',', '.') }}</span>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-3 text-muted">
                    Tidak ada data peminjaman dalam periode ini
                </td>
            </tr>
        @endforelse
    </tbody>
    @if($data->count() > 0)
        <tfoot class="table-light">
            <tr>
                <th colspan="7" class="text-end">Total Transaksi:</th>
                <th class="text-center">{{ $data->count() }}</th>
            </tr>
            @php
                $totalFines = $data->filter(fn($b) => $b->fine)->sum(fn($b) => $b->fine->amount ?? 0);
            @endphp
            @if($totalFines > 0)
            <tr>
                <th colspan="7" class="text-end">Total Denda:</th>
                <th class="text-end text-danger">Rp {{ number_format($totalFines, 0, ',', '.') }}</th>
            </tr>
            @endif
        </tfoot>
    @endif
</table>

<div class="mt-4">
    <h6>Ringkasan Laporan:</h6>
    <table class="table table-sm" style="width: auto;">
        <tr>
            <td><strong>Total Peminjaman:</strong></td>
            <td>{{ $data->count() }} transaksi</td>
        </tr>
        <tr>
            <td><strong>Status Pending:</strong></td>
            <td>{{ $data->where('status', 'pending')->count() }} transaksi</td>
        </tr>
        <tr>
            <td><strong>Status Dipinjam:</strong></td>
            <td>{{ $data->where('status', 'borrowed')->count() }} transaksi</td>
        </tr>
        <tr>
            <td><strong>Status Dikembalikan:</strong></td>
            <td>{{ $data->where('status', 'returned')->count() }} transaksi</td>
        </tr>
        <tr>
            <td><strong>Status Ditolak:</strong></td>
            <td>{{ $data->where('status', 'rejected')->count() }} transaksi</td>
        </tr>
    </table>
</div>

<div class="mt-3">
    <h6>Catatan:</h6>
    <ul class="small text-muted">
        <li>Laporan ini menampilkan seluruh transaksi peminjaman dalam periode yang dipilih</li>
        <li>Status "Dipinjam" menandakan alat masih dalam masa peminjaman</li>
        <li>Denda dihitung otomatis berdasarkan keterlambatan pengembalian (Rp 5.000/hari)</li>
    </ul>
</div>
@endsection
