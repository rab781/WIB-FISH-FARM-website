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
        // Check current column definition
        $columns = DB::select('DESCRIBE pesanan');
        $statusColumn = collect($columns)->firstWhere('Field', 'status_pesanan');

        if ($statusColumn) {
            echo "Current status_pesanan type: " . $statusColumn->Type . "\n";

            // If it's still the old enum, update it
            if (strpos($statusColumn->Type, 'pending') !== false) {
                // Update the enum to include Indonesian status values
                DB::statement("ALTER TABLE pesanan MODIFY COLUMN status_pesanan ENUM(
                    'Menunggu Pembayaran',
                    'Pembayaran Dikonfirmasi',
                    'Diproses',
                    'Dikirim',
                    'Selesai',
                    'Dibatalkan',
                    'Refund Requested',
                    'Refund Approved',
                    'Refund Rejected',
                    'Refund Processed'
                ) DEFAULT 'Menunggu Pembayaran'");

                echo "Updated status_pesanan enum to include Indonesian values\n";
            } else {
                echo "status_pesanan enum already appears to be updated\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum
        DB::statement("ALTER TABLE pesanan MODIFY COLUMN status_pesanan ENUM(
            'pending',
            'processing',
            'shipped',
            'delivered',
            'cancelled'
        ) DEFAULT 'pending'");
    }
};
