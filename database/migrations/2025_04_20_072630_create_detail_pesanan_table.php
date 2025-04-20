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
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pesanan');
            $table->foreignId('id_Produk')->constrained('produk', 'id_Produk');
            $table->integer('jumlah');
            $table->decimal('total_harga', 10, 2);
            $table->primary(['id_pesanan', 'id_Produk']); // Composite primary key
            $table->timestamps();
            
            // Foreign key untuk id_pesanan akan ditambahkan di migration lain
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
