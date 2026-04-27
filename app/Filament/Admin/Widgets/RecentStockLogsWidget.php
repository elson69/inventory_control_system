<?php

namespace App\Filament\Admin\Widgets;

use App\Models\StockLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentStockLogsWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Stock Activity';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                StockLog::query()
                    ->with(['product', 'user'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product'),

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

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('When'),
            ])
            ->paginated(false);
    }
}
