<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Borrowing;
use App\Models\Fine;
use Illuminate\Http\Request;

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

        return view('admin.dashboard', compact('stats', 'recent_borrowings', 'low_stock_equipment'));
    }
}
