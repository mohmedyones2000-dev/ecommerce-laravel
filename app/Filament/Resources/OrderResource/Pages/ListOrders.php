<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected static ?string $pollingInterval = '30s';

    public function getTitle(): string
    {
        return 'الطلبات';
    }

    public function getSubheading(): ?string
    {
        $total = Order::count();
        $pending = Order::where('status', 'pending')->count();

        if ($total === 0) {
            return null;
        }

        return $pending > 0
            ? "إجمالي الطلبات: {$total} • قيد المراجعة: {$pending}"
            : "إجمالي الطلبات: {$total}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'الطلبات',
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
                ->badge(Order::count()),

            'pending' => Tab::make('قيد المراجعة')
                ->badge(Order::where('status', 'pending')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('status', 'pending')),

            'processing' => Tab::make('قيد المعالجة')
                ->badge(Order::where('status', 'processing')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('status', 'processing')),

            'shipped' => Tab::make('تم الشحن')
                ->badge(Order::where('status', 'shipped')->count())
                ->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('status', 'shipped')),

            'delivered' => Tab::make('تم التوصيل')
                ->badge(Order::where('status', 'delivered')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('status', 'delivered')),

            'cancelled' => Tab::make('ملغي')
                ->badge(Order::where('status', 'cancelled')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('status', 'cancelled')),

            'unpaid' => Tab::make('غير مدفوع')
                ->badge(Order::where('payment_status', 'unpaid')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('payment_status', 'unpaid')),

            'today' => Tab::make('اليوم')
                ->badge(Order::whereDate('created_at', today())->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereDate('created_at', today())),
        ];
    }
}