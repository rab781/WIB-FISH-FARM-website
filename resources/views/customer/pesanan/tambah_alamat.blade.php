@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-amber-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Enhanced Header with Breadcrumb -->
        <div class="mb-8">
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
                <a href="{{ route('checkout', ['selected_items' => request('selected_items')]) }}" class="hover:text-orange-600 transition-colors">Checkout</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-orange-600 font-medium">Tambah Alamat</span>
            </nav>

            <div class="flex items-center">
                <div class="flex-shrink-0 bg-gradient-to-r from-orange-600 to-orange-700 rounded-full p-3 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h1 class="text-3xl font-bold text-gray-900">Tambah Alamat Pengiriman</h1>
                    <p class="text-gray-600 mt-1">Pastikan informasi alamat yang Anda masukkan akurat untuk pengiriman yang tepat</p>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Enhanced Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-8 py-6">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h2 class="text-xl font-semibold text-white">Informasi Alamat Pengiriman</h2>
                </div>
                <p class="text-orange-100 mt-2 text-sm">Silakan lengkapi informasi alamat pengiriman Anda dengan benar</p>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <form action="{{ route('alamat.simpan') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Hidden fields for selected cart items -->
                    @foreach(request('selected_items') as $item)
                        <input type="hidden" name="selected_items[]" value="{{ $item }}">
                    @endforeach

                    <!-- Address Search Section -->
                    <div class="space-y-6">
                        <div class="relative">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                    <span class="text-orange-600 font-bold text-sm">1</span>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Cari & Pilih Lokasi</h3>
                                    <p class="text-sm text-gray-600">Ketik nama kota atau kecamatan untuk mencari alamat</p>
                                </div>
                            </div>

                            <div class="section-card bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                                <label for="alamat_search" class="flex items-center text-sm font-medium text-gray-700 mb-3">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Pencarian Alamat
                                </label>
                                <div class="address-search-container">
                                    <input type="text"
                                           id="alamat_search"
                                           class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 @error('alamat_id') border-red-300 @enderror"
                                           placeholder="Contoh: Jakarta Selatan, Bandung, Surabaya..."
                                           autocomplete="off">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                    </div>

                                    <!-- Dropdown untuk hasil pencarian alamat -->
                                    <div id="address-dropdown" class="address-dropdown"></div>
                                </div>

                                <input type="hidden" id="alamat_id" name="alamat_id" value="{{ $user->alamat_id ?? '' }}">

                                <!-- Selected Address Display -->
                                <div class="selected-address mt-4" style="{{ ($user->alamat_id ?? false) && isset($alamat) ? '' : 'display:none' }}">
                                    <span id="selected-address-display" class="text-sm text-gray-700">
                                        {{ ($user->alamat_id ?? false) && isset($alamat) ? $alamat->kecamatan . ', ' . $alamat->kabupaten . ', ' . $alamat->provinsi . ' ' . $alamat->kode_pos : '' }}
                                    </span>
                                    <span id="clear-address" class="clear-address text-red-500 cursor-pointer ml-2" title="Hapus alamat">×</span>
                                </div>

                                <p class="mt-2 text-xs text-blue-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Ketik minimal 3 karakter untuk mulai mencari alamat
                                </p>
                                @error('alamat_id')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Street Address Section -->
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                    <span class="text-orange-600 font-bold text-sm">2</span>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Detail Alamat Jalan</h3>
                                    <p class="text-sm text-gray-600">Masukkan alamat lengkap termasuk nama jalan, nomor rumah, RT/RW</p>
                                </div>
                            </div>

                            <div class="section-card bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100">
                                <label for="alamat_jalan" class="flex items-center text-sm font-medium text-gray-700 mb-3">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    Alamat Lengkap
                                </label>
                                <textarea name="alamat_jalan"
                                          id="alamat_jalan"
                                          rows="4"
                                          class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 resize-none @error('alamat_jalan') border-red-400 @enderror"
                                          placeholder="Contoh: Jl. Sudirman No. 123, RT 01/RW 05, Komplek Permata Hijau, dekat Alfamart">{{ old('alamat_jalan', $user->alamat_jalan ?? '') }}</textarea>
                                <p class="mt-2 text-xs text-green-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Semakin detail alamat, semakin mudah kurir menemukan lokasi Anda
                                </p>
                                @error('alamat_jalan')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone Number Section -->
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                    <span class="text-orange-600 font-bold text-sm">3</span>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Nomor Telepon</h3>
                                    <p class="text-sm text-gray-600">Nomor yang dapat dihubungi saat pengiriman</p>
                                </div>
                            </div>

                            <div class="section-card bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl p-6 border border-purple-100">
                                <label for="no_hp" class="flex items-center text-sm font-medium text-gray-700 mb-3">
                                    <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    Nomor Telepon Aktif
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           id="no_hp"
                                           name="no_hp"
                                           value="{{ old('no_hp', $user->no_hp ?? '') }}"
                                           class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 @error('no_hp') border-red-400 @enderror"
                                           placeholder="Contoh: 08123456789"
                                           required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-purple-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.343 4.343l1.414 1.414m0 0L12 12l6.243-6.243 1.414-1.414M12 2v4m0 12v4m10-10h-4M6 12H2"/>
                                    </svg>
                                    Pastikan nomor aktif untuk konfirmasi pengiriman
                                </p>
                                @error('no_hp')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Action Buttons -->
                    <div class="pt-8 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('checkout', ['selected_items' => request('selected_items')]) }}"
                               class="btn-secondary inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 rounded-xl shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200">
                                <svg class="animated-icon w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Kembali ke Checkout
                            </a>
                            <button type="submit"
                                    class="btn-primary inline-flex items-center justify-center px-8 py-3 border border-transparent rounded-xl shadow-sm text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200">
                                <svg class="animated-icon w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="btn-text">Simpan Alamat & Lanjutkan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/address-autocomplete.css') }}">
<style>
    /* Additional custom styles for enhanced form */
    .step-indicator {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
        border-radius: 50%;
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.875rem;
        box-shadow: 0 4px 8px rgba(249, 115, 22, 0.3);
        border: 2px solid white;
    }

    .section-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .section-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s;
    }

    .section-card:hover::before {
        left: 100%;
    }

    .section-card:hover {
        border-color: #fed7aa;
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .gradient-bg {
        background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
    }

    /* Enhanced input animations */
    .form-input {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .form-input:focus {
        transform: scale(1.02);
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.15), 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .form-input:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Step number enhanced styling */
    .w-8.h-8.bg-orange-100 {
        background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
        box-shadow: 0 4px 8px rgba(249, 115, 22, 0.2);
        transition: all 0.3s ease;
    }

    .w-8.h-8.bg-orange-100:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 12px rgba(249, 115, 22, 0.3);
    }

    /* Loading spinner for form submission */
    .loading-spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 2px solid #ffffff;
        border-top: 2px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .btn-loading .loading-spinner {
        display: inline-block;
    }

    .btn-loading .btn-text {
        display: none;
    }

    /* Enhanced button styles */
    .btn-primary {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.4);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-primary:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 8px 25px rgba(249, 115, 22, 0.5);
        background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
    }

    .btn-secondary {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-secondary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Animated icons */
    .animated-icon {
        transition: transform 0.3s ease;
    }

    .animated-icon:hover {
        transform: scale(1.2) rotate(5deg);
    }

    /* Address search container styling */
    .address-search-container {
        position: relative;
    }

    /* Allow dropdown to extend outside section-card */
    .section-card:has(.address-search-container),
    .section-card.address-dropdown-open {
        overflow: visible !important;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/address-autocomplete-fixed.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced form submission with loading state
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const spinner = '<div class="loading-spinner mr-2"></div>';

    form.addEventListener('submit', function(e) {
        // Add loading state
        submitBtn.classList.add('btn-loading');
        submitBtn.disabled = true;
        submitBtn.innerHTML = spinner + '<span class="btn-text">Menyimpan...</span>';

        // Validate required fields
        const alamatId = document.getElementById('alamat_id').value;
        const alamatJalan = document.getElementById('alamat_jalan').value.trim();
        const noHp = document.getElementById('no_hp').value.trim();

        if (!alamatId) {
            e.preventDefault();
            resetSubmitButton();
            showAlert('error', 'Silakan pilih alamat terlebih dahulu');
            return false;
        }

        if (!alamatJalan) {
            e.preventDefault();
            resetSubmitButton();
            showAlert('error', 'Alamat jalan harus diisi');
            return false;
        }

        if (!noHp) {
            e.preventDefault();
            resetSubmitButton();
            showAlert('error', 'Nomor telepon harus diisi');
            return false;
        }
    });

    function resetSubmitButton() {
        submitBtn.classList.remove('btn-loading');
        submitBtn.disabled = false;
        submitBtn.innerHTML = `
            <svg class="animated-icon w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="btn-text">Simpan Alamat & Lanjutkan</span>
        `;
    }

    function showAlert(type, message) {
        // Simple alert function - you can replace with SweetAlert if available
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: type === 'error' ? 'Oops...' : 'Berhasil!',
                text: message,
                confirmButtonColor: '#f97316'
            });
        } else {
            alert(message);
        }
    }

    // Add interactive effects to form sections
    const sectionCards = document.querySelectorAll('.section-card');
    sectionCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
            this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.15)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });

    // Add focus effects to step indicators
    const stepIndicators = document.querySelectorAll('.w-8.h-8.bg-orange-100');
    stepIndicators.forEach(indicator => {
        indicator.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });

        indicator.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Phone number formatting
    const phoneInput = document.getElementById('no_hp');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');

        // Format Indonesian phone numbers
        if (value.startsWith('08')) {
            // Format: 08xx-xxxx-xxxx
            value = value.replace(/(\d{4})(\d{4})(\d+)/, '$1-$2-$3');
        } else if (value.startsWith('628')) {
            // Format: +62 8xx-xxxx-xxxx
            value = '+62 ' + value.substring(2).replace(/(\d{3})(\d{4})(\d+)/, '$1-$2-$3');
        }

        e.target.value = value;
    });

    // Auto-resize textarea
    const alamatTextarea = document.getElementById('alamat_jalan');
    alamatTextarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
});
</script>
@endpush
@endsection
