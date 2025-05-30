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
        // Add quarantine and refund fields to pesanan table
        Schema::table('pesanan', function (Blueprint $table) {
            // Karantina fields
            $table->timestamp('karantina_mulai')->nullable()->comment('Tanggal mulai karantina 7 hari');
            $table->timestamp('karantina_selesai')->nullable()->comment('Tanggal selesai karantina');
            $table->boolean('is_karantina_active')->default(false)->comment('Status karantina aktif');

            // Refund fields
            $table->enum('status_refund', ['none', 'requested', 'approved', 'rejected', 'processed'])->default('none');
            $table->text('alasan_refund')->nullable()->comment('Alasan permintaan refund');
            $table->string('bukti_kerusakan')->nullable()->comment('Upload bukti kerusakan produk');
            $table->text('catatan_admin_refund')->nullable()->comment('Catatan admin untuk refund');
            $table->timestamp('tanggal_refund_request')->nullable()->comment('Tanggal permintaan refund');
            $table->timestamp('tanggal_refund_processed')->nullable()->comment('Tanggal refund diproses');
            $table->decimal('jumlah_refund', 10, 2)->nullable()->comment('Jumlah refund yang disetujui');

            // Order tracking enhancement
            $table->string('no_resi')->nullable()->comment('Nomor resi pengiriman');
            $table->timestamp('tanggal_pengiriman')->nullable()->comment('Tanggal pengiriman');
            $table->timestamp('tanggal_diterima')->nullable()->comment('Tanggal pesanan diterima customer');
            $table->json('tracking_history')->nullable()->comment('History tracking dari TIKI API');
            $table->enum('kondisi_diterima', ['baik', 'rusak', 'belum_dikonfirmasi'])->default('belum_dikonfirmasi');
            $table->text('catatan_penerimaan')->nullable()->comment('Catatan saat penerimaan');

            // Additional fields
            $table->boolean('is_reviewable')->default(false)->comment('Apakah bisa direview');
            $table->text('alasan_pembatalan')->nullable()->comment('Alasan pembatalan pesanan');
            $table->decimal('berat_total', 8, 2)->nullable()->comment('Total berat pengiriman dalam gram');
        });

        // Update status_pesanan enum to include new statuses
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('status_pesanan');
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->enum('status_pesanan', [
                'Menunggu Pembayaran',
                'Diproses',
                'Karantina',
                'Dikirim',
                'Selesai',
                'Dibatalkan',
                'Refund Requested',
                'Refund Approved',
                'Refund Rejected',
                'Refund Processed'
            ])->default('Menunggu Pembayaran')->after('total_harga');
        });

        // Create refund_requests table for detailed refund tracking
        Schema::create('refund_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pesanan');
            $table->enum('jenis_refund', ['kerusakan', 'keterlambatan', 'tidak_sesuai', 'kematian_ikan', 'lainnya']);
            $table->text('deskripsi_masalah');
            $table->json('bukti_pendukung')->nullable()->comment('Array path file bukti');
            $table->enum('status', ['pending', 'reviewing', 'approved', 'rejected', 'processed'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->decimal('jumlah_diminta', 10, 2);
            $table->decimal('jumlah_disetujui', 10, 2)->nullable();
            $table->string('metode_refund')->nullable()->comment('Bank transfer, etc');
            $table->text('detail_refund')->nullable()->comment('Detail rekening atau metode refund');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
        });

        // Enhance ulasan table for admin replies
        Schema::table('ulasan', function (Blueprint $table) {
            $table->text('balasan_admin')->nullable()->comment('Balasan dari admin');
            $table->timestamp('tanggal_balasan')->nullable()->comment('Tanggal balasan admin');
            $table->foreignId('admin_reply_by')->nullable()->constrained('users')->comment('Admin yang membalas');
            $table->boolean('is_verified_purchase')->default(false)->comment('Apakah pembelian terverifikasi');
            $table->json('foto_review')->nullable()->comment('Foto-foto review produk');
            $table->enum('status_review', ['pending', 'approved', 'rejected'])->default('approved');
            $table->boolean('is_helpful')->default(false)->comment('Apakah review membantu');
            $table->integer('helpful_count')->default(0)->comment('Jumlah yang menganggap helpful');
        });

        // Create review_interactions table for like/helpful tracking
        Schema::create('review_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('ulasan_id')->constrained('ulasan', 'id_ulasan')->onDelete('cascade');
            $table->enum('interaction_type', ['helpful', 'not_helpful']);
            $table->timestamps();

            $table->unique(['user_id', 'ulasan_id']);
        });

        // Create order_timeline table for detailed tracking
        Schema::create('order_timeline', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pesanan');
            $table->string('status');
            $table->string('title');
            $table->text('description');
            $table->json('metadata')->nullable()->comment('Additional data like tracking info');
            $table->boolean('is_customer_visible')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
        });

        // Create quarantine_logs table for quarantine tracking
        Schema::create('quarantine_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pesanan');
            $table->timestamp('started_at');
            $table->timestamp('scheduled_end_at');
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->json('daily_checks')->nullable()->comment('Daily quarantine check logs');
            $table->timestamps();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new tables
        Schema::dropIfExists('quarantine_logs');
        Schema::dropIfExists('order_timeline');
        Schema::dropIfExists('review_interactions');
        Schema::dropIfExists('refund_requests');

        // Remove added columns from pesanan table
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn([
                'karantina_mulai',
                'karantina_selesai',
                'is_karantina_active',
                'status_refund',
                'alasan_refund',
                'bukti_kerusakan',
                'catatan_admin_refund',
                'tanggal_refund_request',
                'tanggal_refund_processed',
                'jumlah_refund',
                'no_resi',
                'tanggal_pengiriman',
                'tanggal_diterima',
                'tracking_history',
                'kondisi_diterima',
                'catatan_penerimaan',
                'is_reviewable',
                'alasan_pembatalan',
                'berat_total'
            ]);
        });

        // Restore original status_pesanan enum
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('status_pesanan');
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->enum('status_pesanan', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])
                  ->default('pending');
        });

        // Remove added columns from ulasan table
        Schema::table('ulasan', function (Blueprint $table) {
            $table->dropColumn([
                'balasan_admin',
                'tanggal_balasan',
                'admin_reply_by',
                'is_verified_purchase',
                'foto_review',
                'status_review',
                'is_helpful',
                'helpful_count'
            ]);
        });
    }
};
