<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'إدارة المستخدمين';

    protected static ?string $navigationLabel = 'المستخدمون';

    protected static ?string $modelLabel = 'مستخدم';

    protected static ?string $pluralModelLabel = 'المستخدمون';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('معلومات المستخدم')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('الاسم')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label('البريد الإلكتروني')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Forms\Components\TextInput::make('phone')
                        ->label('رقم الهاتف')
                        ->tel()
                        ->maxLength(20),

                    Forms\Components\Select::make('role')
                        ->label('نوع المستخدم')
                        ->options([
                            'admin' => 'مدير عام (كل الصلاحيات)',
                            'manager' => 'مدير (صلاحيات محددة)',
                            'customer' => 'عميل (لا يدخل لوحة التحكم)',
                        ])
                        ->required()
                        ->default('customer')
                        ->live()
                        ->native(false),
                ])->columns(2),

            Forms\Components\Section::make('صلاحيات المدير')
                ->description('حدّد الأقسام التي يمكن لهذا المدير الوصول إليها')
                ->visible(fn (Get $get) => $get('role') === 'manager')
                ->schema([

                    Forms\Components\Section::make('إدارة المتجر')
                        ->collapsible()
                        ->schema([
                            Forms\Components\Grid::make(3)
                                ->schema([
                                    Forms\Components\Toggle::make('perm_products')->label('المنتجات')->inline(false),
                                    Forms\Components\Toggle::make('perm_categories')->label('التصنيفات الرئيسية')->inline(false),
                                    Forms\Components\Toggle::make('perm_sub_categories')->label('التصنيفات الفرعية')->inline(false),
                                    Forms\Components\Toggle::make('perm_brands')->label('العلامات التجارية')->inline(false),
                                    Forms\Components\Toggle::make('perm_colors')->label('الألوان')->inline(false),
                                    Forms\Components\Toggle::make('perm_size_guides')->label('أدلة المقاسات')->inline(false),
                                    Forms\Components\Toggle::make('perm_orders')->label('الطلبات')->inline(false),
                                    Forms\Components\Toggle::make('perm_coupons')->label('كوبونات الخصم')->inline(false),
                                    Forms\Components\Toggle::make('perm_cities')->label('المدن')->inline(false),
                                    Forms\Components\Toggle::make('perm_reviews')->label('المراجعات')->inline(false),
                                    Forms\Components\Toggle::make('perm_addresses')->label('العناوين')->inline(false),
                                ]),
                        ]),

                    Forms\Components\Section::make('إدارة المحتوى')
                        ->collapsible()
                        ->schema([
                            Forms\Components\Grid::make(3)
                                ->schema([
                                    Forms\Components\Toggle::make('perm_pages')->label('الصفحات الثابتة')->inline(false),
                                    Forms\Components\Toggle::make('perm_faqs')->label('الأسئلة الشائعة')->inline(false),
                                    Forms\Components\Toggle::make('perm_settings')->label('إعدادات الموقع')->inline(false),
                                ]),
                        ]),

                    Forms\Components\Section::make('إدارة المستخدمين')
                        ->collapsible()
                        ->schema([
                            Forms\Components\Toggle::make('perm_users')->label('المستخدمون')->inline(false),
                        ]),

                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('select_all')
                            ->label('تحديد الكل')
                            ->color('gray')
                            ->action(function (Set $set) {
                                foreach (array_keys(User::PERMISSIONS) as $key) {
                                    $set('perm_' . $key, true);
                                }
                            }),

                        Forms\Components\Actions\Action::make('deselect_all')
                            ->label('إلغاء الكل')
                            ->color('gray')
                            ->action(function (Set $set) {
                                foreach (array_keys(User::PERMISSIONS) as $key) {
                                    $set('perm_' . $key, false);
                                }
                            }),
                    ]),
                ]),

            Forms\Components\Section::make('كلمة المرور')
                ->schema([
                    Forms\Components\TextInput::make('password')
                        ->label('كلمة المرور')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->maxLength(255)
                        ->revealable(),

                    Forms\Components\TextInput::make('password_confirmation')
                        ->label('تأكيد كلمة المرور')
                        ->password()
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->same('password')
                        ->dehydrated(false)
                        ->revealable(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('نوع المستخدم')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'مدير عام',
                        'manager' => 'مدير',
                        'customer' => 'عميل',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'manager' => 'warning',
                        'customer' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('permissions')
                    ->label('الصلاحيات')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(function (User $record) {
                        if ($record->role === 'admin') return 'الكل';
                        if ($record->role === 'customer') return '—';
                        return count($record->permissions ?? []) . ' صلاحية';
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('نوع المستخدم')
                    ->options([
                        'admin' => 'مدير عام',
                        'manager' => 'مدير',
                        'customer' => 'عميل',
                    ]),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}