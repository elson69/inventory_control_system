<?php

namespace App\Filament\Admin\Resources\ProductResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StockLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'stockLogs';

    protected static ?string $title = 'Stock History';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
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
                    ->label('By'),

                Tables\Columns\TextColumn::make('remarks'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}
