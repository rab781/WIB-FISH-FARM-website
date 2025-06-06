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
Route::get('/produk', [HomeController::class, 'produk'])->name('produk.index');
Route::get('/produk/{id}', [HomeController::class, 'detailProduk'])->name('detailProduk');
Route::get('/produk/{id}/reviews', [App\Http\Controllers\ReviewController::class, 'publicIndex'])->name('produk.reviews');
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
    Route::post('/checkout', [App\Http\Controllers\PesananController::class, 'checkout'])->name('checkout.store');
    Route::get('/checkout', [App\Http\Controllers\PesananController::class, 'checkout'])->name('checkout');
    Route::get('/alamat/tambah', [App\Http\Controllers\PesananController::class, 'tambahAlamat'])->name('alamat.tambah');
    Route::post('/alamat/simpan', [App\Http\Controllers\PesananController::class, 'simpanAlamat'])->name('alamat.simpan');
    Route::get('/checkout/get-ongkir/{kabupaten_id}', [App\Http\Controllers\PesananController::class, 'getOngkir'])->name('checkout.ongkir');

    // Pesanan management
    Route::get('/pesanan', [App\Http\Controllers\PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{id}', [App\Http\Controllers\PesananController::class, 'show'])->name('pesanan.show');
    Route::get('/pesanan/{id}/payment/upload', [App\Http\Controllers\PesananController::class, 'showPaymentUpload'])->name('pembayaran.upload');
    Route::post('/pesanan/{id}/payment', [App\Http\Controllers\PesananController::class, 'submitPayment'])->name('pesanan.payment');
    Route::post('/pesanan/{id}/konfirmasi', [App\Http\Controllers\PesananController::class, 'konfirmasiPesanan'])->name('pesanan.konfirmasi');

    // Direct payment proof viewing route
    Route::get('/pesanan/{id}/payment-proof', [App\Http\Controllers\PesananController::class, 'viewPaymentProof'])->name('pesanan.payment-proof');

    // Keluhan routes
    Route::get('/keluhan', [App\Http\Controllers\KeluhanController::class, 'index'])->name('keluhan.index');
    Route::get('/keluhan/create', [App\Http\Controllers\KeluhanController::class, 'create'])->name('keluhan.create');
    Route::post('/keluhan', [App\Http\Controllers\KeluhanController::class, 'store'])->name('keluhan.store');
    Route::get('/keluhan/{id}', [App\Http\Controllers\KeluhanController::class, 'show'])->name('keluhan.show');

    // Enhanced order tracking and management
    Route::get('/pesanan/{pesanan}/tracking', [App\Http\Controllers\PesananController::class, 'tracking'])->name('pesanan.tracking');
    Route::post('/pesanan/{pesanan}/delivery-status', [App\Http\Controllers\PesananController::class, 'updateDeliveryStatus'])->name('pesanan.delivery-status');
    Route::get('/pesanan/{pesanan}/track-shipping', [App\Http\Controllers\PesananController::class, 'trackShipping'])->name('pesanan.track-shipping');
    Route::get('/pesanan/{pesanan}/review', [App\Http\Controllers\PesananController::class, 'review'])->name('pesanan.review');
    Route::get('/pesanan/{pesanan}/invoice', [App\Http\Controllers\PesananController::class, 'downloadInvoice'])->name('pesanan.invoice');
    Route::post('/pesanan/{pesanan}/cancel', [App\Http\Controllers\PesananController::class, 'cancel'])->name('pesanan.cancel');
    Route::get('/pesanan/statistics', [App\Http\Controllers\PesananController::class, 'statistics'])->name('pesanan.statistics');

    // Refund management - Customer
    Route::get('/refunds', [App\Http\Controllers\RefundController::class, 'customerIndex'])->name('refunds.index');
    Route::get('/refunds/{refund}', [App\Http\Controllers\RefundController::class, 'customerShow'])->name('refunds.show');
    Route::get('/pesanan/{pesanan}/refund/create', [App\Http\Controllers\RefundController::class, 'customerCreate'])->name('refunds.create');
    Route::post('/pesanan/{pesanan}/refund', [App\Http\Controllers\RefundController::class, 'store'])->name('refunds.store');

    // Order timeline view
    Route::get('/pesanan/{pesanan}/timeline', [App\Http\Controllers\PesananController::class, 'timeline'])->name('pesanan.timeline');

    // Review management - Customer
    Route::get('/reviews', [App\Http\Controllers\ReviewController::class, 'customerIndex'])->name('reviews.index');
    Route::get('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'customerShow'])->name('reviews.show');
    Route::get('/pesanan/{pesanan}/reviews/create', [App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/pesanan/{pesanan}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/interaction', [App\Http\Controllers\ReviewController::class, 'toggleInteraction'])->name('reviews.interaction');

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
    Route::get('/pesanan/edit-alamat/{id}', [App\Http\Controllers\PesananController::class, 'editAlamat'])->name('pesanan.editAlamat');
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

    // Admin dashboard routes
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('reports/sales', [App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('admin.reports.sales');
    Route::get('reports/financial', [App\Http\Controllers\Admin\ReportController::class, 'financial'])->name('admin.reports.financial');

    // Expense management routes
    Route::resource('expenses', App\Http\Controllers\Admin\ExpenseController::class, ['as' => 'admin']);

    // Keluhan management routes
    Route::get('keluhan', [App\Http\Controllers\Admin\KeluhanController::class, 'index'])->name('admin.keluhan.index');
    Route::get('keluhan/{id}', [App\Http\Controllers\Admin\KeluhanController::class, 'show'])->name('admin.keluhan.show');
    Route::put('keluhan/{id}/respond', [App\Http\Controllers\Admin\KeluhanController::class, 'respond'])->name('admin.keluhan.respond');

    // Admin profile routes
    Route::get('profile', [App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('admin.profile.show');
    Route::get('profile/edit', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('admin.profile.update');

    // Admin keluhan routes
    Route::get('keluhan', [App\Http\Controllers\Admin\KeluhanController::class, 'index'])->name('admin.keluhan.index');
    Route::get('keluhan/{id}', [App\Http\Controllers\Admin\KeluhanController::class, 'show'])->name('admin.keluhan.show');
    Route::post('keluhan/{id}/respond', [App\Http\Controllers\Admin\KeluhanController::class, 'respond'])->name('admin.keluhan.respond');

    // Admin notification routes
    Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('admin.notifications.index');

    // Admin pesanan routes
    Route::get('pesanan', [App\Http\Controllers\Admin\PesananController::class, 'index'])->name('admin.pesanan.index');
    Route::get('pesanan/{id}', [App\Http\Controllers\Admin\PesananController::class, 'show'])->name('admin.pesanan.show');
    Route::get('pesanan/{id}/edit', [App\Http\Controllers\Admin\PesananController::class, 'edit'])->name('admin.pesanan.edit');
    Route::match(['post', 'put'], 'pesanan/{id}/update-status', [App\Http\Controllers\Admin\PesananController::class, 'updateStatus'])->name('admin.pesanan.updateStatus');
    Route::post('pesanan/{id}/force-expire', [App\Http\Controllers\Admin\PesananController::class, 'forceExpireOrder'])->name('admin.pesanan.forceExpire');

    // Add missing order processing routes
    Route::post('pesanan/{id}/process', [App\Http\Controllers\Admin\PesananController::class, 'process'])->name('admin.pesanan.process');
    Route::post('pesanan/{id}/ship', [App\Http\Controllers\Admin\PesananController::class, 'ship'])->name('admin.pesanan.ship');
    Route::post('pesanan/{id}/confirm-payment', [App\Http\Controllers\Admin\PesananController::class, 'confirmPayment'])->name('admin.pesanan.confirm-payment');
    Route::post('pesanan/{id}/cancel', [App\Http\Controllers\Admin\PesananController::class, 'cancel'])->name('admin.pesanan.cancel');
    Route::get('pesanan/{id}/payment-proof', [App\Http\Controllers\Admin\PesananController::class, 'viewPaymentProof'])->name('admin.pesanan.payment-proof');

    // Admin users routes
    Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('users/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
    Route::get('users/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::post('users/{id}/toggle-active', [App\Http\Controllers\Admin\UserController::class, 'toggleActive'])->name('admin.users.toggle-active');

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

    // Enhanced Order Management - Admin Routes

    // Enhanced Pesanan Management
    Route::get('pesanan/dashboard', [App\Http\Controllers\Admin\PesananController::class, 'dashboard'])->name('admin.pesanan.dashboard');
    Route::get('pesanan/statistics', [App\Http\Controllers\Admin\PesananController::class, 'statistics'])->name('admin.pesanan.statistics');
    Route::get('pesanan/export', [App\Http\Controllers\Admin\PesananController::class, 'export'])->name('admin.pesanan.export');
    Route::post('pesanan/bulk-action', [App\Http\Controllers\Admin\PesananController::class, 'bulkAction'])->name('admin.pesanan.bulk-action');
    Route::get('pesanan/{pesanan}/timeline', [App\Http\Controllers\Admin\PesananController::class, 'timeline'])->name('admin.pesanan.timeline');
    Route::post('pesanan/{pesanan}/timeline', [App\Http\Controllers\Admin\PesananController::class, 'addTimeline'])->name('admin.pesanan.timeline.add');

    // Refund Management
    Route::prefix('refunds')->group(function () {
        Route::get('/', [App\Http\Controllers\RefundController::class, 'adminIndex'])->name('admin.refunds.index');
        Route::get('/{refund}', [App\Http\Controllers\RefundController::class, 'adminShow'])->name('admin.refunds.show');
        Route::post('/{refund}/process', [App\Http\Controllers\RefundController::class, 'process'])->name('admin.refunds.process');
        Route::get('/dashboard/stats', [App\Http\Controllers\RefundController::class, 'dashboardStats'])->name('admin.refunds.dashboard');
        Route::get('/export', [App\Http\Controllers\RefundController::class, 'export'])->name('admin.refunds.export');
    });

    // Review Management
    Route::prefix('reviews')->group(function () {
        Route::get('/', [App\Http\Controllers\ReviewController::class, 'adminIndex'])->name('admin.reviews.index');
        Route::get('/moderate', [App\Http\Controllers\ReviewController::class, 'moderate'])->name('admin.reviews.moderate');
        Route::get('/statistics', [App\Http\Controllers\ReviewController::class, 'statistics'])->name('admin.reviews.statistics');
        Route::get('/{review}', [App\Http\Controllers\ReviewController::class, 'adminShow'])->name('admin.reviews.show');
        Route::post('/{review}/reply', [App\Http\Controllers\ReviewController::class, 'addAdminReply'])->name('admin.reviews.addAdminReply');
        Route::post('/{review}/update-status', [App\Http\Controllers\ReviewController::class, 'updateStatus'])->name('admin.reviews.updateStatus');
        Route::post('/bulk-moderate', [App\Http\Controllers\ReviewController::class, 'bulkModerate'])->name('admin.reviews.bulkModerate');
    });

    // Diagnostic Tools
    Route::get('diagnostic/payment-proofs', [App\Http\Controllers\Admin\DiagnosticController::class, 'checkPaymentProofPaths'])->name('admin.diagnostic.payment-proofs');

    // Expense Management (export route only - main resource route is already defined above)
    Route::get('expenses-export', [App\Http\Controllers\Admin\ExpenseController::class, 'export'])->name('admin.expenses.export');
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

// Keluhan routes
Route::get('/keluhan', [App\Http\Controllers\KeluhanController::class, 'index'])->name('keluhan.index')->middleware('auth');
Route::get('/keluhan/create', [App\Http\Controllers\KeluhanController::class, 'create'])->name('keluhan.create')->middleware('auth');
Route::post('/keluhan', [App\Http\Controllers\KeluhanController::class, 'store'])->name('keluhan.store')->middleware('auth');
Route::get('/keluhan/{id}', [App\Http\Controllers\KeluhanController::class, 'show'])->name('keluhan.show')->middleware('auth');

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
Route::get('/debug-http-request', [RajaOngkirController::class, 'debugHttpRequest'])->name('debug-http-request');
Route::get('/test-domestic-cost', [RajaOngkirController::class, 'testDomesticCost'])->name('test-domestic-cost');
Route::post('/cek-tracking', [RajaOngkirController::class, 'cekTrackingStatus'])->name('cek-tracking');

// Public address search for shipping calculator
Route::get('/public/search-alamat', [RajaOngkirController::class, 'publicSearchAlamat'])->name('public.search-alamat');

// Web endpoint for shipping cost calculation (for testing without throttle)
Route::post('/web/cek-ongkir', [RajaOngkirController::class, 'cekOngkir'])->name('web.cek-ongkir');

// Shipping calculator frontend page
Route::get('/shipping-calculator', function () {
    return view('shipping-calculator');
})->name('shipping-calculator');

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
