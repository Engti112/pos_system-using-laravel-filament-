<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Ensure no duplicate users
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin', // Add a role column in your users table
            ]
        );

        // Create Cashier User
        User::updateOrCreate(
            ['email' => 'cashier@gmail.com'],
            [
                'name' => 'Cashier User',
                'password' => Hash::make('password'),
                'role' => 'cashier',
            ]
        );
    }
}
