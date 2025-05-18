<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KeranjangController;
use Illuminate\Session\Middleware\AuthenticateSession;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Auth\GoogleController;
use Laravel\Socialite\Two\GoogleProvider;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
Route::get('/produk/{id}', [HomeController::class, 'detailProduk'])->name('detailProduk');
Route::get('/tentang-kami', [HomeController::class, 'tentangKami'])->name('tentang-kami');

Route::middleware('auth')->group(function () {
    // Keranjang view and CRUD operations
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang', [KeranjangController::class, 'store'])->name('keranjang.store');
    Route::post('/keranjang/{id}/update', [KeranjangController::class, 'updateViaPost'])->name('keranjang.updatepost');
    Route::post('/keranjang/{id}/delete', [KeranjangController::class, 'destroyViaPost'])->name('keranjang.destroy.post');
    Route::post('/keranjang/bulk-delete', [KeranjangController::class, 'bulkDelete'])->name('keranjang.bulk-delete');

    // Profile routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/kabupaten', [App\Http\Controllers\ProfileController::class, 'getKabupaten'])->name('profile.kabupaten');
    Route::get('/profile/kecamatan', [App\Http\Controllers\ProfileController::class, 'getKecamatan'])->name('profile.kecamatan');

    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
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

// Auth Google routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']) ->name('auth.google.callback');
