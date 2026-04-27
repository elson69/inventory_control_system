<?php

namespace App\Filament\Supplier\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SupplierStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $supplierId = filament()->getTenant()?->id;

        $totalProducts = Product::where('supplier_id', $supplierId)->count();
        $lowStockCount = Product::where('supplier_id', $supplierId)
            ->whereColumn('stock_quantity', '<', 'min_stock_level')
            ->count();
        $totalValue = Product::where('supplier_id', $supplierId)
            ->selectRaw('SUM(price * stock_quantity) as value')
            ->value('value') ?? 0;

        return [
            Stat::make('My Products', $totalProducts)
                ->icon('heroicon-o-cube')
                ->color('primary'),

            Stat::make('Low Stock Items', $lowStockCount)
                ->icon('heroicon-o-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'danger' : 'success'),

            Stat::make('My Inventory Value', '₱' . number_format($totalValue, 2))
                ->icon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }
}
