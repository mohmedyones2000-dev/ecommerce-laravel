<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\SubCategoryResource\Pages;
use App\Models\SubCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SubCategoryResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'sub_categories';

    protected static ?string $model = SubCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'التصنيفات الفرعية';

    protected static ?string $modelLabel = 'تصنيف فرعي';

    protected static ?string $pluralModelLabel = 'التصنيفات الفرعية';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات التصنيف الفرعي')->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم التصنيف الفرعي')
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

                Forms\Components\Select::make('categories')
                    ->label('التصنيفات الرئيسية')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->helperText('اختر التصنيفات الرئيسية التي ينتمي إليها هذا التصنيف الفرعي'),
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

                Tables\Columns\TextColumn::make('categories.name')
                    ->label('التصنيفات الرئيسية')
                    ->badge()
                    ->color('gray')
                    ->separator(','),

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
            ->filters([
                Tables\Filters\SelectFilter::make('categories')
                    ->label('التصنيف الرئيسي')
                    ->relationship('categories', 'name'),
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
            'index' => Pages\ListSubCategories::route('/'),
            'create' => Pages\CreateSubCategory::route('/create'),
            'edit' => Pages\EditSubCategory::route('/{record}/edit'),
        ];
    }
}