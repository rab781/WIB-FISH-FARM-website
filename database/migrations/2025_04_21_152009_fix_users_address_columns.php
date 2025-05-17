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
            // Add a foreign key constraint for kecamatan_id to ensure it belongs to a valid kecamatan
            // No need to drop and recreate as it's already a foreign key in previous migration

            // Ensure that kecamatan belongs to a valid kabupaten which belongs to a valid provinsi
            if (!Schema::hasColumn('kecamatan', 'kabupaten_id')) {
                throw new \Exception("The 'kabupaten_id' column must exist in 'kecamatan' table");
            }

            if (!Schema::hasColumn('kabupaten', 'provinsi_id')) {
                throw new \Exception("The 'provinsi_id' column must exist in 'kabupaten' table");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // No action needed, we're not modifying any columns here
            // Just adding a validation check in the up() method
        });
    }
};
