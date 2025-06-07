<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            // Force update the enum column to support Indonesian status values
            DB::statement("ALTER TABLE pesanan MODIFY COLUMN status_pesanan VARCHAR(50) DEFAULT 'Menunggu Pembayaran'");
            echo "Changed status_pesanan to VARCHAR(50) to support Indonesian status values\n";

        } catch (Exception $e) {
            echo "Error updating column: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to enum if needed
        DB::statement("ALTER TABLE pesanan MODIFY COLUMN status_pesanan ENUM(
            'pending',
            'processing',
            'shipped',
            'delivered',
            'cancelled'
        ) DEFAULT 'pending'");
    }
};
