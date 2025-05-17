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
        Schema::table('users', function (Blueprint $table) {
            // Add provinsi_id and kabupaten_id if they don't exist
            if (!Schema::hasColumn('users', 'provinsi_id')) {
                $table->foreignId('provinsi_id')->nullable()->after('is_admin')->constrained('provinsi')->onDelete('set null');
            }

            if (!Schema::hasColumn('users', 'kabupaten_id')) {
                $table->foreignId('kabupaten_id')->nullable()->after('provinsi_id')->constrained('kabupaten')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'provinsi_id')) {
                $table->dropForeign(['provinsi_id']);
                $table->dropColumn('provinsi_id');
            }

            if (Schema::hasColumn('users', 'kabupaten_id')) {
                $table->dropForeign(['kabupaten_id']);
                $table->dropColumn('kabupaten_id');
            }
        });
    }
};
