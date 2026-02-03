<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Category;
use Illuminate\Http\Request;

class EquipmentBrowseController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::with('category')
            ->where('condition', 'baik');

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $equipment = $query->paginate(12);
        $categories = Category::all();

        return view('peminjam.equipment.browse', compact('equipment', 'categories'));
    }

    public function show(Equipment $equipment)
    {
        $equipment->load('category', 'activeBorrowings.user');
        return view('peminjam.equipment.show', compact('equipment'));
    }
}
