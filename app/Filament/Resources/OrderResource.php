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

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات الطلب')
                ->description('لا يمكن تعديل تفاصيل الطلب بعد إنشائه، فقط الحالة.')
                ->schema([
                    Forms\Components\TextInput::make('order_number')
                        ->label('رقم الطلب')
                        ->disabled(),

                    Forms\Components\TextInput::make('user.name')
                        ->label('العميل')
                        ->disabled(),

                    Forms\Components\TextInput::make('total_amount')
                        ->label('الإجمالي')
                        ->prefix('$')
                        ->disabled(),

                    Forms\Components\TextInput::make('created_at')
                        ->label('تاريخ الطلب')
                        ->disabled(),
                ])->columns(2),

            Forms\Components\Section::make('حالة الطلب')
                ->description('يمكنك تعديل حالة الطلب فقط.')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('حالة الطلب')
                        ->options([
                            'pending'    => 'قيد المراجعة',
                            'processing' => 'قيد المعالجة',
                            'shipped'    => 'تم الشحن',
                            'delivered'  => 'تم التوصيل',
                            'cancelled'  => 'ملغي',
                        ])
                        ->required(),

                    Forms\Components\Select::make('payment_status')
                        ->label('حالة الدفع')
                        ->options([
                            'unpaid'   => 'غير مدفوع',
                            'paid'     => 'مدفوع',
                            'refunded' => 'مسترجع',
                        ])
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
                    ->copyable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل')
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('الإجمالي')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'    => 'قيد المراجعة',
                        'processing' => 'قيد المعالجة',
                        'shipped'    => 'تم الشحن',
                        'delivered'  => 'تم التوصيل',
                        'cancelled'  => 'ملغي',
                        default      => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending'    => 'warning',
                        'processing' => 'info',
                        'shipped'    => 'primary',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    }),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('الدفع')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'unpaid'   => 'غير مدفوع',
                        'paid'     => 'مدفوع',
                        'refunded' => 'مسترجع',
                        default    => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'unpaid'   => 'warning',
                        'paid'     => 'success',
                        'refunded' => 'danger',
                        default    => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending'    => 'قيد المراجعة',
                        'processing' => 'قيد المعالجة',
                        'shipped'    => 'تم الشحن',
                        'delivered'  => 'تم التوصيل',
                        'cancelled'  => 'ملغي',
                    ]),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('حالة الدفع')
                    ->options([
                        'unpaid'   => 'غير مدفوع',
                        'paid'     => 'مدفوع',
                        'refunded' => 'مسترجع',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('change_status')
                    ->label('تغيير الحالة')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('حالة الطلب')
                            ->options([
                                'pending'    => 'قيد المراجعة',
                                'processing' => 'قيد المعالجة',
                                'shipped'    => 'تم الشحن',
                                'delivered'  => 'تم التوصيل',
                                'cancelled'  => 'ملغي',
                            ])
                            ->required()
                            ->default(fn (Order $record) => $record->status),

                        Forms\Components\Select::make('payment_status')
                            ->label('حالة الدفع')
                            ->options([
                                'unpaid'   => 'غير مدفوع',
                                'paid'     => 'مدفوع',
                                'refunded' => 'مسترجع',
                            ])
                            ->required()
                            ->default(fn (Order $record) => $record->payment_status),
                    ])
                    ->fillForm(fn (Order $record) => [
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
                            ->success()
                            ->send();
                    })
                    ->modalHeading('تغيير حالة الطلب')
                    ->modalSubmitActionLabel('حفظ التغييرات')
                    ->modalCancelActionLabel('إلغاء'),

                Tables\Actions\ViewAction::make()
                    ->label('عرض التفاصيل')
                    ->icon('heroicon-o-eye'),
            ])
            ->defaultSort('created_at', 'desc');
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