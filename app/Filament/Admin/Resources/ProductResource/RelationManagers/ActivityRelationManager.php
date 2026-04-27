<?php

namespace App\Filament\Admin\Resources\ProductResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Audit Trail';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Event'),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('By'),

                Tables\Columns\TextColumn::make('properties')
                    ->label('Changes')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) {
                            return '—';
                        }
                        $props = is_string($state) ? json_decode($state, true) : (array) $state;
                        $old = $props['old'] ?? [];
                        $new = $props['attributes'] ?? [];
                        $changes = [];
                        foreach ($new as $key => $val) {
                            if (isset($old[$key]) && $old[$key] !== $val) {
                                $changes[] = "{$key}: {$old[$key]} → {$val}";
                            }
                        }
                        return implode(', ', $changes) ?: json_encode($new);
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
