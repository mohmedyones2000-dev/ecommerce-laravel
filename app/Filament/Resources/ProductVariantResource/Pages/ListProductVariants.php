<?php

namespace App\Filament\Resources\ProductVariantResource\Pages;

use App\Filament\Resources\ProductVariantResource;
use App\Models\ProductVariant;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProductVariants extends ListRecords
{
    protected static string $resource = ProductVariantResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'متغيرات المنتجات';
    }

    public function getSubheading(): ?string
    {
        $total = ProductVariant::count();
        $lowStock = ProductVariant::where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 5)
            ->count();
        $outOfStock = ProductVariant::where('stock_quantity', '<=', 0)->count();

        if ($total === 0) {
            return null;
        }

        $parts = ["إجمالي المتغيرات: {$total}"];

        if ($lowStock > 0) {
            $parts[] = "مخزون منخفض: {$lowStock}";
        }

        if ($outOfStock > 0) {
            $parts[] = "نفذ المخزون: {$outOfStock}";
        }

        return implode(' • ', $parts);
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'متغيرات المنتجات',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة متغير جديد')
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
                ->badge(ProductVariant::count()),

            'in_stock' => Tab::make('متوفر')
                ->badge(ProductVariant::where('stock_quantity', '>', 5)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('stock_quantity', '>', 5)),

            'low_stock' => Tab::make('مخزون منخفض')
                ->badge(ProductVariant::where('stock_quantity', '>', 0)
                    ->where('stock_quantity', '<=', 5)
                    ->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('stock_quantity', '>', 0)
                        ->where('stock_quantity', '<=', 5)),

            'out_of_stock' => Tab::make('نفذ المخزون')
                ->badge(ProductVariant::where('stock_quantity', '<=', 0)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('stock_quantity', '<=', 0)),

            'recent' => Tab::make('أُضيفت حديثاً')
                ->badge(ProductVariant::where('created_at', '>=', now()->subDays(30))->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('created_at', '>=', now()->subDays(30))),
        ];
    }
}