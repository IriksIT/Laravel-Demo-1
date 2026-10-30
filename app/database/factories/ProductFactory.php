<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name'        => ucfirst($this->faker->words(3, true)),
            'sku'         => strtoupper($this->faker->unique()->bothify('??-#####')),
            'description' => $this->faker->paragraph(),
            'price'       => $this->faker->randomFloat(2, 4.95, 499.95),
            'stock'       => $this->faker->numberBetween(0, 250),
            'is_active'   => $this->faker->boolean(85),
        ];
    }
}
