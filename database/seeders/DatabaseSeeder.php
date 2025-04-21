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
            OngkirSeeder::class,        // 6. Ongkir
            KeranjangSeeder::class,     // 7. Keranjang (membutuhkan User dan Produk)
            PesananSeeder::class,       // 8. Pesanan (membutuhkan User dan Ongkir)
            DetailPesananSeeder::class, // 9. Detail Pesanan (membutuhkan Pesanan dan Produk)
            PembayaranSeeder::class,    // 10. Pembayaran (membutuhkan Pesanan)
            UlasanSeeder::class,        // 11. Ulasan (membutuhkan User dan Produk)
        ]);
    }
}
