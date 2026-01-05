<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@greenresources.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'), // Change this in production!
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'editor@greenresources.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password123'), // Change this in production!
                'role' => 'editor',
            ]
        );

        User::updateOrCreate(
            ['email' => 'viewer@greenresources.com'],
            [
                'name' => 'Viewer User',
                'password' => Hash::make('password123'), // Change this in production!
                'role' => 'viewer',
            ]
        );
    }
}
