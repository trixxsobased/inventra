@extends('layouts.app')

@section('title', 'Cari Alat')
@section('page-title', 'Cari Alat')

@section('content')
<div class="page-heading mb-4">
    <h3>Katalog Alat</h3>
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
    
    <!-- Search & Filter Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('equipment.browse') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" 
                                   class="form-control border-start-0 ps-0" 
                                   name="search" 
                                   placeholder="Cari nama atau kode alat..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="category">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                            Cari Alat
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="row">
        @forelse($equipment as $item)
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0 transition-hover">
                    <div class="position-relative">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $item->name }}"
                                 style="height: 220px; object-fit: cover;">
                        @else
                            <div class="bg-body-secondary d-flex align-items-center justify-content-center" 
                                 style="height: 220px;">
                                <i class="bi bi-box-seam fs-1 text-secondary opacity-50"></i>
                            </div>
                        @endif
                        
                        <div class="position-absolute top-0 end-0 p-3">
                             @if($item->stock > 0)
                                <span class="badge bg-success bg-opacity-75 backdrop-blur shadow-sm rounded-pill px-3">
                                    <i class="bi bi-check-circle me-1"></i> Tersedia
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-75 backdrop-blur shadow-sm rounded-pill px-3">
                                    <i class="bi bi-x-circle me-1"></i> Habis
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="mb-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill mb-2">
                                <i class="bi bi-tag me-1"></i> {{ $item->category->name }}
                            </span>
                            <h5 class="card-title fw-bold text-truncate mb-1" title="{{ $item->name }}">{{ $item->name }}</h5>
                            <small class="text-muted font-monospace">{{ $item->code }}</small>
                        </div>
                        
                        <p class="card-text text-muted small mb-4 flex-grow-1">
                            {{ Str::limit($item->description, 90) }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="d-flex align-items-center text-muted">
                                <i class="bi bi-box-seam me-2"></i>
                                <span class="small">Stok: <strong class="text-dark">{{ $item->stock }}</strong></span>
                            </div>
                            
                            @if($item->isAvailable())
                                <button type="button" 
                                        class="btn btn-primary btn-sm rounded-pill px-4"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#borrowModal{{ $item->id }}">
                                    <i class="bi bi-plus-lg me-1"></i> Pinjam
                                </button>
                            @else
                                <button class="btn btn-secondary btn-sm rounded-pill px-4" disabled>
                                    Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Borrow Modal -->
            @if($item->isAvailable())
            <div class="modal fade" id="borrowModal{{ $item->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0">
                        <form action="{{ route('borrowings.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="equipment_id" value="{{ $item->id }}">
                            
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold">
                                    <i class="bi bi-clipboard-check me-2 text-primary"></i>Ajukan Peminjaman
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            
                            <div class="modal-body p-4">
                                <div class="row">
                                    <!-- Info Alat -->
                                    <div class="col-md-5 mb-4 mb-md-0 border-end">
                                        <div class="pe-md-3">
                                            <div class="text-center mb-3">
                                                @if($item->image)
                                                    <img src="{{ asset('storage/' . $item->image) }}" 
                                                         class="rounded-3 shadow-sm w-100" 
                                                         style="max-height: 200px; object-fit: cover;">
                                                @else
                                                    <div class="bg-body-secondary rounded-3 d-flex align-items-center justify-content-center w-100" 
                                                         style="height: 200px;">
                                                        <i class="bi bi-box-seam fs-1 text-secondary opacity-50"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <h6 class="fw-bold mb-3">{{ $item->name }}</h6>
                                            
                                            <div class="list-group list-group-flush small">
                                                <div class="list-group-item px-0 d-flex justify-content-between bg-transparent">
                                                    <span class="text-muted">Kode</span>
                                                    <span class="font-monospace text-dark">{{ $item->code }}</span>
                                                </div>
                                                <div class="list-group-item px-0 d-flex justify-content-between bg-transparent">
                                                    <span class="text-muted">Kategori</span>
                                                    <span class="fw-medium text-dark">{{ $item->category->name }}</span>
                                                </div>
                                                <div class="list-group-item px-0 d-flex justify-content-between bg-transparent">
                                                    <span class="text-muted">Stok</span>
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill">{{ $item->stock }} unit</span>
                                                </div>
                                                <div class="list-group-item px-0 d-flex justify-content-between bg-transparent">
                                                    <span class="text-muted">Lokasi</span>
                                                    <span class="text-dark">{{ $item->location ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Form -->
                                    <div class="col-md-7 ps-md-4">
                                        <div class="alert alert-info border-0 rounded-3 mb-4">
                                            <div class="d-flex">
                                                <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                                                <div class="small">
                                                    Pastikan tanggal kembali diisi dengan benar. Keterlambatan akan dikenakan denda.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label text-muted small text-uppercase fw-bold">Tanggal Rencana Kembali <span class="text-danger">*</span></label>
                                            <input type="date" 
                                                   class="form-control" 
                                                   name="planned_return_date" 
                                                   min="{{ date('Y-m-d') }}"
                                                   required>
                                            <div class="form-text">Deadline pengembalian alat</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label text-muted small text-uppercase fw-bold">Tujuan Peminjaman <span class="text-danger">*</span></label>
                                            <textarea class="form-control" 
                                                      name="purpose" 
                                                      rows="3" 
                                                      placeholder="Jelaskan keperluan peminjaman..."
                                                      required></textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label text-muted small text-uppercase fw-bold">Catatan (Opsional)</label>
                                            <textarea class="form-control" 
                                                      name="notes" 
                                                      rows="2"
                                                      placeholder="Info tambahan..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">
                                    Ajukan Pinjaman
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="col-12">
                <div class="card shadow-sm border-0 py-5">
                    <div class="card-body text-center">
                        <div class="bg-body-secondary rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-search fs-1 text-muted"></i>
                        </div>
                        <h5 class="text-muted mb-1">Tidak ada alat ditemukan</h5>
                        <p class="text-muted small">Coba ubah kata kunci atau kategori pencarian Anda</p>
                        <a href="{{ route('equipment.browse') }}" class="btn btn-outline-primary rounded-pill mt-2">
                            Reset Pencarian
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    @if($equipment->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $equipment->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
    .backdrop-blur {
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
    .transition-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endpush
@endsection
