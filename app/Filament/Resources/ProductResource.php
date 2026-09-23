<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SubCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'products';

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'المنتجات';

    protected static ?string $modelLabel = 'منتج';

    protected static ?string $pluralModelLabel = 'المنتجات';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $inactive = Product::where('is_active', false)->count();

        return $inactive > 0 ? (string) $inactive : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('المعلومات الأساسية')
                ->description('اسم المنتج، الرابط، والوصف')
                ->icon('heroicon-o-information-circle')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('اسم المنتج')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-tag')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                            $operation === 'create'
                                ? $set('slug', Str::slug($state) . '-' . rand(100, 999))
                                : null),

                    Forms\Components\TextInput::make('slug')
                        ->label('المعرّف الفريد (Slug)')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-link')
                        ->helperText('المعرّف الفريد للمنتج في الرابط'),

                    Forms\Components\Textarea::make('description')
                        ->label('الوصف')
                        ->rows(4)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('التصنيف والعلامة التجارية')
                ->description('ربط المنتج بالتصنيف والعلامة التجارية')
                ->icon('heroicon-o-folder')
                ->schema([
                    Forms\Components\Select::make('category_id')
                        ->label('التصنيف الرئيسي')
                        ->relationship('category', 'name')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->prefixIcon('heroicon-o-folder-open')
                        ->live()
                        ->afterStateUpdated(fn (Set $set) => $set('sub_category_id', null)),

                    Forms\Components\Select::make('sub_category_id')
                        ->label('التصنيف الفرعي')
                        ->options(function (Get $get) {
                            $categoryId = $get('category_id');

                            if (! $categoryId) {
                                return [];
                            }

                            $category = Category::with('subCategories')->find($categoryId);

                            if (! $category) {
                                return [];
                            }

                            return $category->subCategories
                                ->pluck('name', 'id')
                                ->toArray();
                        })
                        ->searchable()
                        ->live()
                        ->prefixIcon('heroicon-o-folder-arrow-down')
                        ->placeholder('اختر التصنيف الفرعي')
                        ->helperText('تظهر التصنيفات الفرعية التابعة للتصنيف الرئيسي المختار'),

                    Forms\Components\Select::make('brand_id')
                        ->label('العلامة التجارية')
                        ->relationship('brand', 'name')
                        ->searchable()
                        ->preload()
                        ->prefixIcon('heroicon-o-building-storefront')
                        ->placeholder('اختر العلامة التجارية'),

                    Forms\Components\Select::make('size_guide_id')
                        ->label('دليل المقاسات')
                        ->relationship('sizeGuide', 'name', fn ($query) => $query->where('is_active', true))
                        ->searchable()
                        ->preload()
                        ->prefixIcon('heroicon-o-rectangle-group')
                        ->placeholder('اختر دليل المقاسات'),
                ])->columns(2),

            Forms\Components\Section::make('التسعير')
                ->description('السعر الأساسي وسعر الخصم')
                ->icon('heroicon-o-currency-dollar')
                ->schema([
                    Forms\Components\TextInput::make('price')
                        ->label('السعر الأساسي')
                        ->numeric()
                        ->prefix('$')
                        ->required()
                        ->minValue(0)
                        ->live(onBlur: true),

                    Forms\Components\TextInput::make('discount_price')
                        ->label('سعر الخصم')
                        ->numeric()
                        ->prefix('$')
                        ->nullable()
                        ->minValue(0)
                        ->rule(fn (Get $get) =>
                            $get('price') && $get('discount_price') && $get('discount_price') >= $get('price')
                                ? 'nullable|lt:price'
                                : 'nullable')
                        ->helperText('اتركه فارغاً إذا لم يكن هناك خصم. يجب أن يكون أقل من السعر الأساسي'),
                ])->columns(2),

            Forms\Components\Section::make('حالة المنتج')
                ->description('ظهور المنتج في المتجر')
                ->icon('heroicon-o-eye')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('نشط')
                        ->helperText('ظاهر في المتجر')
                        ->default(true)
                        ->onColor('success')
                        ->offColor('danger'),

                    Forms\Components\Toggle::make('is_featured')
                        ->label('منتج مميز')
                        ->helperText('يظهر في الصفحة الرئيسية')
                        ->default(false)
                        ->onColor('warning'),
                ])->columns(2),

            Forms\Components\Section::make('الألوان والمقاسات')
                ->description('أضف لوناً، ثم أضف له المقاسات المتوفرة مع الكميات.')
                ->icon('heroicon-o-swatch')
                ->schema([
                    Forms\Components\Repeater::make('color_groups')
                        ->label('')
                        ->schema([
                            Forms\Components\Select::make('color')
                                ->label('اللون')
                                ->options(fn () => Color::active()->orderBy('name')->pluck('name', 'name')->toArray())
                                ->searchable()
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set) {
                                    $color = Color::where('name', $state)->first();
                                    if ($color) {
                                        $set('hex_code', $color->hex_code);
                                    }
                                })
                                ->placeholder('اختر اللون'),

                            Forms\Components\Hidden::make('hex_code'),

                            Forms\Components\Repeater::make('sizes')
                                ->label('المقاسات')
                                ->schema([
                                    Forms\Components\TextInput::make('size')
                                        ->label('المقاس')
                                        ->required()
                                        ->maxLength(50)
                                        ->placeholder('S / M / L / XL'),

                                    Forms\Components\TextInput::make('stock_quantity')
                                        ->label('الكمية')
                                        ->numeric()
                                        ->default(0)
                                        ->required()
                                        ->minValue(0),
                                ])
                                ->columns(2)
                                ->defaultItems(1)
                                ->minItems(1)
                                ->addActionLabel('+ إضافة مقاس')
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string =>
                                    'مقاس: ' . ($state['size'] ?? '—') . ' | كمية: ' . ($state['stock_quantity'] ?? 0)),
                        ])
                        ->columns(1)
                        ->defaultItems(1)
                        ->minItems(0)
                        ->addActionLabel('+ إضافة لون جديد')
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(function (array $state): ?string {
                            $color = $state['color'] ?? 'لون جديد';
                            $sizesCount = count($state['sizes'] ?? []);

                            return $color . ' — ' . $sizesCount . ' مقاس';
                        })
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(false),

            Forms\Components\Section::make('صور المنتج')
                ->description('أضف صورة لكل لون. 3 صور على الأقل لكل لون، 7 كحد أقصى. الصور الأولى للّون الأول تظهر في بطاقة المنتج.')
                ->icon('heroicon-o-photo')
                ->schema([
                    Forms\Components\Repeater::make('images')
                        ->relationship()
                        ->label('')
                        ->schema([
                            Forms\Components\Select::make('color')
                                ->label('اللون')
                                ->options(function (Get $get) {
                                    $colorGroups = $get('../../color_groups') ?? [];

                                    $colorsFromForm = collect($colorGroups)
                                        ->pluck('color')
                                        ->filter()
                                        ->unique()
                                        ->mapWithKeys(fn ($c) => [$c => $c])
                                        ->toArray();

                                    if (! empty($colorsFromForm)) {
                                        return $colorsFromForm;
                                    }

                                    $productId = $get('../../id');

                                    if ($productId) {
                                        $colors = ProductVariant::where('product_id', $productId)
                                            ->whereNotNull('color')
                                            ->distinct()
                                            ->pluck('color', 'color')
                                            ->toArray();

                                        if (! empty($colors)) {
                                            return $colors;
                                        }
                                    }

                                    return Color::active()->pluck('name', 'name')->toArray();
                                })
                                ->searchable()
                                ->required()
                                ->placeholder('اختر اللون'),

                            Forms\Components\FileUpload::make('image_path')
                                ->label('الصورة')
                                ->image()
                                ->directory('products')
                                ->imageEditor()
                                ->imagePreviewHeight('80')
                                ->openable()
                                ->downloadable()
                                ->required()
                                ->maxSize(5120),

                            Forms\Components\Hidden::make('sort_order')
                                ->default(0),
                        ])
                        ->columns(2)
                        ->addActionLabel('+ إضافة صورة')
                        ->minItems(0)
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible()
                        ->cloneable()
                        ->itemLabel(fn (array $state): ?string =>
                            $state['color'] ?? 'صورة جديدة')
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images.image_path')
                    ->label('الصورة')
                    ->disk('public')
                    ->circular()
                    ->size(48)
                    ->defaultImageUrl(asset('images/product-placeholder.svg')),

                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المنتج')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->limit(30)
                    ->tooltip(fn (Product $record): string => $record->name),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('التصنيف')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('brand.name')
                    ->label('العلامة التجارية')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('sizeGuide.name')
                    ->label('دليل المقاسات')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')
                    ->money('USD')
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('discount_price')
                    ->label('سعر الخصم')
                    ->money('USD')
                    ->color('danger')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('variants_sum_stock_quantity')
                    ->label('المخزون')
                    ->sum('variants', 'stock_quantity')
                    ->badge()
                    ->alignCenter()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger'))
                    ->icon(fn ($state) => $state > 10
                        ? 'heroicon-m-check-circle'
                        : ($state > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-x-circle'))
                    ->suffix(' قطعة'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('مميز')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('التصنيف الرئيسي')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('brand')
                    ->label('العلامة التجارية')
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('size_guide')
                    ->label('دليل المقاسات')
                    ->relationship('sizeGuide', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط')
                    ->placeholder('الكل')
                    ->trueLabel('النشطة فقط')
                    ->falseLabel('غير النشطة'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('مميز')
                    ->placeholder('الكل')
                    ->trueLabel('المميزة فقط')
                    ->falseLabel('غير المميزة'),

                Tables\Filters\Filter::make('has_discount')
                    ->label('يحتوي على خصم')
                    ->query(fn ($query) => $query->whereNotNull('discount_price'))
                    ->toggle(),

                Tables\Filters\Filter::make('low_stock')
                    ->label('مخزون منخفض')
                    ->query(fn ($query) =>
                        $query->whereHas('variants', fn ($q) =>
                            $q->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 5)))
                    ->toggle(),

                Tables\Filters\Filter::make('out_of_stock')
                    ->label('نفذ المخزون')
                    ->query(fn ($query) =>
                        $query->whereDoesntHave('variants', fn ($q) =>
                            $q->where('stock_quantity', '>', 0)))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل')
                    ->size('sm'),
                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->size('sm'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد'),

                    Tables\Actions\BulkAction::make('activate')
                        ->label('تفعيل')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('تعطيل')
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->emptyStateHeading('لا توجد منتجات')
            ->emptyStateDescription('ابدأ بإضافة أول منتج للمتجر')
            ->emptyStateIcon('heroicon-o-shopping-bag')
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/edit/{record}'),
        ];
    }
}