<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KeranjangController;
use Illuminate\Session\Middleware\AuthenticateSession;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;


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

    // Google OAuth Routes
    Route::get('auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])
        ->name('auth.google');
    Route::get('google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback'])
        ->name('auth.google.callback');
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
Route::post('/cart/add', [KeranjangController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [KeranjangController::class, 'index'])->name('cart.view')->middleware('auth');
Route::get('/cart/count', [KeranjangController::class, 'getCartCount'])->name('cart.count');
Route::post('/cart/bulk-delete', [KeranjangController::class, 'bulkDelete'])->name('cart.bulk-delete')->middleware('auth');

// Test route to generate sample notifications
Route::get('/test-notifications', function() {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
    }

    // Create a sample notification for the current user
    \App\Http\Controllers\NotificationController::createNotification([
        'user_id' => $user->id,
        'type' => 'product',
        'title' => 'Produk Baru Tersedia',
        'message' => 'Produk baru telah ditambahkan ke katalog kami. Silakan cek untuk detail lebih lanjut.',
        'data' => ['url' => route('produk')],
        'for_admin' => $user->is_admin,
    ]);

    // Create an order notification
    \App\Http\Controllers\NotificationController::createNotification([
        'user_id' => $user->id,
        'type' => 'order',
        'title' => 'Status Pesanan Diperbarui',
        'message' => 'Pesanan anda #123 telah diperbarui statusnya menjadi "Sedang Diproses".',
        'data' => ['url' => route('home')],
        'for_admin' => $user->is_admin,
    ]);

    return redirect()->back()->with('success', 'Notifikasi sampel telah dibuat, silakan cek ikon notifikasi.');
});

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
