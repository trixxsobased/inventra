@extends('layouts.app')

@section('title', 'Manajemen Denda')
@section('page-title', 'Manajemen Denda')

@section('content')
<div class="page-heading">
    <h3>Daftar Denda</h3>
</div>

<div class="page-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Terlambat</th>
                            <th>Jumlah Denda</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fines as $fine)
                            <tr>
                                <td>{{ $fine->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <strong>{{ $fine->borrowing->user->name }}</strong><br>
                                    <small class="text-muted">{{ $fine->borrowing->user->email }}</small>
                                </td>
                                <td>{{ $fine->borrowing->equipment->name }}</td>
                                <td>
                                    <span class="badge bg-danger">{{ $fine->days_late }} hari</span>
                                </td>
                                <td>
                                    <strong>{{ $fine->formatted_amount }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ number_format($fine->rate_per_day, 0, ',', '.') }}/hari
                                    </small>
                                </td>
                                <td>
                                    @if($fine->is_paid)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Lunas
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $fine->paid_at->format('d/m/Y') }}</small>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-circle"></i> Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$fine->is_paid)
                                        <form action="{{ route('admin.fines.pay', $fine->id) }}" 
                                              method="POST"
                                              onsubmit="event.preventDefault(); confirmAction(this, {title: 'Tandai Lunas?', text: 'Denda sebesar {{ $fine->formatted_amount }} akan ditandai sebagai sudah dibayar.', icon: 'question', confirmButtonText: '<i class=\'bi bi-cash-coin me-1\'></i> Ya, Tandai Lunas', confirmButtonColor: '#059669'})">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-cash"></i> Tandai Lunas
                                            </button>
                                        </form>
                                    @else
                                        <small class="text-muted">
                                            Diterima: {{ $fine->receivedBy->name ?? '-' }}
                                        </small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-cash-stack fs-2 text-muted"></i>
                                    <p class="text-muted mt-2">Tidak ada denda</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $fines->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
