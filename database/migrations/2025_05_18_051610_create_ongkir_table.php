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
        Schema::create('ongkir', function (Blueprint $table) {
            $table->id('id_ongkir');
            $table->unsignedBigInteger('kabupaten_id');
            $table->decimal('biaya', 10, 2)->default(50000);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign key constraint menggunakan 'id' sebagai referenced column
            $table->foreign('kabupaten_id')->references('id')->on('kabupaten');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ongkir');
    }
};
