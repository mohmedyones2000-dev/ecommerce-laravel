<?php

namespace App\Filament\Resources\SubCategoryResource\Pages;

use App\Filament\Resources\SubCategoryResource;
use App\Models\SubCategory;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSubCategories extends ListRecords
{
    protected static string $resource = SubCategoryResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'التصنيفات الفرعية';
    }

    public function getSubheading(): ?string
    {
        $total = SubCategory::count();
        $empty = SubCategory::whereDoesntHave('products')->count();
        $unassigned = SubCategory::doesntHave('categories')->count();

        if ($total === 0) {
            return null;
        }

        $parts = ["إجمالي التصنيفات الفرعية: {$total}"];

        if ($empty > 0) {
            $parts[] = "فارغة: {$empty}";
        }

        if ($unassigned > 0) {
            $parts[] = "بدون تصنيف رئيسي: {$unassigned}";
        }

        return implode(' • ', $parts);
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'التصنيفات الفرعية',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة تصنيف فرعي')
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
                ->badge(SubCategory::count()),

            'with_products' => Tab::make('تحتوي على منتجات')
                ->badge(SubCategory::has('products')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->has('products')),

            'empty' => Tab::make('فارغة')
                ->badge(SubCategory::whereDoesntHave('products')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereDoesntHave('products')),

            'unassigned' => Tab::make('بدون تصنيف رئيسي')
                ->badge(SubCategory::doesntHave('categories')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->doesntHave('categories')),

            'assigned' => Tab::make('مرتبطة بتصنيف رئيسي')
                ->badge(SubCategory::has('categories')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->has('categories')),

            'recent' => Tab::make('أُضيفت حديثاً')
                ->badge(SubCategory::where('created_at', '>=', now()->subDays(30))->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('created_at', '>=', now()->subDays(30))),
        ];
    }
}