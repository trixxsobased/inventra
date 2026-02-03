<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected function isSuperAdmin(): bool
    {
        return auth()->check() && auth()->id() === 1;
    }

    public function index()
    {
        $users = User::orderByRaw("FIELD(role, 'admin', 'petugas', 'peminjam')")
            ->orderBy('id', 'asc')
            ->paginate(15);
            
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        if ($request->role === 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Role Admin tidak dapat ditambahkan. Sistem hanya mengizinkan 1 Superadmin.');
        }

        if ($request->role === 'petugas' && !$this->isSuperAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Hanya Superadmin yang dapat membuat user dengan role Petugas.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:peminjam,petugas',
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
        if ($user->id === 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akun Administrator Utama tidak dapat diubah.');
        }

        if ($request->role === 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Role Admin tidak dapat diubah. Sistem hanya mengizinkan 1 Superadmin.');
        }

        if ($request->role === 'petugas' && !$this->isSuperAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Hanya Superadmin yang dapat mengubah role user menjadi Petugas.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:peminjam,petugas',
        ]);

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
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->id === 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Administrator Utama tidak dapat dihapus.');
        }

        if ($user->borrowings()->whereIn('status', ['pending', 'borrowed'])->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User tidak dapat dihapus karena masih memiliki peminjaman aktif.');
        }

        $hasUnpaidFines = \App\Models\Fine::whereHas('borrowing', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('is_paid', false)->exists();

        if ($hasUnpaidFines) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User tidak dapat dihapus karena masih memiliki denda yang belum dibayar.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
