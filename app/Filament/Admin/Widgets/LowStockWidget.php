<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockWidget extends BaseWidget
{
    protected static ?string $heading = 'Low Stock Alerts';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->whereColumn('stock_quantity', '<', 'min_stock_level')
                    ->with(['category', 'supplier'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU'),

                Tables\Columns\TextColumn::make('supplier.company_name')
                    ->label('Supplier'),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Current Stock')
                    ->badge()
                    ->color('danger'),

                Tables\Columns\TextColumn::make('min_stock_level')
                    ->label('Min Required'),
            ])
            ->paginated([5, 10, 25]);
    }
}
