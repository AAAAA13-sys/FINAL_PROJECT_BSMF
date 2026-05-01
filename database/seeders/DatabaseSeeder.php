<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductImage;
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
        // 1. Users are now seeded directly in 1_users_table migration for stability.


        // 2. Brands
        $hw = Brand::create([
            'name' => 'Hot Wheels',
            'slug' => 'hot-wheels',
            'description' => 'Mattel Premium Die-Cast',
        ]);

        // 3. Scales
        $scale64 = Scale::create([
            'name' => '1:64',
            'sort_order' => 0,
        ]);

        // 4. Series
        $sthSeries = Series::create([
            'brand_id' => $hw->id,
            'name' => 'Super Treasure Hunt',
            'slug' => 'super-treasure-hunt',
            'is_premium' => false,
        ]);

        $premiumSeries = Series::create([
            'brand_id' => $hw->id,
            'name' => 'Premium',
            'slug' => 'premium',
            'is_premium' => true,
        ]);

        // 5. Products

        // --- SUBARU ---
        $subaru = Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => $sthSeries->id,
            'name' => 'STH Subaru Impreza STI',
            'casting_name' => 'Subaru Impreza STI',
            'slug' => 'subaru-impreza-sti',
            'year' => 2026,
            'is_super_treasure_hunt' => true,
            'price' => 1999.00,
            'stock_quantity' => 5,
            'is_carded' => false,
            'is_loose' => true,
            'card_condition' => 'Loose Pack',
            'main_image' => 'images/products/subaru-impreza-sti/main.jpg',
            'description' => "The super treasure hunt Subaru Impreza STI was recently released by hotwheels on the newly released J case 2026. The STH have the traditional Subaru Rally Livery in a spectraflame blue and a golden yellow tires.\n\nFun Fact: STH (Super Treasure Hunts) have rubber tires. There is only one exception on this which is the Custom otto STH.\n\nCondition: Loose Pack",
        ]);

        for ($i = 1; $i <= 4; $i++) {
            ProductImage::create([
                'product_id' => $subaru->id,
                'image_path' => "images/products/subaru-impreza-sti/gallery-$i.jpg",
                'type' => 'gallery',
            ]);
        }

        // --- CRUELLA DE VILL ---
        $cruella = Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => $sthSeries->id,
            'name' => 'STH Cruella De Vill',
            'casting_name' => 'Cruella De Vill',
            'slug' => 'cruella-de-vill',
            'is_super_treasure_hunt' => true,
            'price' => 999.00,
            'stock_quantity' => 5,
            'is_carded' => false,
            'is_loose' => true,
            'card_condition' => 'Loose Pack',
            'main_image' => 'images/products/cruella-de-vill/main.jpg',
            'description' => "The Super Treasure Hunt Cruella De Vill was one of the oldest STH released by hotwheels. It is a disney collaboration exclusive which makes it very sought after and hard to find nowadays.\n\nFun Fact: STH (Super Treasure Hunts) Have a \"TH\" logo on the car. This separates it from the normal/basic version.\n\nCondition: Loose Pack",
        ]);

        for ($i = 1; $i <= 4; $i++) {
            ProductImage::create([
                'product_id' => $cruella->id,
                'image_path' => "images/products/cruella-de-vill/gallery-$i.jpg",
                'type' => 'gallery',
            ]);
        }

        // --- DODGE CHARGER ---
        $dodge = Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => $sthSeries->id,
            'name' => "STH '20 Dodge Charger Hellcat",
            'casting_name' => "'20 Dodge Charger Hellcat",
            'slug' => 'dodge-charger-hellcat',
            'year' => 2026,
            'is_super_treasure_hunt' => true,
            'price' => 1499.00,
            'stock_quantity' => 5,
            'is_carded' => false,
            'is_loose' => true,
            'card_condition' => 'Loose Pack',
            'main_image' => 'images/products/dodge-charger-hellcat/main.jpg',
            'description' => "The Super Treasure Hunt '20 Dodge Charger Was released as the STH of the 2026 P case of hotwheels. It is a spectraflame pink with a clean finish on the decals. One of the most sought after and most expensive STH of the 2025 Batch\n\nFun fact: STH (Super Treasure Hunts) Are Painted in spectraflame and are very sought after by the collectors.\n\nCondition: Loose Pack",
        ]);

        for ($i = 1; $i <= 4; $i++) {
            ProductImage::create([
                'product_id' => $dodge->id,
                'image_path' => "images/products/dodge-charger-hellcat/gallery-$i.jpg",
                'type' => 'gallery',
            ]);
        }

        // --- MCLAREN 720S ---
        $mclaren = Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => $premiumSeries->id,
            'name' => 'Hotwheels Premium LBWK McLaren 720s',
            'casting_name' => 'McLaren 720s',
            'slug' => 'mclaren-720s',
            'price' => 399.00,
            'stock_quantity' => 5,
            'is_carded' => false,
            'is_loose' => true,
            'card_condition' => 'Loose Pack',
            'main_image' => 'images/products/mclaren-720s/main.jpg',
            'description' => "The Premium Hotwheels LBWK (Liberty walk) McLaren 720s was one of the most recent releases of hotwheels premium from the \"silhouette\" set. This model contains the usual aggressive body kits of LBWK with the deep rims.\n\nFun Fact: Premium Hotwheels have rubber tires. They are called \"Premium\" for a reason. It is much expensive compared to the 129 Pesos we see on the mall.\n\nCondition: Loose Pack",
        ]);

        for ($i = 1; $i <= 4; $i++) {
            ProductImage::create([
                'product_id' => $mclaren->id,
                'image_path' => "images/products/mclaren-720s/gallery-$i.jpg",
                'type' => 'gallery',
            ]);
        }

        // --- NISSAN SILVIA ---
        $nissan = Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => $premiumSeries->id,
            'name' => 'Hotwheels Premium Forza Nissan Silvia S15',
            'casting_name' => 'Nissan Silvia S15',
            'slug' => 'nissan-silvia-s15',
            'price' => 799.00,
            'stock_quantity' => 5,
            'is_carded' => false,
            'is_loose' => true,
            'card_condition' => 'Loose Pack',
            'main_image' => 'images/products/nissan-silvia-s15/main.jpg',
            'description' => "The Premium Forza Nissan Silvia S15 was released in a box set and individual blister packaging. It is one of the hardest to find forza cars and it is much expensive compared to other premium cars due to it's demand. This Premium consist of the Forza livery in sea blue with a yellow 6 spoke wheels.\n\nFun Fact: Premium Hotwheels have metal base making it much heavier than normal hotwheels.\n\nCondition: Loose Pack",
        ]);

        for ($i = 1; $i <= 4; $i++) {
            ProductImage::create([
                'product_id' => $nissan->id,
                'image_path' => "images/products/nissan-silvia-s15/gallery-$i.jpg",
                'type' => 'gallery',
            ]);
        }

        // 6. Coupons
        // Note: WELCOME10 coupon is seeded in migration 2026_04_30_114048_seed_welcome_coupon.php
        // No need to create it here to avoid duplicates
    }
}
