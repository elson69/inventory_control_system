<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = Supplier::all();
        $categories = Category::all();

        if ($suppliers->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('No suppliers or categories found. Run SupplierSeeder and CategorySeeder first.');
            return;
        }

        $products = [
            // Electronics
            ['name' => 'Wireless Keyboard', 'sku' => 'SKU-1001-EL', 'price' => 1500, 'stock' => 45, 'min' => 10, 'cat' => 'Electronics'],
            ['name' => 'USB-C Hub 7-port', 'sku' => 'SKU-1002-EL', 'price' => 2200, 'stock' => 30, 'min' => 10, 'cat' => 'Electronics'],
            ['name' => '27" LED Monitor', 'sku' => 'SKU-1003-EL', 'price' => 12500, 'stock' => 8, 'min' => 5, 'cat' => 'Electronics'],
            ['name' => 'Wireless Mouse', 'sku' => 'SKU-1004-EL', 'price' => 950, 'stock' => 3, 'min' => 10, 'cat' => 'Electronics'],
            ['name' => 'Laptop Stand Aluminum', 'sku' => 'SKU-1005-EL', 'price' => 1800, 'stock' => 20, 'min' => 5, 'cat' => 'Electronics'],
            ['name' => 'Bluetooth Speaker', 'sku' => 'SKU-1006-EL', 'price' => 3500, 'stock' => 12, 'min' => 5, 'cat' => 'Electronics'],

            // Office Supplies
            ['name' => 'A4 Bond Paper (500 sheets)', 'sku' => 'SKU-2001-OS', 'price' => 250, 'stock' => 150, 'min' => 30, 'cat' => 'Office Supplies'],
            ['name' => 'Ballpen (box of 12)', 'sku' => 'SKU-2002-OS', 'price' => 180, 'stock' => 5, 'min' => 20, 'cat' => 'Office Supplies'],
            ['name' => 'Stapler Heavy Duty', 'sku' => 'SKU-2003-OS', 'price' => 350, 'stock' => 25, 'min' => 5, 'cat' => 'Office Supplies'],
            ['name' => 'Sticky Notes Pack', 'sku' => 'SKU-2004-OS', 'price' => 120, 'stock' => 2, 'min' => 15, 'cat' => 'Office Supplies'],
            ['name' => 'Document Folder A4', 'sku' => 'SKU-2005-OS', 'price' => 45, 'stock' => 80, 'min' => 20, 'cat' => 'Office Supplies'],

            // Furniture
            ['name' => 'Ergonomic Office Chair', 'sku' => 'SKU-3001-FU', 'price' => 8500, 'stock' => 10, 'min' => 3, 'cat' => 'Furniture'],
            ['name' => 'Standing Desk 120cm', 'sku' => 'SKU-3002-FU', 'price' => 15000, 'stock' => 4, 'min' => 2, 'cat' => 'Furniture'],
            ['name' => 'Metal Shelving Unit 5-tier', 'sku' => 'SKU-3003-FU', 'price' => 4200, 'stock' => 7, 'min' => 2, 'cat' => 'Furniture'],
            ['name' => 'Filing Cabinet 3-drawer', 'sku' => 'SKU-3004-FU', 'price' => 6800, 'stock' => 1, 'min' => 2, 'cat' => 'Furniture'],

            // Consumables
            ['name' => 'Toner Cartridge Black', 'sku' => 'SKU-4001-CO', 'price' => 2800, 'stock' => 8, 'min' => 10, 'cat' => 'Consumables'],
            ['name' => 'Ink Cartridge Cyan', 'sku' => 'SKU-4002-CO', 'price' => 650, 'stock' => 15, 'min' => 10, 'cat' => 'Consumables'],
            ['name' => 'AA Batteries (pack 8)', 'sku' => 'SKU-4003-CO', 'price' => 280, 'stock' => 60, 'min' => 20, 'cat' => 'Consumables'],
            ['name' => 'Thermal Paper Roll 80mm', 'sku' => 'SKU-4004-CO', 'price' => 95, 'stock' => 0, 'min' => 15, 'cat' => 'Consumables'],

            // Tools
            ['name' => 'Screwdriver Set 12pcs', 'sku' => 'SKU-5001-TO', 'price' => 750, 'stock' => 18, 'min' => 5, 'cat' => 'Tools'],
            ['name' => 'Cordless Drill 18V', 'sku' => 'SKU-5002-TO', 'price' => 4500, 'stock' => 6, 'min' => 2, 'cat' => 'Tools'],
            ['name' => 'Cable Tester RJ45', 'sku' => 'SKU-5003-TO', 'price' => 1200, 'stock' => 4, 'min' => 3, 'cat' => 'Tools'],

            // Safety Equipment
            ['name' => 'Hard Hat White', 'sku' => 'SKU-6001-SE', 'price' => 380, 'stock' => 25, 'min' => 10, 'cat' => 'Safety Equipment'],
            ['name' => 'Safety Vest Reflective', 'sku' => 'SKU-6002-SE', 'price' => 290, 'stock' => 3, 'min' => 10, 'cat' => 'Safety Equipment'],
            ['name' => 'First Aid Kit Standard', 'sku' => 'SKU-6003-SE', 'price' => 1500, 'stock' => 8, 'min' => 3, 'cat' => 'Safety Equipment'],

            // Cleaning Supplies
            ['name' => 'Disinfectant Spray 500ml', 'sku' => 'SKU-7001-CS', 'price' => 150, 'stock' => 40, 'min' => 20, 'cat' => 'Cleaning Supplies'],
            ['name' => 'Mop and Bucket Set', 'sku' => 'SKU-7002-CS', 'price' => 450, 'stock' => 5, 'min' => 3, 'cat' => 'Cleaning Supplies'],
            ['name' => 'Garbage Bags (50pcs)', 'sku' => 'SKU-7003-CS', 'price' => 180, 'stock' => 30, 'min' => 10, 'cat' => 'Cleaning Supplies'],

            // Networking
            ['name' => 'Cat6 Ethernet Cable 10m', 'sku' => 'SKU-8001-NE', 'price' => 320, 'stock' => 35, 'min' => 10, 'cat' => 'Networking'],
            ['name' => '8-port Network Switch', 'sku' => 'SKU-8002-NE', 'price' => 2200, 'stock' => 2, 'min' => 3, 'cat' => 'Networking'],
            ['name' => 'Wi-Fi Access Point', 'sku' => 'SKU-8003-NE', 'price' => 3800, 'stock' => 5, 'min' => 2, 'cat' => 'Networking'],
        ];

        // Distribute products evenly across suppliers
        $supplierCycle = $suppliers->values();
        $supplierIndex = 0;

        foreach ($products as $data) {
            $category = $categories->firstWhere('name', $data['cat']);
            if (! $category) {
                continue;
            }

            $supplier = $supplierCycle[$supplierIndex % $supplierCycle->count()];
            $supplierIndex++;

            Product::firstOrCreate(
                ['sku' => $data['sku']],
                [
                    'name' => $data['name'],
                    'supplier_id' => $supplier->id,
                    'category_id' => $category->id,
                    'price' => $data['price'],
                    'stock_quantity' => $data['stock'],
                    'min_stock_level' => $data['min'],
                    'description' => null,
                ]
            );
        }
    }
}
