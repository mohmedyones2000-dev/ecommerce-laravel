<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\ProductVariantResource\Pages;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductVariantResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'products';

    protected static ?string $model = ProductVariant::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'متغيرات المنتجات';

    protected static ?string $modelLabel = 'متغير';

    protected static ?string $pluralModelLabel = 'متغيرات المنتجات';

    protected static ?string $recordTitleAttribute = 'size';

    protected static ?int $navigationSort = 7;

    public static function getNavigationBadge(): ?string
    {
        $lowStock = ProductVariant::where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 5)
            ->count();

        return $lowStock > 0 ? (string) $lowStock : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات المتغير')
                ->description('المنتج، اللون، والمقاس')
                ->icon('heroicon-o-squares-2x2')
                ->schema([
                    Forms\Components\Select::make('product_id')
                        ->label('المنتج')
                        ->relationship('product', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->prefixIcon('heroicon-o-shopping-bag'),

                    Forms\Components\TextInput::make('color')
                        ->label('اللون')
                        ->maxLength(50)
                        ->prefixIcon('heroicon-o-swatch')
                        ->placeholder('أحمر / أزرق / أسود'),

                    Forms\Components\TextInput::make('hex_code')
                        ->label('كود اللون (HEX)')
                        ->maxLength(7)
                        ->prefixIcon('heroicon-o-paint-brush')
                        ->placeholder('#FF0000'),

                    Forms\Components\TextInput::make('size')
                        ->label('المقاس')
                        ->maxLength(50)
                        ->prefixIcon('heroicon-o-tag')
                        ->placeholder('S / M / L / XL'),

                    Forms\Components\TextInput::make('stock_quantity')
                        ->label('الكمية في المخزون')
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->default(0)
                        ->prefixIcon('heroicon-o-cube'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('المنتج')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->limit(35)
                    ->tooltip(fn (ProductVariant $record): ?string => $record->product?->name),

                Tables\Columns\ColorColumn::make('hex_code')
                    ->label('اللون')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('color')
                    ->label('اسم اللون')
                    ->badge()
                    ->color('gray')
                    ->alignCenter()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('size')
                    ->label('المقاس')
                    ->badge()
                    ->color('info')
                    ->alignCenter()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('المخزون')
                    ->badge()
                    ->alignCenter()
                    ->sortable()
                    ->color(fn ($state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default     => 'success',
                    })
                    ->icon(fn ($state): string => match (true) {
                        $state <= 0 => 'heroicon-m-x-circle',
                        $state <= 5 => 'heroicon-m-exclamation-triangle',
                        default     => 'heroicon-m-check-circle',
                    })
                    ->suffix(' قطعة'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->since()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product')
                    ->label('المنتج')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('low_stock')
                    ->label('مخزون منخفض (1-5)')
                    ->query(fn (Builder $query) => $query
                        ->where('stock_quantity', '>', 0)
                        ->where('stock_quantity', '<=', 5))
                    ->toggle(),

                Tables\Filters\Filter::make('out_of_stock')
                    ->label('نفذ المخزون')
                    ->query(fn (Builder $query) => $query->where('stock_quantity', '<=', 0))
                    ->toggle(),

                Tables\Filters\Filter::make('in_stock')
                    ->label('متوفر')
                    ->query(fn (Builder $query) => $query->where('stock_quantity', '>', 5))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل')
                    ->icon('heroicon-o-pencil')
                    ->size('sm'),

                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->icon('heroicon-o-trash')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('حذف المتغير')
                    ->modalDescription('هل أنت متأكد من حذف هذا المتغير؟ لا يمكن التراجع.')
                    ->modalSubmitActionLabel('نعم، احذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('لا توجد متغيرات')
            ->emptyStateDescription('المتغيرات تُضاف عادةً من صفحة تعديل المنتج')
            ->emptyStateIcon('heroicon-o-squares-2x2')
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->persistSearchInSession()
            ->deferLoading();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProductVariants::route('/'),
            'create' => Pages\CreateProductVariant::route('/create'),
            'edit'   => Pages\EditProductVariant::route('/{record}/edit'),
        ];
    }
}