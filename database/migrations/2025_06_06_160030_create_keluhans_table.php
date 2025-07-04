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
        Schema::create('keluhan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('jenis_keluhan');
            $table->text('keluhan');
            $table->string('gambar')->nullable();
            $table->enum('status', ['Belum Diproses', 'Sedang Diproses', 'Selesai'])->default('Belum Diproses');
            $table->text('respon_admin')->nullable();
            $table->timestamp('respon_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluhan');
    }
};
