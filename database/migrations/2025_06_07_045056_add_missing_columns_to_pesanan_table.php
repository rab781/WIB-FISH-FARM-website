<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('pesanan', 'tanggal_pembayaran')) {
                $table->timestamp('tanggal_pembayaran')->nullable();
            }
            if (!Schema::hasColumn('pesanan', 'tanggal_selesai')) {
                $table->timestamp('tanggal_selesai')->nullable();
            }
            if (!Schema::hasColumn('pesanan', 'alasan_pembatalan')) {
                $table->text('alasan_pembatalan')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['tanggal_pembayaran', 'tanggal_selesai', 'alasan_pembatalan']);
        });
    }
};
