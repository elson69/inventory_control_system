<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'action' => $this->faker->randomElement(['restock', 'deduction', 'adjustment']),
            'quantity_changed' => $this->faker->numberBetween(-50, 100),
            'balance_after' => $this->faker->numberBetween(0, 200),
            'remarks' => $this->faker->optional(0.6)->sentence(),
        ];
    }
}
