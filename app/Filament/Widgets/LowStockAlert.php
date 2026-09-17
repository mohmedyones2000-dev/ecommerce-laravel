<?php

namespace App\Filament\Widgets;

use App\Models\ProductVariant;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockAlert extends BaseWidget
{
    protected static ?string $heading = 'تنبيهات المخزون';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProductVariant::query()
                    ->with('product.images')
                    ->where('stock_quantity', '<=', 5)
                    ->orderBy('stock_quantity')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('product.images.image_path')
                    ->label('الصورة')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(asset('images/product-placeholder.svg')),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('المنتج')
                    ->weight('semibold')
                    ->limit(40),

                Tables\Columns\TextColumn::make('color')
                    ->label('اللون')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('size')
                    ->label('المقاس')
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('المخزون')
                    ->badge()
                    ->color(fn ($state) => $state == 0 ? 'danger' : 'warning')
                    ->formatStateUsing(fn ($state) => $state == 0 ? 'نفذ' : $state . ' قطعة'),
            ])
            ->paginated(false)
            ->emptyStateHeading('لا توجد تنبيهات')
            ->emptyStateDescription('جميع المنتجات لديها مخزون كافٍ')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}