<?php

return [
    /*
     * Webhook URL for low-stock notifications.
     * Set LOW_STOCK_WEBHOOK_URL in your .env file to enable.
     */
    'low_stock_webhook_url' => env('LOW_STOCK_WEBHOOK_URL', null),
];
