<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alamat;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder provides dummy data since we don't have an actual RajaOngkir API key
     */
    public function run(): void
    {
        $this->command->info("Menyisipkan data lokasi dummy untuk pengujian...");

        // Cek apakah sudah ada data di tabel alamat
        if (Alamat::count() > 0) {
            $this->command->info("Tabel alamat sudah berisi data, melewati seed.");
            return;
        }

        // Data dummy untuk alamat (lokasi populer di Indonesia)
        $dummyLocations = [
            [
                'id' => 1001,
                'provinsi' => 'DKI Jakarta',
                'kabupaten' => 'Jakarta Pusat',
                'kecamatan' => 'Tanah Abang',
                'tipe' => 'Kota',
                'kode_pos' => '10110'
            ],
            [
                'id' => 1002,
                'provinsi' => 'DKI Jakarta',
                'kabupaten' => 'Jakarta Selatan',
                'kecamatan' => 'Kebayoran Baru',
                'tipe' => 'Kota',
                'kode_pos' => '12170'
            ],
            [
                'id' => 1003,
                'provinsi' => 'Jawa Barat',
                'kabupaten' => 'Bandung',
                'kecamatan' => 'Coblong',
                'tipe' => 'Kota',
                'kode_pos' => '40132'
            ],
            [
                'id' => 1004,
                'provinsi' => 'Jawa Barat',
                'kabupaten' => 'Bekasi',
                'kecamatan' => 'Tambun',
                'tipe' => 'Kabupaten',
                'kode_pos' => '17510'
            ],
            [
                'id' => 1005,
                'provinsi' => 'Jawa Tengah',
                'kabupaten' => 'Semarang',
                'kecamatan' => 'Ngaliyan',
                'tipe' => 'Kota',
                'kode_pos' => '50184'
            ],
            [
                'id' => 1006,
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Surabaya',
                'kecamatan' => 'Gubeng',
                'tipe' => 'Kota',
                'kode_pos' => '60286'
            ],
            [
                'id' => 1007,
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Malang',
                'kecamatan' => 'Lowokwaru',
                'tipe' => 'Kota',
                'kode_pos' => '65141'
            ],
            [
                'id' => 1008,
                'provinsi' => 'Bali',
                'kabupaten' => 'Denpasar',
                'kecamatan' => 'Denpasar Barat',
                'tipe' => 'Kota',
                'kode_pos' => '80113'
            ],
            [
                'id' => 1009,
                'provinsi' => 'Sulawesi Selatan',
                'kabupaten' => 'Makassar',
                'kecamatan' => 'Panakkukang',
                'tipe' => 'Kota',
                'kode_pos' => '90231'
            ],
            [
                'id' => 1010,
                'provinsi' => 'Sumatera Utara',
                'kabupaten' => 'Medan',
                'kecamatan' => 'Medan Petisah',
                'tipe' => 'Kota',
                'kode_pos' => '20112'
            ],
            // Data untuk kabupaten yang ada di OngkirSeeder
            [
                'id' => 2001,
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'PASURUAN',
                'kecamatan' => 'Pasuruan',
                'tipe' => 'Kabupaten',
                'kode_pos' => '67156'
            ],
            [
                'id' => 2002,
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'BANGIL',
                'kecamatan' => 'Bangil',
                'tipe' => 'Kecamatan',
                'kode_pos' => '67153'
            ],
            [
                'id' => 2003,
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'SIDOARJO',
                'kecamatan' => 'Sidoarjo',
                'tipe' => 'Kabupaten',
                'kode_pos' => '61219'
            ],
            [
                'id' => 2004,
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'MOJOKERTO',
                'kecamatan' => 'Mojokerto',
                'tipe' => 'Kabupaten',
                'kode_pos' => '61382'
            ],
            [
                'id' => 2005,
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'GRESIK',
                'kecamatan' => 'Gresik',
                'tipe' => 'Kabupaten',
                'kode_pos' => '61114'
            ],
            [
                'id' => 2006,
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'JOMBANG',
                'kecamatan' => 'Jombang',
                'tipe' => 'Kabupaten',
                'kode_pos' => '61419'
            ],
            [
                'id' => 2007,
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'BLITAR',
                'kecamatan' => 'Blitar',
                'tipe' => 'Kabupaten',
                'kode_pos' => '66171'
            ],
            [
                'id' => 2008,
                'provinsi' => 'Jawa Tengah',
                'kabupaten' => 'DEMAK',
                'kecamatan' => 'Demak',
                'tipe' => 'Kabupaten',
                'kode_pos' => '59511'
            ],
            [
                'id' => 2009,
                'provinsi' => 'Jawa Tengah',
                'kabupaten' => 'YOGYAKARTA',
                'kecamatan' => 'Yogyakarta',
                'tipe' => 'Kota',
                'kode_pos' => '55222'
            ],
            [
                'id' => 2010,
                'provinsi' => 'Jawa Tengah',
                'kabupaten' => 'SOLO',
                'kecamatan' => 'Solo',
                'tipe' => 'Kota',
                'kode_pos' => '57139'
            ]
        ];

        // Masukkan data dummy
        $counter = 0;
        foreach ($dummyLocations as $location) {
            Alamat::create($location);
            $counter++;
        }

        $this->command->info("Berhasil menambahkan {$counter} lokasi dummy ke database");
    }
}
