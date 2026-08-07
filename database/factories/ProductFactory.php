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
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->numberBetween(10000, 500000),
            'stock' => $this->faker->numberBetween(10, 100),
            'image' => null,
            'material_source' => $this->faker->country(),
            'eco_rating' => $this->faker->numberBetween(1, 5),
            'whatsapp_number' => '+62' . $this->faker->numberBetween(800000000, 899999999),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
