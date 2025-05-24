<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RajaOngkirController;
use App\Http\Controllers\TestApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API routes for RajaOngkir integration
// Menggunakan throttle yang lebih tinggi (30 requests per minute) untuk pencarian alamat
Route::middleware('throttle:30,1')->group(function() {
    Route::get('/alamat/search', [RajaOngkirController::class, 'searchLocation']);
    Route::get('/alamat', [RajaOngkirController::class, 'getAlamat']);
});

// API routes yang membutuhkan throttle lebih rendah (5 requests per minute)
Route::middleware('throttle:5,1')->group(function() {
    Route::get('/komerce/test', [RajaOngkirController::class, 'testKomerce']);
    Route::get('/standard/test', [RajaOngkirController::class, 'testStandard']);

    // Route for calculating shipping cost
    Route::post('/ongkir/calculate', [RajaOngkirController::class, 'cekOngkir']);
});

// Test API connection
Route::get('/test/rajaongkir', [TestApiController::class, 'testRajaOngkir']);

// Debug rate limiter (more generous limit - 30 reqs per minute)
Route::middleware('throttle:30,1')->group(function() {
    Route::get('/debug/ratelimiter', function() {
        return response()->json([
            'success' => true,
            'message' => 'Rate limiter is working correctly',
            'time' => now()->toDateTimeString()
        ]);
    });
});
