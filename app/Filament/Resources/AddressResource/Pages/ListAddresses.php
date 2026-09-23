<?php

namespace App\Filament\Resources\AddressResource\Pages;

use App\Filament\Resources\AddressResource;
use App\Models\Address;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAddresses extends ListRecords
{
    protected static string $resource = AddressResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'العناوين';
    }

    public function getSubheading(): ?string
    {
        $total = Address::count();

        if ($total === 0) {
            return null;
        }

        $withPhone = Address::whereNotNull('phone')->where('phone', '!=', '')->count();
        $newThisWeek = Address::where('created_at', '>=', now()->subWeek())->count();

        $parts = ["إجمالي العناوين: {$total}", "تحتوي هاتف: {$withPhone}"];

        if ($newThisWeek > 0) {
            $parts[] = "جديدة هذا الأسبوع: {$newThisWeek}";
        }

        return implode(' • ', $parts);
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'العناوين',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
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
                ->badge(Address::count()),

            'with_phone' => Tab::make('تحتوي على هاتف')
                ->badge(Address::whereNotNull('phone')
                    ->where('phone', '!=', '')
                    ->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereNotNull('phone')->where('phone', '!=', '')),

            'no_phone' => Tab::make('بدون هاتف')
                ->badge(Address::where(fn ($q) => $q
                    ->whereNull('phone')
                    ->orWhere('phone', ''))
                    ->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where(fn ($q) => $q
                        ->whereNull('phone')
                        ->orWhere('phone', ''))),

            'with_notes' => Tab::make('تحتوي على ملاحظات')
                ->badge(Address::whereNotNull('notes')
                    ->where('notes', '!=', '')
                    ->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereNotNull('notes')->where('notes', '!=', '')),

            'this_week' => Tab::make('هذا الأسبوع')
                ->badge(Address::where('created_at', '>=', now()->subWeek())->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('created_at', '>=', now()->subWeek())),
        ];
    }
}