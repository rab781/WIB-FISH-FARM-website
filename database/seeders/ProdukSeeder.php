<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Produk ikan koki
        Produk::create([
            'nama_ikan' => 'Ikan Koki Merah',
            'deskripsi' => 'Ikan koki merah dengan sisik berkilau dan ekor yang indah. Cocok untuk aquarium hias.',
            'stok' => 50,
            'harga' => 75000,
            'jenis_ikan' => 'Ikan Koki',
        ]);

        Produk::create([
            'nama_ikan' => 'Ikan Koki Calico',
            'deskripsi' => 'Ikan koki calico dengan corak warna yang unik. Sangat populer di kalangan penghobi.',
            'stok' => 45,
            'harga' => 95000,
            'jenis_ikan' => 'Ikan Koki',
        ]);

        Produk::create([
            'nama_ikan' => 'Ikan Koki Oranda',
            'deskripsi' => 'Ikan koki oranda dengan mahkota yang indah di kepalanya. Sangat diminati oleh kolektor.',
            'stok' => 30,
            'harga' => 125000,
            'jenis_ikan' => 'Ikan Koki',
        ]);

        Produk::create([
            'nama_ikan' => 'Ikan Koki Ranchu',
            'deskripsi' => 'Ikan koki ranchu dengan bentuk tubuh yang unik dan elegan. Terkenal sebagai raja ikan koki.',
            'stok' => 25,
            'harga' => 150000,
            'jenis_ikan' => 'Ikan Koki',
        ]);

        // Produk ikan koi
        Produk::create([
            'nama_ikan' => 'Ikan Koi Kohaku',
            'deskripsi' => 'Ikan koi kohaku dengan corak merah putih yang menawan. Simbol keberuntungan dalam budaya Jepang.',
            'stok' => 20,
            'harga' => 250000,
            'jenis_ikan' => 'Ikan Koi',
        ]);

        Produk::create([
            'nama_ikan' => 'Ikan Koi Showa',
            'deskripsi' => 'Ikan koi showa dengan corak hitam, merah, dan putih yang elegan. Sangat diminati oleh kolektor.',
            'stok' => 15,
            'harga' => 300000,
            'jenis_ikan' => 'Ikan Koi',
        ]);

        Produk::create([
            'nama_ikan' => 'Ikan Koi Sanke',
            'deskripsi' => 'Ikan koi sanke dengan corak tiga warna yang harmonis. Salah satu varietas koi paling populer.',
            'stok' => 18,
            'harga' => 275000,
            'jenis_ikan' => 'Ikan Koi',
        ]);

        Produk::create([
            'nama_ikan' => 'Ikan Koi Bekko',
            'deskripsi' => 'Ikan koi bekko dengan dasar putih dan corak hitam yang menarik. Cocok untuk kolam hias outdoor.',
            'stok' => 25,
            'harga' => 225000,
            'jenis_ikan' => 'Ikan Koi',
        ]);
    }
}
