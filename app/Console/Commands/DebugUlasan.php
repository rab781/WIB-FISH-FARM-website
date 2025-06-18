<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pesanan;
use App\Models\Ulasan;

class DebugUlasan extends Command
{
    protected $signature = 'debug:ulasan {pesanan_id?}';
    protected $description = 'Debug ulasan issues';

    public function handle()
    {
        $pesananId = $this->argument('pesanan_id');
        
        if ($pesananId) {
            $pesanan = Pesanan::with(['detailPesanan.produk', 'ulasanPesanan.produk'])->find($pesananId);
        } else {
            $pesanan = Pesanan::with(['detailPesanan.produk', 'ulasanPesanan.produk'])->latest()->first();
        }

        if (!$pesanan) {
            $this->error('Pesanan tidak ditemukan');
            return;
        }

        $this->info("=== DEBUGGING PESANAN {$pesanan->id_pesanan} ===");
        $this->info("User ID: {$pesanan->user_id}");
        $this->info("Status: {$pesanan->status_pesanan}");
        $this->line('');

        $this->info("Produk dalam pesanan ini:");
        foreach($pesanan->detailPesanan as $detail) {
            $this->line("- {$detail->produk->nama_ikan} (ID: {$detail->id_Produk})");
        }
        $this->line('');

        $this->info("Ulasan terkait pesanan ini (relasi ulasanPesanan):");
        foreach($pesanan->ulasanPesanan as $ulasan) {
            $this->line("- {$ulasan->produk->nama_ikan} (Product ID: {$ulasan->id_Produk}, Order ID: {$ulasan->id_pesanan})");
        }
        $this->line('');

        $this->info("Ulasan menggunakan getUlasanAttribute():");
        $ulasanFromAttribute = $pesanan->ulasan;
        foreach($ulasanFromAttribute as $ulasan) {
            $this->line("- {$ulasan->produk->nama_ikan} (Product ID: {$ulasan->id_Produk}, Order ID: {$ulasan->id_pesanan})");
        }
        $this->line('');

        $this->info("Semua ulasan user {$pesanan->user_id}:");
        $allReviews = Ulasan::where('user_id', $pesanan->user_id)->with('produk')->get();
        foreach($allReviews as $review) {
            $this->line("Review ID: {$review->id_ulasan} | Product: {$review->produk->nama_ikan} (ID: {$review->id_Produk}) | Order ID: " . ($review->id_pesanan ?? 'NULL'));
        }
    }
}
