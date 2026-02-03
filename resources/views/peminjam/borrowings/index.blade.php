@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat Peminjaman Saya')

@section('content')
<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Riwayat Peminjaman</h3>
        <a href="{{ route('equipment.browse') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-1"></i> Pinjam Alat
        </a>
    </div>
</div>

<div class="page-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Alat</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Tanggal Pinjam</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Deadline</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Status</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Denda</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $borrowing)
                            @php
                                $daysLeft = now()->startOfDay()->diffInDays($borrowing->planned_return_date->startOfDay(), false);
                                $isLate = $daysLeft < 0;
                            @endphp
                            <tr class="{{ $borrowing->status === 'borrowed' && $isLate ? 'table-danger-soft' : '' }}">
                                <td class="px-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar avatar-lg bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center rounded-3 p-2">
                                            <i class="bi bi-tools fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $borrowing->equipment->name }}</h6>
                                            <small class="text-muted">{{ $borrowing->equipment->code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4">{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}</td>
                                <td class="px-4">
                                    <div class="d-flex flex-column">
                                        <span>{{ $borrowing->planned_return_date->format('d/m/Y') }}</span>
                                        @if($borrowing->status === 'borrowed')
                                            @if($isLate)
                                                <small class="text-danger fw-bold">Terlambat {{ abs($daysLeft) }} hari</small>
                                            @elseif($daysLeft == 0)
                                                <small class="text-warning fw-bold">Hari ini</small>
                                            @else
                                                <small class="text-success">{{ $daysLeft }} hari lagi</small>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4">
                                    @if($borrowing->status === 'pending')
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">
                                            <i class="bi bi-clock me-1"></i> Menunggu
                                        </span>
                                    @elseif($borrowing->status === 'borrowed')
                                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2">
                                            <i class="bi bi-box-arrow-right me-1"></i> Dipinjam
                                        </span>
                                    @elseif($borrowing->status === 'returned')
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle me-1"></i> Selesai
                                        </span>
                                    @elseif($borrowing->status === 'rejected')
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">
                                            <i class="bi bi-x-circle me-1"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4">
                                    @if($borrowing->fine)
                                        @if($borrowing->fine->is_paid)
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Lunas</span>
                                        @else
                                            <span class="text-danger fw-bold">
                                                Rp {{ number_format($borrowing->fine->amount, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-4">
                                    @if($borrowing->status === 'borrowed')
                                        <button type="button" 
                                                class="btn btn-primary rounded-pill btn-sm px-3 d-inline-flex align-items-center gap-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#returnRequestModal{{ $borrowing->id }}">
                                            <i class="bi bi-arrow-return-left"></i> Kembalikan
                                        </button>
                                    @elseif($borrowing->status === 'pending')
                                        <button type="button" 
                                                class="btn btn-danger rounded-pill btn-sm px-3 d-inline-flex align-items-center gap-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#cancelModal{{ $borrowing->id }}">
                                            <i class="bi bi-x-circle"></i> Batalkan
                                        </button>
                                    @elseif($borrowing->status === 'returned')
                                        <span class="text-muted small"><i class="bi bi-check-all me-1"></i> Selesai</span>
                                    @elseif($borrowing->status === 'rejected')
                                        @if($borrowing->rejection_reason)
                                            <button type="button" 
                                                    class="btn btn-outline-danger rounded-pill btn-sm px-3 d-inline-flex align-items-center gap-2" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectReasonModal{{ $borrowing->id }}">
                                                <i class="bi bi-info-circle"></i> Alasan
                                            </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                                        <h6 class="text-muted mb-2">Belum ada riwayat peminjaman</h6>
                                        <p class="text-muted mb-3 small">Mulai pinjam alat untuk kebutuhan praktik Anda</p>
                                        <a href="{{ route('equipment.browse') }}" class="btn btn-primary rounded-pill px-4">
                                            <i class="bi bi-search me-1"></i> Cari Alat
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($borrowings->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $borrowings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@foreach($borrowings as $borrowing)
    @php
        $daysLeft = $borrowing->planned_return_date ? now()->startOfDay()->diffInDays($borrowing->planned_return_date->startOfDay(), false) : 0;
        $isLate = $daysLeft < 0;
    @endphp

    <!-- Return Request Modal (Borrowed) -->
    @if($borrowing->status === 'borrowed')
        <div class="modal fade" id="returnRequestModal{{ $borrowing->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-arrow-return-left me-2 text-primary"></i>Konfirmasi Pengembalian
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-info border-0 d-flex align-items-center mb-4">
                            <i class="bi bi-info-circle fs-4 me-3"></i>
                            <div>
                                Anda akan mengembalikan alat ini. Pastikan alat dalam kondisi baik sebelum diserahkan.
                            </div>
                        </div>

                        <div class="bg-secondary bg-opacity-10 rounded-3 p-3 mb-3 border">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar bg-transparent p-2 rounded text-primary border">
                                    <i class="bi bi-tools fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $borrowing->equipment->name }}</h6>
                                    <small class="text-muted">{{ $borrowing->equipment->code }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="list-group list-group-flush mb-3">
                            <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                <span class="text-muted">Tanggal Pinjam</span>
                                <span class="fw-medium">{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                <span class="text-muted">Deadline</span>
                                <span class="fw-medium">{{ $borrowing->planned_return_date ? $borrowing->planned_return_date->format('d/m/Y') : '-' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                <span class="text-muted">Status</span>
                                @if($isLate)
                                    <span class="text-danger fw-bold">
                                        <i class="bi bi-exclamation-circle me-1"></i> Terlambat {{ abs($daysLeft) }} hari
                                    </span>
                                @else
                                    <span class="text-success fw-bold"><i class="bi bi-check-circle me-1"></i> Tepat waktu</span>
                                @endif
                            </div>
                            @if($isLate)
                            <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                <span class="text-muted">Estimasi Denda</span>
                                <span class="text-danger fw-bold">Rp {{ number_format(abs($daysLeft) * 5000, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="alert alert-warning border-0 mb-0">
                            <div class="d-flex">
                                <i class="bi bi-exclamation-triangle fs-5 me-2"></i>
                                <div>
                                    <strong>Instruksi:</strong><br>
                                    Silakan bawa alat ke ruang petugas/admin untuk verifikasi fisik.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Cancel Modal (Pending) -->
    @if($borrowing->status === 'pending')
        <div class="modal fade" id="cancelModal{{ $borrowing->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-danger">
                            <i class="bi bi-x-circle me-2"></i>Batalkan Peminjaman
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-4">Apakah Anda yakin ingin membatalkan permohonan peminjaman untuk alat ini?</p>
                        
                        <div class="bg-secondary bg-opacity-10 rounded-3 p-3 mb-2 border">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar bg-transparent p-2 rounded text-danger border">
                                    <i class="bi bi-tools fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $borrowing->equipment->name }}</h6>
                                    <small class="text-muted">{{ $borrowing->equipment->code }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Tidak, Kembali</button>
                        <form action="{{ route('borrowings.destroy', $borrowing->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger rounded-pill px-4">Ya, Batalkan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Rejection Reason Modal (Rejected) -->
    @if($borrowing->status === 'rejected')
        <div class="modal fade" id="rejectReasonModal{{ $borrowing->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>Alasan Penolakan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-danger border-0 d-flex mb-4">
                            <i class="bi bi-x-octagon fs-4 me-3 mt-1"></i>
                            <div>
                                <strong>Permohonan Ditolak</strong>
                                <p class="mb-0 mt-1 small">Maaf, permohonan peminjaman Anda tidak dapat disetujui.</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Alasan</label>
                            <div class="p-3 bg-secondary bg-opacity-10 rounded-3 border">
                                {{ $borrowing->rejection_reason ?? 'Tidak ada alasan spesifik yang dicantumkan.' }}
                            </div>
                        </div>

                        <div class="bg-secondary bg-opacity-10 rounded-3 p-3 border">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar bg-transparent p-2 rounded text-secondary border">
                                    <i class="bi bi-tools fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $borrowing->equipment->name }}</h6>
                                    <div class="text-muted small">Diajukan: {{ $borrowing->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

@push('styles')
<style>
    /* Light Mode: Soft Red */
    .table-danger-soft td {
        background-color: transparent !important;
        color: inherit;
    }
    .table-danger-soft td:first-child {
        border-left: 3px solid #dc3545;
    }
    
    /* Dark Mode: Translucent Red Tint */
    [data-theme="dark"] .table-danger-soft td {
        background-color: transparent !important;
        color: inherit;
    }
    [data-theme="dark"] .table-danger-soft td:first-child {
        border-left: 3px solid #e74a3b;
        box-shadow: inset 3px 0 0 -3px #e74a3b;
    }
    [data-theme="dark"] .table-hover .table-danger-soft:hover > td {
        --bs-table-accent-bg: transparent !important;
        --bs-table-hover-bg: transparent !important;
        background-color: transparent !important;
        color: inherit !important;
    }
</style>
@endpush

@push('scripts')
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
@endsection
