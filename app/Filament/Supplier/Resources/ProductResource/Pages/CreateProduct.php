<?php

namespace App\Filament\Supplier\Resources\ProductResource\Pages;

use App\Filament\Supplier\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Automatically assign the current tenant (supplier) to the product
        $data['supplier_id'] = filament()->getTenant()->id;

        return $data;
    }
}
