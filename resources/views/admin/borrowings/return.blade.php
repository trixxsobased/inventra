@extends('layouts.app')

@section('title', 'Proses Pengembalian')
@section('page-title', 'Proses Pengembalian')

@section('content')
<div class="page-heading">
    <h3>Form Pengembalian Alat</h3>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi Pengembalian</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.borrowings.process-return', $borrowing->id) }}" method="POST">
                        @csrf
                        
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle"></i> Info Denda</h6>
                            @if($fineInfo['is_late'])
                                <p class="mb-1">
                                    Peminjaman <strong>terlambat {{ $fineInfo['days_late'] }} hari</strong>.
                                </p>
                                <p class="mb-0">
                                    <strong>Total Denda: {{ $fineInfo['fine_amount_formatted'] }}</strong>
                                    <small class="text-muted">({{ $fineInfo['rate_per_day_formatted'] }}/hari)</small>
                                </p>
                            @else
                                <p class="mb-0">{{ $fineInfo['message'] }}</p>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="actual_return_date" class="form-label">Tanggal Pengembalian <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('actual_return_date') is-invalid @enderror" 
                                   id="actual_return_date" 
                                   name="actual_return_date" 
                                   value="{{ old('actual_return_date', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('actual_return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @if($fineInfo['is_late'])
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="fine_paid" 
                                           id="fine_paid"
                                           value="1">
                                    <label class="form-check-label" for="fine_paid">
                                        Denda sudah dibayar lunas
                                    </label>
                                </div>
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.borrowings.active') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" 
                                    class="btn btn-success"
                                    onclick="return confirm('Konfirmasi pengembalian alat ini?')">
                                <i class="bi bi-check-circle"></i> Proses Pengembalian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Peminjaman</h4>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Peminjam</th>
                            <td>{{ $borrowing->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Alat</th>
                            <td>{{ $borrowing->equipment->name }}</td>
                        </tr>
                        <tr>
                            <th>Tgl Pinjam</th>
                            <td>{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Deadline</th>
                            <td>{{ $borrowing->planned_return_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tujuan</th>
                            <td>{{ $borrowing->purpose }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
