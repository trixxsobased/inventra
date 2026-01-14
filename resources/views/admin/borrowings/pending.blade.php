@extends('layouts.app')

@section('title', 'Verifikasi Peminjaman')
@section('page-title', 'Verifikasi Peminjaman')

@section('content')
<div class="page-heading">
    <h3>Verifikasi Peminjaman</h3>
    <p class="text-muted">Kelola permintaan peminjaman yang masuk</p>
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

    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-warning text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Request Pending</h5>
                            <small>Menunggu persetujuan Anda</small>
                        </div>
                        <div>
                            <h2 class="mb-0">{{ $borrowings->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    @forelse($borrowings as $borrowing)
        <div class="card mb-3 shadow-sm">
            
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-person-circle me-2"></i>
                        <strong>{{ $borrowing->user->name }}</strong>
                        <small class="text-muted ms-2">{{ $borrowing->user->email }}</small>
                    </div>
                    <div>
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-clock me-1"></i>Pending
                        </span>
                        <small class="text-muted ms-2">{{ $borrowing->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>

            
            <div class="card-body">
                <div class="row">
                    
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-box-seam me-2"></i>Informasi Alat
                        </h6>
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary bg-opacity-10 rounded p-3">
                                    <i class="bi bi-tools fs-2 text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $borrowing->equipment->name }}</h5>
                                <p class="text-muted mb-1">
                                    <small>
                                        <code>{{ $borrowing->equipment->code }}</code>
                                    </small>
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-tag me-1"></i>
                                    <small>{{ $borrowing->equipment->category->name }}</small>
                                </p>
                                @if($borrowing->equipment->stock > 0)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Stok Tersedia: {{ $borrowing->equipment->stock }}
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>Stok Habis
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">
                            <i class="bi bi-calendar-check me-2"></i>Detail Peminjaman
                        </h6>
                        
                        <div class="mb-2">
                            <strong>Tujuan:</strong>
                            <p class="mb-0 text-muted">{{ $borrowing->purpose ?? 'Tidak disebutkan' }}</p>
                        </div>

                        <div class="mb-2">
                            <strong>Durasi Peminjaman:</strong>
                            <p class="mb-0">
                                <i class="bi bi-calendar-event me-1 text-primary"></i>
                                {{ $borrowing->borrow_date->format('d M Y') }}
                                <i class="bi bi-arrow-right mx-2"></i>
                                {{ $borrowing->planned_return_date->format('d M Y') }}
                            </p>
                            <small class="text-muted">
                                ({{ $borrowing->borrow_date->diffInDays($borrowing->planned_return_date) }} hari)
                            </small>
                        </div>

                        @if($borrowing->notes)
                            <div class="mt-2">
                                <strong>Catatan:</strong>
                                <p class="mb-0 text-muted">{{ $borrowing->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>
                            Diajukan: {{ $borrowing->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>
                    <div>
                        
                        <button type="button" 
                                class="btn btn-sm btn-outline-info me-2" 
                                data-bs-toggle="modal" 
                                data-bs-target="#detailModal{{ $borrowing->id }}">
                            <i class="bi bi-eye"></i> Detail
                        </button>

                        
                        <button type="button" 
                                class="btn btn-sm btn-outline-danger me-2" 
                                data-bs-toggle="modal" 
                                data-bs-target="#rejectModal{{ $borrowing->id }}">
                            <i class="bi bi-x-circle"></i> Tolak
                        </button>

                        
                        <form action="{{ route('admin.borrowings.approve', $borrowing->id) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Setujui permintaan peminjaman ini?')">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-check-circle"></i> Setujui
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modal fade" id="detailModal{{ $borrowing->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Peminjaman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Peminjam</th>
                                <td>{{ $borrowing->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $borrowing->user->email }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>{{ $borrowing->user->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Alat</th>
                                <td>{{ $borrowing->equipment->name }}</td>
                            </tr>
                            <tr>
                                <th>Kode Alat</th>
                                <td><code>{{ $borrowing->equipment->code }}</code></td>
                            </tr>
                            <tr>
                                <th>Stok Tersedia</th>
                                <td>{{ $borrowing->equipment->stock }}</td>
                            </tr>
                            <tr>
                                <th>Tujuan</th>
                                <td>{{ $borrowing->purpose ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pinjam</th>
                                <td>{{ $borrowing->borrow_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Rencana Kembali</th>
                                <td>{{ $borrowing->planned_return_date->format('d M Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modal fade" id="rejectModal{{ $borrowing->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.borrowings.reject', $borrowing->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Tolak Peminjaman</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Anda yakin ingin menolak permintaan peminjaman dari <strong>{{ $borrowing->user->name }}</strong>?</p>
                            
                            <div class="mb-3">
                                <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea class="form-control" 
                                          id="rejection_reason" 
                                          name="rejection_reason" 
                                          rows="3" 
                                          required 
                                          placeholder="Contoh: Stok tidak tersedia, Tidak sesuai jadwal, dll"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x-circle"></i> Tolak Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-clipboard-check text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">Tidak ada request yang pending</h5>
                <p class="text-muted">Semua permintaan peminjaman sudah diproses</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
