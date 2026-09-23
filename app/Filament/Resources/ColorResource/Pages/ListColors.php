<?php

namespace App\Filament\Resources\ColorResource\Pages;

use App\Filament\Resources\ColorResource;
use App\Models\Color;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListColors extends ListRecords
{
    protected static string $resource = ColorResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'الألوان';
    }

    public function getSubheading(): ?string
    {
        $total = Color::count();

        if ($total === 0) {
            return null;
        }

        $active = Color::where('is_active', true)->count();
        $inactive = $total - $active;

        $parts = ["إجمالي الألوان: {$total}", "نشط: {$active}"];

        if ($inactive > 0) {
            $parts[] = "غير نشط: {$inactive}";
        }

        return implode(' • ', $parts);
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'الألوان',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة لون جديد')
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
                ->badge(Color::count()),

            'active' => Tab::make('النشطة')
                ->badge(Color::where('is_active', true)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('is_active', true)),

            'inactive' => Tab::make('غير النشطة')
                ->badge(Color::where('is_active', false)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('is_active', false)),

            'recent' => Tab::make('أُضيفت حديثاً')
                ->badge(Color::where('created_at', '>=', now()->subDays(30))->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('created_at', '>=', now()->subDays(30))),
        ];
    }
}