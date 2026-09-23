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
use Illuminate\Database\Eloquent\Builder;

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

    protected static ?string $recordTitleAttribute = 'street_address';

    protected static ?int $navigationSort = 12;

    public static function getNavigationBadge(): ?string
    {
        $newThisWeek = Address::where('created_at', '>=', now()->subWeek())->count();

        return $newThisWeek > 0 ? '+' . $newThisWeek : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['street_address', 'phone'];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات العنوان')
                ->description('العنوان مُدار من قبل العميل، للقراءة فقط')
                ->icon('heroicon-o-home-modern')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('العميل')
                        ->relationship('user', 'name')
                        ->prefixIcon('heroicon-o-user')
                        ->disabled(),

                    Forms\Components\Select::make('city_id')
                        ->label('المدينة')
                        ->relationship('city', 'name')
                        ->prefixIcon('heroicon-o-map-pin')
                        ->disabled(),

                    Forms\Components\TextInput::make('street_address')
                        ->label('العنوان التفصيلي')
                        ->prefixIcon('heroicon-o-map')
                        ->disabled()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('phone')
                        ->label('الهاتف')
                        ->prefixIcon('heroicon-o-phone')
                        ->disabled(),

                    Forms\Components\DateTimePicker::make('created_at')
                        ->label('تاريخ الإضافة')
                        ->prefixIcon('heroicon-o-calendar')
                        ->native(false)
                        ->disabled(),

                    Forms\Components\Textarea::make('notes')
                        ->label('ملاحظات')
                        ->rows(3)
                        ->disabled()
                        ->placeholder('لا توجد ملاحظات')
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->icon('heroicon-m-user')
                    ->iconColor('gray')
                    ->description(fn (Address $record): ?string => $record->user?->email),

                Tables\Columns\TextColumn::make('city.name')
                    ->label('المدينة')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->alignCenter()
                    ->icon('heroicon-m-map-pin')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('street_address')
                    ->label('العنوان')
                    ->limit(40)
                    ->searchable()
                    ->tooltip(fn (Address $record): ?string => $record->street_address),

                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->copyable()
                    ->copyMessage('تم نسخ الهاتف')
                    ->icon('heroicon-m-phone')
                    ->iconColor('gray')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('notes')
                    ->label('ملاحظات')
                    ->limit(30)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->since()
                    ->tooltip(fn (Address $record): string =>
                        $record->created_at?->format('Y-m-d H:i') ?? '')
                    ->color('gray')
                    ->icon('heroicon-m-calendar')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->label('العميل')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('city')
                    ->label('المدينة')
                    ->relationship('city', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('has_phone')
                    ->label('يحتوي على هاتف')
                    ->query(fn (Builder $query) => $query
                        ->whereNotNull('phone')
                        ->where('phone', '!=', ''))
                    ->toggle(),

                Tables\Filters\Filter::make('has_notes')
                    ->label('يحتوي على ملاحظات')
                    ->query(fn (Builder $query) => $query
                        ->whereNotNull('notes')
                        ->where('notes', '!=', ''))
                    ->toggle(),

                Tables\Filters\Filter::make('recent')
                    ->label('أُضيفت آخر 30 يوم')
                    ->query(fn (Builder $query) =>
                        $query->where('created_at', '>=', now()->subDays(30)))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض')
                    ->icon('heroicon-o-eye')
                    ->size('sm'),

                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->icon('heroicon-o-trash')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('حذف العنوان')
                    ->modalDescription(fn (Address $record): string =>
                        "هل أنت متأكد من حذف عنوان \"{$record->user?->name}\"؟ لا يمكن التراجع.")
                    ->modalSubmitActionLabel('نعم، احذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation()
                        ->modalHeading('حذف العناوين المحددة')
                        ->modalDescription('سيتم حذف جميع العناوين المحددة. لا يمكن التراجع.')
                        ->modalSubmitActionLabel('نعم، احذف الكل'),
                ]),
            ])
            ->emptyStateHeading('لا توجد عناوين')
            ->emptyStateDescription('لم يقم أي عميل بإضافة عنوان بعد')
            ->emptyStateIcon('heroicon-o-home-modern')
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->persistSearchInSession()
            ->deferLoading();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAddresses::route('/'),
            'edit'  => Pages\EditAddress::route('/{record}/edit'),
        ];
    }
}