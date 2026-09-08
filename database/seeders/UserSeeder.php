<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Utama untuk Testing / Reviewer
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Akun Kedua untuk menguji isolasi data (keamanan antar-pengguna)
        User::firstOrCreate(
            ['email' => 'user2@example.com'],
            [
                'name' => 'Second User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
