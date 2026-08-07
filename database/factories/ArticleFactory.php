<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $this->faker->sentence(20),
            'content' => $this->faker->paragraphs(5, true),
            'featured_image' => null,
            'author' => $this->faker->name(),
            'views' => $this->faker->numberBetween(0, 1000),
            'is_published' => $this->faker->boolean(70),
            'published_at' => $this->faker->boolean(70) ? $this->faker->dateTime() : null,
        ];
    }
}
