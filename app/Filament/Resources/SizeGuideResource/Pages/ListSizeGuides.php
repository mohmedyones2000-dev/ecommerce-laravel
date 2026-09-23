<?php

namespace App\Filament\Resources\SizeGuideResource\Pages;

use App\Filament\Resources\SizeGuideResource;
use App\Models\SizeGuide;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSizeGuides extends ListRecords
{
    protected static string $resource = SizeGuideResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'أدلة المقاسات';
    }

    public function getSubheading(): ?string
    {
        $total = SizeGuide::count();
        $active = SizeGuide::where('is_active', true)->count();
        $inactive = $total - $active;

        if ($total === 0) {
            return null;
        }

        $parts = ["إجمالي الأدلة: {$total}"];

        if ($inactive > 0) {
            $parts[] = "غير نشطة: {$inactive}";
        }

        return implode(' • ', $parts);
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'أدلة المقاسات',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة دليل مقاسات')
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
                ->badge(SizeGuide::count()),

            'active' => Tab::make('النشطة')
                ->badge(SizeGuide::where('is_active', true)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('is_active', true)),

            'inactive' => Tab::make('غير النشطة')
                ->badge(SizeGuide::where('is_active', false)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('is_active', false)),

            'with_items' => Tab::make('تحتوي على مقاسات')
                ->badge(SizeGuide::has('items')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->has('items')),

            'empty' => Tab::make('فارغة')
                ->badge(SizeGuide::doesntHave('items')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->doesntHave('items')),

            'with_products' => Tab::make('مرتبطة بمنتجات')
                ->badge(SizeGuide::has('products')->count())
                ->badgeColor('teal')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->has('products')),
        ];
    }
}