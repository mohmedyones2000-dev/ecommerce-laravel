<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'categories';

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'التصنيفات الرئيسية';

    protected static ?string $modelLabel = 'تصنيف رئيسي';

    protected static ?string $pluralModelLabel = 'التصنيفات الرئيسية';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات التصنيف')->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم التصنيف')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                Forms\Components\TextInput::make('slug')
                    ->label('المعرّف الفريد (Slug)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('يُولَّد تلقائياً من الاسم، ويمكنك تعديله يدوياً'),

                Forms\Components\Select::make('subCategories')
                    ->label('التصنيفات الفرعية')
                    ->relationship('subCategories', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->helperText('اختر التصنيفات الفرعية التي تنتمي لهذا التصنيف الرئيسي'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('المعرّف الفريد')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('sub_categories_count')
                    ->label('التصنيفات الفرعية')
                    ->counts('subCategories')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('المنتجات')
                    ->counts('products')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}