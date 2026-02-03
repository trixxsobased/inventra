<?php

declare(strict_types=1);

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Fine;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending_requests' => Borrowing::where('status', 'pending')->count(),
            'active_borrowings' => Borrowing::where('status', 'borrowed')->count(),
            'returned_today' => Borrowing::where('status', 'returned')
                ->whereDate('actual_return_date', today())
                ->count(),
            'overdue_count' => Borrowing::where('status', 'borrowed')
                ->where('planned_return_date', '<', now())
                ->count(),
        ];

        $pending_borrowings = Borrowing::with(['user', 'equipment'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $active_borrowings = Borrowing::with(['user', 'equipment'])
            ->where('status', 'borrowed')
            ->orderBy('planned_return_date', 'asc')
            ->take(5)
            ->get();

        return view('petugas.dashboard', compact('stats', 'pending_borrowings', 'active_borrowings'));
    }
}
