<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequisition;
use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Http\Request;

class PurchaseRequisitionController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseRequisition::with(['requestedBy', 'category', 'equipment', 'reviewedBy']);
        
        // Search
        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }
        
        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by priority
        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }
        
        $requisitions = $query->latest()->paginate(15);
        $categories = \App\Models\Category::all();
        
        return view('admin.purchase-requisitions.index', compact('requisitions', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $equipment = Equipment::where('condition', 'rusak berat')->get();
        
        return view('admin.purchase-requisitions.create', compact('categories', 'equipment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'nullable|exists:equipment,id',
            'category_id' => 'required|exists:categories,id',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'estimated_price' => 'required|numeric|min:0',
            'reason' => 'required|in:replacement,new_stock,expansion',
            'justification' => 'required|string|min:20',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $validated['requested_by'] = auth()->id();
        $validated['status'] = 'pending';

        PurchaseRequisition::create($validated);

        return redirect()->route('admin.purchase-requisitions.index')
            ->with('success', 'Pengajuan pembelian berhasil dibuat.');
    }

    public function show(PurchaseRequisition $purchaseRequisition)
    {
        $purchaseRequisition->load(['requestedBy', 'category', 'equipment', 'reviewedBy']);
        
        return view('admin.purchase-requisitions.show', compact('purchaseRequisition'));
    }

    public function edit(PurchaseRequisition $purchaseRequisition)
    {
        // Hanya bisa edit kalau masih pending
        if ($purchaseRequisition->status !== 'pending') {
            return redirect()->route('admin.purchase-requisitions.show', $purchaseRequisition)
                ->with('error', 'Pengajuan yang sudah diproses tidak dapat diedit.');
        }

        $categories = Category::all();
        $equipment = Equipment::where('condition', 'rusak berat')->get();
        
        return view('admin.purchase-requisitions.edit', compact('purchaseRequisition', 'categories', 'equipment'));
    }

    public function update(Request $request, PurchaseRequisition $purchaseRequisition)
    {
        if ($purchaseRequisition->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Pengajuan yang sudah diproses tidak dapat diedit.');
        }

        $validated = $request->validate([
            'equipment_id' => 'nullable|exists:equipment,id',
            'category_id' => 'required|exists:categories,id',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'estimated_price' => 'required|numeric|min:0',
            'reason' => 'required|in:replacement,new_stock,expansion',
            'justification' => 'required|string|min:20',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $purchaseRequisition->update($validated);

        return redirect()->route('admin.purchase-requisitions.show', $purchaseRequisition)
            ->with('success', 'Pengajuan berhasil diupdate.');
    }

    public function approve(Request $request, PurchaseRequisition $purchaseRequisition)
    {
        $validated = $request->validate([
            'review_notes' => 'nullable|string',
        ]);

        $purchaseRequisition->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $validated['review_notes'] ?? null,
        ]);

        return redirect()->back()
            ->with('success', 'Pengajuan berhasil diapprove.');
    }

    public function reject(Request $request, PurchaseRequisition $purchaseRequisition)
    {
        $validated = $request->validate([
            'review_notes' => 'required|string|min:10',
        ]);

        $purchaseRequisition->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $validated['review_notes'],
        ]);

        return redirect()->back()
            ->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function destroy(PurchaseRequisition $purchaseRequisition)
    {
        // Hanya bisa dihapus kalau masih pending
        if ($purchaseRequisition->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Pengajuan yang sudah diproses tidak dapat dihapus.');
        }

        $purchaseRequisition->delete();

        return redirect()->route('admin.purchase-requisitions.index')
            ->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function exportPDF(PurchaseRequisition $purchaseRequisition)
    {
        $purchaseRequisition->load(['requestedBy', 'category', 'equipment', 'reviewedBy']);
        
        $pdf = \PDF::loadView('admin.purchase-requisitions.pdf', compact('purchaseRequisition'))
            ->setPaper('a4', 'portrait');
        
        $filename = 'Surat-Pengadaan-' . $purchaseRequisition->id . '-' . date('Ymd') . '.pdf';
        
        return $pdf->download($filename);
    }
}
