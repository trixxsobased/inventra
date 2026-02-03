<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Equipment;
use App\Services\BorrowingService;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    protected $borrowingService;

    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
    }

    public function index()
    {
        $borrowings = auth()->user()
            ->borrowings()
            ->with(['equipment', 'fine'])
            ->latest()
            ->paginate(10);

        return view('peminjam.borrowings.index', compact('borrowings'));
    }

    public function create(Equipment $equipment)
    {
        if (!$equipment->isAvailable()) {
            return redirect()->route('equipment.browse')
                ->with('error', 'Alat ini tidak tersedia untuk dipinjam.');
        }

        return view('peminjam.borrowings.create', compact('equipment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'planned_return_date' => 'required|date|after_or_equal:today|before_or_equal:+7 days',
            'purpose' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        $hasUnpaidFines = \App\Models\Fine::whereHas('borrowing', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('is_paid', false)->exists();

        if ($hasUnpaidFines) {
            return redirect()->back()
                ->with('error', 'Anda masih memiliki denda yang belum dibayar. Harap lunasi denda terlebih dahulu.');
        }

        $hasOverdueBorrowings = \App\Models\Borrowing::where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->whereDate('planned_return_date', '<', now())
            ->exists();

        if ($hasOverdueBorrowings) {
            return redirect()->back()
                ->with('error', 'Anda memiliki peminjaman yang terlambat dan belum dikembalikan. Harap kembalikan alat tersebut terlebih dahulu.');
        }

        $activeBorrowingsCount = \App\Models\Borrowing::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'borrowed'])
            ->count();
        
        if ($activeBorrowingsCount >= 5) {
            return redirect()->back()
                ->with('error', 'Kuota peminjaman habis. Kembalikan alat yang dipinjam sebelum meminjam lagi (Maksimal 5 item).');
        }

        $borrowing = $this->borrowingService->createBorrowingRequest([
            'user_id' => auth()->id(),
            'equipment_id' => $validated['equipment_id'],
            'planned_return_date' => $validated['planned_return_date'],
            'purpose' => $validated['purpose'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('borrowings.index')
            ->with('success', 'Pengajuan peminjaman berhasil dibuat. Menunggu verifikasi admin.');
    }

    public function show(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== auth()->id()) {
            abort(403);
        }

        $borrowing->load(['equipment', 'verifiedBy', 'fine']);

        return view('peminjam.borrowings.show', compact('borrowing'));
    }

    public function destroy(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== auth()->id()) {
            abort(403);
        }

        if ($borrowing->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Hanya peminjaman dengan status "pending" yang dapat dibatalkan.');
        }

        $borrowing->delete();

        return redirect()->route('borrowings.index')
            ->with('success', 'Permintaan peminjaman berhasil dibatalkan.');
    }
}
