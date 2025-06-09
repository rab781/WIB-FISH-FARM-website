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
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->enum('metode_refund', ['bank_transfer', 'e_wallet'])->default('bank_transfer')->after('jumlah_klaim');
            $table->string('nama_ewallet', 50)->nullable()->after('nama_pemilik_rekening');
            $table->string('nomor_ewallet', 50)->nullable()->after('nama_ewallet');
            $table->string('nama_pemilik_ewallet', 100)->nullable()->after('nomor_ewallet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->dropColumn(['metode_refund', 'nama_ewallet', 'nomor_ewallet', 'nama_pemilik_ewallet']);
        });
    }
};
