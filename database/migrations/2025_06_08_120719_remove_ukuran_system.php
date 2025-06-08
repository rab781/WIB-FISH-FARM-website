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
        // Remove ukuran_id column from detail_pesanan table
        if (Schema::hasColumn('detail_pesanan', 'ukuran_id')) {
            Schema::table('detail_pesanan', function (Blueprint $table) {
                $table->dropForeign(['ukuran_id']);
                $table->dropColumn('ukuran_id');
            });
        }

        // Remove ukuran_id column from keranjang table
        if (Schema::hasColumn('keranjang', 'ukuran_id')) {
            Schema::table('keranjang', function (Blueprint $table) {
                $table->dropForeign(['ukuran_id']);
                $table->dropColumn('ukuran_id');
            });
        }

        // Drop produk_ukuran table entirely
        Schema::dropIfExists('produk_ukuran');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate produk_ukuran table
        Schema::create('produk_ukuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->string('ukuran');
            $table->integer('stok')->default(0);
            $table->decimal('harga_tambahan', 10, 2)->default(0);
            $table->timestamps();
        });

        // Add ukuran_id column back to detail_pesanan table
        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->foreignId('ukuran_id')->nullable()->constrained('produk_ukuran')->onDelete('set null');
        });

        // Add ukuran_id column back to keranjang table
        Schema::table('keranjang', function (Blueprint $table) {
            $table->foreignId('ukuran_id')->nullable()->constrained('produk_ukuran')->onDelete('set null');
        });
    }
};
