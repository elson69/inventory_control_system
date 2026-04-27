<?php

namespace App\Filament\Admin\Pages;

use App\Jobs\ImportProductsJob;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas;
use Filament\Schemas\Schema;

class ImportProducts extends Page
{

    protected string $view = 'filament.admin.pages.import-products';

    protected static ?string $navigationLabel = 'Import Products';

    protected static ?int $navigationSort = 10;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-arrow-up-tray'; }

    public static function getNavigationGroup(): string|\UnitEnum|null { return 'Inventory'; }

    public ?array $data = [];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Upload CSV')
                    ->description('Upload a CSV file to bulk-import products. Required columns: name, sku, category_name, supplier_company_name, price, min_stock_level. Note: stock_quantity must be set via stock adjustment after import.')
                    ->schema([
                        Forms\Components\FileUpload::make('csv_file')
                            ->label('CSV File')
                            ->acceptedFileTypes(['text/csv', 'application/csv', 'text/plain'])
                            ->required()
                            ->disk('local')
                            ->directory('imports'),
                    ]),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        ImportProductsJob::dispatch($data['csv_file'], auth()->id());

        Notification::make()
            ->title('Import queued')
            ->body('Your CSV is being processed. Products will appear shortly.')
            ->success()
            ->send();

        $this->form->fill();
    }
}
