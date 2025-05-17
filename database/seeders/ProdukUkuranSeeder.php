<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\ProdukUkuran;
use Illuminate\Database\Seeder;

class ProdukUkuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan semua produk Koki
        $produkKoki = Produk::where('jenis_ikan', 'Ikan Koki')->get();

        // Ukuran untuk ikan koki (umumnya lebih kecil)
        $ukuranKoki = ['3-5 cm', '5-7 cm', '7-10 cm', '10-12 cm'];

        // Tambahkan ukuran untuk setiap produk Koki
        foreach ($produkKoki as $produk) {
            foreach ($ukuranKoki as $index => $ukuran) {
                ProdukUkuran::create([
                    'id_produk' => $produk->id_Produk,
                    'ukuran' => $ukuran,
                    'stok' => rand(5, 20), // Stok acak antara 5-20
                    'harga' => $produk->harga + ($index * 25000), // Harga naik sesuai ukuran
                    'is_active' => true
                ]);
            }
        }

        // Mendapatkan semua produk Koi
        $produkKoi = Produk::where('jenis_ikan', 'Ikan Koi')->get();

        // Ukuran untuk ikan koi (umumnya lebih besar)
        $ukuranKoi = ['5-10 cm', '10-15 cm', '15-20 cm', '20-25 cm', '25-30 cm'];

        // Tambahkan ukuran untuk setiap produk Koi
        foreach ($produkKoi as $produk) {
            foreach ($ukuranKoi as $index => $ukuran) {
                ProdukUkuran::create([
                    'id_produk' => $produk->id_Produk,
                    'ukuran' => $ukuran,
                    'stok' => rand(3, 15), // Stok acak antara 3-15
                    'harga' => $produk->harga + ($index * 50000), // Harga naik sesuai ukuran
                    'is_active' => true
                ]);
            }
        }
    }
}
