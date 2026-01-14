@extends('layouts.app')

@section('title', 'Beranda Admin')
@section('page-title', 'Beranda')

@section('content')
<div class="page-heading">
    <h3>Beranda Admin</h3>
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
                                    <div class="stats-icon purple mb-2">
                                        <i class="bi bi-box"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Total Alat</h6>
                                    <h6 class="font-extrabold mb-0">{{ $stats['total_equipment'] ?? 0 }}</h6>
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
                                    <div class="stats-icon red mb-2">
                                        <i class="bi bi-cash"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Denda Belum Lunas</h6>
                                    <h6 class="font-extrabold mb-0">{{ $stats['unpaid_fines'] ?? 0 }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Aktivitas Terbaru</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Peminjam</th>
                                            <th>Alat</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recent_borrowings ?? [] as $borrowing)
                                            <tr>
                                                <td>{{ $borrowing->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $borrowing->user->name }}</td>
                                                <td>{{ $borrowing->equipment->name }}</td>
                                                <td>
                                                    @if($borrowing->status === 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($borrowing->status === 'borrowed')
                                                        <span class="badge bg-info">Dipinjam</span>
                                                    @elseif($borrowing->status === 'returned')
                                                        <span class="badge bg-success">Dikembalikan</span>
                                                    @else
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.borrowings.show', $borrowing->id) }}" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Belum ada aktivitas</td>
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
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.equipment.index') }}" class="btn btn-primary">
                            <i class="bi bi-box"></i> Kelola Alat
                        </a>
                        <a href="{{ route('admin.borrowings.pending') }}" class="btn btn-warning">
                            <i class="bi bi-clipboard-check"></i> Verifikasi Pinjaman
                        </a>
                        <a href="{{ route('admin.borrowings.active') }}" class="btn btn-info text-white">
                            <i class="bi bi-arrow-return-left"></i> Proses Pengembalian
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-text"></i> Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
            
            
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Stok Menipis</h4>
                </div>
                <div class="card-body">
                    @forelse($low_stock_equipment ?? [] as $equipment)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $equipment->name }}</h6>
                                <small class="text-muted">Stok: {{ $equipment->stock }}</small>
                            </div>
                            <span class="badge bg-danger">Low</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Semua stok aman</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
