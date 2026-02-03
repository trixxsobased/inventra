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
                
                
                @if(auth()->user()->role === 'admin')
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
                @endif
            </div>
            
            <!-- Charts Section -->
            <div class="row mb-4">
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Statistik Peminjaman (6 Bulan Terakhir)</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="borrowingsChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Status Peminjaman</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="statusChart" height="200"></canvas>
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
                    <div class="d-grid gap-3">
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.equipment.index') }}" class="btn btn-primary d-flex align-items-center text-start p-3">
                            <i class="bi bi-box me-3 fs-4 flex-shrink-0"></i> 
                            <span class="lh-sm fw-semibold">Kelola Alat</span>
                        </a>
                        @endif
                        <a href="{{ route('admin.borrowings.pending') }}" class="btn btn-warning d-flex align-items-center text-start p-3">
                            <i class="bi bi-clipboard-check me-3 fs-4 flex-shrink-0"></i> 
                            <span class="lh-sm fw-semibold">Verifikasi Pinjaman</span>
                        </a>
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.borrowings.active') }}" class="btn btn-info text-white d-flex align-items-center text-start p-3">
                            <i class="bi bi-arrow-return-left me-3 fs-4 flex-shrink-0"></i> 
                            <span class="lh-sm fw-semibold">Proses Pengembalian</span>
                        </a>
                        @endif
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-success d-flex align-items-center text-start p-3">
                            <i class="bi bi-file-earmark-text me-3 fs-4 flex-shrink-0"></i> 
                            <span class="lh-sm fw-semibold">Lihat Laporan</span>
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
                                        @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.equipment.index', ['search' => $equipment->code]) }}" class="btn btn-primary w-100 btn-sm">
                                            <i class="bi bi-box-arrow-in-right"></i> Kelola Stok
                                        </a>
                                        @endif
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get theme colors
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? '#334155' : '#e5e7eb';
    
    // Monthly Borrowings Chart
    const borrowingsCtx = document.getElementById('borrowingsChart').getContext('2d');
    new Chart(borrowingsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyBorrowings->pluck('month')) !!},
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: {!! json_encode($monthlyBorrowings->pluck('count')) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: textColor,
                        stepSize: 1
                    },
                    grid: {
                        color: gridColor
                    }
                },
                x: {
                    ticks: {
                        color: textColor
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Dipinjam', 'Dikembalikan', 'Ditolak'],
            datasets: [{
                data: [
                    {{ $borrowingStatus['pending'] ?? 0 }},
                    {{ $borrowingStatus['borrowed'] ?? 0 }},
                    {{ $borrowingStatus['returned'] ?? 0 }},
                    {{ $borrowingStatus['rejected'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(251, 191, 36, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgba(251, 191, 36, 1)',
                    'rgba(59, 130, 246, 1)',
                    'rgba(16, 185, 129, 1)',
                    'rgba(239, 68, 68, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: textColor,
                        padding: 15,
                        usePointStyle: true
                    }
                }
            },
            cutout: '60%'
        }
    });
});
</script>
@endpush

