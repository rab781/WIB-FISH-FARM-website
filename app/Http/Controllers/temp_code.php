<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Alamat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Temporary code file for developing the getOngkir functionality
 */
class TempOngkirController extends Controller
{
    /**
     * Get ongkir berdasarkan alamat dengan RajaOngkir API
     */
    public function getOngkir($alamatId)
    {
        try {
            // Cek apakah ada cache untuk request ini
            $cacheKey = 'ongkir_' . $alamatId . '_' . implode('_', request('selected_items', []));

            // Coba ambil dari cache dulu
            if (\Illuminate\Support\Facades\Cache::has($cacheKey)) {
                return response()->json(\Illuminate\Support\Facades\Cache::get($cacheKey));
            }

            // Ambil items yang dipilih untuk dihitung total jumlah dan berat
            $selectedItems = request('selected_items', []);
            $totalJumlah = 0;
            $totalBerat = 0; // dalam gram
            $jumlahIkan = 0; // khusus untuk menghitung jumlah ikan

            if (!empty($selectedItems)) {
                $keranjangItems = Keranjang::whereIn('id_keranjang', $selectedItems)
                                ->where('user_id', Auth::id())
                                ->with('produk')
                                ->get();

                foreach ($keranjangItems as $item) {
                    $totalJumlah += $item->jumlah;

                    // Semua produk adalah ikan hias, jadi kita hitung jumlah ikan
                    $jumlahIkan += $item->jumlah;
                }

                // Hitung jumlah box yang dibutuhkan (3 ikan per box)
                $jumlahBox = ceil($jumlahIkan / 3);

                // Setiap box memiliki berat 10kg (10.000 gram)
                $totalBerat = $jumlahBox * 10000;
        } else {
            // Fallback jika tidak ada item
            $fallbackResult = [
                'success' => true,
                'ongkir' => 50000,
                'ongkir_options' => [
                    [
                        'courier' => 'tiki',
                        'courier_name' => 'TIKI',
                        'service' => 'REG',
                        'cost' => 50000,
                        'etd' => '1-3'
                    ]
                ],
                'jumlah_total' => 0,
                'berat_total' => 10000,
                'jumlah_box' => 1
            ];

            // Simpan ke cache selama 15 menit
            \Illuminate\Support\Facades\Cache::put($cacheKey, $fallbackResult, now()->addMinutes(15));

            return response()->json($fallbackResult);
        }

        // Ambil alamat lengkap
        $alamat = Alamat::find($alamatId);
        if (!$alamat) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat tidak ditemukan',
            ], 404);
        }

        // Gunakan RajaOngkir API untuk menghitung ongkos kirim dengan timeout
        $originId = env('STORE_LOCATION_ID', 30957); // ID lokasi toko

        // Persiapkan parameter untuk API RajaOngkir
        $params = [
            'origin' => $originId,
            'destination' => $alamatId,
            'weight' => max(1000, $totalBerat), // Minimal 1000 gram (1 kg)
            'courier' => 'tiki', // Default courier untuk pengiriman ikan hias
        ];

        // Panggil API RajaOngkir melalui HTTP client dengan timeout
        $response = \Illuminate\Support\Facades\Http::timeout(3) // 3 detik timeout
            ->withHeaders([
                'key' => env('RAJA_ONGKIR_API_KEY'),
            ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', $params);

        if ($response->successful()) {
            $responseData = $response->json();

            // Proses data hasil API
            if (isset($responseData['data']) && !empty($responseData['data'])) {
                // Ambil opsi pengiriman termurah sebagai default
                $ongkirOptions = [];
                $defaultOngkir = PHP_INT_MAX;

                foreach ($responseData['data'] as $courier) {
                    if (isset($courier['costs']) && is_array($courier['costs'])) {
                        foreach ($courier['costs'] as $cost) {
                            $biaya = $cost['cost'][0]['value'] ?? 0;
                            $estimasi = $cost['cost'][0]['etd'] ?? '-';
                            $layanan = $cost['service'] ?? '';

                            $ongkirOptions[] = [
                                'courier' => $courier['code'],
                                'courier_name' => $courier['name'],
                                'service' => $layanan,
                                'cost' => $biaya,
                                'etd' => $estimasi
                            ];

                            // Update ongkir termurah
                            if ($biaya < $defaultOngkir) {
                                $defaultOngkir = $biaya;
                            }
                        }
                    }
                }

                // Tambahan biaya berdasarkan jumlah item (sesuai dengan logika sebelumnya)
                $tambahan = 0;
                if ($totalJumlah > 3) {
                    $tambahan = ceil(($totalJumlah - 3) / 3) * 2000;
                }

                $finalOngkir = $defaultOngkir + $tambahan;

                $result = [
                    'success' => true,
                    'ongkir' => $finalOngkir,
                    'ongkir_options' => $ongkirOptions,
                    'jumlah_total' => $totalJumlah,
                    'berat_total' => $totalBerat,
                    'jumlah_box' => $jumlahBox,
                    'ikan_per_box' => 3,
                    'berat_per_box' => 10, // dalam kg
                    'biaya_tambahan' => $tambahan,
                    'box_info' => [
                        'ukuran_box' => '40x40x40 cm',
                        'deskripsi' => 'Box khusus pengiriman ikan hias dengan sistem aerasi',
                        'max_kapasitas' => 3,
                        'rekomendasi' => 'Pengiriman menggunakan kurir TIKI untuk menjaga keamanan dan kesegaran ikan'
                    ]
                ];

                // Simpan ke cache selama 30 menit
                \Illuminate\Support\Facades\Cache::put($cacheKey, $result, now()->addMinutes(30));

                return response()->json($result);
            }
        }

        // Fallback jika API gagal
        $fallbackResult = [
            'success' => true,
            'ongkir' => 50000, // Default ongkir
            'ongkir_options' => [
                [
                    'courier' => 'tiki',
                    'courier_name' => 'TIKI',
                    'service' => 'REG',
                    'cost' => 50000,
                    'etd' => '1-3'
                ]
            ],
            'jumlah_total' => $totalJumlah,
            'berat_total' => $totalBerat,
            'jumlah_box' => $jumlahBox,
            'ikan_per_box' => 3,
            'berat_per_box' => 10, // dalam kg
            'box_info' => [
                'ukuran_box' => '40x40x40 cm',
                'deskripsi' => 'Box khusus pengiriman ikan hias dengan sistem aerasi',
                'max_kapasitas' => 3,
                'rekomendasi' => 'Pengiriman menggunakan kurir TIKI untuk menjaga keamanan dan kesegaran ikan'
            ]
        ];

        // Simpan fallback ke cache selama 15 menit
        \Illuminate\Support\Facades\Cache::put($cacheKey, $fallbackResult, now()->addMinutes(15));

        return response()->json($fallbackResult);

        } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error in getOngkir: ' . $e->getMessage());

        return response()->json([
            'success' => true,
            'message' => 'Menggunakan tarif standar: ' . $e->getMessage(),
            'ongkir' => 50000, // Default ongkir jika gagal
            'ongkir_options' => [
                [
                    'courier' => 'tiki',
                    'courier_name' => 'TIKI',
                    'service' => 'REG',
                    'cost' => 50000,
                    'etd' => '1-3'
                ]
            ],
            'jumlah_total' => $totalJumlah ?? 0,
            'berat_total' => isset($jumlahBox) ? $jumlahBox * 10000 : 10000,
            'jumlah_box' => $jumlahBox ?? 1,
            'ikan_per_box' => 3,
            'berat_per_box' => 10 // dalam kg
        ]);
        }
    }
};
