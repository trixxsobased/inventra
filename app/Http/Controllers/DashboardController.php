<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return match(auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            default => redirect()->route('peminjam.dashboard'),
        };
    }

    private function peminjamDashboard()
    {
        $user = auth()->user();

        $stats = [
            'active_borrowings' => $user->borrowings()->where('status', 'borrowed')->count(),
            'completed_borrowings' => $user->borrowings()->where('status', 'returned')->count(),
            'pending_borrowings' => $user->borrowings()->where('status', 'pending')->count(),
            'total_fines' => $user->borrowings()
                ->whereHas('fine', function ($query) {
                    $query->where('is_paid', false);
                })
                ->with('fine')
                ->get()
                ->sum(function ($borrowing) {
                    return $borrowing->fine->amount ?? 0;
                }),
        ];

        $active_borrowings = $user->borrowings()
            ->whereIn('status', ['pending', 'borrowed'])
            ->with('equipment')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('peminjam.dashboard', compact('stats', 'active_borrowings'));
    }
}
