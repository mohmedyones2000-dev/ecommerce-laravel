<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use App\Services\NotificationService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'orders';

    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'الطلبات';

    protected static ?string $modelLabel = 'طلب';

    protected static ?string $pluralModelLabel = 'الطلبات';

    protected static ?string $recordTitleAttribute = 'order_number';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $pending = Order::where('status', 'pending')->count();

        return $pending > 0 ? (string) $pending : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['order_number'];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function statusOptions(): array
    {
        return [
            'pending'    => 'قيد المراجعة',
            'processing' => 'قيد المعالجة',
            'shipped'    => 'تم الشحن',
            'delivered'  => 'تم التوصيل',
            'cancelled'  => 'ملغي',
        ];
    }

    public static function paymentStatusOptions(): array
    {
        return [
            'unpaid'   => 'غير مدفوع',
            'paid'     => 'مدفوع',
            'refunded' => 'مسترجع',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات الطلب')
                ->description('لا يمكن تعديل تفاصيل الطلب بعد إنشائه، فقط الحالة.')
                ->icon('heroicon-o-information-circle')
                ->schema([
                    Forms\Components\TextInput::make('order_number')
                        ->label('رقم الطلب')
                        ->prefixIcon('heroicon-o-hashtag')
                        ->disabled(),

                    Forms\Components\TextInput::make('user.name')
                        ->label('العميل')
                        ->prefixIcon('heroicon-o-user')
                        ->disabled(),

                    Forms\Components\TextInput::make('total_amount')
                        ->label('الإجمالي')
                        ->prefix('$')
                        ->prefixIcon('heroicon-o-banknotes')
                        ->disabled(),

                    Forms\Components\TextInput::make('created_at')
                        ->label('تاريخ الطلب')
                        ->prefixIcon('heroicon-o-calendar')
                        ->disabled(),
                ])->columns(2),

            Forms\Components\Section::make('حالة الطلب')
                ->description('يمكنك تعديل حالة الطلب فقط.')
                ->icon('heroicon-o-arrow-path')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('حالة الطلب')
                        ->options(self::statusOptions())
                        ->prefixIcon('heroicon-o-clipboard-document-check')
                        ->required(),

                    Forms\Components\Select::make('payment_status')
                        ->label('حالة الدفع')
                        ->options(self::paymentStatusOptions())
                        ->prefixIcon('heroicon-o-credit-card')
                        ->required(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('رقم الطلب')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('تم نسخ رقم الطلب')
                    ->copyMessageDuration(1500)
                    ->weight('semibold')
                    ->icon('heroicon-m-hashtag')
                    ->iconColor('gray'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل')
                    ->searchable()
                    ->weight('medium')
                    ->description(fn (Order $record): ?string => $record->user?->email)
                    ->icon('heroicon-m-user')
                    ->iconColor('gray'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('الإجمالي')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold')
                    ->color('success')
                    ->icon('heroicon-m-banknotes')
                    ->iconColor('success'),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->alignCenter()
                    ->formatStateUsing(fn (string $state): string => self::statusOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'pending'    => 'warning',
                        'processing' => 'info',
                        'shipped'    => 'primary',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'pending'    => 'heroicon-m-clock',
                        'processing' => 'heroicon-m-arrow-path',
                        'shipped'    => 'heroicon-m-truck',
                        'delivered'  => 'heroicon-m-check-circle',
                        'cancelled'  => 'heroicon-m-x-circle',
                        default      => 'heroicon-m-question-mark-circle',
                    }),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('الدفع')
                    ->badge()
                    ->alignCenter()
                    ->formatStateUsing(fn (string $state): string => self::paymentStatusOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'unpaid'   => 'warning',
                        'paid'     => 'success',
                        'refunded' => 'danger',
                        default    => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'unpaid'   => 'heroicon-m-clock',
                        'paid'     => 'heroicon-m-check-badge',
                        'refunded' => 'heroicon-m-arrow-uturn-left',
                        default    => 'heroicon-m-question-mark-circle',
                    }),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('المنتجات')
                    ->counts('items')
                    ->badge()
                    ->color('gray')
                    ->alignCenter()
                    ->suffix(' منتج')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->since()
                    ->tooltip(fn (Order $record): string =>
                        $record->created_at?->format('Y-m-d H:i') ?? '')
                    ->icon('heroicon-m-calendar')
                    ->iconColor('gray')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(self::statusOptions())
                    ->multiple(),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('حالة الدفع')
                    ->options(self::paymentStatusOptions())
                    ->multiple(),

                Tables\Filters\Filter::make('today')
                    ->label('طلبات اليوم')
                    ->query(fn (Builder $query) => $query->whereDate('created_at', today()))
                    ->toggle(),

                Tables\Filters\Filter::make('this_month')
                    ->label('طلبات هذا الشهر')
                    ->query(fn (Builder $query) => $query
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year))
                    ->toggle(),

                Tables\Filters\Filter::make('high_value')
                    ->label('طلبات بقيمة عالية (+100$)')
                    ->query(fn (Builder $query) => $query->where('total_amount', '>=', 100))
                    ->toggle(),

                Tables\Filters\Filter::make('date_range')
                    ->label('نطاق تاريخ')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('من تاريخ')
                            ->native(false),
                        Forms\Components\DatePicker::make('to')
                            ->label('إلى تاريخ')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['to'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('من: ' . $data['from'])
                                ->removeField('from');
                        }

                        if ($data['to'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('إلى: ' . $data['to'])
                                ->removeField('to');
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('change_status')
                    ->label('تغيير الحالة')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->size('sm')
                    ->tooltip('تعديل حالة الطلب والدفع')
                    ->modalHeading('تغيير حالة الطلب')
                    ->modalDescription('قم بتحديث حالة الطلب وحالة الدفع ثم اضغط حفظ.')
                    ->modalIcon('heroicon-o-arrow-path')
                    ->modalIconColor('warning')
                    ->modalSubmitActionLabel('حفظ التغييرات')
                    ->modalCancelActionLabel('إلغاء')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('حالة الطلب')
                            ->options(self::statusOptions())
                            ->prefixIcon('heroicon-o-clipboard-document-check')
                            ->required(),

                        Forms\Components\Select::make('payment_status')
                            ->label('حالة الدفع')
                            ->options(self::paymentStatusOptions())
                            ->prefixIcon('heroicon-o-credit-card')
                            ->required(),
                    ])
                    ->fillForm(fn (Order $record): array => [
                        'status'         => $record->status,
                        'payment_status' => $record->payment_status,
                    ])
                    ->action(function (Order $record, array $data) {
                        $oldStatus = $record->status;

                        $record->update([
                            'status'         => $data['status'],
                            'payment_status' => $data['payment_status'],
                        ]);

                        if ($oldStatus !== $data['status']) {
                            NotificationService::orderStatusChanged($record, $data['status']);
                        }

                        FilamentNotification::make()
                            ->title('تم تحديث حالة الطلب')
                            ->body("تم تحديث الطلب #{$record->order_number} بنجاح.")
                            ->success()
                            ->icon('heroicon-o-check-circle')
                            ->send();
                    }),

                Tables\Actions\ViewAction::make()
                    ->label('عرض')
                    ->icon('heroicon-o-eye')
                    ->size('sm')
                    ->tooltip('عرض تفاصيل الطلب'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_as_processing')
                        ->label('تعيين: قيد المعالجة')
                        ->icon('heroicon-m-arrow-path')
                        ->color('info')
                        ->action(fn ($records) => $records->each->update(['status' => 'processing']))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('mark_as_shipped')
                        ->label('تعيين: تم الشحن')
                        ->icon('heroicon-m-truck')
                        ->color('primary')
                        ->action(fn ($records) => $records->each->update(['status' => 'shipped']))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('mark_as_delivered')
                        ->label('تعيين: تم التوصيل')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => 'delivered']))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('mark_as_cancelled')
                        ->label('تعيين: ملغي')
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['status' => 'cancelled']))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('لا توجد طلبات')
            ->emptyStateDescription('لم يقم أي عميل بالطلب حتى الآن')
            ->emptyStateIcon('heroicon-o-shopping-cart')
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->persistSearchInSession()
            ->deferLoading();
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
            'index' => Pages\ListOrders::route('/'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}