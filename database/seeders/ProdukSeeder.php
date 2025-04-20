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
        // Produk ikan
        Produk::create([
            'nama_ikan' => 'Ikan Lele',
            'deskripsi' => 'Ikan lele segar berkualitas tinggi. Cocok untuk digoreng atau dimasak dengan bumbu pedas.',
            'stok' => 100,
            'harga' => 30000,
            'jenis_ikan' => 'Air Tawar',
        ]);

        Produk::create([
            'nama_ikan' => 'Ikan Nila',
            'deskripsi' => 'Ikan nila segar dengan daging tebal. Sangat baik untuk digoreng atau dibakar.',
            'stok' => 80,
            'harga' => 35000,
            'jenis_ikan' => 'Air Tawar',
        ]);

        Produk::create([
            'nama_ikan' => 'Ikan Gurame',
            'deskripsi' => 'Ikan gurame segar ukuran jumbo. Cocok untuk hidangan keluarga besar atau acara.',
            'stok' => 50,
            'harga' => 75000,
            'jenis_ikan' => 'Air Tawar',
        ]);

        Produk::create([
            'nama_ikan' => 'Ikan Kembung',
            'deskripsi' => 'Ikan kembung segar dari laut. Sangat lezat untuk digoreng atau dibuat pindang.',
            'stok' => 120,
            'harga' => 40000,
            'jenis_ikan' => 'Air Laut',
        ]);

        Produk::create([
            'nama_ikan' => 'Ikan Tongkol',
            'deskripsi' => 'Ikan tongkol segar dengan daging tebal. Cocok untuk dimasak dengan bumbu pedas.',
            'stok' => 70,
            'harga' => 45000,
            'jenis_ikan' => 'Air Laut',
        ]);

        Produk::create([
            'nama_ikan' => 'Ikan Salmon',
            'deskripsi' => 'Ikan salmon premium import. Sempurna untuk sushi, sashimi, atau dimasak dengan saus spesial.',
            'stok' => 30,
            'harga' => 150000,
            'jenis_ikan' => 'Air Laut',
        ]);

        Produk::create([
            'nama_ikan' => 'Ikan Tuna',
            'deskripsi' => 'Ikan tuna segar dengan kualitas terbaik. Cocok untuk steak atau hidangan premium lainnya.',
            'stok' => 40,
            'harga' => 120000,
            'jenis_ikan' => 'Air Laut',
        ]);

        Produk::create([
            'nama_ikan' => 'Ikan Mas',
            'deskripsi' => 'Ikan mas segar dengan daging gurih. Sangat cocok untuk hidangan berkuah.',
            'stok' => 60,
            'harga' => 50000,
            'jenis_ikan' => 'Air Tawar',
        ]);
    }
}
