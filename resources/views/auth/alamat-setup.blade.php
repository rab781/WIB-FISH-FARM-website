<x-guest-layout>
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Lengkapi Data Alamat</h2>

    <form method="POST" action="{{ route('alamat.store') }}">
        @csrf

        <!-- Nomor HP -->
        <div class="mb-4">
            <x-input-label for="no_hp" :value="__('Nomor HP')" class="text-gray-700 font-semibold" />
            <x-text-input id="no_hp" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500"
                type="text" name="no_hp" :value="old('no_hp')" required autofocus />
            <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
        </div>

        <!-- Provinsi -->
        <div class="mb-4">
            <x-input-label for="provinsi_id" :value="__('Provinsi')" class="text-gray-700 font-semibold" />
            <select id="provinsi_id" name="provinsi_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                <option value="">Pilih Provinsi</option>
                @foreach($provinsi as $p)
                    <option value="{{ $p->id }}">{{ $p->nama_provinsi }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('provinsi_id')" class="mt-2" />
        </div>

        <!-- Kabupaten -->
        <div class="mb-4">
            <x-input-label for="kabupaten_id" :value="__('Kabupaten')" class="text-gray-700 font-semibold" />
            <select id="kabupaten_id" name="kabupaten_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500" required disabled>
                <option value="">Pilih Kabupaten</option>
            </select>
            <x-input-error :messages="$errors->get('kabupaten_id')" class="mt-2" />
        </div>

        <!-- Kecamatan -->
        <div class="mb-4">
            <x-input-label for="kecamatan_id" :value="__('Kecamatan')" class="text-gray-700 font-semibold" />
            <select id="kecamatan_id" name="kecamatan_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500" required disabled>
                <option value="">Pilih Kecamatan</option>
            </select>
            <x-input-error :messages="$errors->get('kecamatan_id')" class="mt-2" />
        </div>

        <!-- Alamat (Jalan) -->
        <div class="mb-6">
            <x-input-label for="alamat_jalan" :value="__('Alamat (Jalan)')" class="text-gray-700 font-semibold" />
            <textarea id="alamat_jalan" name="alamat_jalan" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500" required>{{ old('alamat_jalan') }}</textarea>
            <x-input-error :messages="$errors->get('alamat_jalan')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full py-3 flex justify-center bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-md">
                {{ __('Simpan Data') }}
            </x-primary-button>
        </div>
    </form>

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
</x-guest-layout>
