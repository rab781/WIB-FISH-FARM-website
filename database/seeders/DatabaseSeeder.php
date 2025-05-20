<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder dalam urutan yang benar untuk menjaga integritas relasi
        $this->call([
            ProvinsiSeeder::class,      // 1. Provinsi harus dibuat terlebih dahulu
            KabupatenSeeder::class,     // 2. Kabupaten (membutuhkan Provinsi)
            KecamatanSeeder::class,     // 3. Kecamatan (membutuhkan Kabupaten)
            UserSeeder::class,          // 4. User
            ProdukSeeder::class,        // 5. Produk
            LokasiSeeder::class,        // 6. Lokasi (membutuhkan Provinsi dan Kabupaten)
            OngkirSeeder::class,        // 7. Ongkir
            KeranjangSeeder::class,     // 8. Keranjang (membutuhkan User dan Produk)
            PesananSeeder::class,       // 9. Pesanan (membutuhkan User dan Ongkir)
            DetailPesananSeeder::class, // 10. Detail Pesanan (membutuhkan Pesanan dan Produk)
            PembayaranSeeder::class,    // 11. Pembayaran (membutuhkan Pesanan)
            UlasanSeeder::class,        // 12. Ulasan (membutuhkan User dan Produk)
        ]);
    }
}
