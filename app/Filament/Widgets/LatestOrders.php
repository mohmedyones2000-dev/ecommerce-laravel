<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected static ?string $heading = 'آخر الطلبات';

    protected static ?string $description = 'أحدث 5 طلبات واردة';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $pollingInterval = '60s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with(['user', 'items'])
                    ->latest()
                    ->limit(5)
            )
            ->recordUrl(null)
            ->columns([
                Tables\Columns\ImageColumn::make('user.avatar')
                    ->label('العميل')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(fn (Order $record): string =>
                        'https://ui-avatars.com/api/?name=' . urlencode($record->user?->name ?? 'User')
                        . '&background=14b8a6&color=fff&size=80'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل')
                    ->searchable()
                    ->weight('semibold')
                    ->description(fn (Order $record): ?string => $record->user?->email),

                Tables\Columns\TextColumn::make('order_number')
                    ->label('رقم الطلب')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('تم نسخ رقم الطلب')
                    ->copyMessageDuration(1500)
                    ->weight('medium')
                    ->color('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('المنتجات')
                    ->counts('items')
                    ->badge()
                    ->color('gray')
                    ->suffix(' منتج')
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('الإجمالي')
                    ->money('USD')
                    ->weight('bold')
                    ->color('teal')
                    ->sortable()
                    ->numeric(),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->alignCenter()
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
                    ->since()
                    ->sortable()
                    ->color('gray')
                    ->tooltip(fn (Order $record): string =>
                        $record->created_at?->format('Y-m-d H:i') ?? ''),
            ])
            ->headerActions([
                Tables\Actions\Action::make('view_all')
                    ->label('عرض الكل')
                    ->icon('heroicon-m-arrow-left')
                    ->url(fn () => route('filament.admin.resources.orders.index'))
                    ->color('gray')
                    ->size('sm'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('عرض')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Order $record) => route('filament.admin.resources.orders.edit', $record))
                    ->color('gray')
                    ->size('sm'),
            ])
            ->paginated(false)
            ->emptyStateHeading('لا توجد طلبات بعد')
            ->emptyStateDescription('لم يقم أي عميل بالطلب حتى الآن')
            ->emptyStateIcon('heroicon-o-shopping-cart');
    }
}