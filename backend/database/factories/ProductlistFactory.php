<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Productlist>
 */
class ProductlistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'edition_id' => $this->faker->numberBetween(1, 10),
            'user_id' => $this->faker->numberBetween(1, 10),
            // List number should be unique consisting of 3 digits
            'list_number' => $this->faker->unique()->numberBetween(100, 999),
            // member_number should be 30% empty
            'member_number' => rand(1, 10) > 3 ? fake()->unique()->regexify('^\d{3}-\d{3}-\d{3}$') : null,
            'is_user_confirmed' => false,
            'is_employee_validated' => false,
        ];
    }
}