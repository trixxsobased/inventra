<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - SMKN 1 Jenangan Ponorogo</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
        }
        
        /* Official Header */
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .header .logo {
            display: inline-block;
            width: 70px;
            height: 70px;
            vertical-align: middle;
        }
        
        .header .school-info {
            display: inline-block;
            vertical-align: middle;
            margin-left: 15px;
        }
        
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .header p {
            font-size: 10pt;
            margin: 1px 0;
        }
        
        /* Report Title */
        .report-title {
            text-align: center;
            margin: 25px 0;
        }
        
        .report-title h3 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        
        .report-title p {
            font-size: 11pt;
        }
        
        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        
        table td {
            border: 1px solid #000;
            padding: 6px 8px;
        }
        
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        /* Signatures */
        .signatures {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        
        .signature-row {
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 10px;
        }
        
        .signature-box p {
            margin: 5px 0;
        }
        
        .signature-space {
            height: 60px;
            margin: 10px 0;
        }
        
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        
        /* Footer */
        .footer {
            position: fixed;
            bottom: 10mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
        
        /* Utility Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mt-20 { margin-top: 20px; }
        
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    
    <div class="header">
        <table style="border: none; width: 100%;">
            <tr style="border: none;">
                <td style="border: none; width: 90px; text-align: center; vertical-align: middle;">
                    
                    <img src="{{ asset('assets/images/logo_tutwuri.png') }}" 
                         alt="Logo" 
                         style="width: 70px; height: 70px; object-fit: contain;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display: none; width: 70px; height: 70px; border: 2px solid #000; border-radius: 50%; line-height: 66px; font-weight: bold; font-size: 10pt;">LOGO</div>
                </td>
                <td style="border: none; text-align: center; vertical-align: middle;">
                    <h1 style="font-size: 14pt; margin: 0;">PEMERINTAH PROVINSI JAWA TIMUR</h1>
                    <h1 style="font-size: 14pt; margin: 0;">DINAS PENDIDIKAN</h1>
                    <h2 style="font-size: 16pt; margin: 4px 0;">SMK NEGERI 1 JENANGAN PONOROGO</h2>
                    <p style="font-size: 9pt; margin: 0;">Jl. Ngrukem, Ngrukem, Jenangan, Kabupaten Ponorogo, Jawa Timur 63492</p>
                    <p style="font-size: 9pt; margin: 0;">Telp: (0352) 311042 | Email: smkn1jenangan@gmail.com</p>
                </td>
                <td style="border: none; width: 90px;">
                    
                </td>
            </tr>
        </table>
    </div>
    
    
    <div class="report-title">
        <h3>{{ strtoupper($title) }}</h3>
    </div>
    
    
    <div class="content">
        @yield('report-content')
    </div>
    
    
    <div class="signatures">
        <div class="signature-row">
            <div class="signature-box">
                <p>Mengetahui,</p>
                <p class="font-bold">Kepala Laboratorium</p>
                <div class="signature-space"></div>
                <p class="signature-name">________________________</p>
                <p>NIP. ____________________</p>
            </div>
            
            <div class="signature-box">
                <p>Ponorogo, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
                <p class="font-bold">Petugas Inventaris</p>
                <div class="signature-space"></div>
                <p class="signature-name">{{ auth()->user()->name }}</p>
                <p>NIP. ____________________</p>
            </div>
        </div>
    </div>
    
    
    <div class="footer no-print">
        <p>Dokumen ini dibuat oleh Sistem Inventra - SMK Negeri 1 Jenangan Ponorogo</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
    
    
    <div class="no-print" style="position: fixed; top: 20px; right: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14pt; cursor: pointer;">
            🖨️ Cetak
        </button>
    </div>
</body>
</html>
