@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-lg">
        <div class="px-6 py-4">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Pengaturan Alamat</h2>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('alamat.update') }}">
                @csrf

                <!-- Nomor HP -->
                <div class="mb-4">
                    <label for="no_hp" class="block text-gray-700 font-bold mb-2">Nomor HP</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                        class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    @error('no_hp')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Provinsi -->
                <div class="mb-4">
                    <label for="provinsi_id" class="block text-gray-700 font-bold mb-2">Provinsi</label>
                    <select id="provinsi_id" name="provinsi_id" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinsi as $p)
                            <option value="{{ $p->id }}" {{ isset($kabupaten) && $kabupaten->provinsi_id == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_provinsi }}
                            </option>
                        @endforeach
                    </select>
                    @error('provinsi_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kabupaten -->
                <div class="mb-4">
                    <label for="kabupaten_id" class="block text-gray-700 font-bold mb-2">Kabupaten</label>
                    <select id="kabupaten_id" name="kabupaten_id" data-selected="{{ old('kabupaten_id', $user->kabupaten_id ?? ($kabupaten->id ?? '')) }}" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required disabled>
                        <option value="">-- Pilih Kabupaten --</option>
                        @if(isset($kabupaten_list))
                            @foreach($kabupaten_list as $k)
                                <option value="{{ $k->id }}" {{ isset($kabupaten) && $kabupaten->id == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kabupaten }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('kabupaten_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kecamatan -->
                <div class="mb-4">
                    <label for="kecamatan_id" class="block text-gray-700 font-bold mb-2">Kecamatan</label>
                    <select id="kecamatan_id" name="kecamatan_id" data-selected="{{ old('kecamatan_id', $user->kecamatan_id ?? ($kecamatan->id ?? '')) }}" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required disabled>
                        <option value="">-- Pilih Kecamatan --</option>
                        @if(isset($kecamatan_list))
                            @foreach($kecamatan_list as $kec)
                                <option value="{{ $kec->id }}" {{ isset($kecamatan) && $kecamatan->id == $kec->id ? 'selected' : '' }}>
                                    {{ $kec->nama_kecamatan }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('kecamatan_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat (Jalan) -->
                <div class="mb-6">
                    <label for="alamat_jalan" class="block text-gray-700 font-bold mb-2">Alamat (Jalan)</label>
                    <textarea id="alamat_jalan" name="alamat_jalan" rows="3"
                        class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('alamat_jalan', $user->alamat_jalan) }}</textarea>
                    @error('alamat_jalan')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="w-full py-3 flex justify-center bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinsiSelect = document.getElementById('provinsi_id');
        const kabupatenSelect = document.getElementById('kabupaten_id');
        const kecamatanSelect = document.getElementById('kecamatan_id');

        // Simpan nilai yang sudah dipilih sebelumnya
        const selectedKabupaten = kabupatenSelect.getAttribute('data-selected');
        const selectedKecamatan = kecamatanSelect.getAttribute('data-selected');

        // Event listener untuk perubahan provinsi
        provinsiSelect.addEventListener('change', function() {
            const provinsiId = this.value;

            // Reset dan disable dropdown kabupaten dan kecamatan
            kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
            kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

            // Aktifkan/nonaktifkan dropdown berdasarkan pilihan
            kabupatenSelect.disabled = !provinsiId;
            kecamatanSelect.disabled = true;

            if (provinsiId) {
                // Tampilkan indikator loading
                kabupatenSelect.innerHTML = '<option value="">Memuat data...</option>';

                // Fetch kabupaten data
                fetch(`/api/kabupaten/${provinsiId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Reset dropdown
                        kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';

                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(kabupaten => {
                                const option = document.createElement('option');
                                option.value = kabupaten.id;
                                option.textContent = kabupaten.nama_kabupaten;
                                kabupatenSelect.appendChild(option);
                            });
                            kabupatenSelect.disabled = false;
                        } else {
                            kabupatenSelect.innerHTML = '<option value="">Tidak ada data kabupaten</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching kabupaten:', error);
                        kabupatenSelect.innerHTML = '<option value="">Error: Gagal memuat data</option>';
                    });
            }
        });

        // Event listener untuk perubahan kabupaten
        kabupatenSelect.addEventListener('change', function() {
            const kabupatenId = this.value;

            // Reset dropdown kecamatan
            kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            kecamatanSelect.disabled = !kabupatenId;

            if (kabupatenId) {
                // Tampilkan indikator loading
                kecamatanSelect.innerHTML = '<option value="">Memuat data...</option>';

                // Fetch kecamatan data
                fetch(`/api/kecamatan/${kabupatenId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Reset dropdown
                        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(kecamatan => {
                                const option = document.createElement('option');
                                option.value = kecamatan.id;
                                option.textContent = kecamatan.nama_kecamatan;
                                kecamatanSelect.appendChild(option);
                            });
                            kecamatanSelect.disabled = false;
                        } else {
                            kecamatanSelect.innerHTML = '<option value="">Tidak ada data kecamatan</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching kecamatan:', error);
                        kecamatanSelect.innerHTML = '<option value="">Error: Gagal memuat data</option>';
                    });
            }
        });

        // Auto-load kabupaten & kecamatan jika ada nilai yang dipilih sebelumnya
        const selectedProvinsi = provinsiSelect.value;

        if (selectedProvinsi && selectedKabupaten) {
            // Trigger change event pada provinsi untuk memuat kabupaten
            provinsiSelect.dispatchEvent(new Event('change'));

            // Set timeout untuk memastikan data kabupaten sudah dimuat
            setTimeout(() => {
                // Pilih kabupaten yang sesuai
                for (let i = 0; i < kabupatenSelect.options.length; i++) {
                    if (kabupatenSelect.options[i].value == selectedKabupaten) {
                        kabupatenSelect.selectedIndex = i;
                        break;
                    }
                }

                // Trigger change event pada kabupaten untuk memuat kecamatan
                kabupatenSelect.dispatchEvent(new Event('change'));

                // Set timeout untuk memastikan data kecamatan sudah dimuat
                setTimeout(() => {
                    // Pilih kecamatan yang sesuai
                    for (let i = 0; i < kecamatanSelect.options.length; i++) {
                        if (kecamatanSelect.options[i].value == selectedKecamatan) {
                            kecamatanSelect.selectedIndex = i;
                            break;
                        }
                    }
                }, 300);
            }, 300);
        }
    });
</script>
@endpush
@endsection
