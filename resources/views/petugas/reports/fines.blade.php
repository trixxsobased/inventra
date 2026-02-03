@extends('admin.reports.template')

@section('report-title', $title)

@section('report-content')
<div class="mb-4">
    <h5>Laporan Denda Keterlambatan</h5>
    <p class="text-muted mb-0">
        Periode: <strong>{{ $periodText ?? 'Semua Data' }}</strong>
    </p>
    <p class="text-muted">Total Denda: <strong>{{ $data->count() }}</strong></p>
</div>

<table class="table table-bordered table-sm">
    <thead class="table-light">
        <tr>
            <th width="5%">No</th>
            <th width="15%">Tanggal</th>
            <th width="20%">Peminjam</th>
            <th width="25%">Alat</th>
            <th width="10%">Terlambat</th>
            <th width="15%">Jumlah Denda</th>
            <th width="10%">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $fine)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $fine->created_at->format('d/m/Y') }}</td>
                <td>
                    {{ $fine->borrowing->user->name }}<br>
                    <small class="text-muted">{{ $fine->borrowing->user->email }}</small>
                </td>
                <td>
                    {{ $fine->borrowing->equipment->name }}<br>
                    <small class="text-muted"><code>{{ $fine->borrowing->equipment->code }}</code></small>
                </td>
                <td class="text-center">
                    <span class="badge bg-danger">{{ $fine->days_late }} hari</span>
                </td>
                <td class="text-end">
                    <strong>Rp {{ number_format($fine->amount, 0, ',', '.') }}</strong>
                </td>
                <td class="text-center">
                    @if($fine->is_paid)
                        <span class="badge bg-success">Lunas</span>
                    @else
                        <span class="badge bg-warning text-dark">Belum Bayar</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-3 text-muted">
                    Tidak ada denda dalam periode ini
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot class="table-light">
        <tr>
            <th colspan="5" class="text-end">Total Denda:</th>
            <th class="text-end"><strong>Rp {{ number_format($data->sum('amount'), 0, ',', '.') }}</strong></th>
            <th></th>
        </tr>
        <tr>
            <th colspan="5" class="text-end">Yang Sudah Dibayar:</th>
            <th class="text-end"><strong>Rp {{ number_format($data->where('is_paid', true)->sum('amount'), 0, ',', '.') }}</strong></th>
            <th></th>
        </tr>
        <tr>
            <th colspan="5" class="text-end">Yang Belum Dibayar:</th>
            <th class="text-end text-danger"><strong>Rp {{ number_format($data->where('is_paid', false)->sum('amount'), 0, ',', '.') }}</strong></th>
            <th></th>
        </tr>
    </tfoot>
</table>

<div class="mt-4">
    <h6>Informasi:</h6>
    <ul class="small text-muted">
        <li>Denda dihitung berdasarkan keterlambatan pengembalian alat</li>
        <li>Rate denda: Rp 5.000 per hari keterlambatan</li>
        <li>Status "Lunas" menandakan denda sudah dibayar oleh peminjam</li>
    </ul>
</div>
@endsection
