<?php

namespace App\Providers;

use App\Events\LowStockDetected;
use App\Listeners\DispatchLowStockWebhook;
use App\Listeners\LogLowStockAlert;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        LowStockDetected::class => [
            LogLowStockAlert::class,
            DispatchLowStockWebhook::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
