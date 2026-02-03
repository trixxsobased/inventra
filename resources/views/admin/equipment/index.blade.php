@extends('layouts.app')

@section('title', 'Daftar Alat')
@section('page-title', 'Manajemen Alat')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Daftar Alat</h3>
        <div class="btn-group">
            <a href="{{ route('admin.equipment.qr.scan') }}" class="btn btn-info">
                <i class="bi bi-qr-code-scan"></i> Scan QR
            </a>
            <a href="{{ route('admin.equipment.qr.bulk') }}" class="btn btn-secondary">
                <i class="bi bi-printer"></i> Print Semua QR
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle"></i> Tambah Alat
            </button>
        </div>
    </div>
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
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Cari alat..." id="searchInput">
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="categoryFilter">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold" width="5%">#</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Kode</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Nama Alat</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Kategori</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Stok</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Lokasi</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold">Kondisi</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipment as $index => $item)
                            <tr>
                                <td>{{ $equipment->firstItem() + $index }}</td>
                                <td><code>{{ $item->code }}</code></td>
                                <td><strong>{{ $item->name }}</strong></td>
                                <td>{{ $item->category->name }}</td>
                                <td>
                                    @if($item->stock > 10)
                                        <span class="badge bg-success">{{ $item->stock }}</span>
                                    @elseif($item->stock > 0)
                                        <span class="badge bg-warning">{{ $item->stock }}</span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </td>
                                <td>{{ $item->location ?? '-' }}</td>
                                <td>
                                    @if($item->condition === 'baik')
                                        <span class="badge bg-success">Baik</span>
                                    @elseif($item->condition === 'rusak ringan')
                                        <span class="badge bg-warning">Rusak Ringan</span>
                                    @else
                                        <span class="badge bg-danger">Rusak Berat</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group rounded-pill shadow-sm" role="group" style="overflow: hidden;">
                                        <button type="button" 
                                                class="btn btn-sm btn-info px-3 border-0"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#qrModal{{ $item->id }}"
                                                title="QR Code">
                                            <i class="bi bi-qr-code"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-warning px-3 border-0"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal{{ $item->id }}"
                                                title="Edit">
                                            <i class="bi bi-pencil text-dark"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger px-3 border-0" 
                                                title="Hapus"
                                                onclick="var form = document.getElementById('delete-form-{{ $item->id }}'); confirmAction(form, {title: 'Hapus Alat?', text: 'Data alat {{ $item->name }} ({{ $item->code }}) akan dihapus secara permanen.', icon: 'danger', confirmButtonText: '<i class=\'bi bi-trash me-1\'></i> Ya, Hapus', confirmButtonColor: '#dc2626'})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $item->id }}" 
                                          action="{{ route('admin.equipment.destroy', $item->id) }}" 
                                          method="POST" 
                                          class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>

                            
                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.equipment.update', $item->id) }}" 
                                              method="POST" 
                                              enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Alat: {{ $item->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Kode Alat <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                               class="form-control @error('code') is-invalid @enderror" 
                                                               name="code" 
                                                               value="{{ old('code', $item->code) }}"
                                                               required>
                                                        @error('code')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nama Alat <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                               class="form-control @error('name') is-invalid @enderror" 
                                                               name="name" 
                                                               value="{{ old('name', $item->name) }}"
                                                               required>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                                                name="category_id" 
                                                                required>
                                                            <option value="">Pilih Kategori</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}" 
                                                                        {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('category_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Stok <span class="text-danger">*</span></label>
                                                        <input type="number" 
                                                               class="form-control @error('stock') is-invalid @enderror" 
                                                               name="stock" 
                                                               value="{{ old('stock', $item->stock) }}"
                                                               min="0"
                                                               required>
                                                        @error('stock')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Lokasi</label>
                                                        <input type="text" 
                                                               class="form-control @error('location') is-invalid @enderror" 
                                                               name="location" 
                                                               value="{{ old('location', $item->location) }}">
                                                        @error('location')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                                                        <select class="form-select @error('condition') is-invalid @enderror" 
                                                                name="condition" 
                                                                required>
                                                            <option value="baik" {{ old('condition', $item->condition) === 'baik' ? 'selected' : '' }}>Baik</option>
                                                            <option value="rusak ringan" {{ old('condition', $item->condition) === 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                                            <option value="rusak berat" {{ old('condition', $item->condition) === 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                                                        </select>
                                                        @error('condition')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                                              name="description" 
                                                              rows="3">{{ old('description', $item->description) }}</textarea>
                                                    @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Gambar</label>
                                                    @if($item->image)
                                                        <div class="mb-2">
                                                            <img src="{{ asset('storage/' . $item->image) }}" 
                                                                 alt="{{ $item->name }}" 
                                                                 class="img-thumbnail" 
                                                                 style="max-height: 150px;">
                                                        </div>
                                                    @endif
                                                    <input type="file" 
                                                           class="form-control @error('image') is-invalid @enderror" 
                                                           name="image" 
                                                           accept="image/*">
                                                    @error('image')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">JPG, PNG (Max: 2MB). Kosongkan jika tidak ingin mengubah.</small>
                                                </div>
                                            </div>
                                            
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-save"></i> Update
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- QR Code Modal -->
                            <div class="modal fade" id="qrModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="bi bi-qr-code me-2"></i>QR Code
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <div class="mb-3">
                                                <img src="{{ route('admin.equipment.qr', $item->id) }}" 
                                                     alt="QR {{ $item->code }}"
                                                     class="img-fluid"
                                                     style="max-width: 250px;">
                                            </div>
                                            <div class="bg-light rounded p-3 mb-3">
                                                <h6 class="mb-1">{{ $item->name }}</h6>
                                                <code class="text-primary">{{ $item->code }}</code>
                                                <p class="text-muted mb-0 mt-2 small">
                                                    {{ $item->category->name ?? '-' }} | 
                                                    Stok: {{ $item->stock }} | 
                                                    {{ ucfirst($item->condition) }}
                                                </p>
                                            </div>
                                            <p class="text-muted small mb-0">
                                                Scan QR code ini untuk langsung ke halaman detail alat
                                            </p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <a href="{{ route('admin.equipment.qr.download', $item->id) }}" 
                                               class="btn btn-primary">
                                                <i class="bi bi-download me-1"></i> Download PNG
                                            </a>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bi bi-box fs-2 text-muted"></i>
                                    <p class="text-muted mt-2">Belum ada data alat</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $equipment->links() }}
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('admin.equipment.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Alat Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Kode Alat <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code') }}"
                                   placeholder="INV-RPL-2026-001"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: INV-[Jurusan]-[Tahun]-[Nomor]</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Alat <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id" 
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" 
                                   name="stock" 
                                   value="{{ old('stock', 0) }}"
                                   min="0"
                                   required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Lokasi</label>
                            <input type="text" 
                                   class="form-control @error('location') is-invalid @enderror" 
                                   id="location" 
                                   name="location" 
                                   value="{{ old('location') }}"
                                   placeholder="Contoh: Ruang Lab 1">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="condition" class="form-label">Kondisi <span class="text-danger">*</span></label>
                            <select class="form-select @error('condition') is-invalid @enderror" 
                                    id="condition" 
                                    name="condition" 
                                    required>
                                <option value="baik" {{ old('condition') === 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak ringan" {{ old('condition') === 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak berat" {{ old('condition') === 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                            </select>
                            @error('condition')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar</label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">JPG, PNG (Max: 2MB)</small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Buka ulang modal jika ada error validasi
        @if($errors->any())
            @if(!old('_method'))
                // Tidak ada _method berarti POST request (create)
                var createModal = new bootstrap.Modal(document.getElementById('createModal'));
                createModal.show();
            @elseif(old('_method') === 'PUT')
                // PUT request (edit), cari data berdasarkan session
                @if(session('equipment_id'))
                    var editModal = new bootstrap.Modal(document.getElementById('editModal{{ session("equipment_id") }}'));
                    editModal.show();
                @endif
            @endif
        @endif
    });
</script>
@endpush
@endsection
