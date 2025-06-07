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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id('id_pengembalian');
            $table->unsignedBigInteger('id_pesanan');
            $table->unsignedBigInteger('user_id');
            $table->enum('jenis_keluhan', [
                'Barang Rusak',
                'Barang Tidak Sesuai',
                'Barang Kurang',
                'Kualitas Buruk',
                'Lainnya'
            ]);
            $table->text('deskripsi_masalah');
            $table->json('foto_bukti')->nullable(); // Menyimpan array path foto
            $table->decimal('jumlah_klaim', 10, 2);

            // Data bank untuk pengembalian dana
            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->string('nama_pemilik_rekening');

            // Status dan tracking
            $table->enum('status_pengembalian', [
                'Menunggu Review',
                'Dalam Review',
                'Disetujui',
                'Ditolak',
                'Dana Dikembalikan',
                'Selesai'
            ])->default('Menunggu Review');

            // Admin response
            $table->text('catatan_admin')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable(); // Admin yang review
            $table->timestamp('tanggal_review')->nullable();
            $table->timestamp('tanggal_pengembalian_dana')->nullable();
            $table->string('nomor_transaksi_pengembalian')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['user_id', 'status_pengembalian']);
            $table->index(['id_pesanan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
