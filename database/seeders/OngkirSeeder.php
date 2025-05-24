<?php

namespace Database\Seeders;

use App\Models\Alamat;
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

        // Data kabupaten dengan tarif tetap
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

        // Untuk setiap kabupaten dalam data, temukan alamat yang sesuai
        foreach ($kabupatens as $namaKabupaten => $biaya) {
            try {
                // Cari alamat berdasarkan kabupaten (dengan pencarian yang lebih fleksibel)
                $alamat = Alamat::where('kabupaten', 'LIKE', "%$namaKabupaten%")
                                ->orWhere('kabupaten', 'LIKE', "%".strtolower($namaKabupaten)."%")
                                ->first();

                if ($alamat) {
                    // Buat data ongkir jika alamat ditemukan
                    Ongkir::create([
                        'alamat_id' => $alamat->id,
                        'biaya' => $biaya,
                        'keterangan' => "Ongkir untuk {$alamat->kabupaten}, {$alamat->provinsi} (Rp 2.500/kg, minimal 10kg)"
                    ]);

                    $successCount++;
                    $this->command->info("✓ Berhasil membuat ongkir untuk {$alamat->kabupaten}");
                } else {
                    $failedCount++;
                    $this->command->warn("⚠ Tidak menemukan alamat dengan kabupaten: $namaKabupaten");
                }
            } catch (\Exception $e) {
                $failedCount++;
                $this->command->error("✗ Error saat membuat ongkir untuk $namaKabupaten: {$e->getMessage()}");
                Log::error("Error saat membuat ongkir untuk $namaKabupaten: {$e->getMessage()}");
            }
        }

        // Tampilkan informasi tentang hasil seeding
        $this->command->info("Selesai mengisi data ongkir:");
        $this->command->info("- Berhasil: $successCount entri");
        $this->command->info("- Gagal: $failedCount entri");
    }
}
