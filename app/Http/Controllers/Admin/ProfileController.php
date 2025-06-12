<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the admin's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        $title = 'Profil Admin';
        $header = 'Profil Admin';

        return view('admin.profile.show', compact('user', 'title', 'header'));
    }

    /**
     * Show the form for editing the admin's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
        $title = 'Edit Profil Admin';

        return view('admin.profile.edit', compact('user', 'title'));
    }

    /**
     * Update the admin's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */

        // Get the currently authenticated user
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'no_hp' => 'required|string|max:20',
            'foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'sometimes|nullable|min:8|confirmed',
        ],
        [
            'name.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'no_hp.required' => 'Nomor HP tidak boleh kosong',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar tidak valid (hanya jpeg, png, jpg, gif)',
            'foto.max' => 'Ukuran gambar terlalu besar (maksimal 2MB)',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Handle profile photo upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->foto && Storage::exists('public/uploads/users/' . $user->foto)) {
                Storage::delete('public/uploads/users/' . $user->foto);
            }

            // Make sure the directory exists
            Storage::makeDirectory('public/uploads/users');

            $file = $request->file('foto');
            $fileName = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();

            // Store the file and save the path
            $file->storeAs('public/uploads/users', $fileName);
            $user->foto = $fileName;
        }

        // Save all changes to the database
        $user->save();

        return redirect()->route('admin.profile.show')
            ->with('success', 'Profil berhasil diperbarui!');
    }
}
