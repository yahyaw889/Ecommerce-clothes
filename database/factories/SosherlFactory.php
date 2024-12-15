<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sosherl>
 */
class SosherlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
   {
    return [
        'name' => fake()->name,
        'phone' => fake()->phoneNumber,
        'email' => fake()->email,
        'price_forward' => 200,
        'price_back' => 50,
        'sosherl' => fake()->url,
        'disc' => implode(' ', fake()->sentences(3)),
        ];
    }
}
