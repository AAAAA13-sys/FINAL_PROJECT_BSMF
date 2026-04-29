<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Brands
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        // 2. Scales
        Schema::create('scales', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); // e.g. 1:64, 1:24
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 3. Series
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('slug', 170)->unique();
            $table->integer('year')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
        });


        // 5. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('series_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 200);
            $table->string('casting_name', 200);
            $table->string('slug', 220)->unique();
            $table->integer('year')->nullable();
            $table->string('color', 50)->nullable();
            $table->boolean('is_treasure_hunt')->default(false);
            $table->boolean('is_super_treasure_hunt')->default(false);
            $table->boolean('is_chase')->default(false);
            $table->boolean('is_rlc_exclusive')->default(false);
            $table->string('card_condition', 20)->default('mint');
            $table->boolean('is_carded')->default(true);
            $table->boolean('is_loose')->default(false);
            $table->decimal('price', 12, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->string('main_image')->nullable();
            $table->json('additional_images')->nullable();
            $table->longText('description')->nullable();
            $table->integer('views')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 6. Product Images
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->string('type', 50)->default('gallery');
            $table->timestamps();
        });

        // 7. Coupons
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->enum('discount_type', ['percentage', 'fixed', 'free_shipping', 'bogo'])->default('percentage');
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('min_order_amount', 12, 2)->default(0);
            $table->decimal('max_discount', 12, 2)->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('times_used')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 8. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'processing', 'out_for_delivery', 'delivered', 'cancelled', 'refunded'])->default('pending');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->string('coupon_code', 50)->nullable();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->text('shipping_address');
            $table->string('payment_method', 50);
            $table->boolean('extra_packaging_requested')->default(false);
            $table->text('notes')->nullable();
            $table->timestamp('placed_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('out_for_delivery_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        // 9. Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->string('product_brand')->nullable();
            $table->string('product_image')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('total', 12, 2);
            $table->timestamps();
        });

        // 10. Carts
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // 11. Cart Items
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('price_at_time', 12, 2)->nullable();
            $table->timestamps();
        });

        // 12. Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->boolean('is_verified_purchase')->default(false);
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('reviews');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('series');
        Schema::dropIfExists('scales');
        Schema::dropIfExists('brands');
    }
};
