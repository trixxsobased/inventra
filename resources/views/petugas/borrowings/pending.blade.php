@extends('layouts.app')

@section('title', 'Verifikasi Peminjaman')
@section('page-title', 'Daftar Peminjaman Pending')

@section('content')
<div class="page-heading">
    <h3>Peminjaman Menunggu Persetujuan</h3>
</div>

<div class="page-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Periode</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $borrowing)
                            @php
                                $duration = $borrowing->borrow_date->diffInDays($borrowing->planned_return_date);
                                
                                // Smart Queue Logic
                                $queuePosition = $borrowing->equipment->pendingBorrowings->where('created_at', '<', $borrowing->created_at)->count() + 1;
                                $totalQueued = $borrowing->equipment->pending_count;
                                $currentStock = $borrowing->equipment->stock;
                                $isStockCritical = $currentStock < $totalQueued;
                                $isOutOfOrder = $queuePosition > $currentStock;
                            @endphp
                            <tr class="{{ $isOutOfOrder && $isStockCritical ? 'bg-warning bg-opacity-10 border-warning' : '' }}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="position-relative">
                                            <div class="avatar avatar-md bg-light-primary text-primary">
                                                <i class="bi bi-person-circle fs-5"></i>
                                            </div>
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary border border-light">
                                                #{{ $queuePosition }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $borrowing->user->name }}</h6>
                                            <small class="text-muted">{{ $borrowing->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-md bg-light-info text-info">
                                            <i class="bi bi-box-seam fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $borrowing->equipment->name }}</h6>
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                <span class="badge bg-secondary">Stok: {{ $currentStock }}</span>
                                                <span class="badge bg-info text-dark">Antrian: {{ $totalQueued }}</span>
                                            </div>
                                            
                                            @if($isStockCritical)
                                                <small class="text-danger d-block mt-1">
                                                    <i class="bi bi-exclamation-triangle"></i> Stok Kritis!
                                                </small>
                                            @endif
                                            
                                            <div class="mt-1">
                                                <small class="text-muted">{{ $borrowing->equipment->code }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="d-flex align-items-center gap-2">
                                            <i class="bi bi-calendar-range text-muted"></i>
                                            {{ $borrowing->borrow_date->format('d M') }} - {{ $borrowing->planned_return_date->format('d M Y') }}
                                        </span>
                                        <small class="text-muted ms-4">
                                            <i class="bi bi-clock"></i> {{ $duration }} hari
                                            <span class="mx-1">•</span>
                                            {{ $borrowing->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group rounded-pill shadow-sm" role="group" style="overflow: hidden;">
                                        @if($queuePosition > 1 && $queuePosition > $currentStock)
                                            <button type="button" 
                                                    class="btn btn-warning btn-sm px-3 border-0" 
                                                    title="Peringatan: Stok mungkin habis sebelum antrian ini"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#queueWarningModal{{ $borrowing->id }}">
                                                <i class="bi bi-exclamation-circle text-dark"></i>
                                            </button>
                                        @endif

                                        <button type="button" 
                                                class="btn btn-success btn-sm px-3 border-0" 
                                                title="Setujui"
                                                onclick="document.getElementById('approve-form-{{ $borrowing->id }}').submit()">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm px-3 border-0" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $borrowing->id }}"
                                                title="Tolak">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                    <form id="approve-form-{{ $borrowing->id }}" 
                                          action="{{ route('petugas.borrowings.approve', $borrowing->id) }}" 
                                          method="POST" 
                                          class="d-none"
                                          onsubmit="return confirmAction(this, {title: 'Setujui Peminjaman?', text: 'Antrian #{{ $queuePosition }} - {{ $borrowing->equipment->name }}', icon: 'question', confirmButtonText: 'Ya, Setujui', confirmButtonColor: '#198754'})">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                </td>
                            </tr>
                            
                            <!-- Queue Warning Modal -->
                            <div class="modal fade" id="queueWarningModal{{ $borrowing->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content text-start">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title text-warning">
                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Peringatan Antrian
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center pt-4">
                                            <div class="avatar bg-warning bg-opacity-10 p-3 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                                <i class="bi bi-sort-numeric-down fs-1 text-warning"></i>
                                            </div>
                                            <h4 class="mb-2">Posisi Antrian #{{ $queuePosition }}</h4>
                                            <p class="text-muted mb-4">
                                                Stok saat ini hanya tersedia <strong>{{ $currentStock }} unit</strong>.
                                            </p>
                                            
                                            <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning d-flex align-items-start text-start gap-3">
                                                <i class="bi bi-info-circle fs-5 mt-1 flex-shrink-0"></i>
                                                <div>
                                                    <strong>Saran Sistem:</strong><br>
                                                    Harap selesaikan <u>{{ $queuePosition - 1 }} antrian awal</u> terlebih dahulu sebelum memproses permintaan ini untuk menjaga keadilan.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 justify-content-center pb-4">
                                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                            <button type="button" 
                                                    class="btn btn-warning rounded-pill px-4"
                                                    onclick="document.getElementById('queueWarningModal{{ $borrowing->id }}').classList.remove('show'); document.getElementById('approve-form-{{ $borrowing->id }}').submit()">
                                                Tetap Setujui
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $borrowing->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('petugas.borrowings.reject', $borrowing->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title text-danger">
                                                    <i class="bi bi-x-circle me-2"></i>Tolak Peminjaman
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body pt-4">
                                                <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded bg-secondary bg-opacity-10 border border-secondary border-opacity-10">
                                                    <div class="avatar bg-danger bg-opacity-10 text-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                        <i class="bi bi-box-seam fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $borrowing->equipment->name }}</h6>
                                                        <div class="text-muted small">
                                                            <i class="bi bi-person me-1"></i> {{ $borrowing->user->name }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label text-secondary small text-uppercase fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                                                    <textarea class="form-control" name="rejection_reason" rows="3" minlength="10" required placeholder="Jelaskan alasan penolakan (minimal 10 karakter)..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0 pb-4">
                                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger rounded-pill px-4">
                                                    Tolak Peminjaman
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <div class="avatar avatar-xl bg-light-primary text-primary mb-3">
                                            <i class="bi bi-inbox fs-1"></i>
                                        </div>
                                        <h5 class="text-muted">Tidak ada peminjaman</h5>
                                        <p class="text-muted small">Semua permohonan telah diproses</p>
                                    </div>
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
