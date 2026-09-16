<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\AddressResource\Pages;
use App\Models\Address;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AddressResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'addresses';

    protected static ?string $model = Address::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'العناوين';

    protected static ?string $modelLabel = 'عنوان';

    protected static ?string $pluralModelLabel = 'العناوين';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('العميل')
                ->relationship('user', 'name')
                ->disabled(),

            Forms\Components\Select::make('city_id')
                ->label('المدينة')
                ->relationship('city', 'name')
                ->disabled(),

            Forms\Components\TextInput::make('street_address')
                ->label('العنوان التفصيلي')
                ->disabled()
                ->columnSpanFull(),

            Forms\Components\TextInput::make('phone')
                ->label('الهاتف')
                ->disabled(),

            Forms\Components\Textarea::make('notes')
                ->label('ملاحظات')
                ->disabled()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل')
                    ->searchable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('city.name')
                    ->label('المدينة')
                    ->sortable(),

                Tables\Columns\TextColumn::make('street_address')
                    ->label('العنوان')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('city')
                    ->label('المدينة')
                    ->relationship('city', 'name'),
            ])
            ->actions([
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
            'index' => Pages\ListAddresses::route('/'),
            'edit' => Pages\EditAddress::route('/{record}/edit'),
        ];
    }
}