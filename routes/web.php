<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('LandingPage');
});

// Product Route
Route::get('/produk', function () {
    return view('Produk');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/daftar', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('/daftar', [RegisteredUserController::class, 'store']);

    Route::get('/masuk', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/masuk', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::fallback(function () {
    return view('404');
});
