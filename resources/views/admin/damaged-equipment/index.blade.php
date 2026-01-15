@extends('layouts.app')

@section('title', 'Log Barang Rusak')
@section('page-title', 'Log Barang Rusak')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Log Barang Rusak</h3>
    </div>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-body">
            <!-- Filter & Search -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="{{ route('admin.damaged-equipment.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Cari barang rusak..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode Alat</th>
                            <th>Nama Alat</th>
                            <th>Pelapor</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($damagedEquipments as $item)
                        <tr>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td><code>{{ $item->equipment->code }}</code></td>
                            <td><strong>{{ $item->equipment->name }}</strong></td>
                            <td>{{ $item->reportedBy->name ?? 'Sistem' }}</td>
                            <td>{{ Str::limit($item->description, 50) }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                    <i class="bi bi-eye"></i> Detail
                                </button>
                            </td>
                        </tr>

                        <!-- Detail Modal -->
                        <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Kerusakan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="fw-bold">Barang:</label>
                                            <p>{{ $item->equipment->name }} ({{ $item->equipment->code }})</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold">Tanggal Lapor:</label>
                                            <p>{{ $item->created_at->format('d F Y H:i') }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold">Pelapor:</label>
                                            <p>{{ $item->reportedBy->name ?? '-' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold">Deskripsi Kerusakan:</label>
                                            <p class="bg-light p-2 rounded">{{ $item->description }}</p>
                                        </div>
                                        @if($item->borrowing)
                                        <div class="alert alert-warning">
                                            <i class="bi bi-info-circle"></i> Kerusakan dilaporkan dari transaksi peminjaman.
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-check-circle fs-1 text-success"></i>
                                <p class="text-muted mt-2">Tidak ada log kerusakan barang.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $damagedEquipments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
