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
                                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $borrowing->id }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Detail Modal -->
                                            <div class="modal fade" id="detailModal{{ $borrowing->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Detail Peminjaman</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="fw-bold">Peminjam:</label>
                                                                <p>{{ $borrowing->user->name }} ({{ $borrowing->user->email }})</p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="fw-bold">Alat:</label>
                                                                <p>{{ $borrowing->equipment->name }} - {{ $borrowing->equipment->code }}</p>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6 mb-3">
                                                                    <label class="fw-bold">Tgl Pinjam:</label>
                                                                    <p>{{ $borrowing->borrow_date->format('d/m/Y') }}</p>
                                                                </div>
                                                                <div class="col-6 mb-3">
                                                                    <label class="fw-bold">Tgl Kembali:</label>
                                                                    <p>{{ $borrowing->planned_return_date->format('d/m/Y') }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="fw-bold">Status:</label>
                                                                <span class="badge bg-{{ $borrowing->status == 'borrowed' ? 'info' : ($borrowing->status == 'returned' ? 'success' : 'warning') }}">
                                                                    {{ ucfirst($borrowing->status) }}
                                                                </span>
                                                            </div>
                                                            @if($borrowing->purpose)
                                                            <div class="mb-3">
                                                                <label class="fw-bold">Tujuan:</label>
                                                                <p>{{ $borrowing->purpose }}</p>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                        <div class="d-flex align-items-center mb-3" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#stockModal{{ $equipment->id }}">
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-primary">{{ $equipment->name }}</h6>
                                <small class="text-muted">Stok: {{ $equipment->stock }}</small>
                            </div>
                            <span class="badge bg-danger">Low</span>
                        </div>

                        <!-- Stock Modal -->
                        <div class="modal fade" id="stockModal{{ $equipment->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Info Stok</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-center mb-3">
                                            @if($equipment->image)
                                                <img src="{{ Storage::url($equipment->image) }}" class="img-fluid rounded" style="max-height: 150px">
                                            @else
                                                <div class="bg-light p-3 rounded d-inline-block">
                                                    <i class="bi bi-box fs-1 text-secondary"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <h5>{{ $equipment->name }}</h5>
                                        <p class="text-muted mb-2">Kode: {{ $equipment->code }}</p>
                                        <div class="alert alert-danger py-2">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            Stok Tersisa: <strong>{{ $equipment->stock }}</strong>
                                        </div>
                                        <a href="{{ route('admin.equipment.index', ['search' => $equipment->code]) }}" class="btn btn-primary w-100 btn-sm">
                                            <i class="bi bi-box-arrow-in-right"></i> Kelola Stok
                                        </a>
                                    </div>
                                </div>
                            </div>
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
