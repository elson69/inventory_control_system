<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics',       'description' => 'Electronic devices and components'],
            ['name' => 'Office Supplies',   'description' => 'Stationery, paper, and desk accessories'],
            ['name' => 'Furniture',         'description' => 'Desks, chairs, shelving, and storage units'],
            ['name' => 'Consumables',       'description' => 'Items that get used up regularly'],
            ['name' => 'Tools',             'description' => 'Hand tools and power tools'],
            ['name' => 'Safety Equipment',  'description' => 'PPE and workplace safety items'],
            ['name' => 'Cleaning Supplies', 'description' => 'Janitorial and sanitation products'],
            ['name' => 'Networking',        'description' => 'Cables, routers, switches, and accessories'],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                ]
            );
        }
    }
}
