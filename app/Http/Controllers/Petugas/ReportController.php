<?php

declare(strict_types=1);

namespace App\Http\Controllers\Petugas;

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
        return view('petugas.reports.index');
    }

    public function generate(Request $request)
    {
        $type = $request->query('type');
        $period = $request->query('period', 'all');
        
        $startDate = null;
        $endDate = null;
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
            ]);
            
            $startDate = Carbon::parse($request->query('start_date'));
            $endDate = Carbon::parse($request->query('end_date'));
            $periodText = $startDate->isoFormat('D MMM Y') . ' s/d ' . $endDate->isoFormat('D MMM Y');
        }
        
        $title = '';
        
        switch ($type) {
            case 'borrowings':
                $data = $this->getBorrowingsReport($startDate, $endDate);
                $title = 'Laporan Data Peminjaman';
                return view('petugas.reports.borrowings', compact('data', 'title', 'period', 'periodText', 'startDate', 'endDate'));
                
            case 'equipment':
                $data = $this->getEquipmentReport();
                $title = 'Laporan Inventaris Alat';
                $periodText = 'Per Tanggal ' . Carbon::now()->isoFormat('D MMMM Y');
                return view('petugas.reports.equipment', compact('data', 'title', 'period', 'periodText'));
                
            default:
                return redirect()->route('petugas.reports.index')
                    ->with('error', 'Tipe laporan tidak valid.');
        }
    }

    private function getBorrowingsReport($startDate = null, $endDate = null)
    {
        $query = Borrowing::with(['user', 'equipment.category', 'verifiedBy']);
        
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
}
