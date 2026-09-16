<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected static ?string $heading = 'آخر الطلبات';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('orders') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('رقم الطلب')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('الإجمالي')
                    ->money('USD')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'قيد المراجعة',
                        'processing' => 'قيد المعالجة',
                        'shipped' => 'تم الشحن',
                        'delivered' => 'تم التوصيل',
                        'cancelled' => 'ملغي',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('Y-m-d H:i')
                    ->since(),
            ])
            ->paginated(false);
    }
}