<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('No users or products found.');
            return;
        }

        $actions = ['restock', 'restock', 'restock', 'deduction', 'deduction', 'adjustment'];

        foreach ($products as $product) {
            $balance = 0;
            $entryCount = rand(3, 6);

            for ($i = 0; $i < $entryCount; $i++) {
                $action = $actions[array_rand($actions)];
                $user = $users->random();

                if ($action === 'restock') {
                    $qty = rand(10, 60);
                    $balance += $qty;
                    $quantityChanged = $qty;
                } elseif ($action === 'deduction') {
                    $qty = rand(1, min(20, max(1, $balance)));
                    $balance = max(0, $balance - $qty);
                    $quantityChanged = -$qty;
                } else {
                    // adjustment — set absolute
                    $newBalance = rand(5, 100);
                    $quantityChanged = $newBalance - $balance;
                    $balance = $newBalance;
                }

                StockLog::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'action' => $action,
                    'quantity_changed' => $quantityChanged,
                    'balance_after' => $balance,
                    'remarks' => rand(0, 1) ? fake()->sentence() : null,
                    'created_at' => now()->subDays(rand(1, 90))->subHours(rand(0, 23)),
                    'updated_at' => now()->subDays(rand(0, 5)),
                ]);
            }
        }
    }
}
