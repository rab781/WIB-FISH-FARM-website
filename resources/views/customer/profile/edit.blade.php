@extends('layouts.customer')

@section('content')
<div class="bg-white py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Edit Profil</h1>
            <p class="mt-2 text-sm text-gray-600">Perbarui informasi profil dan alamat pengiriman Anda.</p>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                @csrf
                @method('PUT')

                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-5">Informasi Pribadi</h3>

                    <!-- Profile Photo -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                        <div class="flex items-center">
                            <div class="w-20 h-20 rounded-full overflow-hidden border border-gray-300 mr-4">
                                @if($user->foto)
                                <img src="{{ asset('storage/uploads/users/'.$user->foto) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @endif
                            </div>
                            <div>
                                <input type="file" name="foto" id="foto" class="hidden" accept="image/*" onchange="showPreview(this)">
                                <label for="foto" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 cursor-pointer text-sm font-medium">
                                    Pilih Foto
                                </label>
                                <p class="mt-1 text-xs text-gray-500">JPG, PNG, atau GIF. Maksimal 2MB.</p>
                                @error('foto')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500 @error('name') border-red-300 @enderror">
                            @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500 @error('email') border-red-300 @enderror">
                            @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                            <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500 @error('no_hp') border-red-300 @enderror">
                            @error('no_hp')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-5">Informasi Alamat</h3>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Current Address -->
                        @if ($user->alamat_id && $alamat)
                        <div class="bg-gray-50 p-4 rounded-md mb-4">
                            <h4 class="font-medium text-gray-700 mb-2">Alamat Saat Ini:</h4>
                            <p>{{ $user->alamat_jalan }}, {{ $alamat->kecamatan }}, {{ $alamat->kabupaten }}, {{ $alamat->provinsi }} {{ $alamat->kode_pos }}</p>
                        </div>
                        @endif

                        <!-- Cari & Pilih Alamat dengan Autocomplete -->
                        <div class="address-search-container">
                            <label for="alamat_search" class="block text-sm font-medium text-gray-700 mb-1">Cari & Pilih Alamat</label>
                            <input type="text" id="alamat_search" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500 @error('alamat_id') border-red-300 @enderror"
                                placeholder="Ketik untuk mencari (cth: Jakarta, Bandung, Surabaya)">
                            <input type="hidden" id="alamat_id" name="alamat_id" value="{{ $user->alamat_id ?? '' }}">

                            <!-- Dropdown hasil pencarian -->
                            <div id="address-dropdown" class="address-dropdown"></div>

                            <!-- Tampilan alamat yang terpilih -->
                            <div class="selected-address" style="{{ $user->alamat_id && $alamat ? '' : 'display:none' }}">
                                <span id="selected-address-display">{{ $user->alamat_id && $alamat ? $alamat->kecamatan . ', ' . $alamat->kabupaten . ', ' . $alamat->provinsi . ' ' . $alamat->kode_pos : '' }}</span>
                                <span id="clear-address" class="clear-address" title="Hapus alamat">×</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Ketik minimal 3 karakter untuk mencari alamat</p>
                            @error('alamat_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Jalan -->
                        <div>
                            <label for="alamat_jalan" class="block text-sm font-medium text-gray-700 mb-1">Alamat Jalan</label>
                            <textarea name="alamat_jalan" id="alamat_jalan" rows="3" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500 @error('alamat_jalan') border-red-300 @enderror">{{ old('alamat_jalan', $user->alamat_jalan) }}</textarea>
                            @error('alamat_jalan')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                    </div>
                </div>

                <!-- Change Password -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Ubah Password</h3>
                    <p class="text-sm text-gray-500 mb-5">Biarkan kosong jika tidak ingin mengubah password</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru (Opsional)</label>
                            <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500 @error('password') border-red-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter. Biarkan kosong jika tidak ingin mengubah.</p>
                            @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Ulangi password baru jika mengubah password.</p>
                        </div>
                    </div>
                </div>

                <!-- Form Buttons -->
                <div class="px-6 py-4 bg-gray-50 flex justify-end">
                    <a href="{{ route('profile.show') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/address-autocomplete.css') }}">
<style>
    .preview-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Shipping calculator styling */
    #shipping-loading {
        display: flex;
        align-items: center;
    }

    .alert {
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        border-radius: 0.375rem;
    }

    .alert-warning {
        background-color: #fff7ed;
        color: #c2410c;
        border-left: 4px solid #fb923c;
    }

    /* Table styles */
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    th, td {
        padding: 0.75rem 1rem;
        text-align: left;
    }

    th {
        font-weight: 500;
        background-color: #f9fafb;
    }

    tr:nth-child(even) {
        background-color: #f9fafb;
    }

    tr:hover {
        background-color: #f3f4f6;
    }
</style>
@endpush

@push('scripts')
<script>
    function showPreview(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    title: 'File Terlalu Besar',
                    text: 'Ukuran foto maksimal 2MB',
                    icon: 'error',
                    confirmButtonColor: '#dc2626'
                });
                input.value = '';
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    title: 'Format File Tidak Valid',
                    text: 'Format foto harus JPEG, PNG, JPG, GIF, atau WebP',
                    icon: 'error',
                    confirmButtonColor: '#dc2626'
                });
                input.value = '';
                return;
            }

            // Show preview
            var reader = new FileReader();
            reader.onload = function(e) {
                const preview = input.parentElement.previousElementSibling;
                preview.innerHTML = '<img src="' + e.target.result + '" class="preview-image">';
            }
            reader.readAsDataURL(file);
        }
    }

    function confirmUpdate() {
        return new Promise((resolve) => {
            Swal.fire({
                title: 'Konfirmasi Perubahan',
                text: 'Apakah anda yakin untuk mengubah data profil?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f97316',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                resolve(result.isConfirmed);
            });
        });
    }

    // Handle form submission with validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('profileForm');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Validate password confirmation if password is filled
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;

                if (password && password !== passwordConfirmation) {
                    Swal.fire({
                        title: 'Password Tidak Cocok',
                        text: 'Konfirmasi password tidak sesuai dengan password baru',
                        icon: 'error',
                        confirmButtonColor: '#dc2626'
                    });
                    return;
                }

                if (password && password.length < 8) {
                    Swal.fire({
                        title: 'Password Terlalu Pendek',
                        text: 'Password minimal 8 karakter',
                        icon: 'error',
                        confirmButtonColor: '#dc2626'
                    });
                    return;
                }

                const confirmed = await confirmUpdate();
                if (confirmed) {
                    this.submit();
                }
            });
        }
    });
</script>
<script src="{{ asset('js/address-autocomplete-fixed.js') }}"></script>
@endpush

@endsection
