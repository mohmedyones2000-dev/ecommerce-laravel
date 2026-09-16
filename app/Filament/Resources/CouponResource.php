<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات الكوبون')->schema([
                Forms\Components\TextInput::make('code')
                    ->label('كود الخصم')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50)
                    ->helperText('مثال: SAVE20, WELCOME10')
                    ->extraInputAttributes(['style' => 'text-transform: uppercase']),

                Forms\Components\TextInput::make('description')
                    ->label('الوصف')
                    ->maxLength(255),

                Forms\Components\Select::make('type')
                    ->label('نوع الخصم')
                    ->options([
                        'percentage' => 'نسبة مئوية (%)',
                        'fixed' => 'مبلغ ثابت ($)',
                    ])
                    ->required()
                    ->live(),

                Forms\Components\TextInput::make('value')
                    ->label(fn (Forms\Get $get) => $get('type') === 'percentage' ? 'النسبة (%)' : 'المبلغ ($)')
                    ->numeric()
                    ->required()
                    ->minValue(0),

                Forms\Components\Toggle::make('free_shipping')
                    ->label('شحن مجاني')
                    ->helperText('عند تفعيل هذا الخيار، يحصل العميل على شحن مجاني بغض النظر عن المدينة')
                    ->default(false)
                    ->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('شروط الاستخدام')->schema([
                Forms\Components\TextInput::make('min_order_amount')
                    ->label('الحد الأدنى للطلب ($)')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),

                Forms\Components\TextInput::make('usage_limit')
                    ->label('الحد الأقصى للاستخدام')
                    ->numeric()
                    ->minValue(1)
                    ->helperText('اتركه فارغاً لاستخدام غير محدود'),

                Forms\Components\DatePicker::make('expires_at')
                    ->label('تاريخ الانتهاء')
                    ->helperText('اتركه فارغاً لعدم وجود تاريخ انتهاء'),

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
                Tables\Columns\TextColumn::make('code')
                    ->label('الكود')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('الوصف')
                    ->limit(30),

                Tables\Columns\TextColumn::make('type')
                    ->label('النوع')
                    ->formatStateUsing(fn (string $state): string => $state === 'percentage' ? 'نسبة' : 'مبلغ ثابت'),

                Tables\Columns\TextColumn::make('value')
                    ->label('القيمة')
                    ->formatStateUsing(function ($record, $state) {
                        return $record->type === 'percentage'
                            ? $state . '%'
                            : '$' . number_format($state, 2);
                    })
                    ->weight('semibold'),

                Tables\Columns\IconColumn::make('free_shipping')
                    ->label('شحن مجاني')
                    ->boolean()
                    ->trueIcon('heroicon-o-truck')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('gray')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('used_count')
                    ->label('الاستخدام')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($record, $state) =>
                        $state . ($record->usage_limit ? ' / ' . $record->usage_limit : '')
                    ),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('ينتهي في')
                    ->date('Y-m-d')
                    ->placeholder('بدون انتهاء')
                    ->color(fn ($record) =>
                        $record->expires_at && $record->expires_at->isPast() ? 'danger' : 'gray'
                    ),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('نشط'),
                Tables\Filters\TernaryFilter::make('free_shipping')->label('شحن مجاني'),
                Tables\Filters\Filter::make('expired')
                    ->label('منتهي الصلاحية')
                    ->query(fn ($query) => $query->where('expires_at', '<', now())),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}