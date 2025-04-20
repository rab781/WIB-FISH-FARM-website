<?php

namespace Database\Seeders;

use App\Models\Ulasan;
use Illuminate\Database\Seeder;

class UlasanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ulasan dari Budi untuk produk yang dibeli (Pesanan ID 1)
        Ulasan::create([
            'user_id' => 2, // Budi
            'id_Produk' => 2, // Ikan Nila
            'rating' => 4.5,
            'komentar' => 'Barang nya sesuai dengan pesanan',
        ]);

        Ulasan::create([
            'user_id' => 2, // Budi
            'id_Produk' => 5, // Ikan Tongkol
            'rating' => 5.0,
            'komentar' => 'Kualitas sangat baik',
        ]);

        // Ulasan dari Ani untuk produk yang dibeli (Pesanan ID 2)
        Ulasan::create([
            'user_id' => 3, // Ani
            'id_Produk' => 3, // Ikan Gurame
            'rating' => 4.0,
            'komentar' => 'Rasa enak dan segar',
        ]);

        // Ulasan dari Dedi untuk produk yang dibeli (Pesanan ID 3)
        Ulasan::create([
            'user_id' => 4, // Dedi
            'id_Produk' => 6, // Ikan Salmon
            'rating' => 5.0,
            'komentar' => 'Sangat puas dengan kualitasnya',
        ]);

        Ulasan::create([
            'user_id' => 4, // Dedi
            'id_Produk' => 8, // Ikan Mas
            'rating' => 3.5,
            'komentar' => 'Cukup baik, tapi bisa lebih cepat pengirimannya',
        ]);
    }
}
