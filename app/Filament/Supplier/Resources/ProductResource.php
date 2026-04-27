<?php

namespace App\Filament\Supplier\Resources;

use App\Filament\Supplier\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Services\StockService;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas;
use Filament\Schemas\Schema;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-cube'; }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Product Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('sku')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100)
                            ->label('SKU'),

                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('₱')
                            ->required()
                            ->minValue(0),

                        Forms\Components\TextInput::make('min_stock_level')
                            ->numeric()
                            ->default(10)
                            ->required()
                            ->minValue(0),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->money('PHP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->badge()
                    ->color(fn (Product $record): string => $record->isLowStock() ? 'danger' : 'success'),

                Tables\Columns\TextColumn::make('min_stock_level')
                    ->label('Min Stock'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),

                Tables\Filters\Filter::make('low_stock')
                    ->label('Low Stock Only')
                    ->query(fn ($query) => $query->whereColumn('stock_quantity', '<', 'min_stock_level')),
            ])
            ->actions([
                Actions\Action::make('adjust_stock')
                    ->label('Adjust Stock')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('action')
                            ->options([
                                'restock' => 'Restock (Add)',
                                'deduction' => 'Deduction (Remove)',
                                'adjustment' => 'Manual Adjustment (Set absolute)',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->label('Quantity'),

                        Forms\Components\Textarea::make('remarks')
                            ->rows(2)
                            ->label('Remarks (optional)'),
                    ])
                    ->action(function (Product $record, array $data): void {
                        $service = app(StockService::class);
                        $actor = auth()->user();

                        match ($data['action']) {
                            'restock' => $service->restock($record, (int) $data['quantity'], $data['remarks'] ?? null, $actor),
                            'deduction' => $service->deduct($record, (int) $data['quantity'], $data['remarks'] ?? null, $actor),
                            'adjustment' => $service->adjust($record, (int) $data['quantity'], $data['remarks'] ?? null, $actor),
                        };

                        Notification::make()
                            ->title('Stock updated successfully')
                            ->success()
                            ->send();
                    }),

                Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
