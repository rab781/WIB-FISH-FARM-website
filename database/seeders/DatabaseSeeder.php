<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Note: After updating RouteServiceProvider::HOME to '/produk', you must:
     * 1. Clear Laravel's cache with: php artisan config:clear
     * 2. Ensure authenticator controllers use the RouteServiceProvider::HOME constant
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // If you need to seed product data, consider adding a ProductSeeder
        // $this->call([
        //     ProductSeeder::class,
        // ]);
    }
}
