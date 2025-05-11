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
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Provinsi -->
                        <div>
                            <label for="provinsi_id" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                            <select id="provinsi_id" name="provinsi_id" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500 @error('provinsi_id') border-red-300 @enderror">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinsi as $prov)
                                <option value="{{ $prov->id }}" {{ old('provinsi_id', $user->provinsi_id) == $prov->id ? 'selected' : '' }}>{{ $prov->nama }}</option>
                                @endforeach
                            </select>
                            @error('provinsi_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kabupaten -->
                        <div>
                            <label for="kabupaten_id" class="block text-sm font-medium text-gray-700 mb-1">Kabupaten / Kota</label>
                            <select id="kabupaten_id" name="kabupaten_id" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500 @error('kabupaten_id') border-red-300 @enderror">
                                <option value="">-- Pilih Kabupaten / Kota --</option>
                                @foreach($kabupaten as $kab)
                                <option value="{{ $kab->id }}" {{ old('kabupaten_id', $user->kabupaten_id) == $kab->id ? 'selected' : '' }}>{{ $kab->nama }}</option>
                                @endforeach
                            </select>
                            @error('kabupaten_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label for="kecamatan_id" class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                            <select id="kecamatan_id" name="kecamatan_id" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500 @error('kecamatan_id') border-red-300 @enderror">
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach($kecamatan as $kec)
                                <option value="{{ $kec->id }}" {{ old('kecamatan_id', $user->kecamatan_id) == $kec->id ? 'selected' : '' }}>{{ $kec->nama }}</option>
                                @endforeach
                            </select>
                            @error('kecamatan_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Jalan -->
                        <div class="md:col-span-2">
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-5">Ubah Password</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500 @error('password') border-red-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah password.</p>
                            @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Form Buttons -->
                <div class="px-6 py-4 bg-gray-50 flex justify-end">
                    <a href="{{ route('profile.show') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .preview-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush

@push('scripts')
<script>
    function showPreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const preview = input.parentElement.previousElementSibling;
                preview.innerHTML = '<img src="' + e.target.result + '" class="preview-image">';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Handle dynamic changing of kabupaten and kecamatan dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        const provinsiSelect = document.getElementById('provinsi_id');
        const kabupatenSelect = document.getElementById('kabupaten_id');
        const kecamatanSelect = document.getElementById('kecamatan_id');

        if (provinsiSelect) {
            provinsiSelect.addEventListener('change', function() {
                const provinsiId = this.value;
                if (!provinsiId) {
                    kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten / Kota --</option>';
                    kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                    return;
                }

                // Fetch kabupaten
                fetch(`/profile/kabupaten?provinsi_id=${provinsiId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten / Kota --</option>';

                    data.forEach(function(item) {
                        kabupatenSelect.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
                    });
                })
                .catch(error => console.error('Error:', error));
            });
        }

        if (kabupatenSelect) {
            kabupatenSelect.addEventListener('change', function() {
                const kabupatenId = this.value;
                if (!kabupatenId) {
                    kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                    return;
                }

                // Fetch kecamatan
                fetch(`/profile/kecamatan?kabupaten_id=${kabupatenId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

                    data.forEach(function(item) {
                        kecamatanSelect.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
                    });
                })
                .catch(error => console.error('Error:', error));
            });
        }
    });
</script>
@endpush

@endsection
