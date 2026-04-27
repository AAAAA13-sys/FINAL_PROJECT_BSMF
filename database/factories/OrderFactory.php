<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['order_number' => 'ORD-'.\Illuminate\Support\Str::random(10), 'user_id' => \App\Models\User::factory(), 'status' => 'pending', 'subtotal' => 10, 'total_amount' => 10, 'customer_name' => 'John', 'customer_email' => 'test@test.com', 'shipping_address' => '123 Test', 'payment_method' => 'credit_card',
            //
        ];
    }
}
