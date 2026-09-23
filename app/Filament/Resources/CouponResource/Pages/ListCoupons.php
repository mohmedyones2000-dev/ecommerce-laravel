<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Filament\Resources\CouponResource;
use App\Models\Coupon;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCoupons extends ListRecords
{
    protected static string $resource = CouponResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'كوبونات الخصم';
    }

    public function getSubheading(): ?string
    {
        $total = Coupon::count();

        if ($total === 0) {
            return null;
        }

        $active = Coupon::where('is_active', true)->count();
        $expired = Coupon::whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->count();
        $expiringSoon = Coupon::whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays(7)])
            ->count();

        $parts = ["إجمالي الكوبونات: {$total}", "نشط: {$active}"];

        if ($expiringSoon > 0) {
            $parts[] = "ينتهي قريباً: {$expiringSoon}";
        }

        if ($expired > 0) {
            $parts[] = "منتهي: {$expired}";
        }

        return implode(' • ', $parts);
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'كوبونات الخصم',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة كوبون جديد')
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
                ->badge(Coupon::count()),

            'valid' => Tab::make('صالح للاستخدام')
                ->badge(Coupon::where('is_active', true)
                    ->where(fn ($q) => $q
                        ->whereNull('expires_at')
                        ->orWhere('expires_at', '>=', now()))
                    ->where(fn ($q) => $q
                        ->whereNull('usage_limit')
                        ->orWhereColumn('used_count', '<', 'usage_limit'))
                    ->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('is_active', true)
                    ->where(fn ($q) => $q
                        ->whereNull('expires_at')
                        ->orWhere('expires_at', '>=', now()))
                    ->where(fn ($q) => $q
                        ->whereNull('usage_limit')
                        ->orWhereColumn('used_count', '<', 'usage_limit'))),

            'expiring_soon' => Tab::make('ينتهي قريباً')
                ->badge(Coupon::whereNotNull('expires_at')
                    ->whereBetween('expires_at', [now(), now()->addDays(7)])
                    ->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereNotNull('expires_at')
                    ->whereBetween('expires_at', [now(), now()->addDays(7)])),

            'expired' => Tab::make('منتهي الصلاحية')
                ->badge(Coupon::whereNotNull('expires_at')
                    ->where('expires_at', '<', now())
                    ->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereNotNull('expires_at')
                    ->where('expires_at', '<', now())),

            'free_shipping' => Tab::make('شحن مجاني')
                ->badge(Coupon::where('free_shipping', true)->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('free_shipping', true)),

            'unused' => Tab::make('لم يُستخدم بعد')
                ->badge(Coupon::where('used_count', 0)->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('used_count', 0)),

            'used_up' => Tab::make('استُنفد')
                ->badge(Coupon::whereNotNull('usage_limit')
                    ->whereColumn('used_count', '>=', 'usage_limit')
                    ->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereNotNull('usage_limit')
                    ->whereColumn('used_count', '>=', 'usage_limit')),
        ];
    }
}