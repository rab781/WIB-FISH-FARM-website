<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\RajaOngkirController;
use App\Http\Controllers\TestApiController;
use App\Models\Expense;

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
    Route::get('/domestic-cost/test', [RajaOngkirController::class, 'testDomesticCost']);

    // Route for calculating shipping cost
    Route::post('/ongkir/calculate', [RajaOngkirController::class, 'cekOngkir']);
    Route::post('/cek-ongkir', [RajaOngkirController::class, 'cekOngkir']);
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

// Expense categories for charts
Route::get('/expenses/categories', function (Request $request) {
    $year = $request->input('year', Carbon::now()->year);
    $month = $request->input('month', Carbon::now()->month);

    $expenseCategories = Expense::select(
        'category',
        DB::raw('SUM(amount) as total')
    )
    ->whereYear('expense_date', $year)
    ->whereMonth('expense_date', $month)
    ->groupBy('category')
    ->orderByDesc('total')
    ->get();

    return response()->json($expenseCategories);
});
