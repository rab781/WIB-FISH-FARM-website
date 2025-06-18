<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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
            $isNewUser = false;

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
                    $message = 'Akun Google berhasil dihubungkan dengan akun yang sudah ada!';
                } else {
                    // Buat user baru
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'google_token' => $googleUser->token,
                        'google_refresh_token' => $googleUser->refreshToken,
                        'avatar' => $googleUser->avatar,
                        'password' => bcrypt(Str::random(16)), // Generate password random menggunakan Str::random()
                        'email_verified_at' => now(), // User dari Google sudah terverifikasi
                    ]);
                    $isNewUser = true;
                    $message = 'Akun berhasil dibuat! Selamat datang, ' . $googleUser->name . '!';
                }
            } else {
                // Update token jika user sudah ada
                $user->update([
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'avatar' => $googleUser->avatar,
                ]);
                $message = 'Selamat datang kembali, ' . $user->name . '!';
            }

            // Debug: Log untuk memastikan user berhasil dibuat/diupdate
            Log::info('Google Auth - User info:', [
                'user_id' => $user->id,
                'email' => $user->email,
                'is_new' => $isNewUser
            ]);

            // Login user
            Auth::login($user, true); // Parameter true untuk remember user

            // Debug: Cek apakah user benar-benar login
            Log::info('Google Auth - After login:', [
                'auth_check' => Auth::check(),
                'auth_id' => Auth::id(),
                'session_id' => session()->getId()
            ]);

            // Regenerate session untuk keamanan
            session()->regenerate();

            // Redirect dengan pesan sukses
            return redirect()->intended('/')->with('success', $message);

        } catch (Exception $e) {
            // Log error untuk debugging
            Log::error('Google Auth Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Tangani error
            return redirect('/login')->with('error', 'Terjadi kesalahan saat login dengan Google: ' . $e->getMessage());
        }
    }
}
