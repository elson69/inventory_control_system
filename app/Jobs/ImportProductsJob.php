<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(
        public readonly string $filePath,
        public readonly int $userId
    ) {}

    public function handle(): void
    {
        $absolutePath = Storage::disk('local')->path($this->filePath);

        if (! file_exists($absolutePath)) {
            Log::error("ImportProductsJob: file not found at {$absolutePath}");
            return;
        }

        $csv = Reader::createFromPath($absolutePath, 'r');
        $csv->setHeaderOffset(0);

        $errors = [];
        $imported = 0;

        foreach ($csv->getRecords() as $offset => $record) {
            try {
                $category = Category::firstOrCreate(
                    ['name' => trim($record['category_name'] ?? '')],
                    ['slug' => \Illuminate\Support\Str::slug($record['category_name'] ?? '')]
                );

                $supplier = Supplier::where('company_name', trim($record['supplier_company_name'] ?? ''))->first();

                if (! $supplier) {
                    $errors[] = "Row {$offset}: Supplier '{$record['supplier_company_name']}' not found.";
                    continue;
                }

                Product::firstOrCreate(
                    ['sku' => trim($record['sku'] ?? '')],
                    [
                        'name' => trim($record['name'] ?? ''),
                        'category_id' => $category->id,
                        'supplier_id' => $supplier->id,
                        'price' => (float) ($record['price'] ?? 0),
                        'min_stock_level' => (int) ($record['min_stock_level'] ?? 10),
                        'description' => trim($record['description'] ?? ''),
                        // stock_quantity intentionally not set here — use StockService
                    ]
                );

                $imported++;
            } catch (\Throwable $e) {
                $errors[] = "Row {$offset}: {$e->getMessage()}";
            }
        }

        Log::info("ImportProductsJob completed: {$imported} products imported.", ['errors' => $errors]);

        Storage::disk('local')->delete($this->filePath);
    }
}
