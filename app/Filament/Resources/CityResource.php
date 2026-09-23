<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\CityResource\Pages;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 10;

    public static function getNavigationBadge(): ?string
    {
        $freeShipping = City::where('is_free_shipping', true)->count();

        return $freeShipping > 0 ? (string) $freeShipping : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'code'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات المدينة')
                ->description('اسم المدينة والرمز')
                ->icon('heroicon-o-map-pin')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('اسم المدينة')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-map-pin')
                        ->placeholder('مثال: الرياض، جدة'),

                    Forms\Components\TextInput::make('code')
                        ->label('الرمز')
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-hashtag')
                        ->placeholder('مثال: RUH، JED'),
                ])->columns(2),

            Forms\Components\Section::make('إعدادات الشحن')
                ->description('تكلفة الشحن لهذه المدينة')
                ->icon('heroicon-o-truck')
                ->schema([
                    Forms\Components\Toggle::make('is_free_shipping')
                        ->label('شحن مجاني')
                        ->helperText('عند تفعيل هذا الخيار، تُلغى تكلفة الشحن لهذه المدينة')
                        ->live()
                        ->default(false)
                        ->onColor('success')
                        ->offColor('gray')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('shipping_cost')
                        ->label('تكلفة الشحن')
                        ->numeric()
                        ->prefix('$')
                        ->default(0)
                        ->minValue(0)
                        ->prefixIcon('heroicon-o-banknotes')
                        ->visible(fn (Get $get): bool => ! $get('is_free_shipping'))
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
                    ->weight('semibold')
                    ->icon('heroicon-m-map-pin')
                    ->iconColor('gray'),

                Tables\Columns\TextColumn::make('code')
                    ->label('الرمز')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->alignCenter()
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('shipping_status')
                    ->label('حالة الشحن')
                    ->badge()
                    ->alignCenter()
                    ->getStateUsing(fn (City $record): string =>
                        $record->hasFreeShipping() ? 'مجاني' : 'مدفوع')
                    ->color(fn (string $state): string => match ($state) {
                        'مجاني' => 'success',
                        'مدفوع' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'مجاني' => 'heroicon-m-gift',
                        'مدفوع' => 'heroicon-m-banknotes',
                        default => 'heroicon-m-question-mark-circle',
                    }),

                Tables\Columns\TextColumn::make('shipping_cost')
                    ->label('تكلفة الشحن')
                    ->sortable()
                    ->alignCenter()
                    ->weight('semibold')
                    ->formatStateUsing(fn (City $record): string =>
                        $record->hasFreeShipping()
                            ? 'مجاني'
                            : '$' . number_format((float) $record->shipping_cost, 2))
                    ->color(fn (City $record): string =>
                        $record->hasFreeShipping() ? 'success' : 'gray'),

                Tables\Columns\TextColumn::make('addresses_count')
                    ->label('العناوين')
                    ->counts('addresses')
                    ->badge()
                    ->color('info')
                    ->alignCenter()
                    ->suffix(' عنوان')
                    ->icon('heroicon-m-home')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->since()
                    ->tooltip(fn (City $record): string =>
                        $record->created_at?->format('Y-m-d H:i') ?? '')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_free_shipping')
                    ->label('شحن مجاني')
                    ->placeholder('الكل')
                    ->trueLabel('مدن بشحن مجاني')
                    ->falseLabel('مدن بشحن مدفوع'),

                Tables\Filters\Filter::make('has_addresses')
                    ->label('لديها عناوين')
                    ->query(fn (Builder $query) => $query->has('addresses'))
                    ->toggle(),

                Tables\Filters\Filter::make('no_addresses')
                    ->label('بدون عناوين')
                    ->query(fn (Builder $query) => $query->doesntHave('addresses'))
                    ->toggle(),

                Tables\Filters\Filter::make('recent')
                    ->label('أُضيفت حديثاً')
                    ->query(fn (Builder $query) =>
                        $query->where('created_at', '>=', now()->subDays(30)))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل')
                    ->icon('heroicon-o-pencil')
                    ->size('sm'),

                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->icon('heroicon-o-trash')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('حذف المدينة')
                    ->modalDescription(fn (City $record): string =>
                        $record->addresses()->count() > 0
                            ? "تحذير: هذه المدينة مرتبطة بـ " . $record->addresses()->count() . " عنوان. لن تُحذف العناوين، لكن ستفقد الربط."
                            : "هل أنت متأكد من حذف المدينة \"{$record->name}\"؟ لا يمكن التراجع.")
                    ->modalSubmitActionLabel('نعم، احذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('make_free_shipping')
                        ->label('شحن مجاني')
                        ->icon('heroicon-m-gift')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update([
                            'is_free_shipping' => true,
                            'shipping_cost' => 0,
                        ]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('make_paid_shipping')
                        ->label('شحن مدفوع')
                        ->icon('heroicon-m-banknotes')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('تحويل إلى شحن مدفوع')
                        ->modalDescription('سيتم تعطيل الشحن المجاني لهذه المدن. يمكنك تعديل التكلفة من صفحة كل مدينة.')
                        ->modalSubmitActionLabel('نعم، حوّل')
                        ->action(fn ($records) => $records->each->update([
                            'is_free_shipping' => false,
                        ]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation()
                        ->modalHeading('حذف المدن المحددة')
                        ->modalDescription('سيتم حذف جميع المدن المحددة. لا يمكن التراجع.')
                        ->modalSubmitActionLabel('نعم، احذف الكل'),
                ]),
            ])
            ->emptyStateHeading('لا توجد مدن')
            ->emptyStateDescription('ابدأ بإضافة أول مدينة لخدمات الشحن')
            ->emptyStateIcon('heroicon-o-map-pin')
            ->defaultSort('name', 'asc')
            ->striped()
            ->persistSearchInSession()
            ->deferLoading();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit'   => Pages\EditCity::route('/{record}/edit'),
        ];
    }
}