<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, convert the column to VARCHAR to avoid enum constraints
        DB::statement("ALTER TABLE pesanan MODIFY COLUMN status_pesanan VARCHAR(30) NOT NULL DEFAULT 'Menunggu Pembayaran'");

        // Now set default to Menunggu Pembayaran for all records with old values
        DB::statement("UPDATE pesanan SET status_pesanan = 'Menunggu Pembayaran' WHERE status_pesanan IN ('Menunggu konfirmasi', 'proses', 'selesai', 'batal')");

        // Then create the new enum with the new values
        DB::statement("ALTER TABLE pesanan MODIFY COLUMN status_pesanan ENUM(
            'Menunggu Pembayaran',
            'Pembayaran Dikonfirmasi',
            'Diproses',
            'Dikirim',
            'Selesai',
            'Dibatalkan'
        ) NOT NULL DEFAULT 'Menunggu Pembayaran'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First convert to VARCHAR to avoid enum constraints
        DB::statement("ALTER TABLE pesanan MODIFY COLUMN status_pesanan VARCHAR(30) NOT NULL DEFAULT 'Menunggu konfirmasi'");

        // Update new status values to old ones
        DB::statement("UPDATE pesanan SET status_pesanan = 'Menunggu konfirmasi' WHERE status_pesanan = 'Menunggu Pembayaran' OR status_pesanan = 'Pembayaran Dikonfirmasi'");
        DB::statement("UPDATE pesanan SET status_pesanan = 'proses' WHERE status_pesanan = 'Diproses'");
        DB::statement("UPDATE pesanan SET status_pesanan = 'selesai' WHERE status_pesanan = 'Selesai' OR status_pesanan = 'Dikirim'");
        DB::statement("UPDATE pesanan SET status_pesanan = 'batal' WHERE status_pesanan = 'Dibatalkan'");

        // Revert to the original enum values
        DB::statement("ALTER TABLE pesanan MODIFY COLUMN status_pesanan ENUM(
            'Menunggu konfirmasi',
            'proses',
            'selesai',
            'batal'
        ) NOT NULL DEFAULT 'Menunggu konfirmasi'");
    }
};
