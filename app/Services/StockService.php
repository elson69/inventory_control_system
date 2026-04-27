<?php

namespace App\Services;

use App\Events\LowStockDetected;
use App\Models\Product;
use App\Models\StockLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function restock(Product $product, int $quantity, ?string $remarks, User $actor): StockLog
    {
        return DB::transaction(function () use ($product, $quantity, $remarks, $actor) {
            $product = Product::lockForUpdate()->findOrFail($product->id);
            $newBalance = $product->stock_quantity + $quantity;

            return $this->applyChange($product, 'restock', $quantity, $newBalance, $remarks, $actor);
        });
    }

    public function deduct(Product $product, int $quantity, ?string $remarks, User $actor): StockLog
    {
        return DB::transaction(function () use ($product, $quantity, $remarks, $actor) {
            $product = Product::lockForUpdate()->findOrFail($product->id);
            $newBalance = max(0, $product->stock_quantity - $quantity);
            $actualChange = $product->stock_quantity - $newBalance;

            return $this->applyChange($product, 'deduction', -$actualChange, $newBalance, $remarks, $actor);
        });
    }

    public function adjust(Product $product, int $newAbsoluteQuantity, ?string $remarks, User $actor): StockLog
    {
        return DB::transaction(function () use ($product, $newAbsoluteQuantity, $remarks, $actor) {
            $product = Product::lockForUpdate()->findOrFail($product->id);
            $quantityChanged = $newAbsoluteQuantity - $product->stock_quantity;

            return $this->applyChange($product, 'adjustment', $quantityChanged, $newAbsoluteQuantity, $remarks, $actor);
        });
    }

    private function applyChange(
        Product $product,
        string $action,
        int $quantityChanged,
        int $newBalance,
        ?string $remarks,
        User $actor
    ): StockLog {
        $product->update(['stock_quantity' => $newBalance]);

        $log = StockLog::create([
            'product_id' => $product->id,
            'user_id' => $actor->id,
            'action' => $action,
            'quantity_changed' => $quantityChanged,
            'balance_after' => $newBalance,
            'remarks' => $remarks,
        ]);

        if ($newBalance < $product->min_stock_level) {
            event(new LowStockDetected($product->fresh()));
        }

        return $log;
    }
}
