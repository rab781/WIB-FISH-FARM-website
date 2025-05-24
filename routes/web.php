<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Laravel\Socialite\Two\GoogleProvider;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Session\Middleware\AuthenticateSession;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\RajaOngkirController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
Route::get('/produk/{id}', [HomeController::class, 'detailProduk'])->name('detailProduk');
Route::get('/tentang-kami', [HomeController::class, 'tentangKami'])->name('tentang-kami');

Route::middleware('auth')->group(function () {
    // Alamat storage route
    Route::post('/alamat', [RegisteredUserController::class, 'storeAlamat'])->name('alamat.store');

    // Keranjang view and CRUD operations
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang', [KeranjangController::class, 'store'])->name('keranjang.store');
    Route::post('/keranjang/{id}/update', [KeranjangController::class, 'updateViaPost'])->name('keranjang.updatepost');
    Route::post('/keranjang/{id}/delete', [KeranjangController::class, 'destroyViaPost'])->name('keranjang.destroy.post');
    Route::post('/keranjang/bulk-delete', [KeranjangController::class, 'bulkDelete'])->name('keranjang.bulk-delete');

    // Pesanan routes
    Route::post('/checkout/process', [App\Http\Controllers\PesananController::class, 'processCheckout'])->name('checkout.process');
    Route::post('/checkout', [App\Http\Controllers\PesananController::class, 'checkout'])->name('checkout');
    Route::get('/checkout', [App\Http\Controllers\PesananController::class, 'checkout'])->name('checkout');
    Route::get('/alamat/tambah', [App\Http\Controllers\PesananController::class, 'tambahAlamat'])->name('alamat.tambah');
    Route::post('/alamat/simpan', [App\Http\Controllers\PesananController::class, 'simpanAlamat'])->name('alamat.simpan');
    Route::get('/checkout/get-ongkir/{kabupaten_id}', [App\Http\Controllers\PesananController::class, 'getOngkir'])->name('checkout.ongkir');

    // Pesanan management
    Route::get('/pesanan', [App\Http\Controllers\PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{id}', [App\Http\Controllers\PesananController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{id}/payment', [App\Http\Controllers\PesananController::class, 'submitPayment'])->name('pesanan.payment');
    Route::post('/pesanan/{id}/konfirmasi', [App\Http\Controllers\PesananController::class, 'konfirmasiPesanan'])->name('pesanan.konfirmasi');

    // Profile routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/search-alamat', [App\Http\Controllers\ProfileController::class, 'searchAlamat'])->name('profile.search-alamat');

    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');

    // Order routes
});

// Auth routes - make sure these exist and are properly defined
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
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

    // Admin profile routes
    Route::get('profile', [App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('admin.profile.show');
    Route::get('profile/edit', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('admin.profile.update');

    // Admin notification routes
    Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('admin.notifications.index');

    // Admin pesanan routes
    Route::get('pesanan', [App\Http\Controllers\Admin\PesananController::class, 'index'])->name('admin.pesanan.index');
    Route::get('pesanan/{id}', [App\Http\Controllers\Admin\PesananController::class, 'show'])->name('admin.pesanan.show');
    Route::post('pesanan/{id}/update-status', [App\Http\Controllers\Admin\PesananController::class, 'updateStatus'])->name('admin.pesanan.updateStatus');
    Route::post('pesanan/{id}/force-expire', [App\Http\Controllers\Admin\PesananController::class, 'forceExpireOrder'])->name('admin.pesanan.forceExpire');

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
Route::post('/cart/bulk-delete', [App\Http\Controllers\KeranjangController::class, 'bulkDelete'])->name('cart.bulk-delete')->middleware('auth');
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

// Diagnostic routes
Route::get('/diagnosa-api', function () {
    return view('diagnosa-api');
})->name('diagnosa-api');

// RajaOngkir API test routes
Route::get('/test-rajaongkir-api', [RajaOngkirController::class, 'testApiStatus'])->name('test-rajaongkir-api');
Route::get('/test-komerce', [RajaOngkirController::class, 'testKomerce'])->name('test-komerce');
Route::get('/test-standard', [RajaOngkirController::class, 'testStandard'])->name('test-standard');
Route::post('/cek-tracking', [RajaOngkirController::class, 'cekTrackingStatus'])->name('cek-tracking');

// Auth Google routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']) ->name('auth.google.callback');

// Test routes for debugging
Route::get('/test/raja-ongkir-bon', [App\Http\Controllers\TestApiController::class, 'testRajaOngkirWithBon']);

// Advanced diagnostic routes for RajaOngkir API
Route::get('/test/api/raja-ongkir-search', [App\Http\Controllers\TestController::class, 'testRajaOngkirSearch'])->name('test.raja-ongkir-search');
Route::get('/test/api/response', [App\Http\Controllers\TestController::class, 'testApiResponse'])->name('test.api-response');
Route::get('/test/debug', [App\Http\Controllers\TestController::class, 'debugAddressSearch'])->name('test.debug');
Route::get('/test/enhanced-search', [App\Http\Controllers\TestController::class, 'enhancedSearch'])->name('test.enhanced-search');
                                      
// Test route for rate limiter
Route::get('/test/rate-limiter', function () {
    return view('test.rate_limiter_test');
})->name('test.rate-limiter');
