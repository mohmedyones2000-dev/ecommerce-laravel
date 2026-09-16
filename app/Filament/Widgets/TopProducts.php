<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopProducts extends BaseWidget
{
    protected static ?string $heading = 'الأكثر مبيعاً';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('products') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->withCount('orderItems')
                    ->withSum('orderItems', 'quantity')
                    ->having('order_items_count', '>', 0)
                    ->orderByDesc('order_items_count')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('images.image_path')
                    ->label('الصورة')
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?background=C9A961&color=fff&name=P'),

                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المنتج')
                    ->searchable()
                    ->weight('bold')
                    ->limit(40),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('التصنيف')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')
                    ->money('USD'),

                Tables\Columns\TextColumn::make('order_items_count')
                    ->label('عدد الطلبات')
                    ->badge()
                    ->color('success')
                    ->suffix(' طلب'),

                Tables\Columns\TextColumn::make('order_items_sum_quantity')
                    ->label('إجمالي الكمية')
                    ->badge()
                    ->color('info')
                    ->suffix(' قطعة'),
            ])
            ->paginated(false);
    }
}