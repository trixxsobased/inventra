<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function index()
    {
        $fines = Fine::with(['borrowing.user', 'borrowing.equipment', 'receivedBy'])
            ->latest()
            ->paginate(15);

        return view('admin.fines.index', compact('fines'));
    }

    public function markAsPaid(Request $request, Fine $fine)
    {
        if ($fine->is_paid) {
            return redirect()->back()->with('error', 'Denda sudah lunas.');
        }

        $fine->markAsPaid(auth()->id());

        return redirect()->back()->with('success', 'Denda berhasil ditandai sebagai lunas.');
    }
}
