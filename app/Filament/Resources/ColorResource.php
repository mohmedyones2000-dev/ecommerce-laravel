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
use Illuminate\Database\Eloquent\Builder;

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

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 9;

    public static function getNavigationBadge(): ?string
    {
        $inactive = Color::where('is_active', false)->count();

        return $inactive > 0 ? (string) $inactive : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'hex_code'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات اللون')
                ->description('اسم اللون وكود HEX')
                ->icon('heroicon-o-swatch')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('اسم اللون')
                        ->required()
                        ->maxLength(50)
                        ->prefixIcon('heroicon-o-tag')
                        ->placeholder('أحمر، أزرق...'),

                    Forms\Components\ColorPicker::make('hex_code')
                        ->label('كود اللون')
                        ->required()
                        ->default('#000000')
                        ->live(),

                    Forms\Components\Toggle::make('is_active')
                        ->label('نشط')
                        ->helperText('الألوان غير النشطة لا تظهر للاختيار في المنتجات')
                        ->default(true)
                        ->onColor('success')
                        ->offColor('danger'),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ColorColumn::make('hex_code')
                    ->label('اللون')
                    ->copyable()
                    ->copyMessage('تم نسخ الكود')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->icon('heroicon-m-tag')
                    ->iconColor('gray'),

                Tables\Columns\TextColumn::make('hex_code')
                    ->label('الكود')
                    ->badge()
                    ->color('gray')
                    ->copyable()
                    ->copyMessage('تم نسخ الكود')
                    ->alignCenter()
                    ->searchable(),

                Tables\Columns\TextColumn::make('variants_count')
                    ->label('الاستخدام')
                    ->counts('variants')
                    ->badge()
                    ->color(fn ($state): string => $state > 0 ? 'success' : 'gray')
                    ->alignCenter()
                    ->suffix(' منتج')
                    ->icon('heroicon-m-shopping-bag')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->since()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط')
                    ->placeholder('الكل')
                    ->trueLabel('النشطة فقط')
                    ->falseLabel('غير النشطة'),

                Tables\Filters\Filter::make('used_in_products')
                    ->label('مستخدم في منتجات')
                    ->query(fn (Builder $query) => $query->has('variants'))
                    ->toggle(),

                Tables\Filters\Filter::make('unused')
                    ->label('غير مستخدم')
                    ->query(fn (Builder $query) => $query->doesntHave('variants'))
                    ->toggle(),

                Tables\Filters\Filter::make('recent')
                    ->label('أُضيف حديثاً')
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
                    ->modalHeading('حذف اللون')
                    ->modalDescription(fn (Color $record): string =>
                        $record->variants()->count() > 0
                            ? "تحذير: هذا اللون مستخدم في " . $record->variants()->count() . " منتج. لن يكون متاحاً بعد الحذف."
                            : "هل أنت متأكد من حذف اللون \"{$record->name}\"؟ لا يمكن التراجع.")
                    ->modalSubmitActionLabel('نعم، احذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('تفعيل')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('تعطيل')
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation()
                        ->modalHeading('حذف الألوان المحددة')
                        ->modalDescription('سيتم حذف جميع الألوان المحددة. لا يمكن التراجع.')
                        ->modalSubmitActionLabel('نعم، احذف الكل'),
                ]),
            ])
            ->emptyStateHeading('لا توجد ألوان')
            ->emptyStateDescription('ابدأ بإضافة أول لون للمتجر')
            ->emptyStateIcon('heroicon-o-swatch')
            ->defaultSort('name', 'asc')
            ->striped()
            ->persistSearchInSession()
            ->deferLoading();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListColors::route('/'),
            'create' => Pages\CreateColor::route('/create'),
            'edit'   => Pages\EditColor::route('/{record}/edit'),
        ];
    }
}