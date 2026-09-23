<?php

namespace App\Filament\Widgets;

use App\Models\ProductVariant;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockAlert extends BaseWidget
{
    protected static ?string $heading = 'تنبيهات المخزون';

    protected static ?string $description = 'المنتجات التي تحتاج إعادة تعبئة';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $pollingInterval = '30s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProductVariant::query()
                    ->with(['product.images', 'product.category'])
                    ->where('stock_quantity', '<=', 5)
                    ->orderBy('stock_quantity')
                    ->limit(5)
            )
            ->recordUrl(null)
            ->columns([
                Tables\Columns\ImageColumn::make('product.images.image_path')
                    ->label('الصورة')
                    ->disk('public')
                    ->circular()
                    ->size(48)
                    ->defaultImageUrl(asset('images/product-placeholder.svg')),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('المنتج')
                    ->searchable()
                    ->weight('semibold')
                    ->limit(40)
                    ->tooltip(fn (ProductVariant $record): ?string => $record->product?->name)
                    ->description(fn (ProductVariant $record): ?string =>
                        $record->product?->category?->name),

                Tables\Columns\TextColumn::make('color')
                    ->label('اللون')
                    ->badge()
                    ->color('gray')
                    ->alignCenter()
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('size')
                    ->label('المقاس')
                    ->badge()
                    ->color('info')
                    ->alignCenter()
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('المخزون')
                    ->badge()
                    ->weight('bold')
                    ->alignCenter()
                    ->sortable()
                    ->color(fn ($state) => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 2 => 'danger',
                        default     => 'warning',
                    })
                    ->formatStateUsing(fn ($state) => match (true) {
                        $state <= 0 => 'نفذ المخزون',
                        $state == 1 => 'قطعة واحدة',
                        $state == 2 => 'قطعتان',
                        $state <= 5 => $state . ' قطع',
                        default     => $state,
                    })
                    ->icon(fn ($state) => $state <= 0
                        ? 'heroicon-m-x-circle'
                        : 'heroicon-m-exclamation-triangle'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('manage_stock')
                    ->label('إدارة المخزون')
                    ->icon('heroicon-m-arrow-left')
                    ->url(fn () => route('filament.admin.resources.products.index'))
                    ->color('gray')
                    ->size('sm'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('تعديل')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (ProductVariant $record) =>
                        route('filament.admin.resources.products.edit', $record->product_id))
                    ->color('gray')
                    ->size('sm'),
            ])
            ->paginated(false)
            ->emptyStateHeading('لا توجد تنبيهات')
            ->emptyStateDescription('جميع المنتجات لديها مخزون كافٍ')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}