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
        // Karena user minta modal, method show return JSON untuk AJAX modal load
        // Atau kita handle logic di modal index langsung
        return response()->json($damagedEquipment->load(['equipment', 'reportedBy', 'borrowing']));
    }
}
