<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Color;
use App\Models\Product;
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

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('المعلومات الأساسية')
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

            Forms\Components\Section::make('التصنيف والعلامة التجارية')
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

            Forms\Components\Section::make('التسعير')
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

            Forms\Components\Section::make('حالة المنتج')
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

            Forms\Components\Section::make('المتغيرات (الألوان والمقاسات)')
                ->description('اختر اللون من القائمة، وسيُحدد كود اللون تلقائياً. أضف المقاس والكمية')
                ->schema([
                    Forms\Components\Repeater::make('variants')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('color')
                                ->label('اللون')
                                ->options(function () {
                                    return Color::active()
                                        ->orderBy('name')
                                        ->pluck('name', 'name')
                                        ->toArray();
                                })
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set) {
                                    $color = Color::where('name', $state)->first();
                                    if ($color) {
                                        $set('hex_code', $color->hex_code);
                                    }
                                })
                                ->placeholder('اختر اللون')
                                ->required(),

                            Forms\Components\Hidden::make('hex_code'),

                            Forms\Components\TextInput::make('size')
                                ->label('المقاس')
                                ->maxLength(50)
                                ->placeholder('مثال: L'),

                            Forms\Components\TextInput::make('stock_quantity')
                                ->label('الكمية في المخزون')
                                ->numeric()
                                ->default(0)
                                ->required()
                                ->minValue(0),
                        ])
                        ->columns(3)
                        ->defaultItems(1)
                        ->addActionLabel('إضافة متغير')
                        ->reorderable(false)
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string =>
                            ($state['color'] ?? 'بدون لون') . ' — ' . ($state['size'] ?? 'بدون مقاس')),
                ]),

            Forms\Components\Section::make('صور المنتج')
                ->description('ارفع حتى 5 صور. الصورة الأولى ستكون الصورة الرئيسية')
                ->schema([
                    Forms\Components\FileUpload::make('images')
                        ->label('الصور')
                        ->multiple()
                        ->image()
                        ->directory('products')
                        ->maxFiles(5)
                        ->reorderable()
                        ->imageEditor()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images.image_path')
                    ->label('الصورة')
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?background=C9A961&color=fff&name=P'),

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