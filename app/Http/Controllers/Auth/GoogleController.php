<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Providers\RouteServiceProvider; // Tambahkan import ini

class GoogleController extends Controller
{
    /**
     * Redirect ke Google untuk autentikasi.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Mendapatkan informasi user dari Google setelah autentikasi.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check jika user dengan google_id ini sudah ada
            $user = User::where('google_id', $googleUser->id)->first();

            // Jika tidak ada, buat user baru
            if (!$user) {
                // Cek apakah email sudah terdaftar
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    // Update data Google pada user yang sudah ada
                    $existingUser->update([
                        'google_id' => $googleUser->id,
                        'google_token' => $googleUser->token,
                        'google_refresh_token' => $googleUser->refreshToken,
                        'avatar' => $googleUser->avatar,
                    ]);

                    $user = $existingUser;
                } else {
                    // Buat user baru dengan is_admin = false
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'google_token' => $googleUser->token,
                        'google_refresh_token' => $googleUser->refreshToken,
                        'avatar' => $googleUser->avatar,
                        'password' => bcrypt(Str::random(16)),
                        'email_verified_at' => now(),
                        'is_admin' => false, // Pastikan user baru bukan admin
                    ]);
                }
            } else {
                // Update token jika user sudah ada
                $user->update([
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'avatar' => $googleUser->avatar,
                ]);
            }

            // Login user
            Auth::login($user);

            // Redirect berdasarkan status admin
            if ($user->is_admin) {
                return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
            }

            // Jika bukan admin, redirect ke home biasa
            return redirect()->intended(RouteServiceProvider::HOME);

        } catch (Exception $e) {
            // Tangani error
            return redirect('/login')->with('error', 'Terjadi kesalahan saat login dengan Google: ' . $e->getMessage());
        }
    }
}
