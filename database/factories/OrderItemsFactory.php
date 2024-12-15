<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItems>
 */
class OrderItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'order_id'=>Order::inRandomOrder()->first()->id,
             'product_id'=>Product::inRandomOrder()->first()->id,
             'color'=>fake()->colorName,
             'unit_amount' => fake()->randomFloat(2, 10, 1000),  
             'total_amount' => fake()->randomFloat(2, 20, 2000),  
              'quantity'=>1,
        ];
    }
}
