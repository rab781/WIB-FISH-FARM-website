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
        Schema::table('ulasan', function (Blueprint $table) {
            // Add id_pesanan column to link reviews to specific orders
            if (!Schema::hasColumn('ulasan', 'id_pesanan')) {
                $table->unsignedBigInteger('id_pesanan')->nullable()->after('id_Produk');
                $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ulasan', function (Blueprint $table) {
            if (Schema::hasColumn('ulasan', 'id_pesanan')) {
                $table->dropForeign(['id_pesanan']);
                $table->dropColumn('id_pesanan');
            }
        });
    }
};
