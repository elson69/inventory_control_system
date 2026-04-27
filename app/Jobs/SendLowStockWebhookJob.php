<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendLowStockWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public readonly Product $product
    ) {}

    public function handle(): void
    {
        $webhookUrl = config('inventory.low_stock_webhook_url');

        if (! $webhookUrl) {
            return;
        }

        Http::timeout(10)
            ->retry(3, 1000)
            ->post($webhookUrl, [
                'event' => 'low_stock_detected',
                'product_id' => $this->product->id,
                'product_name' => $this->product->name,
                'sku' => $this->product->sku,
                'stock_quantity' => $this->product->stock_quantity,
                'min_stock_level' => $this->product->min_stock_level,
                'supplier' => $this->product->supplier?->company_name,
                'timestamp' => now()->toIso8601String(),
            ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Low stock webhook failed', [
            'product_id' => $this->product->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
