<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefundRequest;

class RefundRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pending refund for order 2 (Ani)
        RefundRequest::create([
            'id_pesanan' => 2,
            'jenis_refund' => 'keterlambatan',
            'deskripsi_masalah' => 'Pesanan tiba lebih dari waktu estimasi',
            'bukti_pendukung' => ['foto_kemasan.jpg'],
            'status' => 'pending',
            'jumlah_diminta' => 168000,
            'metode_refund' => 'transfer_bank',
        ]);

        // Approved refund for order 2 (Ani)
        RefundRequest::create([
            'id_pesanan' => 2,
            'jenis_refund' => 'kerusakan',
            'deskripsi_masalah' => 'Produk rusak saat diterima',
            'bukti_pendukung' => ['foto_produk_rusak1.png', 'foto_produk_rusak2.png'],
            'status' => 'approved',
            'catatan_admin' => 'Pengajuan disetujui, akan diproses pengembalian dana',
            'jumlah_diminta' => 168000,
            'jumlah_disetujui' => 168000,
            'metode_refund' => 'qris',
            'reviewed_at' => now(),
            'reviewed_by' => 1, // Admin user
        ]);
    }
}
