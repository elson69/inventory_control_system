<?php

namespace App\Filament\Supplier\Resources;

use App\Filament\Supplier\Resources\StockLogResource\Pages;
use App\Models\StockLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StockLogResource extends Resource
{
    protected static ?string $model = StockLog::class;

protected static ?string $label = 'Stock Log';

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-clipboard-document-list'; }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                StockLog::query()->whereHas('product', function (Builder $query) {
                    $query->where('supplier_id', filament()->getTenant()->id);
                })
            )
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'restock' => 'success',
                        'deduction' => 'danger',
                        'adjustment' => 'warning',
                    }),

                Tables\Columns\TextColumn::make('quantity_changed')
                    ->label('Qty Changed'),

                Tables\Columns\TextColumn::make('balance_after')
                    ->label('Balance After'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('By'),

                Tables\Columns\TextColumn::make('remarks'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
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
