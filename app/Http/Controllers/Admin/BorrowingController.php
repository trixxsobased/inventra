<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Services\BorrowingService;
use App\Helpers\FineCalculator;
use App\Models\Fine;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    protected $borrowingService;

    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
    }

    public function pending()
    {
        $borrowings = Borrowing::with(['user', 'equipment.category'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.borrowings.pending', compact('borrowings'));
    }

    public function active()
    {
        $borrowings = Borrowing::with(['user', 'equipment'])
            ->where('status', 'borrowed')
            ->latest()
            ->paginate(15);

        return view('admin.borrowings.active', compact('borrowings'));
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'equipment', 'verifiedBy', 'fine']);
        return view('admin.borrowings.show', compact('borrowing'));
    }

    public function approve(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending') {
            return redirect()->route('admin.borrowings.pending')
                ->with('error', 'Peminjaman ini sudah diproses.');
        }

        if ($borrowing->equipment->stock <= 0) {
            return redirect()->route('admin.borrowings.pending')
                ->with('error', 'Stok alat tidak tersedia.');
        }

        $result = $this->borrowingService->verifyBorrowing(
            $borrowing->id,
            auth()->id(),
            true,
            null
        );

        if ($result['success']) {
            return redirect()->route('admin.borrowings.pending')
                ->with('success', 'Peminjaman berhasil disetujui.');
        }

        return redirect()->back()->with('error', $result['message']);
    }

    public function reject(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        if ($borrowing->status !== 'pending') {
            return redirect()->route('admin.borrowings.pending')
                ->with('error', 'Peminjaman ini sudah diproses.');
        }

        $result = $this->borrowingService->verifyBorrowing(
            $borrowing->id,
            auth()->id(),
            false,
            $validated['rejection_reason']
        );

        if ($result['success']) {
            return redirect()->route('admin.borrowings.pending')
                ->with('success', 'Peminjaman berhasil ditolak.');
        }

        return redirect()->back()->with('error', $result['message']);
    }

    // Fallback method buat kompabilitas
    public function verify(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'approve' => 'required|boolean',
            'rejection_reason' => 'required_if:approve,false|nullable|string',
        ]);

        $result = $this->borrowingService->verifyBorrowing(
            $borrowing->id,
            auth()->id(),
            $validated['approve'],
            $validated['rejection_reason'] ?? null
        );

        if ($result['success']) {
            return redirect()->route('admin.borrowings.pending')
                ->with('success', $result['message']);
        }

        return redirect()->back()
            ->with('error', $result['message']);
    }

    public function showReturn(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'borrowed') {
            return redirect()->route('admin.borrowings.active')
                ->with('error', 'Peminjaman ini tidak dapat dikembalikan.');
        }

        $fineInfo = FineCalculator::getDetailedInfo(
            $borrowing->planned_return_date->toDateString(),
            now()->toDateString()
        );

        return view('admin.borrowings.return', compact('borrowing', 'fineInfo'));
    }

    public function processReturn(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'actual_return_date' => 'required|date',
            'return_condition' => 'nullable|in:baik,rusak ringan,rusak berat',
            'fine_paid' => 'nullable|boolean',
        ]);

        $fineInfo = FineCalculator::calculate(
            $borrowing->planned_return_date->toDateString(),
            $validated['actual_return_date']
        );

        if ($fineInfo['is_late']) {
            Fine::create([
                'borrowing_id' => $borrowing->id,
                'amount' => $fineInfo['fine_amount'],
                'days_late' => $fineInfo['days_late'],
                'rate_per_day' => $fineInfo['rate_per_day'],
                'is_paid' => $validated['fine_paid'] ?? false,
                'paid_at' => $validated['fine_paid'] ? now() : null,
                'received_by' => $validated['fine_paid'] ? auth()->id() : null,
            ]);
        }

        $result = $this->borrowingService->processReturn(
            $borrowing->id,
            $validated['actual_return_date'],
            auth()->id(),
            $validated['return_condition'] ?? null
        );

        if ($result['success']) {
            // Tambahan alert untuk barang rusak berat
            if ($result['is_damaged'] ?? false) {
                return redirect()->route('admin.borrowings.active')
                    ->with('warning', $result['message'] . ' Silakan buat pengajuan pembelian untuk pengganti.');
            }
            
            return redirect()->route('admin.borrowings.active')
                ->with('success', $result['message']);
        }

        return redirect()->back()
            ->with('error', $result['message']);
    }
}
