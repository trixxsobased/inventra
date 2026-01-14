<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Urutkan berdasarkan prioritas role: Admin -> Petugas -> Peminjam
        $users = User::orderByRaw("FIELD(role, 'admin', 'petugas', 'peminjam')")
            ->orderBy('id', 'asc')
            ->paginate(15);
            
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ' required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:peminjam,admin,petugas',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.')
            ->with('modal_type', 'create');
    }

    public function update(Request $request, User $user)
    {
        // Proteksi akun administrator utama
        if ($user->id === 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akun Administrator Utama tidak dapat diubah.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:peminjam,admin,petugas',
        ]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.')
            ->with('modal_type', 'edit')
            ->with('user_id', $user->id);
    }

    public function destroy(User $user)
    {
        // Cegah menghapus akun sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Hanya admin utama (ID 1) yang tidak bisa dihapus
        if ($user->id === 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Administrator Utama tidak dapat dihapus.');
        }

        // Cegah hapus user yang masih punya peminjaman aktif
        if ($user->borrowings()->whereIn('status', ['pending', 'borrowed'])->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User tidak dapat dihapus karena masih memiliki peminjaman aktif.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
