@extends('layouts.app')

@section('title', 'Profil')
@section('page-title', 'Profil Saya')

@section('content')
<div class="page-heading">
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
        
        <div class="col-lg-8 mb-4" style="max-width: 1200px !important;">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0 text-white">
                        <i class="bi bi-person-circle me-2"></i>Informasi Pribadi
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
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
                                <label for="username" class="form-label">Username</label>
                                <input type="text" 
                                       class="form-control bg-light" 
                                       value="{{ auth()->user()->username }}"
                                       disabled>
                                <small class="text-muted">
                                    <i class="bi bi-lock me-1"></i>Username tidak dapat diubah
                                </small>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
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
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       placeholder="08123456789"
                                       value="{{ old('phone', auth()->user()->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3"
                                      placeholder="Alamat lengkap Anda">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <input type="text" 
                                   class="form-control bg-light" 
                                   value="{{ ucfirst(auth()->user()->role) }}"
                                   disabled>
                            <small class="text-muted">
                                <i class="bi bi-shield-lock me-1"></i>Role ditentukan oleh administrator
                            </small>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save me-2"></i>Update Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4">
            
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="bi bi-image me-2"></i>Foto Profil
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                             alt="Avatar" 
                             class="rounded-circle mb-3"
                             style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #e9ecef;">
                    @else
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 150px; height: 150px;">
                            <i class="bi bi-person-circle text-primary" style="font-size: 80px;"></i>
                        </div>
                    @endif
                    
                    <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                        @csrf
                        @method('POST')
                        
                        <div class="mb-3">
                            <input type="file" 
                                   class="form-control @error('avatar') is-invalid @enderror" 
                                   name="avatar" 
                                   accept="image/jpeg,image/png,image/jpg"
                                   required>
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i>Format: JPG, JPEG, PNG (Max 2MB)
                            </small>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-upload me-2"></i>Upload Foto
                        </button>
                    </form>
                </div>
            </div>

            
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-shield-lock me-2"></i>Ubah Password
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                Password Lama <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   placeholder="Masukkan password lama"
                                   required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">
                                Password Baru <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" 
                                   name="new_password" 
                                   placeholder="Minimal 8 karakter"
                                   required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">
                                Konfirmasi Password Baru <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   placeholder="Ketik ulang password baru"
                                   required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-key me-2"></i>Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
