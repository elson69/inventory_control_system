<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use Filament\Actions;
use Filament\Infolists;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Picqer\Barcode\BarcodeGeneratorSVG;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('print_barcode')
                ->label('Print Barcode')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->alpineClickHandler('() => window.print()'),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Product Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Product Name'),

                        Infolists\Components\TextEntry::make('sku')
                            ->label('SKU'),

                        Infolists\Components\TextEntry::make('category.name')
                            ->label('Category'),

                        Infolists\Components\TextEntry::make('supplier.company_name')
                            ->label('Supplier'),

                        Infolists\Components\TextEntry::make('price')
                            ->money('PHP')
                            ->label('Price'),

                        Infolists\Components\TextEntry::make('stock_quantity')
                            ->label('Current Stock')
                            ->badge()
                            ->color(fn ($record) => $record->isLowStock() ? 'danger' : 'success'),

                        Infolists\Components\TextEntry::make('min_stock_level')
                            ->label('Min Stock Level'),

                        Infolists\Components\TextEntry::make('description')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Schemas\Components\Section::make('Barcode')
                    ->schema([
                        Infolists\Components\ViewEntry::make('sku')
                            ->label('')
                            ->view('filament.infolists.components.barcode-entry'),
                    ]),
            ]);
    }
}
