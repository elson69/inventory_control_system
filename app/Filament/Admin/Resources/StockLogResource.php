<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StockLogResource\Pages;
use App\Models\StockLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StockLogResource extends Resource
{
    protected static ?string $model = StockLog::class;

    protected static ?int $navigationSort = 3;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-clipboard-document-list'; }

    public static function getNavigationGroup(): string|\UnitEnum|null { return 'Inventory'; }

    protected static ?string $label = 'Stock Log';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('product.sku')
                    ->label('SKU')
                    ->searchable(),

                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'restock' => 'success',
                        'deduction' => 'danger',
                        'adjustment' => 'warning',
                    }),

                Tables\Columns\TextColumn::make('quantity_changed')
                    ->label('Qty Changed')
                    ->sortable(),

                Tables\Columns\TextColumn::make('balance_after')
                    ->label('Balance After')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('By')
                    ->sortable(),

                Tables\Columns\TextColumn::make('remarks'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'restock' => 'Restock',
                        'deduction' => 'Deduction',
                        'adjustment' => 'Adjustment',
                    ]),

                Tables\Filters\SelectFilter::make('product')
                    ->relationship('product', 'name'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockLogs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
