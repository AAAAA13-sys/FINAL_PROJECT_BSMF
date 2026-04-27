<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Brand;
use App\Models\Scale;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Epic4_CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        
        $brand = Brand::factory()->create();
        $scale = Scale::factory()->create();
        
        $this->product = Product::factory()->create([
            'price' => 25.00,
            'stock_quantity' => 10,
            'brand_id' => $brand->id,
            'scale_id' => $scale->id,
            'is_active' => true,
        ]);
        
        // Add to cart before checkout tests
        $this->actingAs($this->user)->postJson('/api/v1/cart', [
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);
    }

    // AC 6.1: Checkout form requires shipping address and payment method
    public function test_checkout_requires_shipping_and_payment()
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/orders', []);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['shipping_address', 'payment_method']);
    }

    // AC 6.2, 6.3, 6.4, 6.5: Checkout creates order, confirms, reduces stock, visible to admin
    public function test_checkout_creates_order_and_reduces_stock()
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/orders', [
            'shipping_address' => '123 Test St, NY',
            'payment_method' => 'credit_card',
            'notes' => 'Leave at door'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['data' => ['id', 'order_number', 'status']]);
                 
        $orderId = $response->json('data.id');
        $orderNumber = $response->json('data.order_number');
        
        // AC 6.3: Order record created
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        // AC 6.4: Reduce product stock
        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'stock_quantity' => 8 // 10 - 2
        ]);
        
        // AC 6.5: Generate order ID and make it visible to admin
        $this->assertNotEmpty($orderNumber);
        
        $admin = User::factory()->create(['is_admin' => true]);
        $adminResponse = $this->actingAs($admin)->get('/admin/orders');
        
        $adminResponse->assertStatus(200)
                      ->assertSee($orderNumber);
    }
}
