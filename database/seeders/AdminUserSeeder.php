<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user admin default
        User::create([
            'name' => 'Admin',
            'email' => 'admin@wibfishfarm.com',
            'password' => Hash::make('admin123'), // Ganti dengan password yang lebih aman
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
}
