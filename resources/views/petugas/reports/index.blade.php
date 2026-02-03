@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')
<div class="page-heading">
    <h3>Laporan Sistem</h3>
</div>

<div class="page-content">
    <div class="row">
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Generate Laporan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('petugas.reports.generate') }}" method="GET" target="_blank">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="report_type" class="form-label">Jenis Laporan <span class="text-danger">*</span></label>
                                <select class="form-select" id="report_type" name="type" required>
                                    <option value="">Pilih Jenis Laporan</option>
                                    <option value="borrowings">Laporan Peminjaman</option>
                                    <option value="fines">Laporan Denda</option>
                                    <option value="equipment">Laporan Alat</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="period" class="form-label">Periode <span class="text-danger">*</span></label>
                                <select class="form-select" id="period" name="period" required>
                                    <option value="">Pilih Periode</option>
                                    <option value="today">Hari Ini</option>
                                    <option value="week">Minggu Ini</option>
                                    <option value="month">Bulan Ini</option>
                                    <option value="year">Tahun Ini</option>
                                    <option value="custom">Kustom</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row" id="custom_dates" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="format" class="form-label">Format Output</label>
                            <select class="form-select" id="format" name="format">
                                <option value="pdf">PDF (Cetak/Download)</option>
                                <option value="excel">Excel (Spreadsheet)</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-file-earmark-text"></i> Generate Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Laporan Cepat</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('petugas.reports.generate', ['type' => 'borrowings', 'period' => 'week']) }}" 
                           target="_blank"
                           class="btn btn-outline-primary">
                            <i class="bi bi-calendar-week"></i> Peminjaman Minggu Ini
                        </a>
                        
                        <a href="{{ route('petugas.reports.generate', ['type' => 'borrowings', 'period' => 'month']) }}" 
                           target="_blank"
                           class="btn btn-outline-primary">
                            <i class="bi bi-calendar-month"></i> Peminjaman Bulan Ini
                        </a>
                        
                        <a href="{{ route('petugas.reports.generate', ['type' => 'fines', 'period' => 'month']) }}" 
                           target="_blank"
                           class="btn btn-outline-warning">
                            <i class="bi bi-cash"></i> Denda Bulan Ini
                        </a>
                        
                        <a href="{{ route('petugas.reports.generate', ['type' => 'equipment', 'period' => 'year']) }}" 
                           target="_blank"
                           class="btn btn-outline-success">
                            <i class="bi bi-box"></i> Inventaris Tahunan
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Informasi</h4>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">
                        <i class="bi bi-info-circle"></i> Laporan akan dibuka di tab baru
                    </p>
                    <p class="small text-muted mb-2">
                        <i class="bi bi-printer"></i> Format PDF siap untuk dicetak
                    </p>
                    <p class="small text-muted mb-0">
                        <i class="bi bi-file-earmark-excel"></i> Format Excel untuk analisis lebih lanjut
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const periodSelect = document.getElementById('period');
    const customDates = document.getElementById('custom_dates');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    periodSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customDates.style.display = 'flex';
            startDateInput.required = true;
            endDateInput.required = true;
        } else {
            customDates.style.display = 'none';
            startDateInput.required = false;
            endDateInput.required = false;
        }
    });
    
    // Validasi tanggal akhir >= tanggal mulai
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        
        // Jika end date sudah diisi dan lebih awal dari start date, reset
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = '';
            endDateInput.classList.add('is-invalid');
            let errorDiv = endDateInput.nextElementSibling;
            if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                endDateInput.parentNode.appendChild(errorDiv);
            }
            errorDiv.textContent = '⚠️ Tanggal Akhir harus sama dengan atau setelah Tanggal Mulai!';
        } else {
            endDateInput.classList.remove('is-invalid');
        }
    });
    
    // Validasi real-time saat end date berubah
    endDateInput.addEventListener('change', function() {
        if (startDateInput.value && this.value < startDateInput.value) {
            this.value = '';
            this.classList.add('is-invalid');
            let errorDiv = this.nextElementSibling;
            if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                this.parentNode.appendChild(errorDiv);
            }
            errorDiv.textContent = '⚠️ Tanggal Akhir tidak boleh lebih awal dari Tanggal Mulai!';
        } else {
            this.classList.remove('is-invalid');
        }
    });
</script>
@endpush
@endsection
