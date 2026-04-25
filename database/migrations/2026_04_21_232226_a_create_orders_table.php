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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Status
            $table->enum('status', ['pending', 'processing', 'out_for_delivery', 'delivered', 'cancelled', 'refunded'])->default('pending');
            $table->string('tracking_status', 50)->default('Order Placed');
            $table->string('tracking_number', 100)->nullable();
            $table->string('tracking_url', 500)->nullable();
            
            // Financials
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->string('coupon_code', 50)->nullable();
            $table->decimal('coupon_discount', 12, 2)->default(0);
            
            // Customer info
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 20)->nullable();
            $table->text('shipping_address');
            $table->string('shipping_method', 50)->default('standard');
            $table->string('payment_method', 50);
            $table->boolean('payment_simulated')->default(true);
            
            // Collector preferences
            $table->boolean('extra_packaging_requested')->default(false);
            $table->boolean('gift_wrapping')->default(false);
            $table->text('notes')->nullable();
            
            // Timestamps
            $table->timestamp('placed_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('out_for_delivery_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
