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

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->with('user')->latest()->limit(5))
            ->columns([
                Tables\Columns\ImageColumn::make('user.avatar')
                    ->label('العميل')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn (Order $record) =>
                        'https://ui-avatars.com/api/?name=' . urlencode($record->user->name ?? 'User')
                        . '&background=C9A961&color=fff&size=128'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('الاسم')
                    ->searchable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('order_number')
                    ->label('رقم الطلب')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('الإجمالي')
                    ->money('USD')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'    => 'قيد المراجعة',
                        'processing' => 'قيد المعالجة',
                        'shipped'    => 'تم الشحن',
                        'delivered'  => 'تم التوصيل',
                        'cancelled'  => 'ملغي',
                        default      => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending'    => 'warning',
                        'processing' => 'info',
                        'shipped'    => 'primary',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('Y-m-d H:i')
                    ->since(),
            ])
            ->paginated(false);
    }
}