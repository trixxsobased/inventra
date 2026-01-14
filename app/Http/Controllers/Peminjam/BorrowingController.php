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
            'planned_return_date' => 'required|date|after:today',
            'purpose' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

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
        // Pastikan user hanya bisa lihat peminjamannya sendiri
        if ($borrowing->user_id !== auth()->id()) {
            abort(403);
        }

        $borrowing->load(['equipment', 'verifiedBy', 'fine']);

        return view('peminjam.borrowings.show', compact('borrowing'));
    }
}
