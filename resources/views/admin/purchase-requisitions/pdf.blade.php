<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pengadaan Alat</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16pt;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0;
            font-size: 10pt;
        }
        .nomor-surat {
            margin: 20px 0;
        }
        .perihal {
            margin-bottom: 20px;
        }
        .content {
            text-align: justify;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .signature {
            margin-top: 40px;
        }
        .signature-box {
            display: inline-block;
            width: 45%;
            text-align: center;
        }
        .signature-box.right {
            float: right;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            display: inline-block;
            width: 200px;
        }
        .priority-urgent {
            color: #dc3545;
            font-weight: bold;
        }
        .priority-high {
            color: #fd7e14;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>PEMERINTAH PROVINSI JAWA TIMUR</h2>
        <h2>DINAS PENDIDIKAN</h2>
        <h2>SMK NEGERI 1 JENANGAN</h2>
        <p>Jl. Raya Jenangan, Ponorogo, Jawa Timur</p>
        <p>Telp: (0352) 461208 | Email: smkn1jenangan@gmail.com</p>
    </div>

    <div class="nomor-surat">
        <table style="border: none; width: 60%;">
            <tr style="border: none;">
                <td style="border: none; width: 30%;">Nomor</td>
                <td style="border: none;">: {{ sprintf('%03d', $purchaseRequisition->id) }}/PR/SMK1/{{ date('m/Y') }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">Lampiran</td>
                <td style="border: none;">: 1 (satu) lembar</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">Perihal</td>
                <td style="border: none;">: <strong>Permohonan Pengadaan Alat Praktik</strong></td>
            </tr>
        </table>
    </div>

    <p>Kepada Yth.<br>
    <strong>Kepala Sekolah SMK Negeri 1 Jenangan</strong><br>
    di <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tempat</p>

    <p>Dengan hormat,</p>

    <div class="content">
        <p>Sehubungan dengan 
        @if($purchaseRequisition->reason === 'replacement')
            <strong>kebutuhan penggantian alat yang rusak berat</strong>
        @elseif($purchaseRequisition->reason === 'new_stock')
            <strong>penambahan stok alat praktik</strong>
        @else
            <strong>pengembangan laboratorium</strong>
        @endif
        di lingkungan SMK Negeri 1 Jenangan, bersama ini kami mengajukan permohonan pengadaan alat sebagai berikut:</p>

        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Nama Alat</th>
                    <th width="20%">Kategori</th>
                    <th width="10%">Jumlah</th>
                    <th width="15%">Harga Satuan</th>
                    <th width="15%">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td><strong>{{ $purchaseRequisition->item_name }}</strong></td>
                    <td>{{ $purchaseRequisition->category->name }}</td>
                    <td style="text-align: center;">{{ $purchaseRequisition->quantity }} unit</td>
                    <td style="text-align: right;">Rp {{ number_format($purchaseRequisition->estimated_price, 0, ',', '.') }}</td>
                    <td style="text-align: right;"><strong>Rp {{ number_format($purchaseRequisition->estimated_price * $purchaseRequisition->quantity, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: right;"><strong>TOTAL ESTIMASI</strong></td>
                    <td style="text-align: right; background-color: #f8f9fa;"><strong>Rp {{ number_format($purchaseRequisition->estimated_price * $purchaseRequisition->quantity, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <p><strong>Alasan/Justifikasi:</strong></p>
        <p style="padding-left: 20px;">{{ $purchaseRequisition->justification }}</p>

        @if($purchaseRequisition->priority === 'urgent' || $purchaseRequisition->priority === 'high')
        <p>
            <strong 
                @if($purchaseRequisition->priority === 'urgent') 
                    class="priority-urgent"
                @else 
                    class="priority-high"
                @endif
            >
                PRIORITAS: {{ strtoupper($purchaseRequisition->priority) }}
            </strong>
        </p>
        @endif

        <p>Demikian surat permohonan ini kami sampaikan. Atas perhatian dan persetujuan Bapak/Ibu, kami ucapkan terima kasih.</p>
    </div>

    <div class="signature">
        <div class="signature-box">
            <p>Mengetahui,<br>
            <strong>Kepala Laboratorium</strong></p>
            <div class="signature-line"></div>
            <p><strong>{{ $purchaseRequisition->requestedBy->name }}</strong><br>
            NIP. -</p>
        </div>

        <div class="signature-box right">
            <p>Jenangan, {{ date('d F Y') }}<br>
            <strong>Kepala Sekolah</strong></p>
            <div class="signature-line"></div>
            <p><strong>(...................................)</strong><br>
            NIP. ...................................</p>
        </div>
    </div>

    <div style="clear: both; margin-top: 80px; font-size: 9pt; color: #666;">
        <p>Dokumen ini digenerate oleh Sistem Inventra pada {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
