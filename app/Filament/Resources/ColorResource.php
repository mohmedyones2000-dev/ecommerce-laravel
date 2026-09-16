<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\ColorResource\Pages;
use App\Models\Color;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ColorResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'colors';

    protected static ?string $model = Color::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'الألوان';

    protected static ?string $modelLabel = 'لون';

    protected static ?string $pluralModelLabel = 'الألوان';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات اللون')->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم اللون')
                    ->required()
                    ->maxLength(50)
                    ->placeholder('أحمر، أزرق...'),

                Forms\Components\ColorPicker::make('hex_code')
                    ->label('كود اللون')
                    ->required()
                    ->default('#000000'),

                Forms\Components\Toggle::make('is_active')
                    ->label('نشط')
                    ->default(true),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ColorColumn::make('hex_code')
                    ->label('اللون')
                    ->copyable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('hex_code')
                    ->label('الكود')
                    ->badge()
                    ->color('gray')
                    ->copyable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListColors::route('/'),
            'create' => Pages\CreateColor::route('/create'),
            'edit' => Pages\EditColor::route('/{record}/edit'),
        ];
    }
}