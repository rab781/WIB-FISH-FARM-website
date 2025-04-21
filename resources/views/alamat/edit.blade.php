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
                        <option value="">Pilih Provinsi</option>
                        @foreach($provinsi as $p)
                            <option value="{{ $p->id }}" {{ isset($kabupaten) && $kabupaten->provinsi_id == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_provinsi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kabupaten -->
                <div class="mb-4">
                    <label for="kabupaten_id" class="block text-gray-700 font-bold mb-2">Kabupaten</label>
                    <select id="kabupaten_id" name="kabupaten_id" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required {{ !isset($kabupaten) ? 'disabled' : '' }}>
                        <option value="">Pilih Kabupaten</option>
                        @if(isset($kabupaten_list))
                            @foreach($kabupaten_list as $k)
                                <option value="{{ $k->id }}" {{ isset($kabupaten) && $kabupaten->id == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kabupaten }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Kecamatan -->
                <div class="mb-4">
                    <label for="kecamatan_id" class="block text-gray-700 font-bold mb-2">Kecamatan</label>
                    <select id="kecamatan_id" name="kecamatan_id" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required {{ !isset($kecamatan) ? 'disabled' : '' }}>
                        <option value="">Pilih Kecamatan</option>
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
        // Event listener untuk perubahan provinsi
        document.getElementById('provinsi_id').addEventListener('change', function() {
            const provinsiId = this.value;
            const kabupatenDropdown = document.getElementById('kabupaten_id');
            const kecamatanDropdown = document.getElementById('kecamatan_id');

            // Reset dan disable dropdown kabupaten dan kecamatan
            kabupatenDropdown.innerHTML = '<option value="">Pilih Kabupaten</option>';
            kecamatanDropdown.innerHTML = '<option value="">Pilih Kecamatan</option>';
            kabupatenDropdown.disabled = !provinsiId;
            kecamatanDropdown.disabled = true;

            if (provinsiId) {
                // Fetch kabupaten data dengan URL yang benar
                fetch(`/api/kabupaten/${provinsiId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.length > 0) {
                            data.forEach(kabupaten => {
                                const option = document.createElement('option');
                                option.value = kabupaten.id;
                                option.textContent = kabupaten.nama_kabupaten;
                                kabupatenDropdown.appendChild(option);
                            });
                        } else {
                            console.log('Tidak ada data kabupaten ditemukan');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching kabupaten:', error);
                    });
            }
        });

        // Event listener untuk perubahan kabupaten
        document.getElementById('kabupaten_id').addEventListener('change', function() {
            const kabupatenId = this.value;
            const kecamatanDropdown = document.getElementById('kecamatan_id');

            // Reset dropdown kecamatan
            kecamatanDropdown.innerHTML = '<option value="">Pilih Kecamatan</option>';
            kecamatanDropdown.disabled = !kabupatenId;

            if (kabupatenId) {
                // Fetch kecamatan data dengan URL yang benar
                fetch(`/api/kecamatan/${kabupatenId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.length > 0) {
                            data.forEach(kecamatan => {
                                const option = document.createElement('option');
                                option.value = kecamatan.id;
                                option.textContent = kecamatan.nama_kecamatan;
                                kecamatanDropdown.appendChild(option);
                            });
                        } else {
                            console.log('Tidak ada data kecamatan ditemukan');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching kecamatan:', error);
                    });
            }
        });
    });
</script>
@endpush
@endsection
