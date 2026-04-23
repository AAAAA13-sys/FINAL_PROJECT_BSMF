<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Categories
        $classics = Category::create(['name' => 'Classics']);
        $supercars = Category::create(['name' => 'Supercars']);
        $treasureHunts = Category::create(['name' => 'Treasure Hunts']);
        $muscleCars = Category::create(['name' => 'Muscle Cars']);
        $fantasy = Category::create(['name' => 'Fantasy']);

        // Products
        Product::create([
            'category_id' => $treasureHunts->id,
            'name' => '2026 Hot Wheels RLC x NARUTO Nissan Silvia (S15)',
            'description' => 'Ultra-exclusive Red Line Club collaboration featuring the legendary Naruto Shippuden theme on a Nissan Silvia S15.',
            'price' => 74.99,
            'stock' => 50,
            'image_url' => 'images/2026 Hot Wheels RLC x NARUTO Nissan Silvia (S15).webp',
        ]);

        Product::create([
            'category_id' => $supercars->id,
            'name' => '2026 Hot Wheels RLC Elite 64 Porsche 911 GT2 EVO 993',
            'description' => 'High-detail Elite 64 series Porsche 911 GT2 EVO with opening parts and premium paint finish.',
            'price' => 64.99,
            'stock' => 100,
            'image_url' => 'images/2026 Hot Wheels RLC Elite 64 Porsche 911 GT2 EVO 993.webp',
        ]);

        Product::create([
            'category_id' => $classics->id,
            'name' => "'95 Mazda RX-7",
            'description' => 'A pristine collector piece of the iconic FD3S Mazda RX-7 in a classic street racing setup.',
            'price' => 70.00,
            'stock' => 75,
            'image_url' => 'images/\'95 Mazda rx-7.webp',
        ]);

        Product::create([
            'category_id' => $muscleCars->id,
            'name' => 'Hot Wheels 2026 Legends Tour 1969 Ford Mustang Boss 302',
            'description' => 'Limited edition Legends Tour exclusive 1969 Ford Mustang Boss 302 with authentic racing deco.',
            'price' => 59.99,
            'stock' => 200,
            'image_url' => 'images/Hot Wheels 2026 Legends Tour 1969 Ford Mustang Boss 302.webp',
        ]);
    }
}
