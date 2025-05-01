<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
use App\Models\Ongkir;
use Illuminate\Database\Seeder;

class OngkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari ID kabupaten berdasarkan nama
        $jakarta = Kabupaten::where('nama_kabupaten', 'like', '%Jakarta%')->first()->id ?? 1;
        $bandung = Kabupaten::where('nama_kabupaten', 'like', '%Bandung%')->first()->id ?? 2;
        $surabaya = Kabupaten::where('nama_kabupaten', 'like', '%Surabaya%')->first()->id ?? 3;
        $yogyakarta = Kabupaten::where('nama_kabupaten', 'like', '%Yogyakarta%')->first()->id ?? 4;
        $semarang = Kabupaten::where('nama_kabupaten', 'like', '%Semarang%')->first()->id ?? 5;
        $medan = Kabupaten::where('nama_kabupaten', 'like', '%Medan%')->first()->id ?? 6;
        $makassar = Kabupaten::where('nama_kabupaten', 'like', '%Makassar%')->first()->id ?? 7;
        $palembang = Kabupaten::where('nama_kabupaten', 'like', '%Palembang%')->first()->id ?? 8;
        $bondowoso = Kabupaten::where('nama_kabupaten', 'like', '%Bondowoso%')->first()->id ?? 9;

        // Data ongkos kirim
        Ongkir::create([
            'kabupaten_id' => $jakarta,
            'berat' => 1.00,
            'harga' => 15000,
        ]);

        Ongkir::create([
            'kabupaten_id' => $bandung,
            'berat' => 1.00,
            'harga' => 18000,
        ]);

        Ongkir::create([
            'kabupaten_id' => $surabaya,
            'berat' => 1.00,
            'harga' => 20000,
        ]);

        Ongkir::create([
            'kabupaten_id' => $yogyakarta,
            'berat' => 1.00,
            'harga' => 17000,
        ]);

        Ongkir::create([
            'kabupaten_id' => $semarang,
            'berat' => 1.00,
            'harga' => 16000,
        ]);

        Ongkir::create([
            'kabupaten_id' => $medan,
            'berat' => 1.00,
            'harga' => 25000,
        ]);

        Ongkir::create([
            'kabupaten_id' => $makassar,
            'berat' => 1.00,
            'harga' => 28000,
        ]);

        Ongkir::create([
            'kabupaten_id' => $palembang,
            'berat' => 1.00,
            'harga' => 22000,
        ]);

        // Tambahkan ongkir untuk Bondowoso sesuai contoh di permintaan
        Ongkir::create([
            'kabupaten_id' => $bondowoso,
            'berat' => 1.00,
            'harga' => 14000,
        ]);
    }
}
