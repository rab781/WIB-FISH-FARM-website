<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
use App\Models\Ongkir;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class OngkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan data yang ada terlebih dahulu
        Ongkir::query()->delete();

        // Tampilkan pesan
        $this->command->info('Memulai pengisian data ongkir...');

        // Data kabupaten dengan tarif tetap berdasarkan gambar
        $kabupatens = [
            // Kolom kiri
            'PASURUAN' => 25000,
            'BANGIL' => 25000,
            'SIDOARJO' => 25000,
            'MOJOSARI' => 25000,
            'MOJOKERTO' => 25000,
            'SURABAYA' => 25000,
            'GRESIK' => 25000,
            'LAMONGAN' => 25000,
            'JOMBANG' => 25000,
            'WONOKROMO' => 25000,
            'BLITAR' => 25000,
            'PROBOLINGGO' => 25000,
            'SITUBONDO' => 25000,
            'BONDOWOSO' => 25000,
            'JEMBER' => 25000,
            'LUMAJANG' => 25000,
            'BANYUWANGI' => 25000,
            'BATU' => 25000,
            'SINGOSARI' => 25000,
            'LAMONGAN BARAT' => 25000,
            'DEMAK' => 25000,
            'SEMARANG' => 25000,
            'JEPARA' => 25000,

            // Kolom kanan
            'TUBAN' => 25000,
            'PATI' => 25000,
            'REMBANG' => 25000,
            'KUDUS' => 25000,
            'BLORA' => 25000,
            'UNGARAN' => 25000,
            'KENDAL' => 25000,
            'PARAKAN' => 25000,
            'WALERI' => 25000,
            'TEMANGGUNG' => 25000,
            'MAGELANG' => 25000,
            'BOYOLALI' => 25000,
            'SALATIGA' => 25000,
            'SLEMAN' => 25000,
            'YOGYAKARTA' => 25000,
            'SOLO' => 25000,
            'KLATEN' => 25000,
            'SRAGEN' => 25000,
            'NGAWI' => 25000,
            'MADIUN' => 25000,
            'PONOROGO' => 25000,
            'MAGETAN' => 25000,
            'NGANJUK' => 25000,
            'KEDIRI' => 25000,
            'WONOSOBO' => 25000,
            'GROBOGAN' => 25000,
            'WONOGIRI' => 25000,
            'TULUNGAGUNG' => 25000,
            'KARANGANYAR' => 25000,
        ];

        // Hitung berapa entri yang berhasil dibuat
        $successCount = 0;
        $failedCount = 0;

        // Untuk setiap kabupaten dalam data, temukan ID kabupaten yang sesuai
        foreach ($kabupatens as $namaKabupaten => $biaya) {
            try {
                // Cari kabupaten berdasarkan nama (dengan pencarian yang lebih fleksibel)
                $kabupaten = Kabupaten::where('nama_kabupaten', 'LIKE', "%$namaKabupaten%")
                                    ->orWhere('nama_kabupaten', 'LIKE', "%".strtolower($namaKabupaten)."%")
                                    ->first();

                if ($kabupaten) {
                    // Buat data ongkir jika kabupaten ditemukan
                    Ongkir::create([
                        'kabupaten_id' => $kabupaten->id,
                        'biaya' => $biaya,
                        'keterangan' => "Ongkir untuk {$kabupaten->nama_kabupaten} (Rp 2.500/kg, minimal 10kg)"
                    ]);

                    $this->command->info("Ongkir untuk {$kabupaten->nama_kabupaten} berhasil dibuat: Rp " . number_format($biaya, 0, ',', '.'));
                    $successCount++;
                } else {
                    // Log kabupaten yang tidak ditemukan
                    $this->command->warn("Kabupaten '$namaKabupaten' tidak ditemukan dalam database.");
                    $failedCount++;
                }
            } catch (\Exception $e) {
                $this->command->error("Error saat membuat ongkir untuk $namaKabupaten: " . $e->getMessage());
                Log::error("Error di OngkirSeeder: " . $e->getMessage());
                $failedCount++;
            }
        }

        // Default ongkir untuk kabupaten yang tidak ada dalam daftar
        $this->command->info("Membuat ongkir default untuk kabupaten lainnya...");

        // Cari semua kabupaten yang belum memiliki ongkir
        $kabupatensWithoutOngkir = Kabupaten::whereNotIn('id', function ($query) {
            $query->select('kabupaten_id')->from('ongkir');
        })->get();

        foreach ($kabupatensWithoutOngkir as $kabupaten) {
            try {
                Ongkir::create([
                    'kabupaten_id' => $kabupaten->id,
                    'biaya' => 50000, // Biaya default lebih tinggi
                    'keterangan' => "Ongkir default untuk {$kabupaten->nama_kabupaten}"
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $this->command->error("Error saat membuat ongkir default untuk {$kabupaten->nama_kabupaten}: " . $e->getMessage());
                $failedCount++;
            }
        }

        $this->command->info("Pengisian data ongkir selesai. Berhasil: $successCount, Gagal: $failedCount");
    }
}
