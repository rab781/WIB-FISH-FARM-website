<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kecamatan;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatan = [
            // Jakarta Pusat (ID: 1)
            ['kabupaten_id' => 1, 'nama_kecamatan' => 'Gambir'],
            ['kabupaten_id' => 1, 'nama_kecamatan' => 'Tanah Abang'],
            ['kabupaten_id' => 1, 'nama_kecamatan' => 'Menteng'],

            // Jakarta Utara (ID: 2)
            ['kabupaten_id' => 2, 'nama_kecamatan' => 'Cilincing'],
            ['kabupaten_id' => 2, 'nama_kecamatan' => 'Kelapa Gading'],
            ['kabupaten_id' => 2, 'nama_kecamatan' => 'Tanjung Priok'],

            // Jakarta Barat (ID: 3)
            ['kabupaten_id' => 3, 'nama_kecamatan' => 'Cengkareng'],
            ['kabupaten_id' => 3, 'nama_kecamatan' => 'Grogol Petamburan'],
            ['kabupaten_id' => 3, 'nama_kecamatan' => 'Palmerah'],

            // Jakarta Selatan (ID: 4)
            ['kabupaten_id' => 4, 'nama_kecamatan' => 'Kebayoran Baru'],
            ['kabupaten_id' => 4, 'nama_kecamatan' => 'Pancoran'],
            ['kabupaten_id' => 4, 'nama_kecamatan' => 'Tebet'],

            // Jakarta Timur (ID: 5)
            ['kabupaten_id' => 5, 'nama_kecamatan' => 'Jatinegara'],
            ['kabupaten_id' => 5, 'nama_kecamatan' => 'Duren Sawit'],
            ['kabupaten_id' => 5, 'nama_kecamatan' => 'Matraman'],

            // Bandung (ID: 6)
            ['kabupaten_id' => 6, 'nama_kecamatan' => 'Cicendo'],
            ['kabupaten_id' => 6, 'nama_kecamatan' => 'Coblong'],
            ['kabupaten_id' => 6, 'nama_kecamatan' => 'Bandung Wetan'],

            // Bogor (ID: 7)
            ['kabupaten_id' => 7, 'nama_kecamatan' => 'Bogor Barat'],
            ['kabupaten_id' => 7, 'nama_kecamatan' => 'Bogor Timur'],
            ['kabupaten_id' => 7, 'nama_kecamatan' => 'Bogor Selatan'],

            // Depok (ID: 8)
            ['kabupaten_id' => 8, 'nama_kecamatan' => 'Beji'],
            ['kabupaten_id' => 8, 'nama_kecamatan' => 'Cimanggis'],
            ['kabupaten_id' => 8, 'nama_kecamatan' => 'Pancoran Mas'],

            // Bekasi (ID: 9)
            ['kabupaten_id' => 9, 'nama_kecamatan' => 'Bekasi Timur'],
            ['kabupaten_id' => 9, 'nama_kecamatan' => 'Bekasi Barat'],
            ['kabupaten_id' => 9, 'nama_kecamatan' => 'Bekasi Utara'],

            // Sukabumi (ID: 10)
            ['kabupaten_id' => 10, 'nama_kecamatan' => 'Cikole'],
            ['kabupaten_id' => 10, 'nama_kecamatan' => 'Lembursitu'],
            ['kabupaten_id' => 10, 'nama_kecamatan' => 'Citamiang'],
        ];

        foreach ($kecamatan as $k) {
            Kecamatan::create($k);
        }
    }
}
