<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $qty = $this->faker->numberBetween(1, 20);
        $price = $this->faker->numberBetween(10000, 500000);

        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'qty' => $qty,
            'total_price' => $qty * $price,
            'status' => $this->faker->randomElement(['pending', 'success', 'failed']),
            'notes' => $this->faker->optional(0.5)->sentence(),
        ];
    }
}
