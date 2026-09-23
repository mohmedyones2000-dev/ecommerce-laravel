<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CouponResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'coupons';

    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'كوبونات الخصم';

    protected static ?string $modelLabel = 'كوبون';

    protected static ?string $pluralModelLabel = 'كوبونات الخصم';

    protected static ?string $recordTitleAttribute = 'code';

    protected static ?int $navigationSort = 8;

    public static function getNavigationBadge(): ?string
    {
        $active = Coupon::where('is_active', true)
            ->where(fn ($q) => $q
                ->whereNull('expires_at')
                ->orWhere('expires_at', '>=', now()))
            ->count();

        return $active > 0 ? (string) $active : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['code', 'description'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات الكوبون')
                ->description('الكود، النوع، والقيمة')
                ->icon('heroicon-o-ticket')
                ->schema([
                    Forms\Components\TextInput::make('code')
                        ->label('كود الخصم')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50)
                        ->prefixIcon('heroicon-o-hashtag')
                        ->placeholder('SAVE20')
                        ->helperText('يُحوَّل تلقائياً إلى أحرف كبيرة. مثال: SAVE20, WELCOME10')
                        ->dehydrateStateUsing(fn ($state) => strtoupper(trim($state ?? ''))),

                    Forms\Components\TextInput::make('description')
                        ->label('الوصف')
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-document-text')
                        ->placeholder('خصم 20% على الطلب الأول'),

                    Forms\Components\Select::make('type')
                        ->label('نوع الخصم')
                        ->options([
                            'percentage' => 'نسبة مئوية (%)',
                            'fixed'      => 'مبلغ ثابت ($)',
                        ])
                        ->required()
                        ->native(false)
                        ->prefixIcon('heroicon-o-currency-dollar')
                        ->live()
                        ->afterStateUpdated(fn (Set $set) => $set('value', null)),

                    Forms\Components\TextInput::make('value')
                        ->label(fn (Get $get): string => $get('type') === 'percentage'
                            ? 'النسبة (%)'
                            : 'المبلغ ($)')
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->maxValue(fn (Get $get) => $get('type') === 'percentage' ? 100 : null)
                        ->prefix(fn (Get $get): ?string => $get('type') === 'percentage' ? null : '$')
                        ->suffix(fn (Get $get): ?string => $get('type') === 'percentage' ? '%' : null)
                        ->prefixIcon('heroicon-o-calculator'),

                    Forms\Components\Toggle::make('free_shipping')
                        ->label('شحن مجاني')
                        ->helperText('عند تفعيل هذا الخيار، يحصل العميل على شحن مجاني بغض النظر عن المدينة')
                        ->default(false)
                        ->onColor('success')
                        ->offColor('gray')
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('شروط الاستخدام')
                ->description('الحد الأدنى، الحد الأقصى، وتاريخ الانتهاء')
                ->icon('heroicon-o-clipboard-document-check')
                ->schema([
                    Forms\Components\TextInput::make('min_order_amount')
                        ->label('الحد الأدنى للطلب')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->prefix('$')
                        ->prefixIcon('heroicon-o-shopping-cart'),

                    Forms\Components\TextInput::make('usage_limit')
                        ->label('الحد الأقصى للاستخدام')
                        ->numeric()
                        ->minValue(1)
                        ->prefixIcon('heroicon-o-hashtag')
                        ->helperText('اتركه فارغاً لاستخدام غير محدود'),

                    Forms\Components\DatePicker::make('expires_at')
                        ->label('تاريخ الانتهاء')
                        ->native(false)
                        ->prefixIcon('heroicon-o-calendar')
                        ->helperText('اتركه فارغاً لعدم وجود تاريخ انتهاء'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('نشط')
                        ->helperText('الكوبونات غير النشطة لا تعمل في المتجر')
                        ->default(true)
                        ->onColor('success')
                        ->offColor('danger'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('الكود')
                    ->badge()
                    ->color('warning')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('تم نسخ الكود')
                    ->copyMessageDuration(1500)
                    ->weight('bold')
                    ->icon('heroicon-m-ticket'),

                Tables\Columns\TextColumn::make('description')
                    ->label('الوصف')
                    ->limit(30)
                    ->tooltip(fn (Coupon $record): ?string => $record->description)
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->alignCenter()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string =>
                        $state === 'percentage' ? 'نسبة' : 'مبلغ ثابت'),

                Tables\Columns\TextColumn::make('value')
                    ->label('القيمة')
                    ->weight('semibold')
                    ->alignCenter()
                    ->color('success')
                    ->formatStateUsing(function (Coupon $record, $state): string {
                        return $record->type === 'percentage'
                            ? $state . '%'
                            : '$' . number_format((float) $state, 2);
                    }),

                Tables\Columns\IconColumn::make('free_shipping')
                    ->label('شحن مجاني')
                    ->boolean()
                    ->alignCenter()
                    ->trueIcon('heroicon-o-truck')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('used_count')
                    ->label('الاستخدام')
                    ->badge()
                    ->alignCenter()
                    ->color(fn (Coupon $record): string => match (true) {
                        $record->usage_limit && $record->used_count >= $record->usage_limit => 'danger',
                        $record->used_count > 0 => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (Coupon $record): string =>
                        $record->usage_limit
                            ? $record->used_count . ' / ' . $record->usage_limit
                            : $record->used_count . ' / ∞')
                    ->icon('heroicon-m-user-group'),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->alignCenter()
                    ->getStateUsing(function (Coupon $record): string {
                        if (! $record->is_active) {
                            return 'معطل';
                        }

                        if ($record->expires_at && $record->expires_at->isPast()) {
                            return 'منتهي';
                        }

                        if ($record->usage_limit && $record->used_count >= $record->usage_limit) {
                            return 'استُنفد';
                        }

                        return 'نشط';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'نشط'     => 'success',
                        'معطل'    => 'gray',
                        'منتهي'   => 'danger',
                        'استُنفد' => 'warning',
                        default   => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'نشط'     => 'heroicon-m-check-circle',
                        'معطل'    => 'heroicon-m-pause-circle',
                        'منتهي'   => 'heroicon-m-x-circle',
                        'استُنفد' => 'heroicon-m-exclamation-triangle',
                        default   => 'heroicon-m-question-mark-circle',
                    }),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('ينتهي في')
                    ->date('Y-m-d')
                    ->placeholder('بدون انتهاء')
                    ->sortable()
                    ->color(fn (Coupon $record): string =>
                        $record->expires_at && $record->expires_at->isPast() ? 'danger' : 'gray')
                    ->icon('heroicon-m-calendar')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط')
                    ->placeholder('الكل')
                    ->trueLabel('النشطة فقط')
                    ->falseLabel('غير النشطة'),

                Tables\Filters\TernaryFilter::make('free_shipping')
                    ->label('شحن مجاني')
                    ->placeholder('الكل')
                    ->trueLabel('شحن مجاني فقط')
                    ->falseLabel('بدون شحن مجاني'),

                Tables\Filters\SelectFilter::make('type')
                    ->label('النوع')
                    ->options([
                        'percentage' => 'نسبة مئوية',
                        'fixed'      => 'مبلغ ثابت',
                    ]),

                Tables\Filters\Filter::make('valid')
                    ->label('صالح للاستخدام الآن')
                    ->query(fn (Builder $query) => $query
                        ->where('is_active', true)
                        ->where(fn ($q) => $q
                            ->whereNull('expires_at')
                            ->orWhere('expires_at', '>=', now()))
                        ->where(fn ($q) => $q
                            ->whereNull('usage_limit')
                            ->orWhereColumn('used_count', '<', 'usage_limit')))
                    ->toggle(),

                Tables\Filters\Filter::make('expired')
                    ->label('منتهي الصلاحية')
                    ->query(fn (Builder $query) => $query
                        ->whereNotNull('expires_at')
                        ->where('expires_at', '<', now()))
                    ->toggle(),

                Tables\Filters\Filter::make('expiring_soon')
                    ->label('ينتهي خلال 7 أيام')
                    ->query(fn (Builder $query) => $query
                        ->whereNotNull('expires_at')
                        ->whereBetween('expires_at', [now(), now()->addDays(7)]))
                    ->toggle(),

                Tables\Filters\Filter::make('no_expiry')
                    ->label('بدون تاريخ انتهاء')
                    ->query(fn (Builder $query) => $query->whereNull('expires_at'))
                    ->toggle(),

                Tables\Filters\Filter::make('unused')
                    ->label('لم يُستخدم بعد')
                    ->query(fn (Builder $query) => $query->where('used_count', 0))
                    ->toggle(),

                Tables\Filters\Filter::make('used_up')
                    ->label('استُنفد الحد الأقصى')
                    ->query(fn (Builder $query) => $query
                        ->whereNotNull('usage_limit')
                        ->whereColumn('used_count', '>=', 'usage_limit'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل')
                    ->icon('heroicon-o-pencil')
                    ->size('sm'),

                Tables\Actions\Action::make('toggle_active')
                    ->label(fn (Coupon $record): string => $record->is_active ? 'تعطيل' : 'تفعيل')
                    ->icon(fn (Coupon $record): string => $record->is_active
                        ? 'heroicon-o-x-circle'
                        : 'heroicon-o-check-circle')
                    ->color(fn (Coupon $record): string => $record->is_active ? 'danger' : 'success')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Coupon $record): string =>
                        $record->is_active ? 'تعطيل الكوبون' : 'تفعيل الكوبون')
                    ->modalDescription(fn (Coupon $record): string =>
                        $record->is_active
                            ? "لن يعمل الكوبون \"{$record->code}\" في المتجر بعد التعطيل."
                            : "سيصبح الكوبون \"{$record->code}\" جاهزاً للاستخدام.")
                    ->modalSubmitActionLabel(fn (Coupon $record): string =>
                        $record->is_active ? 'نعم، عطّل' : 'نعم، فعّل')
                    ->action(fn (Coupon $record) =>
                        $record->update(['is_active' => ! $record->is_active])),

                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->icon('heroicon-o-trash')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('حذف الكوبون')
                    ->modalDescription(fn (Coupon $record): string =>
                        "هل أنت متأكد من حذف الكوبون \"{$record->code}\"؟ لا يمكن التراجع.")
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

                    Tables\Actions\BulkAction::make('reset_usage')
                        ->label('تصفير الاستخدام')
                        ->icon('heroicon-m-arrow-path')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('تصفير استخدام الكوبونات')
                        ->modalDescription('سيتم تصفير عدد مرات الاستخدام لجميع الكوبونات المحددة.')
                        ->modalSubmitActionLabel('نعم، صفّر')
                        ->action(fn ($records) => $records->each->update(['used_count' => 0]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('extend_expiry')
                        ->label('تمديد شهر')
                        ->icon('heroicon-m-calendar-days')
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading('تمديد تاريخ الانتهاء')
                        ->modalDescription('سيتم تمديد تاريخ الانتهاء للكوبونات المحددة شهراً من الآن.')
                        ->modalSubmitActionLabel('نعم، مدّد')
                        ->action(fn ($records) => $records->each(function (Coupon $coupon) {
                            $coupon->update(['expires_at' => now()->addMonth()]);
                        }))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation()
                        ->modalHeading('حذف الكوبونات المحددة')
                        ->modalDescription('سيتم حذف جميع الكوبونات المحددة. لا يمكن التراجع.')
                        ->modalSubmitActionLabel('نعم، احذف الكل'),
                ]),
            ])
            ->emptyStateHeading('لا توجد كوبونات')
            ->emptyStateDescription('ابدأ بإضافة أول كوبون خصم لمتجرك')
            ->emptyStateIcon('heroicon-o-ticket')
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->persistSearchInSession()
            ->deferLoading();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit'   => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}