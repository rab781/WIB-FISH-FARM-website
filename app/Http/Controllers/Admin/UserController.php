<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role - exclude admin users by default unless specifically requested
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        } else {
            $query->where('role', '!=', 'admin');
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $pesanan = Pesanan::where('id_user', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users.show', compact('user', 'pesanan'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Only update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.show', $id)
            ->with('success', 'Informasi pengguna berhasil diperbarui');
    }

    /**
     * Toggle the user's active status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'aktif' : 'nonaktif';

        return redirect()->back()
            ->with('success', "Status akun pengguna berhasil diubah menjadi $status");
    }
}
