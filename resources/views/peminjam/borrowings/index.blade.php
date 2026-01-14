@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat Peminjaman Saya')

@section('content')
<div class="page-heading">
    <h3>Riwayat Peminjaman</h3>
</div>

<div class="page-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Rencana Kembali</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $borrowing)
                            <tr>
                                <td>
                                    <strong>{{ $borrowing->equipment->name }}</strong><br>
                                    <small class="text-muted">{{ $borrowing->equipment->code }}</small>
                                </td>
                                <td>{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}</td>
                                <td>{{ $borrowing->planned_return_date->format('d/m/Y') }}</td>
                                <td>{{ $borrowing->actual_return_date ? $borrowing->actual_return_date->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($borrowing->status === 'pending')
                                        <span class="badge bg-warning">Menunggu Verifikasi</span>
                                    @elseif($borrowing->status === 'approved')
                                        <span class="badge bg-info">Disetujui</span>
                                    @elseif($borrowing->status === 'borrowed')
                                        @php
                                            $daysLeft = now()->diffInDays($borrowing->planned_return_date, false);
                                        @endphp
                                        @if($daysLeft < 0)
                                            <span class="badge bg-danger">Terlambat {{ abs($daysLeft) }} hari</span>
                                        @else
                                            <span class="badge bg-info">Dipinjam</span>
                                        @endif
                                    @elseif($borrowing->status === 'returned')
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($borrowing->fine)
                                        @if($borrowing->fine->is_paid)
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-danger">
                                                Rp {{ number_format($borrowing->fine->amount, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="bi bi-inbox fs-2 text-muted"></i>
                                    <p class="text-muted mt-2">Belum ada riwayat peminjaman</p>
                                    <a href="{{ route('equipment.browse') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-search"></i> Cari Alat
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $borrowings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
