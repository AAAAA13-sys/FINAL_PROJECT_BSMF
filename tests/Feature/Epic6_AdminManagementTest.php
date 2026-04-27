<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Brand;
use App\Models\Scale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Epic6_AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $brand;
    protected $scale;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->brand = Brand::factory()->create();
        $this->scale = Scale::factory()->create();
    }

    // AC 9.1: Admin can view all customer orders
    public function test_admin_can_view_all_orders()
    {
        $order = Order::factory()->create();
        
        $response = $this->actingAs($this->admin)->get('/admin/orders');
        
        $response->assertStatus(200)
                 ->assertSee($order->order_number);
    }

    // AC 9.2: Admin can update order status
    public function test_admin_can_update_order_status()
    {
        $order = Order::factory()->create(['status' => 'pending']);
        
        $response = $this->actingAs($this->admin)->post("/admin/orders/{$order->id}/status", [
            'status' => 'out_for_delivery'
        ]);
        
        $response->assertRedirect()
                 ->assertSessionHas('success');
                 
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'out_for_delivery'
        ]);
    }

    // AC 10.1: Admin can add new products
    public function test_admin_can_add_product()
    {
        $response = $this->actingAs($this->admin)->post('/admin/products', [
            'name' => 'New Awesome Car',
            'casting_name' => 'Awesome Casting',
            'brand_id' => $this->brand->id,
            'scale_id' => $this->scale->id,
            'price' => 29.99,
            'stock_quantity' => 100,
            'is_active' => 1
        ]);
        
        $response->assertRedirect()
                 ->assertSessionHas('success');
                 
        $this->assertDatabaseHas('products', [
            'name' => 'New Awesome Car',
            'price' => 29.99,
            'stock_quantity' => 100
        ]);
    }

    // AC 10.2: Admin can edit product details
    public function test_admin_can_edit_product()
    {
        $product = Product::factory()->create([
            'brand_id' => $this->brand->id,
            'scale_id' => $this->scale->id,
            'price' => 10.00
        ]);
        
        $response = $this->actingAs($this->admin)->put("/admin/products/{$product->id}", [
            'name' => 'Updated Car Name',
            'casting_name' => $product->casting_name,
            'brand_id' => $this->brand->id,
            'scale_id' => $this->scale->id,
            'price' => 15.00,
            'stock_quantity' => $product->stock_quantity,
            'is_active' => 1
        ]);
        
        $response->assertRedirect()
                 ->assertSessionHas('success');
                 
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Car Name',
            'price' => 15.00
        ]);
    }

    // AC 10.3: Admin can delete products
    public function test_admin_can_delete_product()
    {
        $product = Product::factory()->create([
            'brand_id' => $this->brand->id,
            'scale_id' => $this->scale->id,
        ]);
        
        $response = $this->actingAs($this->admin)->delete("/admin/products/{$product->id}");
        
        $response->assertRedirect()
                 ->assertSessionHas('success');
                 
        $this->assertDatabaseMissing('products', [
            'id' => $product->id
        ]);
    }
}
