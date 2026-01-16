@extends('layouts.app')

@section('title', 'Scan QR Code')
@section('page-title', 'Scan QR Code Alat')

@push('styles')
<style>
    #qr-reader {
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }
    
    #qr-reader video {
        border-radius: 8px;
    }
    
    .scan-result {
        display: none;
    }
    
    .scan-result.show {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Scan QR Code</h3>
        <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <p class="text-muted">Arahkan kamera ke QR Code pada alat</p>
                    </div>
                    
                    <div id="qr-reader"></div>
                    
                    <div id="scan-result" class="scan-result mt-4">
                        <div class="alert alert-success">
                            <h5 class="alert-heading"><i class="bi bi-check-circle"></i> QR Code Terdeteksi!</h5>
                            <p class="mb-2">Redirecting ke halaman detail alat...</p>
                            <a id="result-link" href="#" class="btn btn-primary btn-sm">
                                Lihat Detail Alat
                            </a>
                        </div>
                    </div>
                    
                    <div id="scan-error" class="scan-result mt-4">
                        <div class="alert alert-warning">
                            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> QR Code Tidak Valid</h5>
                            <p class="mb-0">QR Code ini bukan untuk sistem Inventra.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Atau Masukkan Kode Manual</h5>
                </div>
                <div class="card-body">
                    <form id="manual-search" class="row g-3">
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="equipment-code" 
                                   placeholder="Masukkan kode alat (contoh: INV-RPL-2026-001)">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const html5QrCode = new Html5Qrcode("qr-reader");
    const baseUrl = "{{ url('/') }}";
    
    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0
    };
    
    function onScanSuccess(decodedText, decodedResult) {
        // Cek apakah URL valid dari sistem ini
        if (decodedText.startsWith(baseUrl + '/equipment/')) {
            document.getElementById('scan-result').classList.add('show');
            document.getElementById('scan-error').classList.remove('show');
            document.getElementById('result-link').href = decodedText;
            
            // Auto redirect setelah 1 detik
            setTimeout(() => {
                window.location.href = decodedText;
            }, 1000);
            
            // Stop scanning
            html5QrCode.stop();
        } else {
            document.getElementById('scan-error').classList.add('show');
            document.getElementById('scan-result').classList.remove('show');
        }
    }
    
    function onScanFailure(error) {
        // Ignore scan failures (biasanya karena tidak ada QR terdeteksi)
    }
    
    // Start scanning
    html5QrCode.start(
        { facingMode: "environment" },
        config,
        onScanSuccess,
        onScanFailure
    ).catch((err) => {
        console.error('Camera error:', err);
        document.getElementById('qr-reader').innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-camera-video-off"></i> Tidak dapat mengakses kamera. 
                Pastikan browser memiliki izin mengakses kamera.
            </div>
        `;
    });
    
    // Manual search
    document.getElementById('manual-search').addEventListener('submit', function(e) {
        e.preventDefault();
        const code = document.getElementById('equipment-code').value;
        if (code) {
            // Redirect to browse dengan search parameter
            window.location.href = "{{ route('admin.equipment.index') }}?search=" + encodeURIComponent(code);
        }
    });
});
</script>
@endpush
@endsection
