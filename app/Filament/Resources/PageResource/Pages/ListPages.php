<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Models\Page;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'الصفحات الثابتة';
    }

    public function getSubheading(): ?string
    {
        $total = Page::count();
        $active = Page::where('is_active', true)->count();
        $inFooter = Page::where('show_in_footer', true)->count();
        $inactive = $total - $active;

        if ($total === 0) {
            return null;
        }

        $parts = ["إجمالي الصفحات: {$total}", "في الفوتر: {$inFooter}"];

        if ($inactive > 0) {
            $parts[] = "غير نشطة: {$inactive}";
        }

        return implode(' • ', $parts);
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'الصفحات الثابتة',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة صفحة جديدة')
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
                ->badge(Page::count()),

            'active' => Tab::make('النشطة')
                ->badge(Page::where('is_active', true)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('is_active', true)),

            'inactive' => Tab::make('غير النشطة')
                ->badge(Page::where('is_active', false)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('is_active', false)),

            'in_footer' => Tab::make('في الفوتر')
                ->badge(Page::where('show_in_footer', true)->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('show_in_footer', true)),

            'missing_seo' => Tab::make('بدون وصف SEO')
                ->badge(Page::where(fn ($q) => $q
                    ->whereNull('meta_description')
                    ->orWhere('meta_description', ''))
                    ->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where(fn ($q) => $q
                        ->whereNull('meta_description')
                        ->orWhere('meta_description', ''))),

            'recent' => Tab::make('حُدّثت حديثاً')
                ->badge(Page::where('updated_at', '>=', now()->subDays(30))->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('updated_at', '>=', now()->subDays(30))),
        ];
    }
}