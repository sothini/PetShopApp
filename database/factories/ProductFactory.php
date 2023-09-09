<?php

namespace Database\Factories;

use App\Models\Category;
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
            'uuid' => $this->faker->uuid,
            'category_uuid' => Category::inRandomOrder()->first()->uuid,
            'title' => $this->faker->sentence(3),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->paragraph,
            'metadata' => json_encode( [
                'brand' => $this->faker->uuid,
                'image' => $this->faker->uuid,
            ]),
        ];
    }
}
