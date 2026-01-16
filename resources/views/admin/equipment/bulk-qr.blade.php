@extends('layouts.app')

@section('title', 'Print QR Code')
@section('page-title', 'Print QR Code Alat')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Print QR Code</h3>
        <div>
            <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Print
            </button>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-body">
            <div class="row" id="qr-container">
                @foreach($qrCodes as $item)
                <div class="col-md-3 col-6 mb-4 qr-item">
                    <div class="card h-100 text-center">
                        <div class="card-body p-2">
                            <div class="qr-code mb-2">
                                <img src="data:image/svg+xml;base64,{{ $item['qr'] }}" 
                                     alt="QR {{ $item['equipment']->code }}"
                                     class="img-fluid"
                                     style="max-width: 150px;">
                            </div>
                            <div class="qr-info">
                                <strong class="d-block text-truncate" style="font-size: 0.8rem;">
                                    {{ $item['equipment']->code }}
                                </strong>
                                <small class="text-muted text-truncate d-block">
                                    {{ Str::limit($item['equipment']->name, 25) }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .page-heading .btn,
    .sidebar-wrapper,
    .navbar,
    .footer {
        display: none !important;
    }
    
    .page-content {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .qr-item {
        page-break-inside: avoid;
    }
    
    #qr-container {
        display: flex;
        flex-wrap: wrap;
    }
    
    .col-md-3 {
        width: 25% !important;
        flex: 0 0 25% !important;
    }
}
</style>
@endsection
