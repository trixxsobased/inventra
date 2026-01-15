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
                               $daysLeft = now()->startOfDay()->diffInDays($borrowing->planned_return_date->startOfDay(), false);
                               $isLate = $daysLeft < 0;
                               $lateDays = abs($daysLeft);
                               $fineAmount = $isLate ? $lateDays * 5000 : 0;
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
                                        <span class="badge bg-danger">Terlambat {{ $lateDays }} hari</span>
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
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#returnModal{{ $borrowing->id }}">
                                        <i class="bi bi-arrow-return-left"></i> Proses Kembali
                                    </button>
                                </td>
                            </tr>

                            <!-- Return Modal -->
                            <div class="modal fade" id="returnModal{{ $borrowing->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.borrowings.process-return', $borrowing->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Pengembalian Alat</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Info Alat -->
                                                <div class="alert alert-light border mb-3">
                                                    <strong>{{ $borrowing->equipment->name }}</strong>
                                                    <br>
                                                    <small>Peminjam: {{ $borrowing->user->name }}</small>
                                                </div>

                                                <!-- Info Denda -->
                                                @if($isLate)
                                                <div class="alert alert-danger">
                                                    <h6><i class="bi bi-exclamation-circle"></i> Terlambat {{ $lateDays }} Hari</h6>
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
                                                <button type="submit" class="btn btn-success" onclick="return confirm('Konfirmasi pengembalian alat ini?')">
                                                    <i class="bi bi-check-circle"></i> Proses Selesai
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
