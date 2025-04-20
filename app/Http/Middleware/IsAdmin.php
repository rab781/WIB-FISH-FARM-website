<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log untuk debugging
        Log::info('IsAdmin middleware check', [
            'is_logged_in' => Auth::check(),
            'user' => Auth::check() ? Auth::user()->only(['id', 'name', 'email', 'is_admin']) : null
        ]);

        // Periksa apakah user sudah login dan memiliki status admin
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!Auth::user()->is_admin) {
            // Redirect ke halaman produk dengan pesan error
            return redirect()->route('products')->with('error', 'Akses ditolak. Anda tidak memiliki hak akses admin.');
        }

        return $next($request);
    }
}
