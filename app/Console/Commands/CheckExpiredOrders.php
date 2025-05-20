<?php

namespace App\Console\Commands;

use App\Http\Controllers\NotificationController;
use App\Models\Pesanan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-expired-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periksa dan batalkan pesanan yang melewati batas waktu pembayaran';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai memeriksa pesanan yang melewati batas waktu...');
        
        // Ambil semua pesanan dengan status "Menunggu Pembayaran" dan batas waktu yang sudah terlewati
        $expiredOrders = Pesanan::where('status_pesanan', 'Menunggu Pembayaran')
            ->whereNotNull('batas_waktu')
            ->where('batas_waktu', '<', now())
            ->get();
            
        $count = $expiredOrders->count();
        $this->info("Ditemukan {$count} pesanan yang melewati batas waktu pembayaran.");
        
        foreach ($expiredOrders as $order) {
            try {
                // Update status pesanan menjadi batal
                $order->status_pesanan = 'Dibatalkan';
                $order->save();
                
                // Kirim notifikasi ke pelanggan
                NotificationController::notifyCustomer($order->user_id, [
                    'type' => 'order',
                    'title' => 'Pesanan Dibatalkan',
                    'message' => "Pesanan #{$order->id_pesanan} telah dibatalkan secara otomatis karena melewati batas waktu pembayaran.",
                    'data' => [
                        'order_id' => $order->id_pesanan,
                        'url' => route('pesanan.show', $order->id_pesanan)
                    ]
                ]);
                
                $this->info("Pesanan #{$order->id_pesanan} telah dibatalkan.");
                
                // Kembalikan stok produk
                foreach ($order->detailPesanan as $detail) {
                    if ($detail->ukuran_id) {
                        $ukuran = \App\Models\ProdukUkuran::find($detail->ukuran_id);
                        if ($ukuran) {
                            $ukuran->stok += $detail->kuantitas;
                            $ukuran->save();
                            $this->info("- Stok produk ukuran #{$detail->ukuran_id} ditambahkan: {$detail->kuantitas}");
                        }
                    } else {
                        $produk = \App\Models\Produk::find($detail->id_Produk);
                        if ($produk) {
                            $produk->stok += $detail->kuantitas;
                            $produk->save();
                            $this->info("- Stok produk #{$detail->id_Produk} ditambahkan: {$detail->kuantitas}");
                        }
                    }
                }
                
            } catch (\Exception $e) {
                $this->error("Error saat memproses pesanan #{$order->id_pesanan}: {$e->getMessage()}");
                Log::error("Error saat memproses pesanan yang melewati batas waktu #{$order->id_pesanan}: {$e->getMessage()}");
            }
        }
        
        $this->info('Selesai memeriksa pesanan yang melewati batas waktu.');
    }
}
