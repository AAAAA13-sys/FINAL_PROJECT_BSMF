<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Brand;
use App\Models\Scale;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Epic3_ShoppingCartTest extends TestCase
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
            'price' => 10.00,
            'stock_quantity' => 50,
            'brand_id' => $brand->id,
            'scale_id' => $scale->id,
            'is_active' => true,
        ]);
    }

    // AC 5.1: Users can add products to the cart when logged in
    public function test_user_can_add_to_cart()
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/cart', [
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);
    }

    // AC 5.2: Cart allows quantity updates
    public function test_cart_allows_quantity_updates()
    {
        // Add initially
        $this->actingAs($this->user)->postJson('/api/v1/cart', [
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        $cart = Cart::where('user_id', $this->user->id)->first();
        $cartItem = $cart->items()->first();

        // Update quantity
        $response = $this->putJson("/api/v1/cart/{$cartItem->id}", [
            'quantity' => 5
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 5
        ]);
    }

    // AC 5.3: Cart allows item removal
    public function test_cart_allows_item_removal()
    {
        $this->actingAs($this->user)->postJson('/api/v1/cart', [
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        $cart = Cart::where('user_id', $this->user->id)->first();
        $cartItem = $cart->items()->first();

        $response = $this->deleteJson("/api/v1/cart/{$cartItem->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id
        ]);
    }

    // AC 5.4: Calculate subtotal per item and total amount
    public function test_cart_calculates_totals()
    {
        $this->actingAs($this->user)->postJson('/api/v1/cart', [
            'product_id' => $this->product->id,
            'quantity' => 3
        ]);

        $response = $this->getJson('/api/v1/cart');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'items' => [
                             '*' => ['subtotal']
                         ],
                         'total_price'
                     ]
                 ]);
                 
        $data = $response->json('data');
        
        // Product is $10, qty 3 = $30
        $this->assertEquals(30.00, $data['total_price']);
        $this->assertEquals(30.00, $data['items'][0]['subtotal']);
    }
}
