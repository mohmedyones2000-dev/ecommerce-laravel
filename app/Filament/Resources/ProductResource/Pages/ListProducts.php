<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'المنتجات';
    }

    public function getSubheading(): ?string
    {
        $total = Product::count();

        return $total > 0
            ? "إجمالي المنتجات: {$total}"
            : null;
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'المنتجات',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة منتج جديد')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->keyBindings(['ctrl+n', 'command+n']),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('الكل')
                ->badge(Product::count()),

            'active' => Tab::make('النشطة')
                ->badge(Product::where('is_active', true)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true)),

            'inactive' => Tab::make('غير النشطة')
                ->badge(Product::where('is_active', false)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false)),

            'featured' => Tab::make('المميزة')
                ->badge(Product::where('is_featured', true)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_featured', true)),

            'low_stock' => Tab::make('مخزون منخفض')
                ->badge(Product::whereHas('variants', fn ($q) =>
                    $q->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 5))->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereHas('variants', fn ($q) =>
                        $q->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 5))),

            'out_of_stock' => Tab::make('نفذ المخزون')
                ->badge(Product::whereDoesntHave('variants', fn ($q) =>
                    $q->where('stock_quantity', '>', 0))->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereDoesntHave('variants', fn ($q) =>
                        $q->where('stock_quantity', '>', 0))),
        ];
    }
}