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
        Schema::create('produk_ukuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produk')->constrained('produk', 'id_Produk')->onDelete('cascade');
            $table->string('ukuran', 50); // Misal: "5-7 cm", "8-10 cm", dll
            $table->integer('stok')->default(0);
            $table->decimal('harga', 10, 2)->nullable(); // Opsional, jika harga berbeda per ukuran
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_ukuran');
    }
};
