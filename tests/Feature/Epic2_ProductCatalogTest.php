<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Scale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Epic2_ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $brand = Brand::factory()->create();
        $scale = Scale::factory()->create();

        Product::factory()->create([
            'name' => 'Active Product 1',
            'casting_name' => 'Casting 1',
            'price' => 19.99,
            'is_active' => true,
            'brand_id' => $brand->id,
            'scale_id' => $scale->id,
            'slug' => 'active-product-1'
        ]);

        Product::factory()->create([
            'name' => 'Inactive Product 2',
            'casting_name' => 'Casting 2',
            'price' => 25.00,
            'is_active' => false,
            'brand_id' => $brand->id,
            'scale_id' => $scale->id,
            'slug' => 'inactive-product-2'
        ]);
        
        Product::factory()->create([
            'name' => 'Red Mustang',
            'casting_name' => 'Mustang',
            'price' => 15.00,
            'is_active' => true,
            'brand_id' => $brand->id,
            'scale_id' => $scale->id,
            'slug' => 'red-mustang'
        ]);
    }

    // AC 3.1, 3.2: Each product displays image, name, price
    public function test_products_display_correct_info()
    {
        $response = $this->getJson('/api/v1/products');
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'price', 'main_image']
                     ]
                 ]);
    }

    // AC 3.3: Only active products displayed
    public function test_only_active_products_displayed()
    {
        $response = $this->getJson('/api/v1/products');
        
        $data = $response->json('data');
        $this->assertCount(2, $data); // Active Product 1 and Red Mustang
        
        $names = array_column($data, 'name');
        $this->assertContains('Active Product 1', $names);
        $this->assertNotContains('Inactive Product 2', $names);
    }

    // AC 4.1: Search bar filters products by name
    public function test_search_products_by_name()
    {
        $response = $this->getJson('/api/v1/products?search=Mustang');
        
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Red Mustang', $data[0]['name']);
    }

    // AC 4.2, 4.3, 4.4: Search suggestions
    public function test_search_suggestions()
    {
        $response = $this->getJson('/api/v1/search-suggestions?q=Mus');
        
        $response->assertStatus(200);
        $data = $response->json(); // Returns flat array, not 'data' wrapper
        $this->assertCount(1, $data);
        $this->assertEquals('Red Mustang', $data[0]['name']);
    }

    // AC 4.5: No results found
    public function test_search_no_results()
    {
        $response = $this->getJson('/api/v1/search-suggestions?q=NonExistent');
        
        $response->assertStatus(200);
        $this->assertCount(0, $response->json());
    }
}
