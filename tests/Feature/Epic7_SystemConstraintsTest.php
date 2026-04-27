<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Scale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Epic7_SystemConstraintsTest extends TestCase
{
    use RefreshDatabase;

    // AC 11.2: Protect against SQL injection attacks
    public function test_sql_injection_protection_in_search()
    {
        $brand = Brand::factory()->create();
        $scale = Scale::factory()->create();

        Product::factory()->create([
            'name' => 'Safe Product',
            'brand_id' => $brand->id,
            'scale_id' => $scale->id,
            'is_active' => true
        ]);

        // Attempt SQL injection in search query
        $maliciousQuery = "Safe Product' OR '1'='1";
        
        $response = $this->getJson('/api/v1/products?search=' . urlencode($maliciousQuery));
        
        $response->assertStatus(200);
        $data = $response->json('data');
        
        // Should not return all products (or crash), it should safely search for the literal string
        // Since there is no product literally named "Safe Product' OR '1'='1", it should return 0 results
        $this->assertCount(0, $data);
    }
}
