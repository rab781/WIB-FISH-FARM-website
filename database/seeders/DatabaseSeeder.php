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
            // Provinsi, Kabupaten, dan Kecamatan sudah tidak digunakan
            // karena digantikan oleh tabel Alamat yang mengambil data dari RajaOngkir API
            UserSeeder::class,          // 1. User
            ProdukSeeder::class,        // 2. Produk
            LokasiSeeder::class,        // 3. Lokasi (kini menggunakan Alamat dari RajaOngkir)
            OngkirSeeder::class,        // 4. Ongkir (menggunakan Alamat)
            KeranjangSeeder::class,     // 5. Keranjang (membutuhkan User dan Produk)
            PesananSeeder::class,       // 6. Pesanan (membutuhkan User dan Ongkir)
            DetailPesananSeeder::class, // 7. Detail Pesanan (membutuhkan Pesanan dan Produk)
            PembayaranSeeder::class,    // 8. Pembayaran (membutuhkan Pesanan)
            UlasanSeeder::class,        // 9. Ulasan (membutuhkan User dan Produk)
        ]);

        // Jalankan seeder untuk data laporan
        $this->call([
            SalesReportSeeder::class,  // 10. Data penjualan untuk laporan
            ExpenseSeeder::class,      // 11. Data pengeluaran untuk laporan
        ]);
    }
}
