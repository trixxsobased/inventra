@extends('layouts.app')

@section('title', 'Profil')
@section('page-title', 'Profil Saya')

@section('content')
<div class="page-heading mb-4">
    <h3>Profil Saya</h3>
</div>

<div class="page-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Personal Info -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h5 class="card-title fw-bold text-primary mb-0">
                        <i class="bi bi-person-circle me-2"></i>Informasi Pribadi
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label text-muted small text-uppercase fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', auth()->user()->name) }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label text-muted small text-uppercase fw-bold">Username</label>
                                <input type="text" 
                                       class="form-control bg-body-secondary" 
                                       value="{{ auth()->user()->username }}"
                                       disabled>
                                <small class="text-muted">
                                    <i class="bi bi-lock-fill me-1"></i>Username permanen
                                </small>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label text-muted small text-uppercase fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', auth()->user()->email) }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label text-muted small text-uppercase fw-bold">Nomor Telepon</label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       placeholder="08..."
                                       value="{{ old('phone', auth()->user()->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label text-muted small text-uppercase fw-bold">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3"
                                      placeholder="Alamat lengkap">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-muted small text-uppercase fw-bold">Role</label>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fs-6">
                                    <i class="bi bi-shield-lock me-2"></i>{{ ucfirst(auth()->user()->role) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-5">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Avatar -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <h5 class="card-title fw-bold text-success mb-4 text-start">
                        <i class="bi bi-image me-2"></i>Foto Profil
                    </h5>
                    
                    <div class="position-relative d-inline-block mb-3">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                 alt="Avatar" 
                                 class="rounded-circle shadow-sm"
                                 style="width: 150px; height: 150px; object-fit: cover; border: 4px solid var(--bs-body-bg);">
                        @else
                            <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center"
                                 style="width: 150px; height: 150px; border: 4px solid var(--bs-body-bg);">
                                <i class="bi bi-person text-primary" style="font-size: 80px;"></i>
                            </div>
                        @endif
                        <label for="avatar_upload" class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle p-2 shadow-sm" 
                               style="cursor: pointer; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"
                               data-bs-toggle="tooltip" title="Ganti Foto">
                            <i class="bi bi-camera-fill"></i>
                        </label>
                    </div>

                    <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" id="avatar_upload" name="avatar" class="d-none" onchange="this.form.submit()" accept="image/*">
                    </form>
                    
                    <p class="text-muted small mt-2 mb-0">Klik ikon kamera untuk mengganti foto</p>
                </div>
            </div>

            <!-- Password -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold text-warning mb-4">
                        <i class="bi bi-key me-2"></i>Keamanan
                    </h5>
                    
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label text-muted small text-uppercase fw-bold">Password Lama</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="current_password" 
                                   name="current_password" 
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label text-muted small text-uppercase fw-bold">Password Baru</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password" 
                                   name="new_password" 
                                   placeholder="Min. 8 karakter"
                                   required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label text-muted small text-uppercase fw-bold">Konfirmasi</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning rounded-pill text-dark fw-bold">
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
@endsection
