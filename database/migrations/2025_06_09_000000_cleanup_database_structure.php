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
        // 1. Rename keluhans table to keluhan
        if (Schema::hasTable('keluhans')) {
            Schema::rename('keluhans', 'keluhan');
        }

        // 2. Drop refund_requests table (use pengembalian instead)
        Schema::dropIfExists('refund_requests');

        // 3. Drop produk_ukurans table completely
        Schema::dropIfExists('produk_ukurans');

        // 4. Remove quarantine fields from pesanan table
        Schema::table('pesanan', function (Blueprint $table) {
            $columns = ['karantina_mulai', 'karantina_selesai', 'is_karantina_active'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pesanan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // 5. Remove refund fields from pesanan table (use pengembalian table instead)
        Schema::table('pesanan', function (Blueprint $table) {
            $refundColumns = [
                'status_refund',
                'alasan_refund',
                'bukti_kerusakan',
                'catatan_admin_refund',
                'tanggal_refund_request',
                'tanggal_refund_processed',
                'jumlah_refund'
            ];

            foreach ($refundColumns as $column) {
                if (Schema::hasColumn('pesanan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // 6. Update status_pesanan enum to remove refund statuses
        if (Schema::hasColumn('pesanan', 'status_pesanan')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->dropColumn('status_pesanan');
            });

            Schema::table('pesanan', function (Blueprint $table) {
                $table->enum('status_pesanan', [
                    'Menunggu Pembayaran',
                    'Diproses',
                    'Dikirim',
                    'Selesai',
                    'Dibatalkan'
                ])->default('Menunggu Pembayaran')->after('total_harga');
            });
        }

        // 7. Drop quarantine_logs table if exists
        Schema::dropIfExists('quarantine_logs');

        // 8. Remove duplicate order_timelines table (keep order_timeline)
        Schema::dropIfExists('order_timelines');

        // 9. Ensure pengembalian table exists with proper structure
        if (!Schema::hasTable('pengembalian')) {
            Schema::create('pengembalian', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_pesanan');
                $table->enum('jenis_pengembalian', ['kerusakan', 'keterlambatan', 'tidak_sesuai', 'lainnya']);
                $table->text('alasan_pengembalian');
                $table->string('bukti_pengembalian')->nullable();
                $table->enum('status_pengembalian', ['pending', 'approved', 'rejected', 'processed'])->default('pending');
                $table->decimal('jumlah_pengembalian', 10, 2);
                $table->text('catatan_admin')->nullable();
                $table->timestamp('tanggal_pengajuan')->useCurrent();
                $table->timestamp('tanggal_diproses')->nullable();
                $table->foreignId('diproses_oleh')->nullable()->constrained('users');
                $table->timestamps();

                $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore keluhan to keluhans
        if (Schema::hasTable('keluhan')) {
            Schema::rename('keluhan', 'keluhans');
        }

        // Recreate dropped tables if needed (optional)
        // Note: This is a cleanup migration, reverting may not be desired
    }
};
