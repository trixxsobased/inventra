<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Equipment;
use App\Models\Fine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function generate(Request $request)
    {
        // Menggunakan query parameters
        $type = $request->query('type');
        $period = $request->query('period', 'all');
        
        $startDate = null;
        $endDate = null;
        $title = '';
        $periodText = 'Semua Data';
        
        if ($period === 'today') {
            $startDate = Carbon::today();
            $endDate = Carbon::today()->endOfDay();
            $periodText = 'Hari Ini - ' . Carbon::today()->isoFormat('D MMMM Y');
        } elseif ($period === 'week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
            $periodText = 'Minggu Ini - ' . $startDate->isoFormat('D MMM') . ' s/d ' . $endDate->isoFormat('D MMM Y');
        } elseif ($period === 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            $periodText = 'Bulan ' . Carbon::now()->isoFormat('MMMM Y');
        } elseif ($period === 'year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();
            $periodText = 'Tahun ' . Carbon::now()->year;
        } elseif ($period === 'custom') {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ], [
                'end_date.after_or_equal' => 'Tanggal Akhir harus sama dengan atau setelah Tanggal Mulai.',
            ]);
            
            $startDate = Carbon::parse($request->query('start_date'));
            $endDate = Carbon::parse($request->query('end_date'));
            $periodText = $startDate->isoFormat('D MMM Y') . ' s/d ' . $endDate->isoFormat('D MMM Y');
        }
        
        $data = [];
        $format = $request->query('format', 'pdf');
        
        switch ($type) {
            case 'borrowings':
                $data = $this->getBorrowingsReport($startDate, $endDate);
                $title = 'Laporan Data Peminjaman';
                
                if ($format === 'excel') {
                    return $this->exportBorrowingsToCSV($data, $periodText);
                }
                
                return view('admin.reports.borrowings', compact('data', 'title', 'period', 'periodText', 'startDate', 'endDate'));
                
            case 'fines':
                $data = $this->getFinesReport($startDate, $endDate);
                $title = 'Laporan Denda Keterlambatan';
                
                if ($format === 'excel') {
                    return $this->exportFinesToCSV($data, $periodText);
                }
                
                return view('admin.reports.fines', compact('data', 'title', 'period', 'periodText', 'startDate', 'endDate'));
                
            case 'equipment':
                $data = $this->getEquipmentReport();
                $title = 'Laporan Inventaris Alat';
                $periodText = 'Per Tanggal ' . Carbon::now()->isoFormat('D MMMM Y');
                
                if ($format === 'excel') {
                    return $this->exportEquipmentToCSV($data, $periodText);
                }
                
                return view('admin.reports.equipment', compact('data', 'title', 'period', 'periodText'));
                
            default:
                return redirect()->route('admin.reports.index')
                    ->with('error', 'Tipe laporan tidak valid.');
        }
    }

    public function export(Request $request)
    {
        return redirect()->back()->with('info', 'Fitur export PDF akan segera tersedia.');
    }

    private function getBorrowingsReport($startDate = null, $endDate = null)
    {
        $query = Borrowing::with(['user', 'equipment.category', 'verifiedBy']);
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        return $query->latest()->get();
    }

    private function getFinesReport($startDate = null, $endDate = null)
    {
        $query = Fine::with(['borrowing.user', 'borrowing.equipment']);
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        return $query->latest()->get();
    }

    private function getEquipmentReport()
    {
        return Equipment::withCount(['borrowings' => function ($query) {
                $query->where('status', '!=', 'rejected');
            }])
            ->with('category')
            ->get();
    }
    
    // Fungsi helper: Translate status ke Bahasa Indonesia
    private function translateStatus($status)
    {
        $translations = [
            'pending' => 'Menunggu Verifikasi',
            'borrowed' => 'Sedang Dipinjam',
            'returned' => 'Sudah Dikembalikan',
            'rejected' => 'Ditolak',
        ];
        
        return $translations[$status] ?? ucfirst($status);
    }
    
    private function exportEquipmentToCSV($data, $periodText)
    {
        $filename = 'Laporan_Inventaris_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data, $periodText) {
            $file = fopen('php://output', 'w');
            
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'No', 
                'Kode Alat', 
                'Nama Alat', 
                'Kategori', 
                'Lokasi', 
                'Stok (Unit)', 
                'Harga Satuan (Rp)',
                'Total Nilai (Rp)',
                'Vendor/Supplier',
                'Tahun Beli',
                'Umur (Tahun)',
                'Kondisi'
            ]);
            
            foreach ($data as $index => $equipment) {
                $totalValue = $equipment->price * $equipment->stock;
                $assetAge = $equipment->purchase_year ? (date('Y') - $equipment->purchase_year) : '-';
                
                fputcsv($file, [
                    $index + 1,
                    $equipment->code,
                    $equipment->name,
                    $equipment->category->name ?? '-',
                    $equipment->location ?? 'Lab/Bengkel',
                    $equipment->stock,
                    $equipment->price,
                    $totalValue,
                    $equipment->vendor ?? '-',
                    $equipment->purchase_year ?? '-',
                    $assetAge,
                    $this->translateStatus($equipment->condition),
                ]);
            }
            
            fputcsv($file, []);
            fputcsv($file, ['TOTAL PORTFOLIO ASET:', '', '', '', '', '', '', $data->sum(fn($e) => $e->price * $e->stock)]);
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportBorrowingsToCSV($data, $periodText)
    {
        $filename = 'Laporan_Peminjaman_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data, $periodText) {
            $file = fopen('php://output', 'w');
            
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'No Transaksi',  // Transaction ID (TRX-001)
                'Tanggal Pinjam', 
                'Peminjam', 
                'Email',
                'Alat', 
                'Kode Alat', 
                'Kategori',
                'Tgl Rencana Kembali', 
                'Tgl Kembali', 
                'Durasi (Hari)',
                'Keterlambatan (Hari)', // BUSINESS KPI
                'Status', 
                'Denda (Rp)',
                'Disetujui Oleh', // AUDIT TRAIL
            ]);
            
            foreach ($data as $index => $borrowing) {
                $transactionId = 'TRX-' . $borrowing->borrow_date->format('Ymd') . '-' . str_pad($borrowing->id, 3, '0', STR_PAD_LEFT);
                
                $duration = $borrowing->actual_return_date 
                    ? $borrowing->borrow_date->diffInDays($borrowing->actual_return_date)
                    : '-';
                
                $lateDays = 0;
                if ($borrowing->actual_return_date) {
                    $diffDays = $borrowing->planned_return_date->diffInDays($borrowing->actual_return_date, false);
                    $lateDays = $diffDays > 0 ? $diffDays : 0;
                } elseif ($borrowing->status === 'borrowed') {
                    $diffDays = $borrowing->planned_return_date->diffInDays(now(), false);
                    $lateDays = $diffDays > 0 ? $diffDays : 0;
                }
                $approvedBy = $borrowing->verifiedBy ? $borrowing->verifiedBy->name : 'Belum Disetujui';
                
                fputcsv($file, [
                    $transactionId,
                    $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y') : '-',
                    $borrowing->user->name,
                    $borrowing->user->email,
                    $borrowing->equipment->name,
                    $borrowing->equipment->code,
                    $borrowing->equipment->category->name ?? '-',
                    $borrowing->planned_return_date->format('d/m/Y'),
                    $borrowing->actual_return_date ? $borrowing->actual_return_date->format('d/m/Y') : '-',
                    $duration,
                    $lateDays,
                    $this->translateStatus($borrowing->status),
                    $borrowing->fine ? $borrowing->fine->amount : 0,
                    $approvedBy,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportFinesToCSV($data, $periodText)
    {
        $filename = 'Laporan_Denda_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data, $periodText) {
            $file = fopen('php://output', 'w');
            
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'No', 
                'Tanggal', 
                'Peminjam', 
                'Email', 
                'Alat', 
                'Kode Alat', 
                'Kategori',
                'Terlambat (Hari)', 
                'Denda (Rp)', 
                'Rate/Hari (Rp)',
                'Status Pembayaran'
            ]);
            
            foreach ($data as $index => $fine) {
                fputcsv($file, [
                    $index + 1,
                    $fine->created_at->format('d/m/Y'),
                    $fine->borrowing->user->name,
                    $fine->borrowing->user->email,
                    $fine->borrowing->equipment->name,
                    $fine->borrowing->equipment->code,
                    $fine->borrowing->equipment->category->name ?? '-',
                    $fine->days_late,
                    $fine->amount,
                    $fine->rate_per_day,
                    $fine->is_paid ? 'Lunas' : 'Belum Bayar',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
