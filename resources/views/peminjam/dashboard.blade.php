@extends('layouts.app')

@section('title', 'Beranda')
@section('page-title', 'Beranda')

@section('content')
<div class="page-heading">
    <h3>Selamat Datang, {{ auth()->user()->name }}!</h3>
</div>

<div class="page-content">
    
    <section class="row mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2">
                                <i class="bi bi-clock-history"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Sedang Dipinjam</h6>
                            <h6 class="font-extrabold mb-0">{{ $stats['active_borrowings'] ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 d-flex justify-content-start">
                            <div class="stats-icon green mb-2">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Selesai</h6>
                            <h6 class="font-extrabold mb-0">{{ $stats['completed_borrowings'] ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Menunggu Verifikasi</h6>
                            <h6 class="font-extrabold mb-0">{{ $stats['pending_borrowings'] ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 d-flex justify-content-start">
                            <div class="stats-icon red mb-2">
                                <i class="bi bi-cash"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Denda Aktif</h6>
                            <h6 class="font-extrabold mb-0">Rp {{ number_format($stats['total_fines'] ?? 0, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    
    <section class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm" style="background: linear-gradient(135deg, #2ecc71 0%, #16a085 100%); color: white; border: none;">
                <div class="card-body py-5 text-center">
                    <i class="bi bi-box-seam" style="font-size: 3rem; opacity: 0.9;"></i>
                    <h3 class="text-white mb-3 mt-3">Butuh Alat untuk Kegiatan Anda?</h3>
                    <p class="mb-4" style="font-size: 1.1rem; opacity: 0.95;">Cari dan pinjam alat yang Anda butuhkan dengan mudah</p>
                    <a href="{{ route('equipment.browse') }}" class="btn btn-light btn-lg px-5 py-3" style="color: #16a085; font-weight: 600;">
                        <i class="bi bi-search me-2"></i> Cari Alat Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    
    <section class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0 card-title"><i class="bi bi-list-check me-2"></i>Peminjaman Aktif Saya</h4>
                    <a href="{{ route('borrowings.index') }}" class="btn btn-primary rounded-pill btn-sm px-4">
                        <i class="bi bi-eye me-1"></i> Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-body-tertiary">
                                <tr>
                                    <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Alat</th>
                                    <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Tanggal Pinjam</th>
                                    <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Tanggal Kembali</th>
                                    <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Status</th>
                                    <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Sisa Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($active_borrowings ?? [] as $borrowing)
                                    <tr>
                                        <td class="px-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar avatar-lg bg-secondary bg-opacity-10 text-primary d-flex align-items-center justify-content-center rounded-3 p-2">
                                                    <i class="bi bi-tools fs-4"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $borrowing->equipment->name }}</h6>
                                                    <small class="text-muted">{{ $borrowing->equipment->code }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4">{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}</td>
                                        <td class="px-4">{{ $borrowing->planned_return_date->format('d/m/Y') }}</td>
                                        <td class="px-4">
                                            @if($borrowing->status === 'pending')
                                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">
                                                    <i class="bi bi-clock me-1"></i> Menunggu
                                                </span>
                                            @elseif($borrowing->status === 'borrowed')
                                                <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2">
                                                    <i class="bi bi-box-arrow-down me-1"></i> Dipinjam
                                                </span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                                    <i class="bi bi-check-circle me-1"></i> {{ ucfirst($borrowing->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4">
                                            @php
                                                $daysLeftRaw = now()->diffInDays($borrowing->planned_return_date, false);
                                                $daysLeft = ceil($daysLeftRaw);
                                            @endphp
                                            
                                            @if($daysLeft < 0)
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">
                                                    <i class="bi bi-exclamation-triangle me-1"></i> Telat {{ abs($daysLeft) }} hari
                                                </span>
                                            @elseif($daysLeft == 0)
                                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">
                                                    <i class="bi bi-calendar-event me-1"></i> Hari Ini
                                                </span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                                    <i class="bi bi-check-circle me-1"></i> {{ $daysLeft }} hari lagi
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                                                <h6 class="text-muted mb-2">Belum ada peminjaman aktif</h6>
                                                <a href="{{ route('equipment.browse') }}" class="btn btn-primary rounded-pill btn-sm px-4 mt-2">
                                                    <i class="bi bi-plus-lg me-1"></i> Pinjam Alat
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
