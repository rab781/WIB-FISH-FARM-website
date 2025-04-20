<?php

namespace Database\Seeders;

use App\Models\DetailPesanan;
use Illuminate\Database\Seeder;

class DetailPesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Detail pesanan untuk pesanan ID 1 (Budi)
        DetailPesanan::create([
            'id_pesanan' => 1,
            'id_Produk' => 2, // Ikan Nila
            'jumlah' => 2,
            'total_harga' => 70000, // 2 * 35000
        ]);

        DetailPesanan::create([
            'id_pesanan' => 1,
            'id_Produk' => 5, // Ikan Tongkol
            'jumlah' => 1,
            'total_harga' => 45000, // 1 * 45000
        ]);

        // Detail pesanan untuk pesanan ID 2 (Ani)
        DetailPesanan::create([
            'id_pesanan' => 2,
            'id_Produk' => 3, // Ikan Gurame
            'jumlah' => 2,
            'total_harga' => 150000, // 2 * 75000
        ]);

        // Detail pesanan untuk pesanan ID 3 (Dedi)
        DetailPesanan::create([
            'id_pesanan' => 3,
            'id_Produk' => 6, // Ikan Salmon
            'jumlah' => 1,
            'total_harga' => 150000, // 1 * 150000
        ]);

        DetailPesanan::create([
            'id_pesanan' => 3,
            'id_Produk' => 8, // Ikan Mas
            'jumlah' => 1,
            'total_harga' => 50000, // 1 * 50000
        ]);

        DetailPesanan::create([
            'id_pesanan' => 3,
            'id_Produk' => 1, // Ikan Lele
            'jumlah' => 1,
            'total_harga' => 30000, // 1 * 30000
        ]);

        // Detail pesanan untuk pesanan ID 4 (Budi)
        DetailPesanan::create([
            'id_pesanan' => 4,
            'id_Produk' => 3, // Ikan Gurame
            'jumlah' => 1,
            'total_harga' => 75000, // 1 * 75000
        ]);
    }
}
