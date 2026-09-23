<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopProducts extends BaseWidget
{
    protected static ?string $heading = 'الأكثر مبيعاً';

    protected static ?string $description = 'أفضل 5 منتجات من حيث عدد الطلبات';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $pollingInterval = '60s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->whereHas('orderItems')
                    ->withCount('orderItems')
                    ->withSum('orderItems', 'quantity')
                    ->with(['brand', 'category'])
                    ->orderByDesc('order_items_count')
                    ->limit(5)
            )
            ->recordUrl(null)
            ->columns([
                Tables\Columns\ImageColumn::make('images.image_path')
                    ->label('الصورة')
                    ->disk('public')
                    ->circular()
                    ->size(48)
                    ->defaultImageUrl(asset('images/product-placeholder.svg')),

                Tables\Columns\TextColumn::make('name')
                    ->label('المنتج')
                    ->searchable()
                    ->weight('semibold')
                    ->limit(40)
                    ->description(fn (Product $record): ?string => $record->brand?->name),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('التصنيف')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')
                    ->money('USD')
                    ->weight('semibold')
                    ->sortable()
                    ->numeric(),

                Tables\Columns\TextColumn::make('order_items_count')
                    ->label('الطلبات')
                    ->badge()
                    ->color('teal')
                    ->suffix(' طلب')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('order_items_sum_quantity')
                    ->label('الكمية')
                    ->badge()
                    ->color('info')
                    ->suffix(' قطعة')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('view_all')
                    ->label('عرض الكل')
                    ->icon('heroicon-m-arrow-left')
                    ->url(fn () => route('filament.admin.resources.products.index'))
                    ->color('gray')
                    ->size('sm'),
            ])
            ->paginated(false)
            ->emptyStateHeading('لا توجد مبيعات بعد')
            ->emptyStateDescription('لم يتم بيع أي منتج حتى الآن')
            ->emptyStateIcon('heroicon-o-shopping-bag');
    }
}