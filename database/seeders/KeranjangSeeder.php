<?php

namespace Database\Seeders;

use App\Models\Keranjang;
use Illuminate\Database\Seeder;

class KeranjangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data keranjang untuk user Budi (ID: 2)
        Keranjang::create([
            'user_id' => 2, // Budi
            'id_Produk' => 1, // Ikan Lele
            'jumlah' => 2,
            'total_harga' => 60000, // 2 * 30000
        ]);

        Keranjang::create([
            'user_id' => 2, // Budi
            'id_Produk' => 4, // Ikan Kembung
            'jumlah' => 1,
            'total_harga' => 40000, // 1 * 40000
        ]);

        // Data keranjang untuk user Ani (ID: 3)
        Keranjang::create([
            'user_id' => 3, // Ani
            'id_Produk' => 3, // Ikan Gurame
            'jumlah' => 2,
            'total_harga' => 150000, // 2 * 75000
        ]);

        // Data keranjang untuk user Dedi (ID: 4)
        Keranjang::create([
            'user_id' => 4, // Dedi
            'id_Produk' => 6, // Ikan Salmon
            'jumlah' => 1,
            'total_harga' => 150000, // 1 * 150000
        ]);

        Keranjang::create([
            'user_id' => 4, // Dedi
            'id_Produk' => 7, // Ikan Tuna
            'jumlah' => 1,
            'total_harga' => 120000, // 1 * 120000
        ]);
    }
}
