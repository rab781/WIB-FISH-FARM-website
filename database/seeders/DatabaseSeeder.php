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
            UserSeeder::class,          // 1. User harus dibuat terlebih dahulu
            ProdukSeeder::class,        // 2. Produk
            OngkirSeeder::class,        // 3. Ongkir
            KeranjangSeeder::class,     // 4. Keranjang (membutuhkan User dan Produk)
            PesananSeeder::class,       // 5. Pesanan (membutuhkan User dan Ongkir)
            DetailPesananSeeder::class, // 6. Detail Pesanan (membutuhkan Pesanan dan Produk)
            PembayaranSeeder::class,    // 7. Pembayaran (membutuhkan Pesanan)
            UlasanSeeder::class,        // 8. Ulasan (membutuhkan User dan Produk)
        ]);
    }
}
