@extends('layouts.app')

@section('title', 'Beranda Petugas')
@section('page-title', 'Beranda')

@section('content')
<div class="page-heading">
    <h3>Beranda Petugas</h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon green mb-2">
                                        <i class="bi bi-clipboard-check"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Pending</h6>
                                    <h6 class="font-extrabold mb-0">{{ $stats['pending_requests'] ?? 0 }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon blue mb-2">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Sedang Dipinjam</h6>
                                    <h6 class="font-extrabold mb-0">{{ $stats['active_borrowings'] ?? 0 }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon purple mb-2">
                                        <i class="bi bi-arrow-return-left"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Kembali Hari Ini</h6>
                                    <h6 class="font-extrabold mb-0">{{ $stats['returned_today'] ?? 0 }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon red mb-2">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Terlambat</h6>
                                    <h6 class="font-extrabold mb-0">{{ $stats['overdue_count'] ?? 0 }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pending Borrowings -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Peminjaman Menunggu Persetujuan</h4>
                            <a href="{{ route('petugas.borrowings.pending') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Peminjam</th>
                                            <th>Alat</th>
                                            <th>Tgl Pinjam</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pending_borrowings ?? [] as $borrowing)
                                            <tr>
                                                <td>{{ $borrowing->user->name }}</td>
                                                <td>{{ $borrowing->equipment->name }}</td>
                                                <td>{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                                                <td>
                                                    <a href="{{ route('petugas.borrowings.pending') }}" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-check-circle"></i> Proses
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Tidak ada peminjaman pending</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Active Borrowings -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Peminjaman Aktif (Perlu Dikembalikan)</h4>
                            <a href="{{ route('petugas.borrowings.active') }}" class="btn btn-sm btn-outline-info">Lihat Semua</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Peminjam</th>
                                            <th>Alat</th>
                                            <th>Deadline</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($active_borrowings ?? [] as $borrowing)
                                            @php
                                                $plannedDate = $borrowing->planned_return_date->startOfDay();
                                                $today = now()->startOfDay();
                                                $diff = (int) $today->diffInDays($plannedDate, false);
                                                
                                                // If diff is 0, it is today.
                                                // If diff is -1, it is late 1 day.
                                                // If diff is 1, it is 1 day left.
                                                
                                                $isLate = $diff < 0;
                                                $isToday = $diff === 0;
                                                $daysLate = abs($diff);
                                                $daysLeft = abs($diff);
                                            @endphp
                                            <tr class="align-middle">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="fw-bold">{{ $borrowing->user->name }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $borrowing->equipment->name }}</td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span>{{ $borrowing->planned_return_date->format('d/m/Y') }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($isLate)
                                                        <span class="badge bg-light-danger text-danger">Terlambat {{ $daysLate }} hari</span>
                                                    @elseif($isToday)
                                                        <span class="badge bg-light-warning text-warning">Hari Ini</span>
                                                    @else
                                                        <span class="badge bg-light-info text-info">{{ $daysLeft }} hari lagi</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Tidak ada peminjaman aktif</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h4>Aksi Cepat</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('petugas.borrowings.pending') }}" class="btn btn-warning d-flex align-items-center text-start p-3">
                            <i class="bi bi-clipboard-check me-3 fs-4 flex-shrink-0"></i> 
                            <span class="lh-sm fw-semibold">Verifikasi Pinjaman</span>
                        </a>
                        <a href="{{ route('petugas.borrowings.active') }}" class="btn btn-info text-white d-flex align-items-center text-start p-3">
                            <i class="bi bi-arrow-return-left me-3 fs-4 flex-shrink-0"></i> 
                            <span class="lh-sm fw-semibold">Proses Pengembalian</span>
                        </a>
                        <a href="{{ route('petugas.reports.index') }}" class="btn btn-success d-flex align-items-center text-start p-3">
                            <i class="bi bi-file-earmark-text me-3 fs-4 flex-shrink-0"></i> 
                            <span class="lh-sm fw-semibold">Lihat Laporan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
