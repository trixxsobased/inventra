@extends('layouts.app')

@section('title', 'Pengajuan Pembelian')
@section('page-title', 'Pengajuan Pembelian')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Daftar Pengajuan Pembelian</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-circle"></i> Buat Pengajuan Baru
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

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <!-- Filter -->
            <form method="GET" action="{{ route('admin.purchase-requisitions.index') }}" class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Cari pengajuan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="all">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="ordered" {{ request('status') === 'ordered' ? 'selected' : '' }}>Ordered</option>
                        <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>Received</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="priority" class="form-select" onchange="this.form.submit()">
                        <option value="all">Semua Prioritas</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama Alat</th>
                            <th>Kategori</th>
                            <th>Qty</th>
                            <th>Est. Harga</th>
                            <th>Alasan</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Diajukan</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requisitions as $index => $req)
                            <tr>
                                <td>{{ $requisitions->firstItem() + $index }}</td>
                                <td><strong>{{ $req->item_name }}</strong></td>
                                <td>{{ $req->category->name }}</td>
                                <td>{{ $req->quantity }}</td>
                                <td>Rp {{ number_format($req->estimated_price, 0, ',', '.') }}</td>
                                <td>
                                    @if($req->reason === 'replacement')
                                        <span class="badge bg-warning">Pengganti</span>
                                    @elseif($req->reason === 'new_stock')
                                        <span class="badge bg-info">Stok Baru</span>
                                    @else
                                        <span class="badge bg-success">Ekspansi</span>
                                    @endif
                                </td>
                                <td>
                                    @if($req->priority === 'urgent')
                                        <span class="badge bg-danger">URGENT</span>
                                    @elseif($req->priority === 'high')
                                        <span class="badge bg-warning">High</span>
                                    @elseif($req->priority === 'medium')
                                        <span class="badge bg-info">Medium</span>
                                    @else
                                        <span class="badge bg-secondary">Low</span>
                                    @endif
                                </td>
                                <td>
                                    @if($req->status === 'pending')
                                        <span class="badge bg-secondary">Pending</span>
                                    @elseif($req->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($req->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @elseif($req->status === 'ordered')
                                        <span class="badge bg-primary">Ordered</span>
                                    @else
                                        <span class="badge bg-success">Received</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $req->created_at->format('d/m/Y') }}</small><br>
                                    <small class="text-muted">{{ $req->requestedBy->name }}</small>
                                </td>
                                <td>
                                    <div class="btn-group rounded-pill shadow-sm" role="group" style="overflow: hidden;">
                                        <button type="button" class="btn btn-sm btn-info px-3 border-0" data-bs-toggle="modal" data-bs-target="#detailModal{{ $req->id }}" title="Detail">
                                            <i class="bi bi-info-circle"></i>
                                        </button>
                                        @if($req->status === 'pending')
                                            <button type="button" class="btn btn-sm btn-warning px-3 border-0" data-bs-toggle="modal" data-bs-target="#editModal{{ $req->id }}" title="Edit">
                                                <i class="bi bi-pencil text-dark"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="bi bi-inbox fs-2 text-muted"></i>
                                    <p class="text-muted mt-2">Belum ada pengajuan pembelian</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $requisitions->links() }}
            </div>
        </div>
    </div>
</div>

{{-- MODALS SECTION - Outside table --}}
@foreach($requisitions as $req)
    {{-- Detail Modal --}}
    <div class="modal fade" id="detailModal{{ $req->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">Detail Pengajuan #{{ $req->id }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-4">
                    <div class="card bg-secondary bg-opacity-10 border-0 mb-4">
                        <div class="card-body">
                            <table class="table table-borderless table-sm mb-0">
                                <tr><td class="text-muted" width="35%">Nama Alat</td><td><strong class="text-dark-emphasis">{{ $req->item_name }}</strong></td></tr>
                                <tr><td class="text-muted">Kategori</td><td>{{ $req->category->name }}</td></tr>
                                <tr><td class="text-muted">Jumlah</td><td>{{ $req->quantity }} unit</td></tr>
                                <tr><td class="text-muted">Harga/unit</td><td>Rp {{ number_format($req->estimated_price, 0, ',', '.') }}</td></tr>
                                <tr><td class="text-muted">Total</td><td><strong class="text-primary">Rp {{ number_format($req->estimated_price * $req->quantity, 0, ',', '.') }}</strong></td></tr>
                            </table>
                        </div>
                    </div>
                    
                    <h6 class="text-uppercase text-secondary small fw-bold mb-2">Justifikasi</h6>
                    <div class="p-3 rounded bg-secondary bg-opacity-10 border border-secondary border-opacity-10 mb-4">
                        {{ $req->justification }}
                    </div>

                    @if($req->reviewed_at)
                        <h6 class="text-uppercase text-secondary small fw-bold mb-2">Review</h6>
                        <div class="p-3 rounded bg-light bg-opacity-5 border border-secondary border-opacity-10">
                            <p class="mb-1"><small class="text-muted">Oleh:</small> <strong>{{ $req->reviewedBy->name }}</strong> <small class="text-muted">({{ $req->reviewed_at->format('d/m/Y H:i') }})</small></p>
                            @if($req->review_notes)
                                <div class="mt-2 text-{{ $req->status === 'approved' ? 'success' : 'danger' }}">
                                    <i class="bi bi-chat-quote-fill me-1"></i> {{ $req->review_notes }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-0 pt-0 pb-4">
                    @if($req->status === 'pending' && auth()->user()->role === 'admin')
                        <form action="{{ route('admin.purchase-requisitions.approve', $req) }}" method="POST" class="d-inline" onsubmit="event.preventDefault(); confirmAction(this, {title: 'Setujui Pengajuan?', text: 'Pengajuan pembelian {{ $req->item_name }} ({{ $req->quantity }} unit) akan disetujui.', icon: 'question', confirmButtonText: '<i class=\'bi bi-check-circle me-1\'></i> Ya, Setujui', confirmButtonColor: '#059669'})">
                            @csrf
                            <button type="submit" class="btn btn-success rounded-pill px-3">
                                <i class="bi bi-check-circle"></i> Approve
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $req->id }}">
                            <i class="bi bi-x-circle"></i> Reject
                        </button>
                    @endif
                    @if($req->status === 'approved' && auth()->user()->role === 'admin')
                        <form action="{{ route('admin.purchase-requisitions.receive', $req) }}" method="POST" class="d-inline" onsubmit="event.preventDefault(); confirmAction(this, {title: 'Terima Barang?', text: 'Konfirmasi penerimaan barang {{ $req->item_name }} sejumlah {{ $req->quantity }}. Stok akan otomatis bertambah.', icon: 'question', confirmButtonText: '<i class=\'bi bi-box-seam me-1\'></i> Ya, Terima Barang', confirmButtonColor: '#059669'})">
                            @csrf
                            <button type="submit" class="btn btn-primary rounded-pill px-3">
                                <i class="bi bi-box-seam"></i> Terima Barang
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.purchase-requisitions.export-pdf', $req) }}" class="btn btn-outline-danger rounded-pill px-3" target="_blank">
                        <i class="bi bi-file-pdf"></i> PDF
                    </a>
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    @if($req->status === 'pending')
    <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.purchase-requisitions.reject', $req) }}" method="POST">
                    @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-danger">Tolak Pengajuan #{{ $req->id }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-4">
                    <div class="text-center mb-4">
                        <div class="avatar bg-danger bg-opacity-10 text-danger rounded-circle p-3 d-inline-flex mb-3">
                            <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                        </div>
                        <h5>Yakin tolak pengajuan ini?</h5>
                        <p class="text-muted">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">ALASAN PENOLAKAN <span class="text-danger">*</span></label>
                        <textarea name="review_notes" class="form-control" rows="3" required placeholder="Berikan alasan yang jelas..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4"><i class="bi bi-x-circle"></i> Tolak</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Edit Modal --}}
    @if($req->status === 'pending')
    <div class="modal fade" id="editModal{{ $req->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.purchase-requisitions.update', $req) }}" method="POST">
                    @csrf
                    @method('PUT')
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">Edit Pengajuan #{{ $req->id }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" name="category_id" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $req->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Alat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="item_name" value="{{ $req->item_name }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="quantity" value="{{ $req->quantity }}" min="1" required>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Est. Harga/unit <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="estimated_price" value="{{ $req->estimated_price }}" min="0" step="1000" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Alasan <span class="text-danger">*</span></label>
                                <select class="form-select" name="reason" required>
                                    <option value="replacement" {{ $req->reason === 'replacement' ? 'selected' : '' }}>Pengganti Rusak</option>
                                    <option value="new_stock" {{ $req->reason === 'new_stock' ? 'selected' : '' }}>Stok Baru</option>
                                    <option value="expansion" {{ $req->reason === 'expansion' ? 'selected' : '' }}>Ekspansi</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                                <select class="form-select" name="priority" required>
                                    <option value="low" {{ $req->priority === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $req->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $req->priority === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ $req->priority === 'urgent' ? 'selected' : '' }}>URGENT</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Justifikasi <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="justification" rows="4" required>{{ $req->justification }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill"><i class="bi bi-save"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

{{-- Create Modal --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.purchase-requisitions.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">Buat Pengajuan Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Alat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('item_name') is-invalid @enderror" name="item_name" value="{{ old('item_name') }}" placeholder="Contoh: Laptop ASUS ROG" required>
                            @error('item_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                            <small class="text-muted">Jumlah unit yang dibutuhkan</small>
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Est. Harga/unit <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('estimated_price') is-invalid @enderror" name="estimated_price" value="{{ old('estimated_price', 0) }}" min="0" step="1000" placeholder="15000000" required>
                            <small class="text-muted">Estimasi harga per unit dalam Rupiah</small>
                            @error('estimated_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alasan <span class="text-danger">*</span></label>
                            <select class="form-select @error('reason') is-invalid @enderror" name="reason" required>
                                <option value="">Pilih Alasan</option>
                                <option value="replacement" {{ old('reason') === 'replacement' ? 'selected' : '' }}>Pengganti Rusak</option>
                                <option value="new_stock" {{ old('reason') === 'new_stock' ? 'selected' : '' }}>Stok Baru</option>
                                <option value="expansion" {{ old('reason') === 'expansion' ? 'selected' : '' }}>Ekspansi</option>
                            </select>
                            @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                            <select class="form-select @error('priority') is-invalid @enderror" name="priority" required>
                                <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>URGENT</option>
                            </select>
                            @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Justifikasi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('justification') is-invalid @enderror" name="justification" rows="4" placeholder="Jelaskan alasan pengajuan dan urgensi kebutuhan alat ini..." required>{{ old('justification') }}</textarea>
                        <small class="text-muted">Berikan penjelasan detail mengapa alat ini dibutuhkan</small>
                        @error('justification')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill"><i class="bi bi-send"></i> Ajukan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener(' DOMContentLoaded', function() {
    @if($errors->any())
        new bootstrap.Modal(document.getElementById('createModal')).show();
    @endif
});
</script>
@endpush
@endsection
