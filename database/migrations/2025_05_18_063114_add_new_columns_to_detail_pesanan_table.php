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
        Schema::table('detail_pesanan', function (Blueprint $table) {
            // Ganti jumlah dengan kuantitas jika tidak ada
            if (!Schema::hasColumn('detail_pesanan', 'kuantitas')) {
                $table->renameColumn('jumlah', 'kuantitas');
            }
            
            // Ganti total_harga dengan subtotal jika tidak ada
            if (!Schema::hasColumn('detail_pesanan', 'subtotal')) {
                $table->renameColumn('total_harga', 'subtotal');
            }
            
            // Tambah kolom ukuran_id jika belum ada
            if (!Schema::hasColumn('detail_pesanan', 'ukuran_id')) {
                $table->unsignedBigInteger('ukuran_id')->nullable()->after('id_Produk');
                $table->foreign('ukuran_id')->references('id')->on('produk_ukuran')->onDelete('set null');
            }
            
            // Tambah kolom harga jika belum ada
            if (!Schema::hasColumn('detail_pesanan', 'harga')) {
                $table->decimal('harga', 10, 2)->after('kuantitas');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pesanan', function (Blueprint $table) {
            // Kembalikan nama kolom jika ada
            if (Schema::hasColumn('detail_pesanan', 'kuantitas')) {
                $table->renameColumn('kuantitas', 'jumlah');
            }
            
            if (Schema::hasColumn('detail_pesanan', 'subtotal')) {
                $table->renameColumn('subtotal', 'total_harga');
            }
            
            // Hapus kolom yang ditambahkan
            if (Schema::hasColumn('detail_pesanan', 'ukuran_id')) {
                $table->dropForeign(['ukuran_id']);
                $table->dropColumn('ukuran_id');
            }
            
            if (Schema::hasColumn('detail_pesanan', 'harga')) {
                $table->dropColumn('harga');
            }
        });
    }
};
