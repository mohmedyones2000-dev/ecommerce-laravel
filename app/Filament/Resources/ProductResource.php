<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SubCategory;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('المعلومات الأساسية')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('اسم المنتج')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                            $operation === 'create' ? $set('slug', Str::slug($state) . '-' . rand(100, 999)) : null),

                    Forms\Components\TextInput::make('slug')
                        ->label('المعرّف الفريد (Slug)')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->helperText('المعرّف الفريد للمنتج في الرابط'),

                    Forms\Components\Textarea::make('description')
                        ->label('الوصف')
                        ->rows(4)
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('التصنيف والعلامة التجارية')
                ->schema([
                    Forms\Components\Select::make('category_id')
                        ->label('التصنيف الرئيسي')
                        ->relationship('category', 'name')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (Set $set) => $set('sub_category_id', null)),

                    Forms\Components\Select::make('sub_category_id')
                        ->label('التصنيف الفرعي')
                        ->options(function (Get $get) {
                            $categoryId = $get('category_id');

                            if (!$categoryId) {
                                return [];
                            }

                            $subCategoryIds = \DB::table('category_sub_category')
                                ->where('category_id', $categoryId)
                                ->pluck('sub_category_id')
                                ->toArray();

                            return SubCategory::query()
                                ->whereIn('id', $subCategoryIds)
                                ->pluck('name', 'id')
                                ->toArray();
                        })
                        ->searchable()
                        ->live()
                        ->placeholder('اختر التصنيف الفرعي')
                        ->helperText('تظهر التصنيفات الفرعية التابعة للتصنيف الرئيسي المختار'),

                    Forms\Components\Select::make('brand_id')
                        ->label('العلامة التجارية')
                        ->relationship('brand', 'name')
                        ->searchable()
                        ->preload()
                        ->placeholder('اختر العلامة التجارية'),

                    Forms\Components\Select::make('size_guide_id')
                        ->label('دليل المقاسات')
                        ->relationship('sizeGuide', 'name', fn ($query) => $query->where('is_active', true))
                        ->searchable()
                        ->preload()
                        ->placeholder('اختر دليل المقاسات'),
                ])->columns(2),

            Section::make('التسعير')
                ->schema([
                    Forms\Components\TextInput::make('price')
                        ->label('السعر الأساسي')
                        ->numeric()
                        ->prefix('$')
                        ->required()
                        ->minValue(0),

                    Forms\Components\TextInput::make('discount_price')
                        ->label('سعر الخصم')
                        ->numeric()
                        ->prefix('$')
                        ->nullable()
                        ->minValue(0)
                        ->helperText('اتركه فارغاً إذا لم يكن هناك خصم'),
                ])->columns(2),

            Section::make('حالة المنتج')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('نشط')
                        ->helperText('ظاهر في المتجر')
                        ->default(true)
                        ->onColor('success'),

                    Forms\Components\Toggle::make('is_featured')
                        ->label('منتج مميز')
                        ->helperText('يظهر في الصفحة الرئيسية')
                        ->default(false)
                        ->onColor('warning'),
                ])->columns(2),

            Section::make('الألوان والمقاسات')
                ->description('أضف لوناً، ثم أضف له المقاسات المتوفرة مع الكميات.')
                ->schema([
                    Repeater::make('color_groups')
                        ->label('')
                        ->schema([
                            Select::make('color')
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

                            Hidden::make('hex_code'),

                            Repeater::make('sizes')
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

            Section::make('صور المنتج')
                ->description('أضف صورة لكل لون. اضغط على الصورة لتحريرها. 3 صور على الأقل لكل لون، 7 كحد أقصى. الصور الأولى للّون الأول تظهر في بطاقة المنتج.')
                ->schema([
                    Repeater::make('images')
                        ->relationship()
                        ->label('')
                        ->schema([
                            Select::make('color')
                                ->label('اللون')
                                ->options(function (Get $get) {
                                    $colorGroups = $get('../../color_groups') ?? [];

                                    $colorsFromForm = collect($colorGroups)
                                        ->pluck('color')
                                        ->filter()
                                        ->unique()
                                        ->mapWithKeys(fn ($c) => [$c => $c])
                                        ->toArray();

                                    if (!empty($colorsFromForm)) {
                                        return $colorsFromForm;
                                    }

                                    $productId = $get('../../id');

                                    if ($productId) {
                                        $colors = ProductVariant::where('product_id', $productId)
                                            ->whereNotNull('color')
                                            ->distinct()
                                            ->pluck('color', 'color')
                                            ->toArray();

                                        if (!empty($colors)) {
                                            return $colors;
                                        }
                                    }

                                    return Color::active()->pluck('name', 'name')->toArray();
                                })
                                ->searchable()
                                ->required()
                                ->placeholder('اختر اللون'),

                            FileUpload::make('image_path')
                                ->label('الصورة')
                                ->image()
                                ->directory('products')
                                ->imageEditor()
                                ->imagePreviewHeight('80')
                                ->openable()
                                ->downloadable()
                                ->required()
                                ->maxSize(5120),

                            Hidden::make('sort_order')
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
    ->defaultImageUrl(asset('images/product-placeholder.svg')),

                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المنتج')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->limit(30),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('التصنيف')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('brand.name')
                    ->label('العلامة التجارية')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('sizeGuide.name')
                    ->label('دليل المقاسات')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('discount_price')
                    ->label('سعر الخصم')
                    ->money('USD')
                    ->color('danger')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('variants_sum_stock_quantity')
                    ->label('المخزون')
                    ->sum('variants', 'stock_quantity')
                    ->badge()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger'))
                    ->suffix(' قطعة'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('مميز')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('التصنيف الرئيسي')
                    ->relationship('category', 'name'),

                Tables\Filters\SelectFilter::make('brand')
                    ->label('العلامة التجارية')
                    ->relationship('brand', 'name'),

                Tables\Filters\SelectFilter::make('size_guide')
                    ->label('دليل المقاسات')
                    ->relationship('sizeGuide', 'name'),

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
                    ->query(fn ($query) => $query->whereNotNull('discount_price')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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