@extends('layouts.app')

@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman')

@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi Peminjaman</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Kode Transaksi</th>
                            <td><code>TRX-{{ $borrowing->borrow_date->format('Ymd') }}-{{ str_pad($borrowing->id, 3, '0', STR_PAD_LEFT) }}</code></td>
                        </tr>
                        <tr>
                            <th>Tanggal Pinjam</th>
                            <td>{{ $borrowing->borrow_date->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Peminjam</th>
                            <td>
                                <strong>{{ $borrowing->user->name }}</strong><br>
                                <small class="text-muted">{{ $borrowing->user->email }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Alat</th>
                            <td>
                                <strong>{{ $borrowing->equipment->name }}</strong><br>
                                <small class="text-muted">{{ $borrowing->equipment->code }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>{{ $borrowing->equipment->category->name }}</td>
                        </tr>
                        <tr>
                            <th>Rencana Kembali</th>
                            <td>{{ $borrowing->planned_return_date->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Kembali</th>
                            <td>
                                @if($borrowing->actual_return_date)
                                    {{ $borrowing->actual_return_date->format('d F Y') }}
                                @else
                                    <span class="badge bg-warning">Belum Dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($borrowing->status === 'pending')
                                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                                @elseif($borrowing->status === 'borrowed')
                                    <span class="badge bg-info">Sedang Dipinjam</span>
                                @elseif($borrowing->status === 'returned')
                                    <span class="badge bg-success">Sudah Dikembalikan</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tujuan</th>
                            <td>{{ $borrowing->purpose }}</td>
                        </tr>
                        @if($borrowing->notes)
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $borrowing->notes }}</td>
                        </tr>
                        @endif
                        @if($borrowing->verified_by)
                        <tr>
                            <th>Diverifikasi Oleh</th>
                            <td>
                                {{ $borrowing->verifiedBy->name }}<br>
                                <small class="text-muted">{{ $borrowing->verified_at->format('d F Y H:i') }}</small>
                            </td>
                        </tr>
                        @endif
                        @if($borrowing->rejection_reason)
                        <tr>
                            <th>Alasan Ditolak</th>
                            <td><span class="text-danger">{{ $borrowing->rejection_reason }}</span></td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            @if($borrowing->fine)
            <div class="card mt-3">
                <div class="card-header bg-warning">
                    <h4 class="card-title text-white">Informasi Denda</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Keterlambatan</th>
                            <td>{{ $borrowing->fine->days_late }} hari</td>
                        </tr>
                        <tr>
                            <th>Tarif per Hari</th>
                            <td>Rp {{ number_format($borrowing->fine->rate_per_day, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Total Denda</th>
                            <td><strong class="text-danger">Rp {{ number_format($borrowing->fine->amount, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <th>Status Pembayaran</th>
                            <td>
                                @if($borrowing->fine->is_paid)
                                    <span class="badge bg-success">Lunas</span><br>
                                    <small class="text-muted">Dibayar: {{ $borrowing->fine->paid_at->format('d F Y') }}</small>
                                @else
                                    <span class="badge bg-danger">Belum Bayar</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Aksi</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.borrowings.active') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        
                        @if($borrowing->status === 'pending')
                            <a href="{{ route('admin.borrowings.pending') }}" class="btn btn-warning">
                                <i class="bi bi-clock"></i> Ke Halaman Verifikasi
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
