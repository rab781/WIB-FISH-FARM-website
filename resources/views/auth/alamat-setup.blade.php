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

        <!-- Cari Alamat (RajaOngkir) -->
        <div class="mb-4">
            <x-input-label for="alamat_search" :value="__('Cari Alamat')" class="text-gray-700 font-semibold" />
            <x-text-input id="alamat_search" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500"
                type="text" name="alamat_search" :value="old('alamat_search')" placeholder="Masukkan nama kota/kabupaten" />
            <small class="text-gray-500">Ketik untuk mencari alamat dari RajaOngkir</small>
        </div>

        <!-- Hasil Pencarian -->
        <div class="mb-4">
            <x-input-label for="alamat_id" :value="__('Pilih Alamat')" class="text-gray-700 font-semibold" />
            <select id="alamat_id" name="alamat_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500" required>
                <option value="">Pilih alamat dari hasil pencarian</option>
            </select>
            <x-input-error :messages="$errors->get('alamat_id')" class="mt-2" />
        </div>

        <!-- Alamat (Jalan) -->
        <div class="mb-6">
            <x-input-label for="alamat_jalan" :value="__('Alamat (Jalan)')" class="text-gray-700 font-semibold" />
            <textarea id="alamat_jalan" name="alamat_jalan" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500" required>{{ old('alamat_jalan') }}</textarea>
            <x-input-error :messages="$errors->get('alamat_jalan')" class="mt-2" />
        </div>

        <div class="flex justify-between items-center mt-8">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                {{ __('Kembali ke Halaman Login') }}
            </a>

            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded">
                {{ __('Simpan Alamat') }}
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Raja Ongkir API integration
            const searchInput = document.getElementById('alamat_search');
            const alamatSelect = document.getElementById('alamat_id');

            let searchTimeout;

            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(function() {
                    const searchTerm = searchInput.value;

                    if (searchTerm.length < 3) return;

                    // Clear current options except the first one
                    while (alamatSelect.options.length > 1) {
                        alamatSelect.remove(1);
                    }

                    // Add loading option
                    const loadingOption = new Option('Loading...', '');
                    alamatSelect.add(loadingOption);

                    fetch(`/api/alamat/search?term=${searchTerm}`)
                        .then(response => response.json())
                        .then(data => {
                            // Remove loading option
                            alamatSelect.remove(alamatSelect.options.length - 1);

                            // Add options from API response
                            if (data.data && data.data.length > 0) {
                                data.data.forEach(item => {
                                    const formattedAddress = `${item.subdistrict}, ${item.type} ${item.city}, ${item.province} ${item.postal_code}`;
                                    const option = new Option(formattedAddress, item.id);
                                    alamatSelect.add(option);
                                });
                            } else {
                                const noResultOption = new Option('Tidak ada hasil ditemukan', '');
                                noResultOption.disabled = true;
                                alamatSelect.add(noResultOption);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching alamat:', error);
                            alamatSelect.remove(alamatSelect.options.length - 1);
                            const errorOption = new Option('Error: Gagal memuat data', '');
                            errorOption.disabled = true;
                            alamatSelect.add(errorOption);
                        });
                }, 500);
            });
        });
    </script>
    @endpush
</x-guest-layout>
