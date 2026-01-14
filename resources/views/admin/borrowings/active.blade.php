@extends('layouts.app')

@section('title', 'Pengembalian Alat')
@section('page-title', 'Daftar Peminjaman Aktif')

@section('content')
<div class="page-heading">
    <h3>Peminjaman Aktif (Perlu Dikembalikan)</h3>
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
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Deadline Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $borrowing)
                            @php
                               $daysLeft = now()->diffInDays($borrowing->planned_return_date, false);
                                $isLate = $daysLeft < 0;
                            @endphp
                            <tr class="{{ $isLate ? 'table-danger' : '' }}">
                                <td>
                                    <strong>{{ $borrowing->user->name }}</strong><br>
                                    <small class="text-muted">{{ $borrowing->user->email }}</small>
                                </td>
                                <td>
                                    <strong>{{ $borrowing->equipment->name }}</strong><br>
                                    <small class="text-muted">{{ $borrowing->equipment->code }}</small>
                                </td>
                                <td>{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                                <td>
                                    {{ $borrowing->planned_return_date->format('d/m/Y') }}
                                    <br>
                                    @if($isLate)
                                        <span class="badge bg-danger">Terlambat {{ abs($daysLeft) }} hari</span>
                                    @elseif($daysLeft == 0)
                                        <span class="badge bg-warning">Hari ini</span>
                                    @else
                                        <span class="badge bg-success">{{ $daysLeft }} hari lagi</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">Dipinjam</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.borrowings.return', $borrowing->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-arrow-return-left"></i> Proses Kembali
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="bi bi-check-circle fs-2 text-muted"></i>
                                    <p class="text-muted mt-2">Tidak ada peminjaman aktif</p>
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
