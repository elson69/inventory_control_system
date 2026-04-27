<?php

namespace App\Listeners;

use App\Events\LowStockDetected;
use App\Jobs\SendLowStockWebhookJob;

class DispatchLowStockWebhook
{
    public function handle(LowStockDetected $event): void
    {
        if (config('inventory.low_stock_webhook_url')) {
            SendLowStockWebhookJob::dispatch($event->product);
        }
    }
}
