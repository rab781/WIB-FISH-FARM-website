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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('id_ongkir');
            $table->decimal('total_harga', 10, 2);
            $table->enum('status_pesanan', ['Menunggu konfirmasi', 'proses', 'selesai', 'batal'])->default('Menunggu konfirmasi');
            $table->timestamps();

            // Tambahkan foreign key constraint setelah semua tabel dibuat
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Constraint untuk id_ongkir akan ditambahkan di migration berikutnya
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
