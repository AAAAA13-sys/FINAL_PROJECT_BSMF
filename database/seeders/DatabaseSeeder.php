<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
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
            'Tomica' => 'Takara Tomy',
            'Mini GT' => 'TSM Model',
            'Inno64' => 'Inno Models',
            'Tarmac Works' => 'Tarmac Works',
            'Pop Race' => 'Pop Race',
            'Kaido House' => 'Kaido House',
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

        // Categories
        $categories = ['Cars', 'Track Sets', 'Display Cases'];
        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }

        // Series (Hot Wheels)
        $hw = Brand::where('name', 'Hot Wheels')->first();
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

        // Sample Products Setup
        $scale64 = Scale::where('name', '1:64')->first();
        $catCars = Category::where('name', 'Cars')->first();

        // 1. Hot Wheels '67 Camaro - Gold Chrome (STH)
        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'Super Treasure Hunt')->first()->id,
            'category_id' => $catCars->id,
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
            'category_id' => $catCars->id,
            'name' => "Nissan Skyline GT-R R34 - Blue",
            'casting_name' => "Nissan Skyline GT-R R34",
            'slug' => Str::slug("hw-nissan-skyline-gtr-r34-blue-ff"),
            'year' => 2024,
            'color' => 'Bayside Blue',
            'price' => 12.99,
            'stock_quantity' => 45,
            'description' => "Premium Fast & Furious series casting with metal/metal body and rubber tires.",
        ]);

        // 3. MiniGT Porsche 911 GT3 RS
        Product::create([
            'brand_id' => Brand::where('name', 'Mini GT')->first()->id,
            'scale_id' => $scale64->id,
            'category_id' => $catCars->id,
            'name' => "Porsche 911 GT3 RS - Miami Blue",
            'casting_name' => "Porsche 911 (991) GT3 RS",
            'slug' => Str::slug("minigt-porsche-911-gt3-rs-miami-blue"),
            'price' => 19.99,
            'stock_quantity' => 28,
            'description' => "Highly detailed Mini GT casting in the iconic Miami Blue color.",
        ]);

        // 4. Inno64 Honda Civic EK9
        Product::create([
            'brand_id' => Brand::where('name', 'Inno64')->first()->id,
            'scale_id' => $scale64->id,
            'category_id' => $catCars->id,
            'name' => "Honda Civic EK9 - Spoon Sports",
            'casting_name' => "Honda Civic Type-R EK9",
            'slug' => Str::slug("inno64-honda-civic-ek9-spoon-sports"),
            'price' => 29.99,
            'stock_quantity' => 12,
            'description' => "Limited edition Spoon Sports liveried Civic EK9 with display base and acrylic case.",
        ]);

        // 5. Hot Wheels Toyota Supra - White (Car Culture)
        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'Car Culture')->first()->id,
            'category_id' => $catCars->id,
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
            'brand_id' => Brand::where('name', 'Matchbox')->first()->id,
            'scale_id' => $scale64->id,
            'category_id' => $catCars->id,
            'name' => "Mercedes-Benz G 63 AMG - Silver",
            'casting_name' => "Mercedes-Benz G-Class",
            'slug' => Str::slug("mb-mercedes-g63-amg-silver"),
            'price' => 5.49,
            'stock_quantity' => 15,
            'description' => "Matchbox Moving Parts series with opening doors and detailed interior.",
        ]);

        // 7. MiniGT BMW M4 GT3
        Product::create([
            'brand_id' => Brand::where('name', 'Mini GT')->first()->id,
            'scale_id' => $scale64->id,
            'category_id' => $catCars->id,
            'name' => "BMW M4 GT3 #1 Presentation",
            'casting_name' => "BMW M4 GT3",
            'slug' => Str::slug("minigt-bmw-m4-gt3-presentation"),
            'price' => 16.99,
            'stock_quantity' => 10,
            'description' => "Highly aerodynamic GT3 racing model in matte black presentation livery.",
        ]);

        // 8. Tarmac Works Mitsubishi Lancer Evo V
        Product::create([
            'brand_id' => Brand::where('name', 'Tarmac Works')->first()->id,
            'scale_id' => $scale64->id,
            'category_id' => $catCars->id,
            'name' => "Mitsubishi Lancer Evolution V - Yellow",
            'casting_name' => "Mitsubishi Lancer Evo V",
            'slug' => Str::slug("tarmac-lancer-evo-v-yellow"),
            'price' => 24.99,
            'stock_quantity' => 5,
            'description' => "Global64 series. High detail, metal chassis, and correct scale wheels.",
        ]);

        // 9. Hot Wheels Lamborghini Countach (RLC)
        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'RLC')->first()->id,
            'category_id' => $catCars->id,
            'name' => "Lamborghini Countach LP500 S - Spectraflame Blue",
            'casting_name' => "Lamborghini Countach",
            'slug' => Str::slug("hw-rlc-lamborghini-countach-blue"),
            'year' => 2024,
            'color' => 'Spectraflame Blue',
            'price' => 85.00,
            'stock_quantity' => 2,
            'description' => "Red Line Club Exclusive. Opening scissor doors and Spectraflame finish.",
        ]);

        // 10. Kaido House Datsun 510 Pro Street
        Product::create([
            'brand_id' => Brand::where('name', 'Kaido House')->first()->id,
            'scale_id' => $scale64->id,
            'category_id' => $catCars->id,
            'name' => "Datsun 510 Pro Street - Hanami",
            'casting_name' => "Datsun 510",
            'slug' => Str::slug("kaido-datsun-510-hanami"),
            'price' => 26.99,
            'stock_quantity' => 8,
            'description' => "Jun Imai designed Kaido House casting with opening hood and detailed engine.",
        ]);

        // 11. Hot Wheels '71 Datsun 510 (Boulevard)
        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => Series::where('name', 'Boulevard')->first()->id,
            'category_id' => $catCars->id,
            'name' => "'71 Datsun 510 - BRE Livery",
            'casting_name' => "'71 Datsun 510",
            'slug' => Str::slug("hw-71-datsun-510-bre"),
            'year' => 2024,
            'color' => 'White/Red',
            'price' => 15.00,
            'stock_quantity' => 12,
            'description' => "Boulevard Series. Features the iconic BRE racing livery.",
        ]);

        // 12. Tomica Premium Ferrari F40
        Product::create([
            'brand_id' => Brand::where('name', 'Tomica')->first()->id,
            'scale_id' => $scale64->id,
            'category_id' => $catCars->id,
            'name' => "Ferrari F40 - Red",
            'casting_name' => "Ferrari F40",
            'slug' => Str::slug("tomica-ferrari-f40-red"),
            'price' => 14.50,
            'stock_quantity' => 10,
            'description' => "Tomica Premium series. Suspension and high detail plastic parts.",
        ]);

        // --- TRACK SETS (Category 2) ---
        $catTracks = Category::where('name', 'Track Sets')->first();
        
        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'category_id' => $catTracks->id,
            'name' => 'Ultimate T-Rex Garage',
            'casting_name' => 'Hot Wheels City Ultimate Playset',
            'slug' => Str::slug('ultimate-t-rex-garage'),
            'price' => 129.99,
            'stock_quantity' => 15,
            'description' => 'Massive multi-level garage with T-Rex robot and elevator.',
            'main_image' => '/images/products/t-rex-garage.png'
        ]);

        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'category_id' => $catTracks->id,
            'name' => 'Bowser\'s Castle Track Set',
            'casting_name' => 'Mario Kart Special Edition',
            'slug' => Str::slug('bowsers-castle-track-set'),
            'price' => 119.99,
            'stock_quantity' => 8,
            'description' => 'Interactive Bowser\'s Castle with slam launcher.',
            'main_image' => '/images/products/bowsers-castle.png'
        ]);

        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'category_id' => $catTracks->id,
            'name' => 'F1 Downhill Circuit',
            'casting_name' => 'Formula 1 Racing Series',
            'slug' => Str::slug('f1-downhill-circuit'),
            'price' => 89.99,
            'stock_quantity' => 12,
            'description' => 'Gravity-fed circuit featuring elevation changes.',
            'main_image' => '/images/products/f1-downhill.png'
        ]);

        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'category_id' => $catTracks->id,
            'name' => 'City Ultimate Garage',
            'casting_name' => 'Hot Wheels City Series',
            'slug' => Str::slug('city-ultimate-garage'),
            'price' => 120.00,
            'stock_quantity' => 5,
            'description' => '4 levels of racing with a car-eating dragon battle feature.',
            'main_image' => '/images/placeholder-car.webp'
        ]);

        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'category_id' => $catTracks->id,
            'name' => 'Mario Kart Nemesis Track Set',
            'casting_name' => 'Mario Kart Challenge',
            'slug' => Str::slug('mario-kart-nemesis'),
            'price' => 108.99,
            'stock_quantity' => 4,
            'description' => 'Case of 4 unique Mario Kart obstacles (Thwomp, Piranha, etc).',
            'main_image' => '/images/placeholder-car.webp'
        ]);

        // --- DISPLAY CASES (Category 3) ---
        $catDisplay = Category::where('name', 'Display Cases')->first();

        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'category_id' => $catDisplay->id,
            'name' => '2026 Mainline Case (72pcs)',
            'casting_name' => 'Factory Sealed A-Case',
            'slug' => Str::slug('2026-mainline-case-72'),
            'price' => 94.99,
            'stock_quantity' => 5,
            'description' => 'Unopened case of 72 standard mainline cars.',
            'main_image' => '/images/products/72-piece-case.png'
        ]);

        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'category_id' => $catDisplay->id,
            'name' => 'Japan Historics 5 Set',
            'casting_name' => 'Car Culture Premium Set',
            'slug' => Str::slug('japan-historics-5-set'),
            'price' => 34.99,
            'stock_quantity' => 20,
            'description' => 'Premium set of 5 Japanese historic racing cars.',
            'main_image' => '/images/products/japan-historics-5.png'
        ]);

        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'category_id' => $catDisplay->id,
            'name' => 'Vintage Racing Premium Set',
            'casting_name' => 'Car Culture Vintage',
            'slug' => Str::slug('vintage-racing-premium'),
            'price' => 24.99,
            'stock_quantity' => 15,
            'description' => 'Metal/metal bodies with Real Riders tires (Ferrari 250 GTO included).',
            'main_image' => '/images/placeholder-car.webp'
        ]);

        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'category_id' => $catDisplay->id,
            'name' => 'Team Transport Case',
            'casting_name' => 'Car Culture Transporters',
            'slug' => Str::slug('team-transport-case'),
            'price' => 44.99,
            'stock_quantity' => 6,
            'description' => 'Premium 4-piece set including car and hauler pairs.',
            'main_image' => '/images/placeholder-car.webp'
        ]);

        Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'category_id' => $catDisplay->id,
            'name' => 'F1 Premium 2026 Assortment',
            'casting_name' => 'Formula 1 Premium',
            'slug' => Str::slug('f1-premium-2026'),
            'price' => 49.99,
            'stock_quantity' => 10,
            'description' => 'Premium single cars representing all 20 drivers with special plinth.',
            'main_image' => '/images/placeholder-car.webp'
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
