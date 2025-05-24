<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ],
        [
            'name.required' => 'Data tidak boleh kosong',
            'email.required' => 'Data tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Pengguna sudah terdaftar',
            'password.required' => 'Data tidak boleh kosong',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        // Buat user sebagai customer (is_admin = false)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false, // Memastikan user yang registrasi selalu menjadi customer
        ]);

        event(new Registered($user));

        // Tidak login otomatis, arahkan ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }

    /**
     * Handle the address setup after registration
     */
    public function storeAlamat(Request $request)
    {
        $request->validate([
            'no_hp' => 'required|string|max:15',
            'alamat_id' => 'required|exists:alamat,id',
            'alamat_jalan' => 'required|string|max:255',
        ]);

        try {
            // Get the currently authenticated user's ID
            $userId = Auth::id();

            // Find and update the user using the User model
            User::where('id', $userId)->update([
                'no_hp' => $request->no_hp,
                'alamat_id' => $request->alamat_id,
                'alamat_jalan' => $request->alamat_jalan
            ]);

            return redirect()->route('home')
                ->with('success', 'Alamat berhasil disimpan. Selamat datang di IKAN!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyimpan alamat: ' . $e->getMessage())
                ->withInput();
        }
    }
}
