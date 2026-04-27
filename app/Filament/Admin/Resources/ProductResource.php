<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Filament\Admin\Resources\ProductResource\RelationManagers\ActivityRelationManager;
use App\Filament\Admin\Resources\ProductResource\RelationManagers\StockLogsRelationManager;
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

    protected static ?int $navigationSort = 2;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-cube'; }

    public static function getNavigationGroup(): string|\UnitEnum|null { return 'Inventory'; }

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

                        Forms\Components\Select::make('supplier_id')
                            ->relationship('supplier', 'company_name')
                            ->searchable()
                            ->preload()
                            ->required(),

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
                    ->searchable()
                    ->label('SKU'),

                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('supplier.company_name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->money('PHP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->sortable()
                    ->badge()
                    ->color(fn (Product $record): string => $record->isLowStock() ? 'danger' : 'success'),

                Tables\Columns\TextColumn::make('min_stock_level')
                    ->label('Min Stock')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),

                Tables\Filters\SelectFilter::make('supplier')
                    ->relationship('supplier', 'company_name'),

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

                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Actions\Action::make('export_csv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        $products = Product::with(['category', 'supplier'])->get();
                        $writer = \League\Csv\Writer::createFromString();
                        $writer->insertOne(['ID', 'SKU', 'Name', 'Category', 'Supplier', 'Price', 'Stock', 'Min Stock', 'Description']);

                        foreach ($products as $product) {
                            $writer->insertOne([
                                $product->id,
                                $product->sku,
                                $product->name,
                                $product->category->name ?? '',
                                $product->supplier->company_name ?? '',
                                $product->price,
                                $product->stock_quantity,
                                $product->min_stock_level,
                                $product->description ?? '',
                            ]);
                        }

                        return response()->streamDownload(
                            fn () => print($writer->toString()),
                            'inventory-export-' . now()->format('Y-m-d') . '.csv',
                            ['Content-Type' => 'text/csv']
                        );
                    }),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelationManagers(): array
    {
        return [
            StockLogsRelationManager::class,
            ActivityRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'view' => Pages\ViewProduct::route('/{record}'),
        ];
    }
}
