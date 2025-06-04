<?php

namespace Database\Factories;

use App\Models\Product;
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
    public function definition(): array
    {
        return [
            'user_id' => rand(1,10),
            'name' => fake()->name(),
            'price' => rand(10000,1000000),
            'category' => fake()->randomElement(['Kamera','Elektronik','Outdoor','Kendaraan','Furnitur','Lainnya']),
            'additional_note' => fake()->randomLetter(),
            'address_text' => fake()->address(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'description' => fake()->randomLetter(),
            'is_available' => fake()->boolean(),
        ];
    }
}
