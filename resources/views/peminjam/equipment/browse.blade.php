@extends('layouts.app')

@section('title', 'Cari Alat')
@section('page-title', 'Cari Alat')

@section('content')
<div class="page-heading">
    <h3>Katalog Alat</h3>
</div>

<div class="page-content">
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('equipment.browse') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" 
                               class="form-control" 
                               name="search" 
                               placeholder="Cari nama atau kode alat..."
                               value="{{ request('search') }}">
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
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="row mt-4">
        @forelse($equipment as $item)
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" 
                             class="card-img-top" 
                             alt="{{ $item->name }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="bi bi-box fs-1 text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $item->name }}</h5>
                            @if($item->stock > 0)
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-danger">Habis</span>
                            @endif
                        </div>
                        
                        <p class="text-muted mb-2">
                            <small><i class="bi bi-tag"></i> {{ $item->category->name }}</small>
                        </p>
                        
                        <p class="card-text text-muted small">
                            {{ Str::limit($item->description, 80) }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">Stok: <strong>{{ $item->stock }}</strong></small>
                            </div>
                            @if($item->isAvailable())
                                <a href="{{ route('borrowings.create', $item->id) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Pinjam
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3">Tidak ada alat yang ditemukan</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $equipment->links() }}
    </div>
</div>
@endsection
