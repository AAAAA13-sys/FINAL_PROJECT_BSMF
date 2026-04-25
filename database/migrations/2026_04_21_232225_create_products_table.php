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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('series_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            
            // Car specific
            $table->string('name', 200);
            $table->string('casting_name', 200);
            $table->string('slug', 220)->unique();
            $table->integer('year')->nullable();
            $table->string('color', 50)->nullable();
            
            // Collectibility
            $table->boolean('is_treasure_hunt')->default(false);
            $table->boolean('is_super_treasure_hunt')->default(false);
            $table->boolean('is_chase')->default(false);
            $table->boolean('is_rlc_exclusive')->default(false);
            
            // Physical details
            $table->string('card_condition', 20)->default('mint');
            $table->boolean('is_carded')->default(true);
            $table->boolean('is_loose')->default(false);
            $table->boolean('is_graded')->default(false);
            $table->decimal('grade_score', 3, 1)->nullable();
            
            // Pricing & stock
            $table->decimal('price', 12, 2);
            $table->decimal('original_price', 12, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            
            // Media
            $table->string('main_image')->nullable();
            $table->string('card_image')->nullable();
            $table->string('loose_image')->nullable();
            $table->json('additional_images')->nullable();
            
            // Description
            $table->string('short_description', 500)->nullable();
            $table->longText('description')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_pre_order')->default(false);
            $table->date('expected_release_date')->nullable();
            
            // Metadata
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
