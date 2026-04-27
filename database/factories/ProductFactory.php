<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['name' => fake()->word(), 'casting_name' => fake()->word(), 'slug' => fake()->slug(), 'price' => 10.00, 'stock_quantity' => 10, 'brand_id' => \App\Models\Brand::factory(), 'scale_id' => \App\Models\Scale::factory(), 'is_active' => true,
            //
        ];
    }
}
