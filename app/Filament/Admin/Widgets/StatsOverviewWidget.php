<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Product;
use App\Models\Supplier;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();
        $lowStockCount = Product::whereColumn('stock_quantity', '<', 'min_stock_level')->count();
        $totalStockValue = Product::selectRaw('SUM(price * stock_quantity) as value')->value('value') ?? 0;

        return [
            Stat::make('Total Products', $totalProducts)
                ->icon('heroicon-o-cube')
                ->color('primary'),

            Stat::make('Active Suppliers', $totalSuppliers)
                ->icon('heroicon-o-building-office')
                ->color('info'),

            Stat::make('Low Stock Items', $lowStockCount)
                ->icon('heroicon-o-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'danger' : 'success'),

            Stat::make('Total Inventory Value', '₱' . number_format($totalStockValue, 2))
                ->icon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }
}
