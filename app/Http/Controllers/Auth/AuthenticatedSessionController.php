<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ],
        [
            'email.required' => 'Data tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Data tidak boleh kosong',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Cek apakah user adalah admin
            if (Auth::user()->is_admin) {
                return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
            }

            // Jika bukan admin, redirect ke halaman produk
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // Jika email dan password sudah diisi tapi salah
        return back()->withErrors([
            'email' => 'Data yang diinputkan salah',
        ])->onlyInput('email');

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function index()
    {
        // Mengambil semua data dari tabel users
        $users = DB::table('users')->get();
        // Mengembalikan data ke view atau langsung menampilkan
        return view('index', compact('users'));
    }
}
