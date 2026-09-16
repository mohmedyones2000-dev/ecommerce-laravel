<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\CityResource\Pages;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CityResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'cities';

    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'المدن';

    protected static ?string $modelLabel = 'مدينة';

    protected static ?string $pluralModelLabel = 'المدن';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات المدينة')->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم المدينة')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('code')
                    ->label('الرمز')
                    ->maxLength(255),
            ])->columns(2),

            Forms\Components\Section::make('إعدادات الشحن')->schema([
                Forms\Components\Toggle::make('is_free_shipping')
                    ->label('شحن مجاني')
                    ->helperText('عند تفعيل هذا الخيار، تُلغى تكلفة الشحن لهذه المدينة')
                    ->live()
                    ->default(false)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('shipping_cost')
                    ->label('تكلفة الشحن ($)')
                    ->numeric()
                    ->prefix('$')
                    ->default(0)
                    ->minValue(0)
                    ->visible(fn (Forms\Get $get) => !$get('is_free_shipping'))
                    ->helperText('تكلفة الشحن لهذه المدينة'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المدينة')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('code')
                    ->label('الرمز')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('shipping_cost')
                    ->label('تكلفة الشحن')
                    ->sortable()
                    ->formatStateUsing(fn ($record, $state) =>
                        $record->hasFreeShipping() ? 'مجاني' : '$' . number_format($state, 2)
                    )
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('addresses_count')
                    ->label('العناوين')
                    ->counts('addresses')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_free_shipping')
                    ->label('شحن مجاني')
                    ->placeholder('الكل')
                    ->trueLabel('مدن بشحن مجاني')
                    ->falseLabel('مدن بشحن مدفوع'),
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
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }
}