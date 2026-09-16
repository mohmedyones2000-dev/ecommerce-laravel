<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\SizeGuideResource\Pages;
use App\Filament\Resources\SizeGuideResource\RelationManagers\ItemsRelationManager;
use App\Models\SizeGuide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SizeGuideResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'size_guides';

    protected static ?string $model = SizeGuide::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'أدلة المقاسات';

    protected static ?string $modelLabel = 'دليل مقاسات';

    protected static ?string $pluralModelLabel = 'أدلة المقاسات';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات الدليل')->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم الدليل')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('مثال: مقاسات الرجالي'),

                Forms\Components\Textarea::make('description')
                    ->label('ملاحظات / وصف')
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_active')
                    ->label('نشط')
                    ->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الدليل')
                    ->searchable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('المقاسات')
                    ->counts('items')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('المنتجات المرتبطة')
                    ->counts('products')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر تحديث')
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
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSizeGuides::route('/'),
            'create' => Pages\CreateSizeGuide::route('/create'),
            'edit' => Pages\EditSizeGuide::route('/{record}/edit'),
        ];
    }
}