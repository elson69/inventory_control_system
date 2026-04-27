<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ActivityLogResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $label = 'Activity Log';

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-shield-check'; }

    public static function getNavigationGroup(): string|\UnitEnum|null { return 'System'; }

    protected static ?string $pluralLabel = 'Activity Logs';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->badge(),

                Tables\Columns\TextColumn::make('description')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(fn ($state) => class_basename($state ?? '')),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('Record ID'),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('By')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('subject_type')
                    ->label('Model Type')
                    ->options([
                        'App\Models\Product' => 'Product',
                        'App\Models\Category' => 'Category',
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
