@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="login-wrapper">
    
    <div class="login-brand">
        <div class="login-brand-content">
            <div class="brand-icon">
                <i class="bi bi-box-seam"></i>
            </div>
            <h1>Inventra</h1>
            <p>Kelola inventaris sekolah dengan mudah. Sistem modern untuk tracking, peminjaman, dan pelaporan aset secara real-time.</p>
        </div>
    </div>
    
    
    <div class="login-form-wrapper">
        <div class="login-form-container">
            <div class="login-header">
                <h2>Selamat Datang</h2>
                <p>Masuk untuk mengakses sistem</p>
            </div>
            
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Oops!</strong>
                    @if($errors->has('username'))
                        {{ $errors->first('username') }}
                    @elseif($errors->has('password'))
                        {{ $errors->first('password') }}
                    @else
                        {{ $errors->first() }}
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="username" class="form-label">Username atau Email</label>
                    <input type="text" 
                           class="form-control" 
                           id="username"
                           name="username" 
                           placeholder="Masukkan username atau email"
                           value="{{ old('username') }}"
                           required 
                           autofocus>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" 
                           class="form-control" 
                           id="password"
                           name="password" 
                           placeholder="Masukkan password"
                           required>
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Ingat saya
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    Masuk ke Sistem
                </button>
            </form>
            
            <div class="login-footer">
                <p>
                    <i class="bi bi-shield-check"></i> Sistem ini hanya untuk pengguna terdaftar.<br>
                    Hubungi <strong>Admin Laboratorium</strong> untuk mendapatkan akses.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
