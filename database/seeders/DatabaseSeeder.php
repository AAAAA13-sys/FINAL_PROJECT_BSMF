<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Scale;
use App\Models\Series;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'BSMF Admin',
            'username' => 'bs_garage_admin',
            'email' => 'admin@bsmfgarage.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Sample Customer
        User::create([
            'name' => 'John Collector',
            'username' => 'johndiecast',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Brands
        $brands = [
            'Hot Wheels' => 'Mattel',
            'Matchbox' => 'Mattel',
        ];

        foreach ($brands as $name => $desc) {
            Brand::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $desc,
            ]);
        }

        // Scales
        $scales = ['1:64', '1:43', '1:24', '1:18'];
        foreach ($scales as $index => $name) {
            Scale::create([
                'name' => $name,
                'sort_order' => $index,
            ]);
        }


        // Series (Hot Wheels)
        $hw = Brand::where('name', 'Hot Wheels')->first();
        $mb = Brand::where('name', 'Matchbox')->first();
        
        $seriesData = [
            ['name' => 'Mainline', 'is_premium' => false],
            ['name' => 'Treasure Hunt', 'is_premium' => false],
            ['name' => 'Super Treasure Hunt', 'is_premium' => false],
            ['name' => 'Car Culture', 'is_premium' => true],
            ['name' => 'Boulevard', 'is_premium' => true],
            ['name' => 'Fast & Furious', 'is_premium' => true],
            ['name' => 'RLC', 'is_premium' => true],
        ];

        foreach ($seriesData as $data) {
            Series::create([
                'brand_id' => $hw->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'is_premium' => $data['is_premium'],
            ]);
        }

        // Series (Matchbox)
        $mbSeries = [
            ['name' => 'Basic', 'is_premium' => false],
            ['name' => 'Moving Parts', 'is_premium' => true],
            ['name' => 'Collectors', 'is_premium' => true],
        ];

        foreach ($mbSeries as $data) {
            Series::create([
                'brand_id' => $mb->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'is_premium' => $data['is_premium'],
            ]);
        }

        // Sample Products Setup
        $scale64 = Scale::where('name', '1:64')->first();

        // 1. Hot Wheels '67 Camaro - Gold Chrome (STH)
        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'Super Treasure Hunt')->first()->id,
            'name' => "'67 Camaro - Gold Chrome",
            'casting_name' => "'67 Chevrolet Camaro",
            'slug' => Str::slug("hw-67-camaro-gold-chrome-sth"),
            'year' => 2025,
            'color' => 'Gold Chrome',
            'is_super_treasure_hunt' => true,
            'price' => 149.99,
            'stock_quantity' => 3,
            'is_carded' => true,
            'description' => "Extremely rare 2025 Super Treasure Hunt variant with Spectraflame gold finish and Real Riders wheels.",
        ]);

        // 2. Hot Wheels Nissan Skyline GT-R R34 - Blue (Fast & Furious)
        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'Fast & Furious')->first()->id,
            'name' => "Nissan Skyline GT-R R34 - Blue",
            'casting_name' => "Nissan Skyline GT-R R34",
            'slug' => Str::slug("hw-nissan-skyline-gtr-r34-blue-ff"),
            'year' => 2024,
            'color' => 'Bayside Blue',
            'price' => 12.99,
            'stock_quantity' => 45,
            'description' => "Premium Fast & Furious series casting with metal/metal body and rubber tires.",
        ]);

        // 3. Matchbox 2023 Nissan Z
        Product::create([
            'brand_id' => $mb->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'Moving Parts')->first()->id,
            'name' => "2023 Nissan Z - Ikazuchi Yellow",
            'casting_name' => "2023 Nissan Z",
            'slug' => Str::slug("mb-2023-nissan-z-yellow"),
            'price' => 6.99,
            'stock_quantity' => 28,
            'description' => "Matchbox Moving Parts series with opening hood and detailed engine bay.",
        ]);

        // 4. Hot Wheels Honda Civic EK9
        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'Boulevard')->first()->id,
            'name' => "Honda Civic Type R [EK9] - Yellow",
            'casting_name' => "Honda Civic Type R EK9",
            'slug' => Str::slug("hw-honda-civic-ek9-yellow-boulevard"),
            'price' => 15.99,
            'stock_quantity' => 12,
            'description' => "Highly sought after Boulevard Series casting with Real Riders.",
        ]);

        // 5. Hot Wheels Toyota Supra - White (Car Culture)
        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'Car Culture')->first()->id,
            'name' => "Toyota Supra JZA80 - Modern Classics",
            'casting_name' => "Toyota Supra",
            'slug' => Str::slug("hw-toyota-supra-white-cc"),
            'year' => 2024,
            'color' => 'Super White',
            'price' => 8.99,
            'stock_quantity' => 20,
            'description' => "Modern Classics Series 4 casting. A must-have for JDM fans.",
        ]);

        // 6. Matchbox Mercedes-Benz G-Class
        Product::create([
            'brand_id' => $mb->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'Moving Parts')->first()->id,
            'name' => "Mercedes-Benz G 63 AMG - Silver",
            'casting_name' => "Mercedes-Benz G-Class",
            'slug' => Str::slug("mb-mercedes-g63-amg-silver"),
            'price' => 5.49,
            'stock_quantity' => 15,
            'description' => "Matchbox Moving Parts series with opening doors and detailed interior.",
        ]);

        // 7. Matchbox 1964 Chevy C10 Pickup
        Product::create([
            'brand_id' => $mb->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'Collectors')->first()->id,
            'name' => "1964 Chevy C10 Pickup - Red",
            'casting_name' => "1964 Chevrolet C10",
            'slug' => Str::slug("mb-1964-chevy-c10-red"),
            'price' => 16.99,
            'stock_quantity' => 10,
            'description' => "Matchbox Collectors series with premium packaging and rubber tires.",
        ]);

        // 8. Hot Wheels Mazda RX-7 FD
        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'RLC')->first()->id,
            'name' => "Mazda RX-7 FD - Spectraflame Orange",
            'casting_name' => "Mazda RX-7",
            'slug' => Str::slug("hw-rlc-mazda-rx7-orange"),
            'price' => 75.00,
            'stock_quantity' => 5,
            'description' => "RLC Exclusive with opening hood and premium detail.",
        ]);

        // 9. Hot Wheels Lamborghini Countach (RLC)
        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'RLC')->first()->id,
            'name' => "Lamborghini Countach LP500 S - Spectraflame Blue",
            'casting_name' => "Lamborghini Countach",
            'slug' => Str::slug("hw-rlc-lamborghini-countach-blue"),
            'year' => 2024,
            'color' => 'Spectraflame Blue',
            'price' => 85.00,
            'stock_quantity' => 2,
            'description' => "Red Line Club Exclusive. Opening scissor doors and Spectraflame finish.",
        ]);

        // 10. Matchbox Porsche 911 Carrera 4S
        Product::create([
            'brand_id' => $mb->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'Basic')->first()->id,
            'name' => "Porsche 911 Carrera 4S - Silver",
            'casting_name' => "Porsche 911",
            'slug' => Str::slug("mb-porsche-911-silver"),
            'price' => 1.49,
            'stock_quantity' => 30,
            'description' => "Matchbox Mainline series 2024 release.",
        ]);

        // 11. Hot Wheels '71 Datsun 510 (Boulevard)
        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'Boulevard')->first()->id,
            'name' => "'71 Datsun 510 - BRE Livery",
            'casting_name' => "'71 Datsun 510",
            'slug' => Str::slug("hw-71-datsun-510-bre"),
            'year' => 2024,
            'color' => 'White/Red',
            'price' => 15.00,
            'stock_quantity' => 12,
            'description' => "Boulevard Series. Features the iconic BRE racing livery.",
        ]);

        // 12. Matchbox Toyota 4Runner
        Product::create([
            'brand_id' => $mb->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'Basic')->first()->id,
            'name' => "Toyota 4Runner - Black",
            'casting_name' => "Toyota 4Runner",
            'slug' => Str::slug("mb-toyota-4runner-black"),
            'price' => 1.49,
            'stock_quantity' => 25,
            'description' => "Matchbox Mainline series. Off-road legend.",
        ]);


        // Coupons
        Coupon::create([
            'code' => 'WELCOME10',
            'name' => 'Welcome to BSMF Garage',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'min_order_amount' => 25.00,
            'expires_at' => now()->addDays(30),
        ]);

        Coupon::create([
            'code' => 'FREESHIP64',
            'name' => 'Free Shipping on Die-Cast',
            'discount_type' => 'free_shipping',
            'min_order_amount' => 50.00,
            'expires_at' => now()->addDays(90),
        ]);
    }
}
