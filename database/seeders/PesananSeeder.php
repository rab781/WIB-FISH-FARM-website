<?php

namespace Database\Seeders;

use App\Models\Pesanan;
use Illuminate\Database\Seeder;

class PesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pesanan untuk user Budi (ID: 2)
        Pesanan::create([
            'user_id' => 2, // Budi
            'id_ongkir' => 1, // Jakarta
            'total_harga' => 135000, // 120000 (produk) + 15000 (ongkir)
            'status_pesanan' => 'Selesai',
        ]);

        // Pesanan untuk user Ani (ID: 3)
        Pesanan::create([
            'user_id' => 3, // Ani
            'id_ongkir' => 2, // Bandung
            'total_harga' => 168000, // 150000 (produk) + 18000 (ongkir)
            'status_pesanan' => 'Dikirim',
        ]);

        // Pesanan untuk user Dedi (ID: 4)
        Pesanan::create([
            'user_id' => 4, // Dedi
            'id_ongkir' => 3, // Surabaya
            'total_harga' => 245000, // 225000 (produk) + 20000 (ongkir)
            'status_pesanan' => 'Diproses',
        ]);

        // Pesanan lain untuk user Budi (ID: 2)
        Pesanan::create([
            'user_id' => 2, // Budi
            'id_ongkir' => 5, // Semarang
            'total_harga' => 91000, // 75000 (produk) + 16000 (ongkir)
            'status_pesanan' => 'Menunggu Pembayaran',
        ]);
    }
}
