<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create Admin Account
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '012-3456789',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Categories
        $categories = [
            'Beverages',
            'Snacks',
            'Instant Food',
            'Canned Food',
            'Seasoning & Sauces',
            'Rice & Dry Goods',
            'Health & Beauty',
            'Cleaning Supplies',
            'Kitchenware',
            'Stationery',
            'Others',
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'category_name' => $categoryName,
            ]);
        }
    }
}