<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ProdukSeeder;
use Database\Seeders\LokasiSeeder;
use Database\Seeders\OngkirSeeder;
use Database\Seeders\KeranjangSeeder;
use Database\Seeders\PesananSeeder;
use Database\Seeders\DetailPesananSeeder;
use Database\Seeders\PembayaranSeeder;
use Database\Seeders\UlasanSeeder;
use Database\Seeders\RefundRequestSeeder;
use Database\Seeders\KeluhanSeeder;
use Database\Seeders\SalesReportSeeder;
use Database\Seeders\ExpenseSeeder;

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
            RefundRequestSeeder::class,  // 10. Refund Requests (membutuhkan Pesanan)
            KeluhanSeeder::class,        // 11. Keluhan (membutuhkan User)
        ]);

        // Jalankan seeder untuk data laporan
        $this->call([
            SalesReportSeeder::class,  // 10. Data penjualan untuk laporan
            ExpenseSeeder::class,      // 11. Data pengeluaran untuk laporan
        ]);
    }
}
