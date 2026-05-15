<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Scale;
use App\Models\Series;
use App\Models\User;
use App\Models\Review;
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
        // Add collector users for reviews and realism
        $collector1 = User::create([
            'name' => 'John Doe',
            'username' => 'johndoe_collector',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $collector2 = User::create([
            'name' => 'Jane Smith',
            'username' => 'jane_diecast',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $collector3 = User::create([
            'name' => 'Mike Collector',
            'username' => 'mike_collector99',
            'email' => 'mike@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

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
            'name' => 'Super Treasure Hunt Subaru Impreza STI',
            'casting_name' => 'Subaru Impreza STI',
            'slug' => 'subaru-impreza-sti',
            'year' => 2026,
            'is_super_treasure_hunt' => true,
            'is_featured' => true,
            'price' => 1999.00,
            'stock_quantity' => 10,
            'is_carded' => false,
            'is_loose' => true,
            'card_condition' => 'Loose Pack',
            'main_image' => 'images/products/subaru-impreza-sti/main.jpg',
            'description' => "The Super Treasure Hunt Subaru Impreza STI was recently released by hotwheels on the newly released J case 2026. The Super Treasure Hunt have the traditional Subaru Rally Livery in a spectraflame blue and a golden yellow tires.\n\nFun Fact: Super Treasure Hunts have rubber tires. There is only one exception on this which is the Custom otto Super Treasure Hunt.\n\nCondition: Loose Pack",
        ]);

        for ($i = 1; $i <= 4; $i++) {
            ProductImage::create([
                'product_id' => $subaru->id,
                'image_path' => "images/products/subaru-impreza-sti/gallery-$i.jpg",
                'type' => 'gallery',
            ]);
        }

        Review::create(['product_id' => $subaru->id, 'user_id' => $collector1->id, 'rating' => 5, 'comment' => 'Incredible casting! The spectraflame blue is stunning and the golden wheels match the real rally car perfectly.']);
        Review::create(['product_id' => $subaru->id, 'user_id' => $collector2->id, 'rating' => 4, 'comment' => 'Beautiful car, condition was exactly as described. Dropped one star because I prefer carded, but I knew what I was buying.']);

        // --- CRUELLA DE VILL ---
        $cruella = Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => $sthSeries->id,
            'name' => 'Super Treasure Hunt Cruella De Vill',
            'casting_name' => 'Cruella De Vill',
            'slug' => 'cruella-de-vill',
            'is_super_treasure_hunt' => true,
            'is_featured' => true,
            'price' => 999.00,
            'stock_quantity' => 10,
            'is_carded' => false,
            'is_loose' => true,
            'card_condition' => 'Loose Pack',
            'main_image' => 'images/products/cruella-de-vill/main.jpg',
            'description' => "The Super Treasure Hunt Cruella De Vill was one of the oldest Super Treasure Hunt released by hotwheels. It is a disney collaboration exclusive which makes it very sought after and hard to find nowadays.\n\nFun Fact: Super Treasure Hunts Have a \"TH\" logo on the car. This separates it from the normal/basic version.\n\nCondition: Loose Pack",
        ]);

        for ($i = 1; $i <= 4; $i++) {
            ProductImage::create([
                'product_id' => $cruella->id,
                'image_path' => "images/products/cruella-de-vill/gallery-$i.jpg",
                'type' => 'gallery',
            ]);
        }
        
        Review::create(['product_id' => $cruella->id, 'user_id' => $collector3->id, 'rating' => 5, 'comment' => 'A rare classic! The details on this older Super Treasure Hunt are amazing. Happy to finally add it to my collection.']);

        // --- DODGE CHARGER ---
        $dodge = Product::create([
            'brand_id' => $hw->id,
            'scale_id' => $scale64->id,
            'series_id' => $sthSeries->id,
            'name' => "Super Treasure Hunt '20 Dodge Charger Hellcat",
            'casting_name' => "'20 Dodge Charger Hellcat",
            'slug' => 'dodge-charger-hellcat',
            'year' => 2026,
            'is_super_treasure_hunt' => true,
            'is_featured' => true,
            'price' => 1499.00,
            'stock_quantity' => 10,
            'is_carded' => false,
            'is_loose' => true,
            'card_condition' => 'Loose Pack',
            'main_image' => 'images/products/dodge-charger-hellcat/main.jpg',
            'description' => "The Super Treasure Hunt '20 Dodge Charger Was released as the Super Treasure Hunt of the 2026 P case of hotwheels. It is a spectraflame pink with a clean finish on the decals. One of the most sought after and most expensive Super Treasure Hunt of the 2025 Batch\n\nFun fact: Super Treasure Hunts Are Painted in spectraflame and are very sought after by the collectors.\n\nCondition: Loose Pack",
        ]);

        for ($i = 1; $i <= 4; $i++) {
            ProductImage::create([
                'product_id' => $dodge->id,
                'image_path' => "images/products/dodge-charger-hellcat/gallery-$i.jpg",
                'type' => 'gallery',
            ]);
        }
        
        Review::create(['product_id' => $dodge->id, 'user_id' => $collector1->id, 'rating' => 5, 'comment' => 'That spectraflame pink pops so hard! The Hellcat details are spot on. Fast shipping too!']);
        Review::create(['product_id' => $dodge->id, 'user_id' => $collector3->id, 'rating' => 5, 'comment' => 'Absolutely massive addition to my Mopar collection. Rubber tires make a huge difference.']);

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
        
        Review::create(['product_id' => $mclaren->id, 'user_id' => $collector2->id, 'rating' => 4, 'comment' => 'Great premium casting. The Liberty Walk body kit looks very aggressive. Would love to see more colors.']);

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
        
        Review::create(['product_id' => $nissan->id, 'user_id' => $collector1->id, 'rating' => 5, 'comment' => 'Been looking for the Forza S15 for months! Arrived in perfect loose condition as described. 10/10.']);
        Review::create(['product_id' => $nissan->id, 'user_id' => $collector2->id, 'rating' => 5, 'comment' => 'The sea blue paint is incredible in person. Metal base gives it nice weight.']);
        Review::create(['product_id' => $nissan->id, 'user_id' => $collector3->id, 'rating' => 4, 'comment' => 'Awesome car, glad I grabbed it before it sold out. Solid JDM piece.']);


        // 6. Orders and Order Items to back up the reviews
        $order1 = \App\Models\Order::create([
            'order_number' => 'BSG-' . date('Ymd') . '-' . str_pad((string)$collector1->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad((string)rand(1, 999), 3, '0', STR_PAD_LEFT),
            'user_id' => $collector1->id,
            'status' => 'delivered',
            'subtotal' => $subaru->price + $dodge->price + $nissan->price,
            'total_amount' => $subaru->price + $dodge->price + $nissan->price,
            'customer_name' => $collector1->name,
            'customer_email' => $collector1->email,
            'shipping_address' => '123 Collector Ave, Manila',
            'payment_method' => 'credit_card',
            'placed_at' => now()->subDays(10),
            'delivered_at' => now()->subDays(2),
        ]);
        
        $order1->items()->create(['product_id' => $subaru->id, 'product_name' => $subaru->name, 'product_brand' => $hw->name, 'product_image' => $subaru->main_image, 'quantity' => 1, 'price' => $subaru->price, 'total' => $subaru->price]);
        $order1->items()->create(['product_id' => $dodge->id, 'product_name' => $dodge->name, 'product_brand' => $hw->name, 'product_image' => $dodge->main_image, 'quantity' => 1, 'price' => $dodge->price, 'total' => $dodge->price]);
        $order1->items()->create(['product_id' => $nissan->id, 'product_name' => $nissan->name, 'product_brand' => $hw->name, 'product_image' => $nissan->main_image, 'quantity' => 1, 'price' => $nissan->price, 'total' => $nissan->price]);

        $order2 = \App\Models\Order::create([
            'order_number' => 'BSG-' . date('Ymd') . '-' . str_pad((string)$collector2->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad((string)rand(1, 999), 3, '0', STR_PAD_LEFT),
            'user_id' => $collector2->id,
            'status' => 'delivered',
            'subtotal' => $subaru->price + $mclaren->price + $nissan->price,
            'total_amount' => $subaru->price + $mclaren->price + $nissan->price,
            'customer_name' => $collector2->name,
            'customer_email' => $collector2->email,
            'shipping_address' => '456 Racer St, Cebu',
            'payment_method' => 'paypal',
            'placed_at' => now()->subDays(8),
            'delivered_at' => now()->subDays(1),
        ]);

        $order2->items()->create(['product_id' => $subaru->id, 'product_name' => $subaru->name, 'product_brand' => $hw->name, 'product_image' => $subaru->main_image, 'quantity' => 1, 'price' => $subaru->price, 'total' => $subaru->price]);
        $order2->items()->create(['product_id' => $mclaren->id, 'product_name' => $mclaren->name, 'product_brand' => $hw->name, 'product_image' => $mclaren->main_image, 'quantity' => 1, 'price' => $mclaren->price, 'total' => $mclaren->price]);
        $order2->items()->create(['product_id' => $nissan->id, 'product_name' => $nissan->name, 'product_brand' => $hw->name, 'product_image' => $nissan->main_image, 'quantity' => 1, 'price' => $nissan->price, 'total' => $nissan->price]);

        $order3 = \App\Models\Order::create([
            'order_number' => 'BSG-' . date('Ymd') . '-' . str_pad((string)$collector3->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad((string)rand(1, 999), 3, '0', STR_PAD_LEFT),
            'user_id' => $collector3->id,
            'status' => 'delivered',
            'subtotal' => $cruella->price + $dodge->price + $nissan->price,
            'total_amount' => $cruella->price + $dodge->price + $nissan->price,
            'customer_name' => $collector3->name,
            'customer_email' => $collector3->email,
            'shipping_address' => '789 Diecast Blvd, Davao',
            'payment_method' => 'bank_transfer',
            'placed_at' => now()->subDays(12),
            'delivered_at' => now()->subDays(4),
        ]);

        $order3->items()->create(['product_id' => $nissan->id, 'product_name' => $nissan->name, 'product_brand' => $hw->name, 'product_image' => $nissan->main_image, 'quantity' => 1, 'price' => $nissan->price, 'total' => $nissan->price]);

        // --- NEW IMPORTS FROM BSMF GARAGE ---
        $p8 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => 2,
            'name' => 'Hotwheels Premium RWB Porsche 930',
            'casting_name' => 'HOTWHEELS PREMIUM RWB PORSCHE 930_',
            'slug' => 'hotwheels-premium-rwb-porsche-930',
            'year' => 2025,
            'is_super_treasure_hunt' => false,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '2999.00',
            'stock_quantity' => 0,
            'main_image' => 'images/products/hotwheels-premium-rwb-porsche-930/main.jpg',
            'description' => 'First released in 2018, this specific RWB Model is the 2nd most expensive "Premium" carded of RWB. The value of this specific model has already reached 10x its value when it was first released. It is a porsche 930 in a RWB kit, which makes it sought after.' . "\n\nCondition: Mint",
        ]);
        ProductImage::create(['product_id' => $p8->id, 'image_path' => 'images/products/hotwheels-premium-rwb-porsche-930/gallery-1.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p8->id, 'image_path' => 'images/products/hotwheels-premium-rwb-porsche-930/gallery-2.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p8->id, 'image_path' => 'images/products/hotwheels-premium-rwb-porsche-930/gallery-3.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p8->id, 'image_path' => 'images/products/hotwheels-premium-rwb-porsche-930/gallery-4.jpg', 'type' => 'gallery']);

        $p9 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => null,
            'name' => 'Fast and Furious "WOF" Honda S2000 Suki',
            'casting_name' => 'HOTWHEELS FAST AND FURIOS HONDA S2000 _SUKI_',
            'slug' => 'fast-and-furious-wof-honda-s2000-suki',
            'year' => 2025,
            'is_super_treasure_hunt' => false,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '1499.00',
            'stock_quantity' => 0,
            'main_image' => 'images/products/fast-and-furious-wof-honda-s2000-suki/main.jpg',
            'description' => 'Pink is the new meta. Suki is one of the hottest things right now in the market because of its color and origin. This car was originally from the Fast and Furious movie, making the demand from the collectors high. This is just a silver series but the price is very high for a normal Silver series.' . "\n\nCondition: Mint",
        ]);

        $p10 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => null,
            'name' => 'Porsche 911 GT3 RS "ID"',
            'casting_name' => 'HOTWHEELS ID PORSCHE 911 GT3 RS',
            'slug' => 'porsche-911-gt3-rs-id',
            'year' => 2025,
            'is_super_treasure_hunt' => false,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '1999.00',
            'stock_quantity' => 0,
            'main_image' => 'images/products/porsche-911-gt3-rs-id/main.jpg',
            'description' => 'This was already discounted Years ago. Hotwheels ID are a unique set of collectible series of hotwheels, wherein you can use the actual vehicle on the hotwheels app (Already discontinued). You can only acquire this years ago, making it expensive and hard to find. It is covered in spectraflame Gold with a unique set of tires.' . "\n\nCondition: Mint",
        ]);
        ProductImage::create(['product_id' => $p10->id, 'image_path' => 'images/products/porsche-911-gt3-rs-id/gallery-1.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p10->id, 'image_path' => 'images/products/porsche-911-gt3-rs-id/gallery-2.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p10->id, 'image_path' => 'images/products/porsche-911-gt3-rs-id/gallery-3.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p10->id, 'image_path' => 'images/products/porsche-911-gt3-rs-id/gallery-4.jpg', 'type' => 'gallery']);

        $p11 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => 2,
            'name' => 'Hotwheels Premium RWB Porsche 930',
            'casting_name' => 'HOTWHEELS PREMIUM RWB PORSCHE 930_',
            'slug' => 'rwb-porsche-930',
            'year' => 2025,
            'is_super_treasure_hunt' => false,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '2999.00',
            'stock_quantity' => 0,
            'main_image' => 'images/products/rwb-porsche-930/main.jpg',
            'description' => 'First released in 2018, this specific RWB Model is the 2nd most expensive "Premium" carded of RWB. The value of this specific model has already reached 10x its value when it was first released. It is a porsche 930 in a RWB kit, which makes it sought after.' . "\n\nCondition: Mint",
        ]);
        ProductImage::create(['product_id' => $p11->id, 'image_path' => 'images/products/rwb-porsche-930/gallery-1.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p11->id, 'image_path' => 'images/products/rwb-porsche-930/gallery-2.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p11->id, 'image_path' => 'images/products/rwb-porsche-930/gallery-3.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p11->id, 'image_path' => 'images/products/rwb-porsche-930/gallery-4.jpg', 'type' => 'gallery']);

        $p12 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => null,
            'name' => 'Legends Tour Honda S2000',
            'casting_name' => 'LEGENDS TOUR HONDA S2000',
            'slug' => 'legends-tour-honda-s2000',
            'year' => 2025,
            'is_super_treasure_hunt' => false,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '1299.00',
            'stock_quantity' => 0,
            'main_image' => 'images/products/legends-tour-honda-s2000/main.jpg',
            'description' => 'The vintage AEM Livery from the STH honda S2000 is now available for the legends tour release. This variant features A rubber tires and a spectraflame violet finish similar to it\'s STH variant (A blue one)' . "\n\nCondition: Mint",
        ]);

        $p13 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => null,
            'name' => 'MSCHF x Hotwheels Collaboration "Not wheels"',
            'casting_name' => 'MSCHF X HOTWHEELS COLLAB _NOT WHEELS_',
            'slug' => 'mschf-x-hotwheels-collaboration-not-wheels',
            'year' => 2025,
            'is_super_treasure_hunt' => false,
            'is_featured' => true,
            'card_condition' => 'Mint/Complete',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '5000.00',
            'stock_quantity' => 10,
            'main_image' => 'images/products/mschf-x-hotwheels-collaboration-not-wheels/main.jpg',
            'description' => 'A collaboration with mschf is something collectors are dying for. Not wheels is an hotwheels model that seems unfinished, but it is as is. This is a very rare piece and you can only acquire one of this through Mattel\'s official website.' . "\n\nCondition: Mint/Complete",
        ]);

        $p14 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => 1,
            'name' => 'STH (Super Treasure Hunt) \'90 Acura NSX',
            'casting_name' => 'STH ACURA NSX',
            'slug' => '90-acura-nsx',
            'year' => 2025,
            'is_super_treasure_hunt' => true,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '3499.00',
            'stock_quantity' => 10,
            'main_image' => 'images/products/90-acura-nsx/main.jpg',
            'description' => 'one of the oldest JDM STH ever released. Iconic spectraflame blue with the 5 spoke chrome wheels. NSX isn\'t just a normal JDM. It was the Supercar killer of the 90\'s.' . "\n\nCondition: Mint",
        ]);

        $p15 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => 1,
            'name' => 'STH (Super Treasure Hunt) 1975 Datsun Sunny Truck (B120)',
            'casting_name' => 'STH DATSUN SUNNY TRUCK',
            'slug' => '1975-datsun-sunny-truck-b120',
            'year' => 2025,
            'is_super_treasure_hunt' => true,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '1999.00',
            'stock_quantity' => 10,
            'main_image' => 'images/products/1975-datsun-sunny-truck-b120/main.jpg',
            'description' => 'Sunnyyyyyyy... This STH was released on the D case of 2025. Spectraflame blue and Golden wheels is what makes this STH Special. It is really hard to find nowadays.' . "\n\nCondition: Mint",
        ]);

        $p16 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => 1,
            'name' => 'STH (Super Treasure Hunt) \'20 Dodge Charger Hellcat',
            'casting_name' => 'STH DODGE CHARGER HELLCAT',
            'slug' => '20-dodge-charger-hellcat',
            'year' => 2025,
            'is_super_treasure_hunt' => true,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '2999.00',
            'stock_quantity' => 10,
            'main_image' => 'images/products/20-dodge-charger-hellcat/main.jpg',
            'description' => 'Spectraflame pink Hellcat is the dream collection of many collectors nowadays. The iconic hellcat in a pink color is something else. This was released as The P case STH of 2025.' . "\n\nCondition: Mint",
        ]);

        $p17 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => 1,
            'name' => 'STH (Super Treasure Hunt) Ford RS200 Gulf',
            'casting_name' => 'STH FORD RS200 GULF',
            'slug' => 'ford-rs200-gulf',
            'year' => 2025,
            'is_super_treasure_hunt' => true,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '1499.00',
            'stock_quantity' => 10,
            'main_image' => 'images/products/ford-rs200-gulf/main.jpg',
            'description' => 'This was released as the last STH of the year 2025. It is a super treasure hunt covered in the iconic Gulf livery with orange wheels. One of the most sought after livery in any die-cast models' . "\n\nCondition: Mint",
        ]);
        ProductImage::create(['product_id' => $p17->id, 'image_path' => 'images/products/ford-rs200-gulf/gallery-1.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p17->id, 'image_path' => 'images/products/ford-rs200-gulf/gallery-2.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p17->id, 'image_path' => 'images/products/ford-rs200-gulf/gallery-3.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p17->id, 'image_path' => 'images/products/ford-rs200-gulf/gallery-4.jpg', 'type' => 'gallery']);

        $p18 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => 1,
            'name' => 'STH (Super Treasure Hunt) Glory Chaser Gulf',
            'casting_name' => 'STH GLORY CHASER GULF',
            'slug' => 'glory-chaser-gulf',
            'year' => 2025,
            'is_super_treasure_hunt' => true,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '999.00',
            'stock_quantity' => 10,
            'main_image' => 'images/products/glory-chaser-gulf/main.jpg',
            'description' => 'This Super Treasure Hunt was released years ago. It is the iconic Glory chaser in a gulf livery, making it one of the most sought after sth of it\'s year.' . "\n\nCondition: Mint",
        ]);
        ProductImage::create(['product_id' => $p18->id, 'image_path' => 'images/products/glory-chaser-gulf/gallery-1.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p18->id, 'image_path' => 'images/products/glory-chaser-gulf/gallery-2.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p18->id, 'image_path' => 'images/products/glory-chaser-gulf/gallery-3.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p18->id, 'image_path' => 'images/products/glory-chaser-gulf/gallery-4.jpg', 'type' => 'gallery']);

        $p19 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => 1,
            'name' => 'STH (Super Treasure Hunt) Mazda 787B',
            'casting_name' => 'STH MAZDA 787B',
            'slug' => 'mazda-787b',
            'year' => 2025,
            'is_super_treasure_hunt' => true,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '2499.00',
            'stock_quantity' => 10,
            'main_image' => 'images/products/mazda-787b/main.jpg',
            'description' => 'One of the most iconic le mans car ever. The 787B isn\'t just an ordinary car. It is the standard for dominance. This sth was featured on the 2024 Set with the renown livery.' . "\n\nCondition: Mint",
        ]);

        $p20 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => 1,
            'name' => 'STH (Super Treasure Hunt) \'71 Mustang Funny Car',
            'casting_name' => 'STH MUSTANG FUNNY CAR_',
            'slug' => '71-mustang-funny-car',
            'year' => 2025,
            'is_super_treasure_hunt' => true,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '1499.00',
            'stock_quantity' => 10,
            'main_image' => 'images/products/71-mustang-funny-car/main.jpg',
            'description' => 'Spectraflame red, Goodyear wheels, Dragster stance? This car is for you. The mustang funny car was a part of the 2025 STH set which makes it a recent release.' . "\n\nCondition: Mint",
        ]);

        $p21 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => null,
            'name' => 'Hotwheels "Eggclusive" Honda Civic SI',
            'casting_name' => 'WALMART EGGSCLUCIVE HONDA CIVIC SI',
            'slug' => 'hotwheels-eggclusive-honda-civic-si',
            'year' => 2025,
            'is_super_treasure_hunt' => false,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '4999.00',
            'stock_quantity' => 10,
            'main_image' => 'images/products/hotwheels-eggclusive-honda-civic-si/main.jpg',
            'description' => 'One of the grails for every Civic SI collector. This isn\'t just a normal grail, this is a hard to find grail. Eggclusive was a very exclusive release by hotwheels below 2010\'s which makes this civic si model one of the hardest to find. You can\'t just buy these nowadays. The supply is very limited and most likely, all quantities are already in the collectors hand.' . "\n\nCondition: Mint",
        ]);

        $p22 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => null,
            'name' => 'Zamac Walmart Exclusive Porsche 911 GT3 rs',
            'casting_name' => 'ZAMAC WALMART EXCLUSIVE PORSCHE 911 GT3 RS',
            'slug' => 'zamac-walmart-exclusive-porsche-911-gt3-rs',
            'year' => 2025,
            'is_super_treasure_hunt' => false,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '1999.00',
            'stock_quantity' => 10,
            'main_image' => 'images/products/zamac-walmart-exclusive-porsche-911-gt3-rs/main.jpg',
            'description' => 'GT3 rs is one of the most in demand casting of hotwheels. This being a zamac release is something special. You can only acquire this nowadays through collectors/reseller. Back when it was released, this was only exclusive at the US Walmart store. You cannot find this in the Philippines.' . "\n\nCondition: Mint",
        ]);

        $p23 = Product::create([
            'brand_id' => 1,
            'scale_id' => 1,
            'series_id' => null,
            'name' => 'Zamac Target Exclusive Porsche 934.5',
            'casting_name' => 'ZAMAC WALMART EXCLUSIVE PORSCHE 934.5',
            'slug' => 'zamac-target-exclusive-porsche-9345',
            'year' => 2025,
            'is_super_treasure_hunt' => false,
            'is_featured' => true,
            'card_condition' => 'Mint',
            'is_carded' => true,
            'is_loose' => false,
            'price' => '999.00',
            'stock_quantity' => 10,
            'main_image' => 'images/products/zamac-target-exclusive-porsche-9345/main.jpg',
            'description' => 'It is a "Target Store" Exclusive only Variant of Porsche 934.5. Zamac is one of the most sought after by collectors of hotwheels. It has "No paint livery, other than its base painting". You can only acquire this through After market stores or on the USA Target Stores.' . "\n\nCondition: Mint",
        ]);
        ProductImage::create(['product_id' => $p23->id, 'image_path' => 'images/products/zamac-target-exclusive-porsche-9345/gallery-1.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p23->id, 'image_path' => 'images/products/zamac-target-exclusive-porsche-9345/gallery-2.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p23->id, 'image_path' => 'images/products/zamac-target-exclusive-porsche-9345/gallery-3.jpg', 'type' => 'gallery']);
        ProductImage::create(['product_id' => $p23->id, 'image_path' => 'images/products/zamac-target-exclusive-porsche-9345/gallery-4.jpg', 'type' => 'gallery']);
    }
}
