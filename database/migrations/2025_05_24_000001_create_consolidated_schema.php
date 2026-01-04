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
        // Modify users table to add new fields
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'is_admin')) {
                    $table->boolean('is_admin')->default(false);
                }
                if (!Schema::hasColumn('users', 'foto')) {
                    $table->string('foto')->nullable();
                }
                if (!Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable();
                }
                if (!Schema::hasColumn('users', 'google_id')) {
                    $table->string('google_id')->nullable();
                }
                if (!Schema::hasColumn('users', 'google_token')) {
                    $table->string('google_token')->nullable();
                }
                if (!Schema::hasColumn('users', 'google_refresh_token')) {
                    $table->string('google_refresh_token')->nullable();
                }
                if (!Schema::hasColumn('users', 'no_hp')) {
                    $table->string('no_hp', 20)->nullable();
                }
            });
        }

        // Create or update alamat table
        if (!Schema::hasTable('alamat')) {
            Schema::create('alamat', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary()->comment('ID from RajaOngkir API');
                $table->string('provinsi');
                $table->string('kabupaten');
                $table->string('kecamatan')->nullable();
                $table->string('tipe')->nullable()->comment('Type like "Kota" or "Kabupaten"');
                $table->string('kode_pos')->nullable();
                $table->text('alamat_jalan')->nullable();
                $table->timestamps();
            });
        }

        // Create ongkir table
        if (!Schema::hasTable('ongkir')) {
            Schema::create('ongkir', function (Blueprint $table) {
                $table->id('id_ongkir');
                $table->unsignedBigInteger('alamat_id');
                $table->decimal('biaya', 10, 2)->default(50000);
                $table->text('keterangan')->nullable();
                $table->timestamps();
                $table->foreign('alamat_id')->references('id')->on('alamat');
            });
        }

        // Create produk table with soft deletes
        if (!Schema::hasTable('produk')) {
            Schema::create('produk', function (Blueprint $table) {
                $table->id('id_Produk');
                $table->string('nama_ikan', 255);
                $table->string('deskripsi', 255);
                $table->integer('stok');
                $table->decimal('harga', 10, 2);
                $table->string('jenis_ikan', 255);
                $table->binary('gambar')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // Create pesanan table
        if (!Schema::hasTable('pesanan')) {
            Schema::create('pesanan', function (Blueprint $table) {
                $table->id('id_pesanan');
                $table->foreignId('user_id')->constrained('users');
                $table->foreignId('alamat_id')->constrained('alamat');

                // Shipping information
                $table->string('kurir')->default('tiki');
                $table->string('kurir_service')->default('REG');
                $table->decimal('ongkir_biaya', 10, 2)->default(0);
                $table->text('alamat_pengiriman')->nullable();

                // Payment information
                $table->string('metode_pembayaran')->nullable();
                $table->decimal('total_harga', 10, 2);
                $table->string('bukti_pembayaran')->nullable();

                // Status and timing
                $table->enum('status_pesanan', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])
                      ->default('pending');
                $table->timestamp('batas_waktu')->nullable();
                $table->integer('jumlah_box')->default(1);
                $table->timestamps();
            });
        }

        // Create detail_pesanan table
        if (!Schema::hasTable('detail_pesanan')) {
            Schema::create('detail_pesanan', function (Blueprint $table) {
                $table->unsignedBigInteger('id_pesanan');
                $table->foreignId('id_Produk')->constrained('produk', 'id_Produk');
                $table->integer('kuantitas');
                $table->decimal('harga', 10, 2);
                $table->decimal('subtotal', 10, 2);
                $table->primary(['id_pesanan', 'id_Produk']);
                $table->timestamps();
                $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            });
        }

        // Create keranjang table
        if (!Schema::hasTable('keranjang')) {
            Schema::create('keranjang', function (Blueprint $table) {
                $table->id('id_keranjang');
                $table->foreignId('user_id')->constrained('users');
                $table->foreignId('id_Produk')->constrained('produk', 'id_Produk');
                $table->integer('jumlah');
                $table->decimal('total_harga', 10, 2);
                $table->timestamps();
            });
        }

        // Create pembayaran table
        if (!Schema::hasTable('pembayaran')) {
            Schema::create('pembayaran', function (Blueprint $table) {
                $table->id('id_pembayaran');
                $table->unsignedBigInteger('id_pesanan');
                $table->boolean('status_pembayaran');
                $table->string('nomor_rekening', 255);
                $table->string('nama_bank', 255);
                $table->timestamps();
                $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            });
        }

        // Create ulasan table
        if (!Schema::hasTable('ulasan')) {
            Schema::create('ulasan', function (Blueprint $table) {
                $table->id('id_ulasan');
                $table->foreignId('user_id')->constrained('users');
                $table->foreignId('id_Produk')->constrained('produk', 'id_Produk');
                $table->decimal('rating', 3, 1);
                $table->text('komentar')->nullable();
                $table->timestamps();
            });
        }

        // Create notifications table
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('type');
                $table->string('title');
                $table->text('message');
                $table->json('data')->nullable();
                $table->boolean('is_read')->default(false);
                $table->boolean('for_admin')->default(false);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop tables that we created
        $tables = [
            'notifications',
            'ulasan',
            'pembayaran',
            'keranjang',
            'detail_pesanan',
            'pesanan',
            'produk_ukuran',
            'produk',
            'ongkir',
            'alamat'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::dropIfExists($table);
            }
        }

        // Remove added columns from users table if they exist
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $columns = [
                    'is_admin',
                    'foto',
                    'avatar',
                    'google_id',
                    'google_token',
                    'google_refresh_token',
                    'no_hp'
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
