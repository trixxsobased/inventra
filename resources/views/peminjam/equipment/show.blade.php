@extends('layouts.app')

@section('title', 'Detail Alat')
@section('page-title', 'Detail Alat')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Detail Alat</h3>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    @if($equipment->image)
                        <img src="{{ asset('storage/' . $equipment->image) }}" 
                             class="img-fluid rounded mb-3" 
                             alt="{{ $equipment->name }}"
                             style="width: 100%; max-height: 400px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" 
                             style="height: 400px;">
                            <i class="bi bi-box fs-1 text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{ $equipment->name }}</h4>
                    
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Kode</th>
                            <td><code>{{ $equipment->code }}</code></td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>
                                <span class="badge bg-primary">
                                    <i class="bi bi-tag"></i> {{ $equipment->category->name }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $equipment->location ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kondisi</th>
                            <td>
                                @if($equipment->condition === 'baik')
                                    <span class="badge bg-success">Baik</span>
                                @elseif($equipment->condition === 'rusak ringan')
                                    <span class="badge bg-warning">Rusak Ringan</span>
                                @else
                                    <span class="badge bg-danger">Rusak Berat</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Stok Tersedia</th>
                            <td>
                                <strong class="{{ $equipment->stock > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $equipment->stock }} unit
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($equipment->isAvailable())
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-danger">Tidak Tersedia</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if($equipment->description)
                    <div class="mt-3">
                        <h6>Deskripsi</h6>
                        <p class="text-muted">{{ $equipment->description }}</p>
                    </div>
                    @endif

                    @if(auth()->user()->role === 'peminjam')
                        <div class="mt-4">
                            @if($equipment->isAvailable())
                                <a href="{{ route('borrowings.create', $equipment->id) }}" 
                                   class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-plus-circle"></i> Ajukan Peminjaman
                                </a>
                            @else
                                <button class="btn btn-secondary btn-lg w-100" disabled>
                                    <i class="bi bi-x-circle"></i> Tidak Tersedia untuk Dipinjam
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            @if($equipment->activeBorrowings->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Peminjaman Aktif</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Peminjam</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Rencana Kembali</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipment->activeBorrowings as $borrowing)
                                <tr>
                                    <td>{{ $borrowing->user->name }}</td>
                                    <td>{{ $borrowing->borrow_date->format('d M Y') }}</td>
                                    <td>{{ $borrowing->planned_return_date->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
