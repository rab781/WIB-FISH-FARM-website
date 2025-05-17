<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        $title = 'Profil Saya';

        return view('customer.profile.show', compact('user', 'title'));
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

        $provinsi = Provinsi::all();
        $kabupaten = $user->kabupaten_id ? Kabupaten::where('provinsi_id', $user->provinsi_id)->get() : collect();
        $kecamatan = $user->kecamatan_id ? Kecamatan::where('kabupaten_id', $user->kabupaten_id)->get() : collect();

        return view('customer.profile.edit', compact('user', 'title', 'provinsi', 'kabupaten', 'kecamatan'));
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'no_hp' => 'required|string|max:20',
            'provinsi_id' => 'sometimes|nullable|exists:provinsi,id',
            'kabupaten_id' => 'sometimes|nullable|exists:kabupaten,id',
            'kecamatan_id' => 'sometimes|nullable|exists:kecamatan,id',
            'alamat_jalan' => 'sometimes|nullable|string',
            'foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'sometimes|nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;

        // Update location data if provided
        if ($request->provinsi_id) {
            $user->provinsi_id = $request->provinsi_id;
        }
        if ($request->kabupaten_id) {
            $user->kabupaten_id = $request->kabupaten_id;
        }
        if ($request->kecamatan_id) {
            $user->kecamatan_id = $request->kecamatan_id;
        }
        if ($request->alamat_jalan) {
            $user->alamat_jalan = $request->alamat_jalan;
        }

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

        return redirect()->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Get kabupaten based on provinsi_id
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKabupaten(Request $request)
    {
        $provinsiId = $request->provinsi_id;
        $kabupaten = Kabupaten::where('provinsi_id', $provinsiId)->get();

        return response()->json($kabupaten);
    }

    /**
     * Get kecamatan based on kabupaten_id
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKecamatan(Request $request)
    {
        $kabupatenId = $request->kabupaten_id;
        $kecamatan = Kecamatan::where('kabupaten_id', $kabupatenId)->get();

        return response()->json($kecamatan);
    }
}
