<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Electronics', 'Office Supplies', 'Furniture', 'Consumables',
            'Tools', 'Safety Equipment', 'Cleaning Supplies', 'Storage',
            'Networking', 'Accessories',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(),
        ];
    }
}
