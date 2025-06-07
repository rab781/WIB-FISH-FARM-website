<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Keluhan;

class KeluhanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Keluhan oleh Budi (User ID 2)
        Keluhan::create([
            'user_id' => 2,
            'jenis_keluhan' => 'produk rusak',
            'keluhan' => 'Ikan yang diterima tidak segar, banyak bau amis.',
            'gambar' => json_encode(['rusak1.jpg', 'rusak2.jpg']),
            'status' => 'Belum Diproses',
        ]);

        // Keluhan oleh Ani (User ID 3)
        Keluhan::create([
            'user_id' => 3,
            'jenis_keluhan' => 'pengiriman terlambat',
            'keluhan' => 'Pesanan tiba lebih dari 3 hari setelah estimasi.',
            'gambar' => json_encode([]),
            'status' => 'Selesai',
            'respon_admin' => 'Maaf atas keterlambatan, kami akan perbaiki pelayanan.',
            'respon_at' => now(),
        ]);
    }
}
