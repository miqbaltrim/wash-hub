<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@washhub.id'],
            [
                'name' => 'Admin Wash Hub',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@washhub.id'],
            [
                'name' => 'Kasir 1',
                'password' => Hash::make('kasir123'),
                'role' => 'cashier',
                'phone' => '081234567891',
                'is_active' => true,
            ]
        );
    }
}