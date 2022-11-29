<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'description' => $this->faker->words(5, true),
            'price_id' => $this->faker->numberBetween(1, 10),
            'productlist_id' => $this->faker->numberBetween(1, 10),
            'product_number' => $this->faker->numberBetween(1, 20),
            'is_sold' => false,
        ];
    }
}