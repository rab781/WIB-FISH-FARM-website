@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex items-center mb-6">
        <a href="{{ url()->previous() }}" class="flex items-center text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Kembali</span>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 ml-4">Tambah Alamat Pengiriman</h1>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Alamat Pengiriman</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('alamat.simpan') }}" method="POST">
                @csrf

                <!-- Hidden fields for selected cart items -->
                @foreach(request('selected_items') as $item)
                    <input type="hidden" name="selected_items[]" value="{{ $item }}">
                @endforeach

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <!-- Provinsi -->
                    <div class="sm:col-span-3">
                        <label for="provinsi_id" class="block text-sm font-medium text-gray-700">Provinsi</label>
                        <div class="mt-1">
                            <select id="provinsi_id" name="provinsi_id" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinsi as $prov)
                                    <option value="{{ $prov->id }}" {{ old('provinsi_id', $user->provinsi_id) == $prov->id ? 'selected' : '' }}>
                                        {{ $prov->nama_provinsi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('provinsi_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kabupaten -->
                    <div class="sm:col-span-3">
                        <label for="kabupaten_id" class="block text-sm font-medium text-gray-700">Kabupaten/Kota</label>
                        <div class="mt-1">
                            <select id="kabupaten_id" name="kabupaten_id" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md" disabled>
                                <option value="">Pilih Kabupaten/Kota</option>
                                <!-- Kabupaten options will be loaded via AJAX -->
                            </select>
                        </div>
                        @error('kabupaten_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kecamatan -->
                    <div class="sm:col-span-3">
                        <label for="kecamatan_id" class="block text-sm font-medium text-gray-700">Kecamatan</label>
                        <div class="mt-1">
                            <select id="kecamatan_id" name="kecamatan_id" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md" disabled>
                                <option value="">Pilih Kecamatan</option>
                                <!-- Kecamatan options will be loaded via AJAX -->
                            </select>
                        </div>
                        @error('kecamatan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat Jalan -->
                    <div class="sm:col-span-6">
                        <label for="alamat_jalan" class="block text-sm font-medium text-gray-700">Detail Alamat</label>
                        <div class="mt-1">
                            <textarea id="alamat_jalan" name="alamat_jalan" rows="3" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Nama jalan, nomor rumah, RT/RW, nama gedung, dll.">{{ old('alamat_jalan', $user->alamat_jalan) }}</textarea>
                        </div>
                        @error('alamat_jalan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Simpan Alamat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinsiSelect = document.getElementById('provinsi_id');
    const kabupatenSelect = document.getElementById('kabupaten_id');
    const kecamatanSelect = document.getElementById('kecamatan_id');

    // Fungsi untuk toggle disabled state kabupaten
    function toggleKabupatenState() {
        const provinsiId = provinsiSelect.value;
        if (provinsiId) {
            kabupatenSelect.disabled = false;
        } else {
            kabupatenSelect.disabled = true;
            kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten / Kota</option>';
            kecamatanSelect.disabled = true;
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        }
    }

    // Fungsi untuk toggle disabled state kecamatan
    function toggleKecamatanState() {
        const kabupatenId = kabupatenSelect.value;
        if (kabupatenId) {
            kecamatanSelect.disabled = false;
        } else {
            kecamatanSelect.disabled = true;
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        }
    }

    // Initial state setup
    toggleKabupatenState();
    toggleKecamatanState();

    if (provinsiSelect) {
        provinsiSelect.addEventListener('change', function() {
            const provinsiId = this.value;
            if (!provinsiId) {
                kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten / Kota</option>';
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                toggleKabupatenState();
                toggleKecamatanState();
                return;
            }

            // Fetch kabupaten - using the same endpoint as profile page
            fetch(`/profile/kabupaten?provinsi_id=${provinsiId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten / Kota</option>';

                data.forEach(function(item) {
                    kabupatenSelect.innerHTML += `<option value="${item.id}">${item.nama_kabupaten}</option>`;
                });

                // Enable kabupaten after data loaded
                toggleKabupatenState();

                // Set selected if it matches user's kabupaten
                if ('{{ old("kabupaten_id", $user->kabupaten_id) }}') {
                    kabupatenSelect.value = '{{ old("kabupaten_id", $user->kabupaten_id) }}';
                    kabupatenSelect.dispatchEvent(new Event('change'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data kabupaten. Silakan coba lagi.');
            });
        });
    }

    if (kabupatenSelect) {
        kabupatenSelect.addEventListener('change', function() {
            const kabupatenId = this.value;
            if (!kabupatenId) {
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                toggleKecamatanState();
                return;
            }

            // Fetch kecamatan - using the same endpoint as profile page
            fetch(`/profile/kecamatan?kabupaten_id=${kabupatenId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

                data.forEach(function(item) {
                    kecamatanSelect.innerHTML += `<option value="${item.id}">${item.nama_kecamatan}</option>`;
                });

                // Enable kecamatan after data loaded
                toggleKecamatanState();

                // Set selected if it matches user's kecamatan
                if ('{{ old("kecamatan_id", $user->kecamatan_id) }}') {
                    kecamatanSelect.value = '{{ old("kecamatan_id", $user->kecamatan_id) }}';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data kecamatan. Silakan coba lagi.');
            });
        });
    }

    // Load initial values
    if (provinsiSelect.value) {
        provinsiSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
