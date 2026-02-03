<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = Equipment::with('category')->latest()->paginate(10);
        $categories = Category::all();
        return view('admin.equipment.index', compact('equipment', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:equipment',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'location' => 'nullable|string|max:255',
            'condition' => 'required|in:baik,rusak ringan,rusak berat',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('equipment', 'public');
        }

        Equipment::create($validated);

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Alat berhasil ditambahkan.')
            ->with('modal_type', 'create');
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:equipment,code,' . $equipment->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'location' => 'nullable|string|max:255',
            'condition' => 'required|in:baik,rusak ringan,rusak berat',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($equipment->image && Storage::disk('public')->exists($equipment->image)) {
                Storage::disk('public')->delete($equipment->image);
            }
            $validated['image'] = $request->file('image')->store('equipment', 'public');
        }

        $equipment->update($validated);

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Alat berhasil diupdate.')
            ->with('modal_type', 'edit')
            ->with('equipment_id', $equipment->id);
    }

    public function destroy(Equipment $equipment)
    {
        if ($equipment->image && Storage::disk('public')->exists($equipment->image)) {
            Storage::disk('public')->delete($equipment->image);
        }
        
        $equipment->delete();

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Alat berhasil dihapus.');
    }

    public function generateQR(Equipment $equipment)
    {
        $url = route('equipment.show', $equipment->id);
        
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($url);
        
        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'inline; filename="qr-' . $equipment->code . '.svg"');
    }

    public function downloadQR(Equipment $equipment)
    {
        $url = route('equipment.show', $equipment->id);
        
        $qrCode = QrCode::format('png')
            ->size(300)
            ->errorCorrection('H')
            ->generate($url);
        
        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-' . $equipment->code . '.png"');
    }

    public function bulkQR(Request $request)
    {
        $equipmentIds = $request->input('equipment_ids', []);
        
        if (empty($equipmentIds)) {
            $equipment = Equipment::with('category')->get();
        } else {
            $equipment = Equipment::with('category')->whereIn('id', $equipmentIds)->get();
        }

        $qrCodes = [];
        foreach ($equipment as $item) {
            $url = route('equipment.show', $item->id);
            $qrCodes[] = [
                'equipment' => $item,
                'qr' => base64_encode(QrCode::format('svg')->size(150)->errorCorrection('H')->generate($url))
            ];
        }

        return view('admin.equipment.bulk-qr', compact('qrCodes'));
    }

    public function scanQR()
    {
        return view('admin.equipment.scan-qr');
    }
}
