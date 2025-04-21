<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Session\Middleware\AuthenticateSession;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
Route::get('/produk/{id}', [HomeController::class, 'detailProduk'])->name('detailProduk');

Route::resource('/keranjang', KeranjangController::class)->middleware('auth');

// Auth routes - make sure these exist and are properly defined
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Alamat routes - for address setup after registration
Route::middleware('auth')->group(function () {
    Route::get('alamat-setup', [RegisteredUserController::class, 'alamatSetup'])
        ->name('alamat.setup');
    Route::post('alamat-store', [RegisteredUserController::class, 'alamatStore'])
        ->name('alamat.store');
});

// Add logout route for authenticated users
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});

// Admin routes protected with admin middleware
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function () {
        return redirect('admin/dashboard');
    });

    // Admin dashboard route
    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Admin produk routes
    Route::resource('/produk', AdminProdukController::class)->names([
        'index' => 'admin.produk.index',
        'create' => 'admin.produk.create',
        'store' => 'admin.produk.store',
        'show' => 'admin.produk.show',
        'edit' => 'admin.produk.edit',
        'update' => 'admin.produk.update',
        'destroy' => 'admin.produk.destroy',
    ]);

    Route::get('/produk/{id}/restore', [AdminProdukController::class, 'restore'])->name('admin.produk.restore');
    Route::delete('/produk/{id}/forceDelete', [AdminProdukController::class, 'forceDelete'])->name('admin.produk.forceDelete');
});

// Test route to ensure views are working
Route::get('/test-login', function () {
    return view('auth.login');
});

Route::get('/test-register', function () {
    return view('auth.register');
});

// Safely include auth routes
require __DIR__.'/auth.php';

// Add specific route to handle expired pages
Route::get('/page-expired', function() {
    return view('errors.419');
})->name('page-expired');

// Cart routes
Route::post('/cart/add', [App\Http\Controllers\KeranjangController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [App\Http\Controllers\KeranjangController::class, 'index'])->name('cart.view')->middleware('auth');
Route::post('/cart/remove', [App\Http\Controllers\KeranjangController::class, 'destroy'])->name('cart.remove')->middleware('auth');
Route::get('/cart/count', [App\Http\Controllers\KeranjangController::class, 'getCartCount'])->name('cart.count');


// fallback route
Route::fallback(function () {
    // Check if this is a CSRF token mismatch
    if (request()->session()->has('errors') &&
        request()->session()->get('errors')->has('csrf')) {
        return redirect()->route('page-expired');
    }

    return view('errors.404');
});

Route::get('/test', AuthenticatedSessionController::class . '@index')->name('index');
