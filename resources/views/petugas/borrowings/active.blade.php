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
                            <th>Jadwal Pengembalian</th>
                            <th>Status & Denda</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $borrowing)
                            @php
                                $plannedDate = $borrowing->planned_return_date->startOfDay();
                                $today = now()->startOfDay();
                                $diff = (int) $today->diffInDays($plannedDate, false);
                                
                                $isLate = $diff < 0;
                                $isToday = $diff === 0;
                                $daysLate = $isLate ? abs($diff) : 0;
                                $daysLeft = abs($diff);

                                $fineAmount = $isLate ? $daysLate * 5000 : 0;
                            @endphp
                            <tr class="{{ $isLate ? 'table-danger-soft' : '' }}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-md bg-light-primary text-primary">
                                            <i class="bi bi-person-circle fs-5"></i>
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
                                            <small class="text-muted">{{ $borrowing->equipment->code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="d-flex align-items-center gap-2">
                                            <i class="bi bi-calendar-event text-muted"></i>
                                            {{ $borrowing->planned_return_date->format('d M Y') }}
                                        </span>
                                        <small class="text-muted ms-4">
                                            Pinjam: {{ $borrowing->borrow_date->format('d M') }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    @if($isLate)
                                        <div class="d-flex flex-column align-items-start">
                                            <span class="badge bg-light-danger text-danger mb-1">
                                                <i class="bi bi-exclamation-circle me-1"></i> Terlambat {{ $daysLate }} hari
                                            </span>
                                            <small class="text-danger fw-bold">Denda: Rp {{ number_format($fineAmount, 0, ',', '.') }}</small>
                                        </div>
                                    @elseif($isToday)
                                        <span class="badge bg-light-warning text-warning">
                                            <i class="bi bi-alarm me-1"></i> Hari Ini
                                        </span>
                                    @else
                                        <span class="badge bg-light-success text-success">
                                            <i class="bi bi-clock-history me-1"></i> {{ $daysLeft }} hari lagi
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group rounded-pill shadow-sm" role="group" style="overflow: hidden;">
                                        <button type="button" 
                                                class="btn btn-primary btn-sm px-4 border-0" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#returnModal{{ $borrowing->id }}"
                                                title="Proses Pengembalian">
                                            Proses
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Return Modal -->
                            <div class="modal fade" id="returnModal{{ $borrowing->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('petugas.borrowings.process-return', $borrowing->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Pengembalian Alat</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Info Alat -->
                                                <div class="p-3 rounded bg-body-tertiary border mb-3">
                                                    <strong>{{ $borrowing->equipment->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">Peminjam: {{ $borrowing->user->name }}</small>
                                                </div>

                                                <!-- Info Denda -->
                                                @if($isLate)
                                                <div class="alert alert-danger">
                                                    <h6><i class="bi bi-exclamation-circle"></i> Terlambat {{ $daysLate }} Hari</h6>
                                                    <p class="mb-0">
                                                        Total Denda: <strong>Rp {{ number_format($fineAmount, 0, ',', '.') }}</strong>
                                                        <small>(Rp 5.000/hari)</small>
                                                    </p>
                                                </div>
                                                @else
                                                <div class="alert alert-success">
                                                    <i class="bi bi-check-circle"></i> Tidak ada denda (Tepat Waktu)
                                                </div>
                                                @endif

                                                <div class="mb-3">
                                                    <label class="form-label">Tanggal Pengembalian <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="actual_return_date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Kondisi Barang <span class="text-danger">*</span></label>
                                                    <select class="form-select return-condition-select" name="return_condition" required data-target="warning-{{ $borrowing->id }}">
                                                        <option value="">Pilih Kondisi</option>
                                                        <option value="baik">Baik</option>
                                                        <option value="rusak ringan">Rusak Ringan</option>
                                                        <option value="rusak berat">Rusak Berat</option>
                                                    </select>
                                                    <div class="alert alert-warning mt-2 d-none" id="warning-{{ $borrowing->id }}">
                                                        <i class="bi bi-exclamation-triangle"></i>
                                                        <strong>Rusak Berat:</strong> Stok tidak akan kembali dan masuk log kerusakan.
                                                    </div>
                                                </div>

                                                @if($isLate)
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="fine_paid" value="1" id="finePaid{{ $borrowing->id }}">
                                                        <label class="form-check-label" for="finePaid{{ $borrowing->id }}">
                                                            Denda sudah dibayar lunas
                                                        </label>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="bi bi-check-circle"></i> Proses Pengembalian
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
    }
    [data-theme="dark"] .table-hover .table-danger-soft:hover > td {
        --bs-table-accent-bg: transparent !important;
        --bs-table-hover-bg: transparent !important;
        background-color: transparent !important;
        color: inherit !important;
    }
    /* Fix Modal Info Box in Dark Mode */
    [data-theme="dark"] .bg-body-tertiary {
        background-color: #2b3547 !important;
        color: #e2e8f0 !important;
        border-color: #4b5563 !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const conditionSelects = document.querySelectorAll('.return-condition-select');
        
        conditionSelects.forEach(select => {
            select.addEventListener('change', function() {
                const targetId = this.getAttribute('data-target');
                const warningEl = document.getElementById(targetId);
                
                if (this.value === 'rusak berat') {
                    warningEl.classList.remove('d-none');
                } else {
                    warningEl.classList.add('d-none');
                }
            });
        });
    });
</script>
@endpush
