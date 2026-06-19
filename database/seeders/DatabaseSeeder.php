<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ONLY ADMIN - Employee will be added by admin later
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '012-3456789',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}