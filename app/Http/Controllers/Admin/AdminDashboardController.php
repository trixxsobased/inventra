<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Borrowing;
use App\Models\Fine;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_equipment' => Equipment::count(),
            'active_borrowings' => Borrowing::where('status', 'borrowed')->count(),
            'pending_requests' => Borrowing::where('status', 'pending')->count(),
            'unpaid_fines' => Fine::where('is_paid', false)->count(),
        ];

        $recent_borrowings = Borrowing::with(['user', 'equipment'])
            ->latest()
            ->take(10)
            ->get();

        $low_stock_equipment = Equipment::where('stock', '<=', 2)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->get();

        $monthlyBorrowings = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Borrowing::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthlyBorrowings->push([
                'month' => $date->isoFormat('MMM'),
                'count' => $count,
            ]);
        }

        $borrowingStatus = [
            'pending' => Borrowing::where('status', 'pending')->count(),
            'borrowed' => Borrowing::where('status', 'borrowed')->count(),
            'returned' => Borrowing::where('status', 'returned')->count(),
            'rejected' => Borrowing::where('status', 'rejected')->count(),
        ];

        return view('admin.dashboard', compact(
            'stats', 
            'recent_borrowings', 
            'low_stock_equipment',
            'monthlyBorrowings',
            'borrowingStatus'
        ));
    }
}

