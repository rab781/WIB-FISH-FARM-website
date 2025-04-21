<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kabupaten;

class KabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kabupaten = [
            // DKI Jakarta (ID: 1)
            ['provinsi_id' => 1, 'nama_kabupaten' => 'Jakarta Pusat'],
            ['provinsi_id' => 1, 'nama_kabupaten' => 'Jakarta Utara'],
            ['provinsi_id' => 1, 'nama_kabupaten' => 'Jakarta Barat'],
            ['provinsi_id' => 1, 'nama_kabupaten' => 'Jakarta Selatan'],
            ['provinsi_id' => 1, 'nama_kabupaten' => 'Jakarta Timur'],

            // Jawa Barat (ID: 2)
            ['provinsi_id' => 2, 'nama_kabupaten' => 'Bandung'],
            ['provinsi_id' => 2, 'nama_kabupaten' => 'Bogor'],
            ['provinsi_id' => 2, 'nama_kabupaten' => 'Depok'],
            ['provinsi_id' => 2, 'nama_kabupaten' => 'Bekasi'],
            ['provinsi_id' => 2, 'nama_kabupaten' => 'Sukabumi'],

            // Jawa Tengah (ID: 3)
            ['provinsi_id' => 3, 'nama_kabupaten' => 'Semarang'],
            ['provinsi_id' => 3, 'nama_kabupaten' => 'Solo'],
            ['provinsi_id' => 3, 'nama_kabupaten' => 'Magelang'],
            ['provinsi_id' => 3, 'nama_kabupaten' => 'Pekalongan'],

            // Jawa Timur (ID: 4)
            ['provinsi_id' => 4, 'nama_kabupaten' => 'Surabaya'],
            ['provinsi_id' => 4, 'nama_kabupaten' => 'Malang'],
            ['provinsi_id' => 4, 'nama_kabupaten' => 'Sidoarjo'],
            ['provinsi_id' => 4, 'nama_kabupaten' => 'Jember'],

            // Banten (ID: 5)
            ['provinsi_id' => 5, 'nama_kabupaten' => 'Tangerang'],
            ['provinsi_id' => 5, 'nama_kabupaten' => 'Serang'],
            ['provinsi_id' => 5, 'nama_kabupaten' => 'Cilegon'],

            // Bali (ID: 6)
            ['provinsi_id' => 6, 'nama_kabupaten' => 'Denpasar'],
            ['provinsi_id' => 6, 'nama_kabupaten' => 'Badung'],
            ['provinsi_id' => 6, 'nama_kabupaten' => 'Gianyar'],
        ];

        foreach ($kabupaten as $k) {
            Kabupaten::create($k);
        }
    }
}
