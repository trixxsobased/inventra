<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Peminjaman - {{ $borrowing->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #666; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .info-item { margin-bottom: 15px; }
        .label { font-weight: bold; display: block; margin-bottom: 5px; }
        .value { border-bottom: 1px dotted #ccc; padding-bottom: 5px; }
        .status-box { text-align: center; border: 2px solid #000; padding: 10px; margin: 20px 0; font-weight: bold; font-size: 18px; text-transform: uppercase; }
        .footer { margin-top: 50px; display: flex; justify-content: space-between; text-align: center; }
        .signature { margin-top: 60px; border-top: 1px solid #000; padding-top: 10px; width: 200px; }
        
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Dokumen</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Tutup</button>
    </div>

    <div class="header">
        <div class="title">BUKTI PEMINJAMAN ALAT</div>
        <div class="subtitle">Inventra - Sistem Inventarisasi Alat Sekolah</div>
        <div>Kode Transaksi: TRX-{{ $borrowing->borrow_date->format('Ymd') }}-{{ str_pad($borrowing->id, 3, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="status-box">
        STATUS: {{ strtoupper($borrowing->status) }}
    </div>

    <div class="info-grid">
        <!-- Peminjam -->
        <div>
            <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px;">Data Peminjam</h3>
            <div class="info-item">
                <span class="label">Nama Lengkap</span>
                <div class="value">{{ $borrowing->user->name }}</div>
            </div>
            <div class="info-item">
                <span class="label">Email</span>
                <div class="value">{{ $borrowing->user->email }}</div>
            </div>
        </div>

        <!-- Alat -->
        <div>
            <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px;">Data Alat</h3>
            <div class="info-item">
                <span class="label">Nama Alat</span>
                <div class="value">{{ $borrowing->equipment->name }}</div>
            </div>
            <div class="info-item">
                <span class="label">Kode Alat</span>
                <div class="value">{{ $borrowing->equipment->code }}</div>
            </div>
            <div class="info-item">
                <span class="label">Kategori</span>
                <div class="value">{{ $borrowing->equipment->category->name }}</div>
            </div>
        </div>
    </div>

    <div class="info-grid">
        <!-- Waktu -->
        <div>
            <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px;">Detail Waktu</h3>
            <div class="info-item">
                <span class="label">Tanggal Pinjam</span>
                <div class="value">{{ $borrowing->borrow_date->format('d F Y') }}</div>
            </div>
            <div class="info-item">
                <span class="label">Rencana Kembali</span>
                <div class="value">{{ $borrowing->planned_return_date->format('d F Y') }}</div>
            </div>
            @if($borrowing->actual_return_date)
            <div class="info-item">
                <span class="label">Dikembalikan Tanggal</span>
                <div class="value">{{ $borrowing->actual_return_date->format('d F Y') }}</div>
            </div>
            @endif
        </div>

        <!-- Lainnya -->
        <div>
            <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px;">Keterangan</h3>
            <div class="info-item">
                <span class="label">Tujuan Peminjaman</span>
                <div class="value">{{ $borrowing->purpose }}</div>
            </div>
            @if($borrowing->fine)
            <div class="info-item">
                <span class="label" style="color: red;">Denda Keterlambatan</span>
                <div class="value">Rp {{ number_format($borrowing->fine->amount, 0, ',', '.') }} ({{ $borrowing->fine->is_paid ? 'LUNAS' : 'BELUM LUNAS' }})</div>
            </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <div>
            <p>Peminjam</p>
            <div class="signature">{{ $borrowing->user->name }}</div>
        </div>
        <div>
            <p>Petugas Verifikasi</p>
            <div class="signature">{{ $borrowing->verifiedBy->name ?? '....................' }}</div>
        </div>
    </div>
</body>
</html>
