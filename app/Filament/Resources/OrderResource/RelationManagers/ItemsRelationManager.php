<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'تفاصيل الطلب';

    protected static ?string $modelLabel = 'عنصر';

    protected static ?string $pluralModelLabel = 'العناصر';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('product_variant_id')
                ->label('المنتج (اللون/المقاس)')
                ->relationship(
                    name: 'variant',
                    titleAttribute: 'id',
                    modifyQueryUsing: fn (Builder $query) => $query->with(['product', 'color']),
                )
                ->getOptionLabelFromRecordUsing(fn (ProductVariant $record): string =>
                    ($record->product?->name ?? 'منتج محذوف')
                    . ' — ' . ($record->color ?? 'بدون لون')
                    . ' — ' . ($record->size ?? 'بدون مقاس')
                    . ' — (متوفر: ' . ($record->stock_quantity ?? 0) . ')')
                ->searchable(['id'])
                ->preload()
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, Set $set) {
                    if (! $state) {
                        return;
                    }

                    $variant = ProductVariant::with('product')->find($state);

                    if ($variant?->product) {
                        $set('unit_price', $variant->product->discount_price ?? $variant->product->price);
                    }
                })
                ->helperText('اختر المنتج مع اللون والمقاس. السعر يُملأ تلقائياً.'),

            Forms\Components\TextInput::make('quantity')
                ->label('الكمية')
                ->numeric()
                ->default(1)
                ->required()
                ->minValue(1)
                ->live(onBlur: true)
                ->rule(function (Get $get) {
                    $variantId = $get('product_variant_id');

                    if (! $variantId) {
                        return null;
                    }

                    $variant = ProductVariant::find($variantId);
                    $available = $variant?->stock_quantity ?? 0;

                    return "max:{$available}";
                })
                ->validationMessages([
                    'max' => 'الكمية المطلوبة أكبر من المخزون المتوفر.',
                ])
                ->helperText(fn (Get $get): ?string => $get('product_variant_id')
                    ? 'المتوفر: ' . (ProductVariant::find($get('product_variant_id'))?->stock_quantity ?? 0) . ' قطعة'
                    : null),

            Forms\Components\TextInput::make('unit_price')
                ->label('سعر الوحدة')
                ->numeric()
                ->prefix('$')
                ->required()
                ->minValue(0),
        ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\ImageColumn::make('variant.product.images.image_path')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(asset('images/product-placeholder.svg')),

                Tables\Columns\TextColumn::make('variant.product.name')
                    ->label('المنتج')
                    ->searchable()
                    ->weight('semibold')
                    ->limit(40)
                    ->tooltip(fn ($record): ?string => $record->variant?->product?->name),

                Tables\Columns\TextColumn::make('variant.color')
                    ->label('اللون')
                    ->badge()
                    ->color('gray')
                    ->alignCenter()
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('variant.size')
                    ->label('المقاس')
                    ->badge()
                    ->color('info')
                    ->alignCenter()
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('الكمية')
                    ->badge()
                    ->color('teal')
                    ->alignCenter()
                    ->suffix(' قطعة'),

                Tables\Columns\TextColumn::make('unit_price')
                    ->label('سعر الوحدة')
                    ->money('USD')
                    ->alignCenter()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('total')
                    ->label('الإجمالي')
                    ->getStateUsing(fn ($record): float =>
                        (float) $record->quantity * (float) $record->unit_price)
                    ->money('USD')
                    ->weight('bold')
                    ->color('success')
                    ->alignCenter(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('إضافة منتج للطلب')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->visible(fn (): bool => $this->canEditOrder())
                    ->after(function () {
                        $this->updateOrderTotal();
                        $this->notifyChange('تمت إضافة المنتج للطلب');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل')
                    ->icon('heroicon-o-pencil')
                    ->size('sm')
                    ->visible(fn (): bool => $this->canEditOrder())
                    ->after(function () {
                        $this->updateOrderTotal();
                        $this->notifyChange('تم تحديث المنتج');
                    }),

                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->icon('heroicon-o-trash')
                    ->size('sm')
                    ->visible(fn (): bool => $this->canEditOrder())
                    ->after(function () {
                        $this->updateOrderTotal();
                        $this->notifyChange('تم حذف المنتج من الطلب');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->visible(fn (): bool => $this->canEditOrder())
                        ->after(function () {
                            $this->updateOrderTotal();
                            $this->notifyChange('تم حذف المنتجات المحددة');
                        }),
                ]),
            ])
            ->emptyStateHeading('لا توجد منتجات في هذا الطلب')
            ->emptyStateDescription('أضف منتجات لبدء تجهيز الطلب')
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->striped()
            ->defaultSort('id', 'asc');
    }

    protected function canEditOrder(): bool
    {
        $order = $this->getOwnerRecord();

        return ! in_array($order->status, ['delivered', 'cancelled']);
    }

    protected function updateOrderTotal(): void
    {
        $order = $this->getOwnerRecord();

        $total = $order->items()
            ->get()
            ->sum(fn ($item) => (float) $item->quantity * (float) $item->unit_price);

        $order->update(['total_amount' => $total]);

        $order->refresh();
    }

    protected function notifyChange(string $message): void
    {
        Notification::make()
            ->title($message)
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }
}