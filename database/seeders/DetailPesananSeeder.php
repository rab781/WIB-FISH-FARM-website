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
            'kuantitas' => 2,
            'harga' => 35000,
            'subtotal' => 70000, // 2 * 35000
        ]);

        DetailPesanan::create([
            'id_pesanan' => 1,
            'id_Produk' => 5, // Ikan Tongkol
            'kuantitas' => 1,
            'harga' => 45000,
            'subtotal' => 45000, // 1 * 45000
        ]);

        // Detail pesanan untuk pesanan ID 2 (Ani)
        DetailPesanan::create([
            'id_pesanan' => 2,
            'id_Produk' => 3, // Ikan Gurame
            'kuantitas' => 2,
            'harga' => 75000,
            'subtotal' => 150000, // 2 * 75000
        ]);

        // Detail pesanan untuk pesanan ID 3 (Dedi)
        DetailPesanan::create([
            'id_pesanan' => 3,
            'id_Produk' => 6, // Ikan Salmon
            'kuantitas' => 1,
            'harga' => 150000,
            'subtotal' => 150000, // 1 * 150000
        ]);

        DetailPesanan::create([
            'id_pesanan' => 3,
            'id_Produk' => 8, // Ikan Mas
            'kuantitas' => 1,
            'harga' => 50000,
            'subtotal' => 50000, // 1 * 50000
        ]);

        // Detail pesanan untuk pesanan ID 4 (Budi lagi)
        DetailPesanan::create([
            'id_pesanan' => 4,
            'id_Produk' => 1, // Ikan Lele
            'kuantitas' => 2,
            'harga' => 25000,
            'subtotal' => 50000, // 2 * 25000
        ]);

        DetailPesanan::create([
            'id_pesanan' => 4,
            'id_Produk' => 4, // Ikan Patin
            'kuantitas' => 1,
            'harga' => 25000,
            'subtotal' => 25000, // 1 * 25000
        ]);

        // Detail pesanan untuk pesanan ID 5 (Completed order)
        DetailPesanan::create([
            'id_pesanan' => 5,
            'id_Produk' => 2, // Ikan Nila
            'kuantitas' => 1,
            'harga' => 35000,
            'subtotal' => 35000,
        ]);
        DetailPesanan::create([
            'id_pesanan' => 5,
            'id_Produk' => 3, // Ikan Gurame
            'kuantitas' => 2,
            'harga' => 75000,
            'subtotal' => 150000,
        ]);

        // Detail pesanan untuk pesanan ID 6 (Shipped order)
        DetailPesanan::create([
            'id_pesanan' => 6,
            'id_Produk' => 5, // Ikan Tongkol
            'kuantitas' => 2,
            'harga' => 45000,
            'subtotal' => 90000,
        ]);

        // Detail pesanan untuk pesanan ID 7 (Return in process)
        DetailPesanan::create([
            'id_pesanan' => 7,
            'id_Produk' => 6, // Ikan Salmon
            'kuantitas' => 1,
            'harga' => 150000,
            'subtotal' => 150000,
        ]);
    }
}
