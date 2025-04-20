<?php

namespace Database\Seeders;

use App\Models\Pembayaran;
use Illuminate\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pembayaran untuk pesanan ID 1 (Budi - Selesai)
        Pembayaran::create([
            'id_pesanan' => 1,
            'status_pembayaran' => true,
            'nomor_rekening' => '1234567890',
            'nama_bank' => 'Bank BCA',
        ]);

        // Pembayaran untuk pesanan ID 2 (Ani - Dikirim)
        Pembayaran::create([
            'id_pesanan' => 2,
            'status_pembayaran' => true,
            'nomor_rekening' => '9876543210',
            'nama_bank' => 'Bank Mandiri',
        ]);

        // Pembayaran untuk pesanan ID 3 (Dedi - Diproses)
        Pembayaran::create([
            'id_pesanan' => 3,
            'status_pembayaran' => true,
            'nomor_rekening' => '5678901234',
            'nama_bank' => 'Bank BNI',
        ]);

        // Pembayaran untuk pesanan ID 4 (Budi - Menunggu Pembayaran)
        Pembayaran::create([
            'id_pesanan' => 4,
            'status_pembayaran' => false,
            'nomor_rekening' => '',
            'nama_bank' => '',
        ]);
    }
}
