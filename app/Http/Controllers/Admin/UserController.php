<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('role')) {
            if ($request->input('role') === 'admin') {
                $query->where('admin_plastani', true);
            }

            if ($request->input('role') === 'user') {
                $query->where('admin_plastani', false);
            }
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'admin_plastani' => ['required', 'boolean'],
        ], [
            'name.required' => 'Nama user wajib diisi.',
            'name.min' => 'Nama user minimal 3 karakter.',
            'name.max' => 'Nama user maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
            'email.max' => 'Email maksimal 255 karakter.',
            'admin_plastani.required' => 'Status role wajib diisi.',
            'admin_plastani.boolean' => 'Status role harus berupa true atau false.',
        ]);

        $newAdminStatus = (bool) $validated['admin_plastani'];

        $errors = [];

        if ((int) $request->user()->id === (int) $user->id && ! $newAdminStatus) {
            $errors['admin_plastani'] = 'Anda tidak dapat menurunkan role akun yang sedang digunakan.';
        }

        if ($user->admin_plastani && ! $newAdminStatus && User::where('admin_plastani', true)->count() <= 1) {
            $errors['admin_plastani'] = 'Minimal harus ada 1 admin aktif di sistem.';
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'admin_plastani' => $newAdminStatus,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ((int) $request->user()->id === (int) $user->id) {
            return back()->withErrors(['id' => 'Akun yang sedang login tidak dapat dihapus.'])->withInput();
        }

        if ($user->admin_plastani && User::where('admin_plastani', true)->count() <= 1) {
            return back()->withErrors(['id' => 'Admin terakhir tidak dapat dihapus.'])->withInput();
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}

