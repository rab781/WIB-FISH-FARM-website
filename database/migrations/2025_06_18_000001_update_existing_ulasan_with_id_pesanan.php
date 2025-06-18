<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Ulasan;
use App\Models\Pesanan;
use App\Models\DetailPesanan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing reviews to link them with orders
        $ulasanTanpaIdPesanan = Ulasan::whereNull('id_pesanan')->get();

        foreach ($ulasanTanpaIdPesanan as $ulasan) {
            // Find the most recent completed order for this user and product
            $pesanan = Pesanan::where('user_id', $ulasan->user_id)
                ->where('status_pesanan', 'Selesai')
                ->whereHas('detailPesanan', function($query) use ($ulasan) {
                    $query->where('id_Produk', $ulasan->id_Produk);
                })
                ->orderBy('created_at', 'desc')
                ->first();

            if ($pesanan) {
                $ulasan->update(['id_pesanan' => $pesanan->id_pesanan]);
                echo "Updated ulasan {$ulasan->id_ulasan} with pesanan {$pesanan->id_pesanan}\n";
            } else {
                echo "Could not find matching pesanan for ulasan {$ulasan->id_ulasan}\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove id_pesanan from existing reviews
        Ulasan::whereNotNull('id_pesanan')->update(['id_pesanan' => null]);
    }
};
