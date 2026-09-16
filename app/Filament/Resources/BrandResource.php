<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BrandResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'brands';

    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'العلامات التجارية';

    protected static ?string $modelLabel = 'علامة تجارية';

    protected static ?string $pluralModelLabel = 'العلامات التجارية';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات العلامة التجارية')->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم العلامة التجارية')
                    ->required()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('logo')
                    ->label('شعار العلامة التجارية')
                    ->image()
                    ->directory('brands')
                    ->maxSize(2048)
                    ->imageEditor()
                    ->helperText('ارفع صورة الشعار بخلفية شفافة PNG إن أمكن'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('الشعار')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=C9A961&color=fff'),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}