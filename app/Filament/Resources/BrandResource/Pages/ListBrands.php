<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use App\Models\Brand;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListBrands extends ListRecords
{
    protected static string $resource = BrandResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'العلامات التجارية';
    }

    public function getSubheading(): ?string
    {
        $total = Brand::count();

        if ($total === 0) {
            return null;
        }

        $withProducts = Brand::has('products')->count();
        $withoutProducts = $total - $withProducts;

        $parts = ["إجمالي العلامات: {$total}", "تحتوي منتجات: {$withProducts}"];

        if ($withoutProducts > 0) {
            $parts[] = "فارغة: {$withoutProducts}";
        }

        return implode(' • ', $parts);
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'العلامات التجارية',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة علامة تجارية')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->keyBindings(['ctrl+n', 'command+n']),

            Actions\Action::make('refresh')
                ->label('تحديث')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn () => $this->resetTable()),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('الكل')
                ->badge(Brand::count()),

            'with_products' => Tab::make('تحتوي على منتجات')
                ->badge(Brand::has('products')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->has('products')),

            'empty' => Tab::make('فارغة')
                ->badge(Brand::doesntHave('products')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->doesntHave('products')),

            'with_logo' => Tab::make('لديها شعار')
                ->badge(Brand::whereNotNull('logo')
                    ->where('logo', '!=', '')
                    ->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereNotNull('logo')->where('logo', '!=', '')),

            'no_logo' => Tab::make('بدون شعار')
                ->badge(Brand::where(fn ($q) => $q
                    ->whereNull('logo')
                    ->orWhere('logo', ''))
                    ->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where(fn ($q) => $q
                        ->whereNull('logo')
                        ->orWhere('logo', ''))),

            'recent' => Tab::make('أُضيفت حديثاً')
                ->badge(Brand::where('created_at', '>=', now()->subDays(30))->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('created_at', '>=', now()->subDays(30))),
        ];
    }
}