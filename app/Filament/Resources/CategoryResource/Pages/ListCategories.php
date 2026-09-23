<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'التصنيفات الرئيسية';
    }

    public function getSubheading(): ?string
    {
        $total = Category::count();
        $empty = Category::whereDoesntHave('products')->count();

        if ($total === 0) {
            return null;
        }

        return $empty > 0
            ? "إجمالي التصنيفات: {$total} • فارغة: {$empty}"
            : "إجمالي التصنيفات: {$total}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'التصنيفات الرئيسية',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة تصنيف جديد')
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
                ->badge(Category::count()),

            'with_products' => Tab::make('تحتوي على منتجات')
                ->badge(Category::has('products')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->has('products')),

            'empty' => Tab::make('فارغة')
                ->badge(Category::whereDoesntHave('products')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereDoesntHave('products')),

            'with_subcategories' => Tab::make('لديها تصنيفات فرعية')
                ->badge(Category::has('subCategories')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->has('subCategories')),

            'recent' => Tab::make('أُضيفت حديثاً')
                ->badge(Category::where('created_at', '>=', now()->subDays(30))->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('created_at', '>=', now()->subDays(30))),
        ];
    }
}