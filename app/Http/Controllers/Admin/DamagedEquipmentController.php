<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DamagedEquipment;
use Illuminate\Http\Request;

class DamagedEquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = DamagedEquipment::with(['equipment', 'reportedBy', 'borrowing'])
            ->latest();

        if ($request->filled('search')) {
            $query->whereHas('equipment', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $damagedEquipments = $query->paginate(10);

        return view('admin.damaged-equipment.index', compact('damagedEquipments'));
    }

    public function show(DamagedEquipment $damagedEquipment)
    {
        return response()->json($damagedEquipment->load(['equipment', 'reportedBy', 'borrowing']));
    }

    public function resolve(Request $request, DamagedEquipment $damagedEquipment)
    {
        $validated = $request->validate([
            'resolution_action' => 'required|in:repaired,written_off',
            'resolution_notes' => 'required|string|min:5',
        ]);

        if ($damagedEquipment->resolution_status !== 'pending') {
            return redirect()->back()->with('error', 'Laporan kerusakan ini sudah diselesaikan sebelumnya.');
        }

        $damagedEquipment->update([
            'resolution_status' => $validated['resolution_action'],
            'resolution_notes' => $validated['resolution_notes'],
            'resolved_at' => now(),
        ]);

        if ($validated['resolution_action'] === 'repaired') {
            $damagedEquipment->equipment->update([
                'condition' => 'baik',
                'stock' => $damagedEquipment->equipment->stock + 1
            ]);
            $message = 'Barang berhasil diperbaiki dan stok telah ditambahkan kembali.';
        } else {
            $message = 'Barang dinyatakan rusak total/dimusnahkan. Stok tidak ditambahkan.';
        }

        return redirect()->back()->with('success', $message);
    }
}
