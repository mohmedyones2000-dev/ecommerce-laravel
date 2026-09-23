<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\NewsletterSubscriberResource\Pages;
use App\Models\NewsletterSubscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NewsletterSubscriberResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'users';

    protected static ?string $model = NewsletterSubscriber::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'إدارة المحتوى';

    protected static ?string $navigationLabel = 'المشتركون في النشرة';

    protected static ?string $modelLabel = 'مشترك';

    protected static ?string $pluralModelLabel = 'المشتركون في النشرة';

    protected static ?string $recordTitleAttribute = 'email';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $newThisWeek = NewsletterSubscriber::where('is_active', true)
            ->where('subscribed_at', '>=', now()->subWeek())
            ->count();

        return $newThisWeek > 0 ? '+' . $newThisWeek : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['email', 'name'];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات المشترك')
                ->description('بيانات المشترك الأساسية')
                ->icon('heroicon-o-user')
                ->schema([
                    Forms\Components\TextInput::make('email')
                        ->label('البريد الإلكتروني')
                        ->email()
                        ->prefixIcon('heroicon-o-envelope')
                        ->disabled(),

                    Forms\Components\TextInput::make('name')
                        ->label('الاسم')
                        ->prefixIcon('heroicon-o-user')
                        ->disabled(),

                    Forms\Components\DateTimePicker::make('subscribed_at')
                        ->label('تاريخ الاشتراك')
                        ->prefixIcon('heroicon-o-calendar')
                        ->native(false)
                        ->disabled(),

                    Forms\Components\DateTimePicker::make('unsubscribed_at')
                        ->label('تاريخ إلغاء الاشتراك')
                        ->prefixIcon('heroicon-o-x-circle')
                        ->native(false)
                        ->placeholder('—')
                        ->disabled(),
                ])->columns(2),

            Forms\Components\Section::make('الحالة')
                ->description('تفعيل أو تعطيل الاشتراك')
                ->icon('heroicon-o-cog-6-tooth')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('نشط')
                        ->helperText('المشتركون غير النشطين لن يستلموا الرسائل')
                        ->onColor('success')
                        ->offColor('danger'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('تم نسخ البريد')
                    ->weight('semibold')
                    ->icon('heroicon-m-envelope')
                    ->iconColor('gray'),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->placeholder('—')
                    ->searchable()
                    ->icon('heroicon-m-user')
                    ->iconColor('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->alignCenter()
                    ->getStateUsing(fn (NewsletterSubscriber $record): string =>
                        $record->is_active ? 'نشط' : 'غير نشط')
                    ->color(fn (string $state): string => match ($state) {
                        'نشط'    => 'success',
                        default  => 'danger',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'نشط'    => 'heroicon-m-check-circle',
                        default  => 'heroicon-m-x-circle',
                    }),

                Tables\Columns\TextColumn::make('subscribed_at')
                    ->label('تاريخ الاشتراك')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->since()
                    ->tooltip(fn (NewsletterSubscriber $record): string =>
                        $record->subscribed_at?->format('Y-m-d H:i') ?? '')
                    ->icon('heroicon-m-calendar')
                    ->iconColor('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('unsubscribed_at')
                    ->label('تاريخ إلغاء الاشتراك')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('—')
                    ->sortable()
                    ->since()
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('الحالة')
                    ->placeholder('الكل')
                    ->trueLabel('النشطون فقط')
                    ->falseLabel('غير النشطين'),

                Tables\Filters\Filter::make('unsubscribed')
                    ->label('ألغوا الاشتراك')
                    ->query(fn (Builder $query) => $query->whereNotNull('unsubscribed_at'))
                    ->toggle(),

                Tables\Filters\Filter::make('has_name')
                    ->label('يحتوي على اسم')
                    ->query(fn (Builder $query) => $query
                        ->whereNotNull('name')
                        ->where('name', '!=', ''))
                    ->toggle(),

                Tables\Filters\Filter::make('this_month')
                    ->label('اشتركوا هذا الشهر')
                    ->query(fn (Builder $query) => $query
                        ->whereMonth('subscribed_at', now()->month)
                        ->whereYear('subscribed_at', now()->year))
                    ->toggle(),

                Tables\Filters\Filter::make('date_range')
                    ->label('نطاق تاريخ الاشتراك')
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
                                fn (Builder $q, $date) => $q->whereDate('subscribed_at', '>=', $date))
                            ->when($data['to'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('subscribed_at', '<=', $date));
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
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn (NewsletterSubscriber $record): string =>
                        $record->is_active ? 'تعطيل' : 'تفعيل')
                    ->icon(fn (NewsletterSubscriber $record): string =>
                        $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (NewsletterSubscriber $record): string =>
                        $record->is_active ? 'danger' : 'success')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading(fn (NewsletterSubscriber $record): string =>
                        $record->is_active ? 'تعطيل الاشتراك' : 'تفعيل الاشتراك')
                    ->modalDescription(fn (NewsletterSubscriber $record): string =>
                        $record->is_active
                            ? "لن يستلم \"{$record->email}\" رسائل النشرة بعد التعطيل."
                            : "سيبدأ \"{$record->email}\" باستلام رسائل النشرة.")
                    ->modalSubmitActionLabel(fn (NewsletterSubscriber $record): string =>
                        $record->is_active ? 'نعم، عطّل' : 'نعم، فعّل')
                    ->action(function (NewsletterSubscriber $record) {
                        $record->update(['is_active' => ! $record->is_active]);
                    }),

                Tables\Actions\EditAction::make()
                    ->label('تعديل')
                    ->icon('heroicon-o-pencil')
                    ->size('sm'),

                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->icon('heroicon-o-trash')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('حذف المشترك')
                    ->modalDescription(fn (NewsletterSubscriber $record): string =>
                        "هل أنت متأكد من حذف المشترك \"{$record->email}\"؟ لا يمكن التراجع.")
                    ->modalSubmitActionLabel('نعم، احذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('تفعيل')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update([
                            'is_active' => true,
                            'unsubscribed_at' => null,
                        ]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('تعطيل')
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update([
                            'is_active' => false,
                            'unsubscribed_at' => now(),
                        ]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),

                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation()
                        ->modalHeading('حذف المشتركين المحددين')
                        ->modalDescription('سيتم حذف جميع المشتركين المحددين. لا يمكن التراجع.')
                        ->modalSubmitActionLabel('نعم، احذف الكل'),
                ]),
            ])
            ->emptyStateHeading('لا يوجد مشتركون')
            ->emptyStateDescription('لم يشترك أي زائر في النشرة البريدية بعد')
            ->emptyStateIcon('heroicon-o-envelope')
            ->defaultSort('subscribed_at', 'desc')
            ->striped()
            ->persistSearchInSession()
            ->deferLoading();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletterSubscribers::route('/'),
            'edit'  => Pages\EditNewsletterSubscriber::route('/{record}/edit'),
        ];
    }
}