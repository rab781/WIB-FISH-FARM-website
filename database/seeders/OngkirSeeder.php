<?php

namespace Database\Seeders;

use App\Models\Ongkir;
use Illuminate\Database\Seeder;

class OngkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data ongkos kirim
        Ongkir::create([
            'nama_kota' => 'Jakarta',
            'berat' => 1.00,
            'harga' => 15000,
        ]);

        Ongkir::create([
            'nama_kota' => 'Bandung',
            'berat' => 1.00,
            'harga' => 18000,
        ]);

        Ongkir::create([
            'nama_kota' => 'Surabaya',
            'berat' => 1.00,
            'harga' => 20000,
        ]);

        Ongkir::create([
            'nama_kota' => 'Yogyakarta',
            'berat' => 1.00,
            'harga' => 17000,
        ]);

        Ongkir::create([
            'nama_kota' => 'Semarang',
            'berat' => 1.00,
            'harga' => 16000,
        ]);

        Ongkir::create([
            'nama_kota' => 'Medan',
            'berat' => 1.00,
            'harga' => 25000,
        ]);

        Ongkir::create([
            'nama_kota' => 'Makassar',
            'berat' => 1.00,
            'harga' => 28000,
        ]);

        Ongkir::create([
            'nama_kota' => 'Palembang',
            'berat' => 1.00,
            'harga' => 22000,
        ]);
    }
}
