<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'المستخدمون';
    }

    public function getSubheading(): ?string
    {
        $total = User::count();
        $customers = User::where('role', 'customer')->count();
        $staff = User::whereIn('role', ['admin', 'manager'])->count();

        if ($total === 0) {
            return null;
        }

        return "إجمالي المستخدمين: {$total} • العملاء: {$customers} • الفريق: {$staff}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'المستخدمون',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة مستخدم جديد')
                ->icon('heroicon-o-user-plus')
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
                ->badge(User::count()),

            'admin' => Tab::make('المديرون العامون')
                ->badge(User::where('role', 'admin')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('role', 'admin')),

            'managers' => Tab::make('المديرون')
                ->badge(User::where('role', 'manager')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('role', 'manager')),

            'customers' => Tab::make('العملاء')
                ->badge(User::where('role', 'customer')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('role', 'customer')),

            'new_this_week' => Tab::make('الجدد هذا الأسبوع')
                ->badge(User::where('created_at', '>=', now()->subWeek())->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('created_at', '>=', now()->subWeek())),

            'with_orders' => Tab::make('لديهم طلبات')
                ->badge(User::has('orders')->count())
                ->badgeColor('teal')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->has('orders')),
        ];
    }
}