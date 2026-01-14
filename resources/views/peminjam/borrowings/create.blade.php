@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')
@section('page-title', 'Ajukan Peminjaman')

@section('content')
<div class="page-heading">
    <h3>Form Peminjaman Alat</h3>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Peminjaman</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('now borrowings.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label">Alat yang Dipinjam</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ $equipment->name }} ({{ $equipment->code }})" 
                                   readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="planned_return_date" class="form-label">Tanggal Rencana Kembali <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('planned_return_date') is-invalid @enderror" 
                                   id="planned_return_date" 
                                   name="planned_return_date" 
                                   value="{{ old('planned_return_date') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   required>
                            @error('planned_return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal besok ({{ date('d/m/Y', strtotime('+1 day')) }})</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Tujuan Peminjaman <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('purpose') is-invalid @enderror" 
                                      id="purpose" 
                                      name="purpose" 
                                      rows="3" 
                                      required>{{ old('purpose') }}</textarea>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Jelaskan untuk keperluan apa alat ini akan digunakan</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan Tambahan (Opsional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="2">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('equipment.browse') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Ajukan Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Info Alat</h4>
                </div>
                <div class="card-body">
                    @if($equipment->image)
                        <img src="{{ asset('storage/' . $equipment->image) }}" 
                             class="img-fluid rounded mb-3" 
                             alt="{{ $equipment->name }}">
                    @endif
                    
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Kode</th>
                            <td>{{ $equipment->code }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>{{ $equipment->category->name }}</td>
                        </tr>
                        <tr>
                            <th>Stok</th>
                            <td><span class="badge bg-success">{{ $equipment->stock }} unit</span></td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $equipment->location ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kondisi</th>
                            <td><span class="badge bg-success">{{ ucfirst($equipment->condition) }}</span></td>
                        </tr>
                    </table>
                    
                    @if($equipment->description)
                        <div class="mt-3">
                            <strong>Deskripsi:</strong>
                            <p class="text-muted small mt-2">{{ $equipment->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
