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
            if (!Schema::hasColumn('pesanan', 'karantina_mulai')) {
                $table->timestamp('karantina_mulai')->nullable()->comment('Tanggal mulai karantina 7 hari');
            }
            if (!Schema::hasColumn('pesanan', 'karantina_selesai')) {
                $table->timestamp('karantina_selesai')->nullable()->comment('Tanggal selesai karantina');
            }
            if (!Schema::hasColumn('pesanan', 'is_karantina_active')) {
                $table->boolean('is_karantina_active')->default(false)->comment('Status karantina aktif');
            }

            // Refund fields
            if (!Schema::hasColumn('pesanan', 'status_refund')) {
                $table->enum('status_refund', ['none', 'requested', 'approved', 'rejected', 'processed'])->default('none');
            }
            if (!Schema::hasColumn('pesanan', 'alasan_refund')) {
                $table->text('alasan_refund')->nullable()->comment('Alasan permintaan refund');
            }
            if (!Schema::hasColumn('pesanan', 'bukti_kerusakan')) {
                $table->string('bukti_kerusakan')->nullable()->comment('Upload bukti kerusakan produk');
            }
            if (!Schema::hasColumn('pesanan', 'catatan_admin_refund')) {
                $table->text('catatan_admin_refund')->nullable()->comment('Catatan admin untuk refund');
            }
            if (!Schema::hasColumn('pesanan', 'tanggal_refund_request')) {
                $table->timestamp('tanggal_refund_request')->nullable()->comment('Tanggal permintaan refund');
            }
            if (!Schema::hasColumn('pesanan', 'tanggal_refund_processed')) {
                $table->timestamp('tanggal_refund_processed')->nullable()->comment('Tanggal refund diproses');
            }
            if (!Schema::hasColumn('pesanan', 'jumlah_refund')) {
                $table->decimal('jumlah_refund', 10, 2)->nullable()->comment('Jumlah refund yang disetujui');
            }

            // Order tracking enhancement
            if (!Schema::hasColumn('pesanan', 'no_resi')) {
                $table->string('no_resi')->nullable()->comment('Nomor resi pengiriman');
            }
            if (!Schema::hasColumn('pesanan', 'tanggal_pengiriman')) {
                $table->timestamp('tanggal_pengiriman')->nullable()->comment('Tanggal pengiriman');
            }
            if (!Schema::hasColumn('pesanan', 'tanggal_diterima')) {
                $table->timestamp('tanggal_diterima')->nullable()->comment('Tanggal pesanan diterima customer');
            }
            if (!Schema::hasColumn('pesanan', 'tracking_history')) {
                $table->json('tracking_history')->nullable()->comment('History tracking dari TIKI API');
            }
            if (!Schema::hasColumn('pesanan', 'kondisi_diterima')) {
                $table->enum('kondisi_diterima', ['baik', 'rusak', 'belum_dikonfirmasi'])->default('belum_dikonfirmasi');
            }
            if (!Schema::hasColumn('pesanan', 'catatan_penerimaan')) {
                $table->text('catatan_penerimaan')->nullable()->comment('Catatan saat penerimaan');
            }

            // Additional fields
            if (!Schema::hasColumn('pesanan', 'is_reviewable')) {
                $table->boolean('is_reviewable')->default(false)->comment('Apakah bisa direview');
            }
            if (!Schema::hasColumn('pesanan', 'alasan_pembatalan')) {
                $table->text('alasan_pembatalan')->nullable()->comment('Alasan pembatalan pesanan');
            }
            if (!Schema::hasColumn('pesanan', 'berat_total')) {
                $table->decimal('berat_total', 8, 2)->nullable()->comment('Total berat pengiriman dalam gram');
            }
        });

        // Update status_pesanan enum to include new statuses
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('status_pesanan');
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->enum('status_pesanan', [
                'Menunggu Pembayaran',
                'Diproses',
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
        if (!Schema::hasTable('refund_requests')) {
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
        }

        // Enhance ulasan table for admin replies
        Schema::table('ulasan', function (Blueprint $table) {
            if (!Schema::hasColumn('ulasan', 'balasan_admin')) {
                $table->text('balasan_admin')->nullable()->comment('Balasan dari admin');
            }
            if (!Schema::hasColumn('ulasan', 'tanggal_balasan')) {
                $table->timestamp('tanggal_balasan')->nullable()->comment('Tanggal balasan admin');
            }
            if (!Schema::hasColumn('ulasan', 'admin_reply_by')) {
                $table->foreignId('admin_reply_by')->nullable()->constrained('users')->comment('Admin yang membalas');
            }
            if (!Schema::hasColumn('ulasan', 'is_verified_purchase')) {
                $table->boolean('is_verified_purchase')->default(false)->comment('Apakah pembelian terverifikasi');
            }
            if (!Schema::hasColumn('ulasan', 'foto_review')) {
                $table->json('foto_review')->nullable()->comment('Foto-foto review produk');
            }
            if (!Schema::hasColumn('ulasan', 'status_review')) {
                $table->enum('status_review', ['pending', 'approved', 'rejected'])->default('approved');
            }
            if (!Schema::hasColumn('ulasan', 'is_helpful')) {
                $table->boolean('is_helpful')->default(false)->comment('Apakah review membantu');
            }
            if (!Schema::hasColumn('ulasan', 'helpful_count')) {
                $table->integer('helpful_count')->default(0)->comment('Jumlah yang menganggap helpful');
            }
        });

        // Create review_interactions table for like/helpful tracking
        if (!Schema::hasTable('review_interactions')) {
            Schema::create('review_interactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users');
                $table->foreignId('ulasan_id')->constrained('ulasan', 'id_ulasan')->onDelete('cascade');
                $table->enum('interaction_type', ['helpful', 'not_helpful']);
                $table->timestamps();

                $table->unique(['user_id', 'ulasan_id']);
            });
        }

        // Create order_timeline table for detailed tracking
        if (!Schema::hasTable('order_timeline')) {
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
        }
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
