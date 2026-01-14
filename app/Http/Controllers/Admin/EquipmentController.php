<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            // Hapus gambar lama jika ada
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
        // Hapus gambar jika ada
        if ($equipment->image && Storage::disk('public')->exists($equipment->image)) {
            Storage::disk('public')->delete($equipment->image);
        }
        
        $equipment->delete();

        return redirect()->route('admin.equipment.index')
            ->with('success', 'Alat berhasil dihapus.');
    }
}
