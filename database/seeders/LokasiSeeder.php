<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Log;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan data provinsi
        $this->command->info("Menambahkan data provinsi...");

        $provinsi = [
            ['nama_provinsi' => 'Jawa Timur'],
            ['nama_provinsi' => 'Jawa Tengah'],
            ['nama_provinsi' => 'Jawa Barat'],
            ['nama_provinsi' => 'DI Yogyakarta'],
            ['nama_provinsi' => 'DKI Jakarta'],
            ['nama_provinsi' => 'Banten'],
        ];

        foreach ($provinsi as $data) {
            $prov = Provinsi::firstOrCreate(
                ['nama_provinsi' => $data['nama_provinsi']],
                $data
            );
            $this->command->info("Provinsi {$prov->nama_provinsi} " . ($prov->wasRecentlyCreated ? 'ditambahkan' : 'sudah ada'));
        }

        // Menambahkan data kabupaten
        $this->command->info("Menambahkan data kabupaten...");

        // Cari ID Provinsi
        $jawaTimur = Provinsi::where('nama_provinsi', 'Jawa Timur')->first();
        $jawaTengah = Provinsi::where('nama_provinsi', 'Jawa Tengah')->first();
        $diYogyakarta = Provinsi::where('nama_provinsi', 'DI Yogyakarta')->first();

        if (!$jawaTimur || !$jawaTengah || !$diYogyakarta) {
            $this->command->error("Beberapa provinsi tidak ditemukan, pastikan seeder provinsi sudah dijalankan!");
            return;
        }

        $kabupatenList = [
            // Jawa Timur
            ['nama_kabupaten' => 'Pasuruan', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Bangil', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Sidoarjo', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Mojosari', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Mojokerto', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Surabaya', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Gresik', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Lamongan', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Jombang', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Wonokromo', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Blitar', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Probolinggo', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Situbondo', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Bondowoso', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Jember', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Lumajang', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Banyuwangi', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Batu', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Singosari', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Lamongan Barat', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Tuban', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Ngawi', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Madiun', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Ponorogo', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Magetan', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Nganjuk', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Kediri', 'provinsi_id' => $jawaTimur->id],
            ['nama_kabupaten' => 'Tulungagung', 'provinsi_id' => $jawaTimur->id],

            // Jawa Tengah
            ['nama_kabupaten' => 'Demak', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Semarang', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Jepara', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Pati', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Rembang', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Kudus', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Blora', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Ungaran', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Kendal', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Parakan', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Waleri', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Temanggung', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Magelang', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Boyolali', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Salatiga', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Solo', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Klaten', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Sragen', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Wonosobo', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Grobogan', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Wonogiri', 'provinsi_id' => $jawaTengah->id],
            ['nama_kabupaten' => 'Karanganyar', 'provinsi_id' => $jawaTengah->id],

            // DI Yogyakarta
            ['nama_kabupaten' => 'Sleman', 'provinsi_id' => $diYogyakarta->id],
            ['nama_kabupaten' => 'Yogyakarta', 'provinsi_id' => $diYogyakarta->id],
        ];

        $createdCount = 0;
        $existingCount = 0;

        foreach ($kabupatenList as $data) {
            $kabupaten = Kabupaten::firstOrCreate(
                ['nama_kabupaten' => $data['nama_kabupaten'], 'provinsi_id' => $data['provinsi_id']],
                $data
            );

            if ($kabupaten->wasRecentlyCreated) {
                $this->command->info("Kabupaten {$kabupaten->nama_kabupaten} ditambahkan");
                $createdCount++;
            } else {
                $this->command->line("Kabupaten {$kabupaten->nama_kabupaten} sudah ada");
                $existingCount++;
            }

            // Tambahkan kecamatan default untuk setiap kabupaten
            if ($kabupaten->wasRecentlyCreated) {
                Kecamatan::firstOrCreate(
                    ['nama_kecamatan' => 'Pusat ' . $kabupaten->nama_kabupaten, 'kabupaten_id' => $kabupaten->id],
                    ['nama_kecamatan' => 'Pusat ' . $kabupaten->nama_kabupaten, 'kabupaten_id' => $kabupaten->id]
                );
            }
        }

        $this->command->info("Total kabupaten: ditambahkan $createdCount, sudah ada $existingCount");
    }
}
