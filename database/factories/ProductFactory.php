<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->title(),
            'price' => $this->faker->numberBetween($min = 15, $max = 60),
            'description' => $this->faker->paragraph,
            'photo' => $this->faker->imageUrl($width = 500, $height = 500),
        ];
    }
}
