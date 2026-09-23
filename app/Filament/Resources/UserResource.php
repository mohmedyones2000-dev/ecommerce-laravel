<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'users';

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'إدارة المستخدمين';

    protected static ?string $navigationLabel = 'المستخدمون';

    protected static ?string $modelLabel = 'مستخدم';

    protected static ?string $pluralModelLabel = 'المستخدمون';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $newThisWeek = User::where('role', 'customer')
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        return $newThisWeek > 0 ? '+' . $newThisWeek : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'phone'];
    }

    public static function roleOptions(): array
    {
        return [
            'admin'    => 'مدير عام',
            'manager'  => 'مدير',
            'customer' => 'عميل',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('معلومات المستخدم')
                ->description('البيانات الأساسية للمستخدم')
                ->icon('heroicon-o-user-circle')
                ->schema([
                    FileUpload::make('avatar')
                        ->label('الصورة الشخصية')
                        ->image()
                        ->avatar()
                        ->directory('avatars')
                        ->imageEditor()
                        ->circleCropper()
                        ->maxSize(2048)
                        ->columnSpanFull(),

                    TextInput::make('name')
                        ->label('الاسم')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-user'),

                    TextInput::make('email')
                        ->label('البريد الإلكتروني')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-envelope'),

                    TextInput::make('phone')
                        ->label('رقم الهاتف')
                        ->tel()
                        ->maxLength(20)
                        ->prefixIcon('heroicon-o-phone'),

                    Select::make('role')
                        ->label('نوع المستخدم')
                        ->options(self::roleOptions())
                        ->required()
                        ->default('customer')
                        ->native(false)
                        ->prefixIcon('heroicon-o-shield-check')
                        ->live(),

                    CheckboxList::make('permissions')
                        ->label('الصلاحيات')
                        ->options(User::PERMISSIONS)
                        ->columns(3)
                        ->searchable()
                        ->bulkToggleable()
                        ->visible(fn (Get $get) => $get('role') === 'manager')
                        ->columnSpanFull()
                        ->helperText('تظهر فقط عند اختيار “مدير”. اختر الصلاحيات المسموح بها.'),
                ])->columns(2),

            Section::make('كلمة المرور')
                ->description('اتركها فارغة إذا كنت لا تريد تغيير كلمة المرور')
                ->icon('heroicon-o-key')
                ->schema([
                    TextInput::make('password')
                        ->label('كلمة المرور')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-lock-closed')
                        ->revealable(),

                    TextInput::make('password_confirmation')
                        ->label('تأكيد كلمة المرور')
                        ->password()
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->same('password')
                        ->dehydrated(false)
                        ->prefixIcon('heroicon-o-lock-closed')
                        ->revealable(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('الصورة')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(fn (User $record): string =>
                        'https://ui-avatars.com/api/?name=' . urlencode($record->name ?? 'User')
                        . '&background=C9A961&color=fff&size=128'),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->icon('heroicon-m-user')
                    ->iconColor('gray'),

                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('تم نسخ البريد')
                    ->icon('heroicon-m-envelope')
                    ->iconColor('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->placeholder('—')
                    ->searchable()
                    ->icon('heroicon-m-phone')
                    ->iconColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('role')
                    ->label('نوع المستخدم')
                    ->badge()
                    ->alignCenter()
                    ->formatStateUsing(fn (string $state): string =>
                        self::roleOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'admin'    => 'danger',
                        'manager'  => 'warning',
                        'customer' => 'info',
                        default    => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'admin'    => 'heroicon-m-shield-check',
                        'manager'  => 'heroicon-m-user-circle',
                        'customer' => 'heroicon-m-user',
                        default    => 'heroicon-m-question-mark-circle',
                    }),

                Tables\Columns\TextColumn::make('orders_count')
                    ->label('الطلبات')
                    ->counts('orders')
                    ->badge()
                    ->color('teal')
                    ->alignCenter()
                    ->suffix(' طلب')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->since()
                    ->tooltip(fn (User $record): string =>
                        $record->created_at?->format('Y-m-d H:i') ?? '')
                    ->icon('heroicon-m-calendar')
                    ->iconColor('gray')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('نوع المستخدم')
                    ->options(self::roleOptions())
                    ->multiple(),

                Tables\Filters\Filter::make('verified')
                    ->label('البريد المؤكد فقط')
                    ->query(fn ($query) => $query->whereNotNull('email_verified_at'))
                    ->toggle(),

                Tables\Filters\Filter::make('new_this_week')
                    ->label('المسجلون هذا الأسبوع')
                    ->query(fn ($query) => $query->where('created_at', '>=', now()->subWeek()))
                    ->toggle(),

                Tables\Filters\Filter::make('has_orders')
                    ->label('لديهم طلبات')
                    ->query(fn ($query) => $query->has('orders'))
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
                    ->visible(fn (User $record): bool => $record->id !== auth()->id())
                    ->requiresConfirmation()
                    ->modalHeading('حذف المستخدم')
                    ->modalDescription(fn (User $record): string =>
                        "هل أنت متأكد من حذف المستخدم \"{$record->name}\"؟ لا يمكن التراجع عن هذا الإجراء.")
                    ->modalSubmitActionLabel('نعم، احذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('make_customer')
                        ->label('تحويل إلى عميل')
                        ->icon('heroicon-m-user')
                        ->color('info')
                        ->action(fn ($records) => $records->each->update(['role' => 'customer']))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('make_manager')
                        ->label('تحويل إلى مدير')
                        ->icon('heroicon-m-user-circle')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['role' => 'manager']))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),

                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->before(function ($records) {
                            $records->reject(fn (User $user) => $user->id === auth()->id());
                        }),
                ]),
            ])
            ->emptyStateHeading('لا يوجد مستخدمون')
            ->emptyStateDescription('ابدأ بإضافة أول مستخدم للمتجر')
            ->emptyStateIcon('heroicon-o-users')
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->persistSearchInSession()
            ->deferLoading();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}