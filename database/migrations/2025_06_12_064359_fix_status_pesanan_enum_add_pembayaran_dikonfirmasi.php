<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update enum to include 'Pembayaran Dikonfirmasi'
        DB::statement("ALTER TABLE pesanan MODIFY COLUMN status_pesanan ENUM(
            'Menunggu Pembayaran',
            'Pembayaran Dikonfirmasi',
            'Diproses',
            'Dikirim',
            'Selesai',
            'Dibatalkan',
            'Karantina',
            'Pengembalian'
        ) DEFAULT 'Menunggu Pembayaran'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum
        DB::statement("ALTER TABLE pesanan MODIFY COLUMN status_pesanan ENUM(
            'Menunggu Pembayaran',
            'Diproses',
            'Dikirim',
            'Selesai',
            'Dibatalkan'
        ) DEFAULT 'Menunggu Pembayaran'");
    }
};
