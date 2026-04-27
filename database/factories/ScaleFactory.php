<?php

namespace Database\Factories;

use App\Models\Scale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Scale>
 */
class ScaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['name' => '1:64',
            //
        ];
    }
}
