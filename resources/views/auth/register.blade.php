@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="auth-logo">
    <h2 style="color: #435ebe; font-weight: 700;">
        <i class="bi bi-box-seam"></i> Inventra
    </h2>
</div>

<h1 class="auth-title">Daftar</h1>
<p class="auth-subtitle mb-4">Buat akun baru untuk mulai meminjam alat.</p>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('register') }}" method="POST">
    @csrf
    
    <div class="form-group mb-3">
        <label for="name" class="form-label">Nama Lengkap</label>
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
    
    <div class="form-group mb-3">
        <label for="username" class="form-label">Username</label>
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
    
    <div class="form-group mb-3">
        <label for="email" class="form-label">Email</label>
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
    
    <div class="form-group mb-3">
        <label for="phone" class="form-label">Nomor Telepon</label>
        <input type="text" 
               class="form-control @error('phone') is-invalid @enderror" 
               id="phone"
               name="phone" 
               value="{{ old('phone') }}">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="form-group mb-3">
        <label for="address" class="form-label">Alamat</label>
        <textarea class="form-control @error('address') is-invalid @enderror" 
                  id="address"
                  name="address" 
                  rows="2">{{ old('address') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="form-group mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               id="password"
               name="password" 
               required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="form-group mb-4">
        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
        <input type="password" 
               class="form-control" 
               id="password_confirmation"
               name="password_confirmation" 
               required>
    </div>
    
    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg">Daftar</button>
</form>

<div class="text-center mt-4">
    <p class="text-gray-600">
        Sudah punya akun? 
        <a href="{{ route('login') }}" class="font-bold">Masuk</a>
    </p>
</div>
@endsection
