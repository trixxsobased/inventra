@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Daftar Pengguna</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-circle"></i> Tambah Pengguna
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
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Telepon</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td><code>{{ $user->username }}</code></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-shield-fill-check"></i> Admin
                                        </span>
                                    @elseif($user->role === 'petugas')
                                        <span class="badge bg-info">
                                            <i class="bi bi-person-badge"></i> Petugas
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="bi bi-person"></i> Peminjam
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal{{ $user->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @else
                                        <button type="button" class="btn btn-sm btn-secondary" disabled title="Tidak dapat menghapus akun sendiri">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            
                            <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Pengguna: {{ $user->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                               class="form-control @error('name') is-invalid @enderror" 
                                                               name="name" 
                                                               value="{{ old('name', $user->name) }}"
                                                               required>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Username <span class="text-danger">*</span></label>
                                                        <input type="text" 
                                                               class="form-control @error('username') is-invalid @enderror" 
                                                               name="username" 
                                                               value="{{ old('username', $user->username) }}"
                                                               required>
                                                        @error('username')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                                        <input type="email" 
                                                               class="form-control @error('email') is-invalid @enderror" 
                                                               name="email" 
                                                               value="{{ old('email', $user->email) }}"
                                                               required>
                                                        @error('email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Telepon</label>
                                                        <input type="text" 
                                                               class="form-control @error('phone') is-invalid @enderror" 
                                                               name="phone" 
                                                               value="{{ old('phone', $user->phone) }}">
                                                        @error('phone')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Role <span class="text-danger">*</span></label>
                                                        <select class="form-select @error('role') is-invalid @enderror" 
                                                                name="role" 
                                                                required>
                                                            <option value="peminjam" {{ old('role', $user->role) === 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                                                            <option value="petugas" {{ old('role', $user->role) === 'petugas' ? 'selected' : '' }}>Petugas</option>
                                                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                                        </select>
                                                        @error('role')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Password Baru</label>
                                                        <input type="password" 
                                                               class="form-control @error('password') is-invalid @enderror" 
                                                               name="password" 
                                                               placeholder="Kosongkan jika tidak ingin mengubah">
                                                        @error('password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                        <small class="text-muted">Minimal 8 karakter</small>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Alamat</label>
                                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                                              name="address" 
                                                              rows="2">{{ old('address', $user->address) }}</textarea>
                                                    @error('address')
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
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-people fs-2 text-muted"></i>
                                    <p class="text-muted mt-2">Belum ada pengguna</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
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
                        
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('username') is-invalid @enderror" 
                                   id="username" 
                                   name="username" 
                                   value="{{ old('username') }}"
                                   required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Telepon</label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" 
                                    name="role" 
                                    required>
                                <option value="">Pilih Role</option>
                                <option value="peminjam" {{ old('role') === 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                                <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="2">{{ old('address') }}</textarea>
                        @error('address')
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
    document.addEventListener('DOMContentLoaded', function() {
        // Buka ulang modal jika ada error validasi
        @if($errors->any())
            @if(!old('_method'))
                // Tidak ada _method berarti POST request (create)
                var createModal = new bootstrap.Modal(document.getElementById('createModal'));
                createModal.show();
            @elseif(old('_method') === 'PUT')
                // PUT request (edit), cari data berdasarkan session
                @if(session('user_id'))
                    var editModal = new bootstrap.Modal(document.getElementById('editModal{{ session("user_id") }}'));
                    editModal.show();
                @endif
            @endif
        @endif
    });
</script>
@endpush
@endsection
