<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Special>
 */
class SpecialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'order_id' => fake()->numberBetween(1, 100),
        'name' => fake()->words(2, true),
        'size' => fake()->randomElement(['S', 'M', 'L', 'XL','XXl']),
        'color' => fake()->randomElement(['red', 'blue', 'black', 'white','green']),
        'quantity' => fake()->numberBetween(1, 100),
        'discount' => 0,
        'price' => fake()->randomFloat(2, 10, 500),
        'image' => fake()->imageUrl(640, 480, ),
        'status' => 1,
        ];
    }
}

