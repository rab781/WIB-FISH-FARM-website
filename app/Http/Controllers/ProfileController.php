<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\RajaOngkirController;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = User::with('alamat')->find(Auth::id());
        $title = 'Profil Saya';
        $foto = $user->foto;

        return view('customer.profile.show', compact('user', 'title', 'foto'));
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
        $title = 'Edit Profil';

        // Load alamat details if exists
        $alamat = null;
        if ($user->alamat_id) {
            $alamat = Alamat::find($user->alamat_id);
        }

        return view('customer.profile.edit', compact('user', 'title', 'alamat'));
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        // Enhanced validation with better error messages
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'no_hp' => 'required|string|max:20',
            'alamat_id' => 'sometimes|nullable|exists:alamat,id',
            'alamat_jalan' => 'sometimes|nullable|string|max:500',
            'foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'password' => 'sometimes|nullable|min:8|confirmed',
        ], [
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPEG, PNG, JPG, GIF, atau WebP',
            'foto.max' => 'Ukuran foto maksimal 2MB',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            // Update basic profile data
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_hp = $request->no_hp;

            // Update location data if provided
            if ($request->filled('alamat_id')) {
                $user->alamat_id = $request->alamat_id;
            }
            if ($request->filled('alamat_jalan')) {
                $user->alamat_jalan = $request->alamat_jalan;
            }

            // Update password ONLY if provided and not empty
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Handle profile photo upload with better error handling
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');

                // Validate file is uploaded successfully
                if ($file->isValid()) {
                    // Delete old photo if exists
                    if ($user->foto && file_exists(public_path('uploads/users/' . $user->foto))) {
                        unlink(public_path('uploads/users/' . $user->foto));
                    }

                    // Make sure the directory exists
                    if (!file_exists(public_path('uploads/users'))) {
                        mkdir(public_path('uploads/users'), 0755, true);
                    }

                    // Generate unique filename
                    $fileName = time() . '_' . $user->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Store the file directly to public folder
                    $file->move(public_path('uploads/users'), $fileName);
                    $user->foto = $fileName;
                    Log::info('Profile photo uploaded successfully: ' . $fileName);
                } else {
                    Log::error('Invalid file upload: ' . $file->getErrorMessage());
                    return redirect()->back()
                        ->with('error', 'File foto tidak valid: ' . $file->getErrorMessage())
                        ->withInput();
                }
            }

            // Save all changes to the database
            $user->save();

            return redirect()->route('profile.show')
                ->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui profil. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Search for addresses using RajaOngkir API
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAlamat(Request $request)
    {
        Log::info('ProfileController@searchAlamat called with term: ' . $request->term);

        // Use RajaOngkirController to search locations
        $controller = new RajaOngkirController();
        $result = $controller->searchLocation($request);

        Log::info('ProfileController@searchAlamat returning result: ' . $result->getContent());

        return $result;
    }
}
