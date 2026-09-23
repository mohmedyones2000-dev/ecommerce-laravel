<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use App\Models\City;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCities extends ListRecords
{
    protected static string $resource = CityResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'المدن';
    }

    public function getSubheading(): ?string
    {
        $total = City::count();

        if ($total === 0) {
            return null;
        }

        $freeShipping = City::where('is_free_shipping', true)->count();
        $paidShipping = $total - $freeShipping;

        $parts = ["إجمالي المدن: {$total}", "شحن مجاني: {$freeShipping}"];

        if ($paidShipping > 0) {
            $parts[] = "شحن مدفوع: {$paidShipping}";
        }

        return implode(' • ', $parts);
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'المدن',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة مدينة جديدة')
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
                ->badge(City::count()),

            'free_shipping' => Tab::make('شحن مجاني')
                ->badge(City::where('is_free_shipping', true)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('is_free_shipping', true)),

            'paid_shipping' => Tab::make('شحن مدفوع')
                ->badge(City::where('is_free_shipping', false)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('is_free_shipping', false)),

            'with_addresses' => Tab::make('لديها عناوين')
                ->badge(City::has('addresses')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->has('addresses')),

            'no_addresses' => Tab::make('بدون عناوين')
                ->badge(City::doesntHave('addresses')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->doesntHave('addresses')),

            'recent' => Tab::make('أُضيفت حديثاً')
                ->badge(City::where('created_at', '>=', now()->subDays(30))->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('created_at', '>=', now()->subDays(30))),
        ];
    }
}