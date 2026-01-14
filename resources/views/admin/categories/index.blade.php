@extends('layouts.app')

@section('title', 'Daftar Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Daftar Kategori</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-circle"></i> Tambah Kategori
        </button>
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
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Alat</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $category)
                            <tr>
                                <td>{{ $categories->firstItem() + $index }}</td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td>{{ $category->description ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $category->equipment_count }} alat</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal{{ $category->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            
                            <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Kategori</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="edit_name_{{ $category->id }}" class="form-label">
                                                        Nama Kategori <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control @error('name') is-invalid @enderror" 
                                                           id="edit_name_{{ $category->id }}" 
                                                           name="name" 
                                                           value="{{ old('name', $category->name) }}"
                                                           required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="edit_description_{{ $category->id }}" class="form-label">Deskripsi</label>
                                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                                              id="edit_description_{{ $category->id }}" 
                                                              name="description" 
                                                              rows="3">{{ old('description', $category->description) }}</textarea>
                                                    @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="bi bi-inbox fs-2 text-muted"></i>
                                    <p class="text-muted mt-2">Belum ada kategori</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
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
    // Buka ulang modal jika ada error validasi
    document.addEventListener('DOMContentLoaded', function() {
        // Cek jika ada error dan old input
        @if($errors->any() && old('name'))
            // Jika ada old input berarti form sudah disubmit
            // Cek apakah dari modal create atau edit
            @if(request()->routeIs('admin.categories.store') || request()->method() === 'POST')
                // Buka ulang modal create
                var createModal = new bootstrap.Modal(document.getElementById('createModal'));
                createModal.show();
            @endif
        @endif
    });
</script>
@endpush
@endsection
