<?php

namespace App\Listeners;

use App\Events\LowStockDetected;

class LogLowStockAlert
{
    public function handle(LowStockDetected $event): void
    {
        activity()
            ->performedOn($event->product)
            ->withProperties([
                'stock_quantity' => $event->product->stock_quantity,
                'min_stock_level' => $event->product->min_stock_level,
            ])
            ->log('Low stock alert: stock fell below minimum level');
    }
}
