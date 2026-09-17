<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('معلومات المستخدم')
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
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('البريد الإلكتروني')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    TextInput::make('phone')
                        ->label('رقم الهاتف')
                        ->tel()
                        ->maxLength(20),

                    Select::make('role')
                        ->label('نوع المستخدم')
                        ->options([
                            'admin'    => 'مدير عام',
                            'manager'  => 'مدير',
                            'customer' => 'عميل',
                        ])
                        ->required()
                        ->default('customer')
                        ->native(false)
                        ->live(),

                    Forms\Components\CheckboxList::make('permissions')
                        ->label('الصلاحيات')
                        ->options(User::PERMISSIONS)
                        ->columns(3)
                        ->searchable()
                        ->bulkToggleable()
                        ->visible(fn (Forms\Get $get) => $get('role') === 'manager')
                        ->columnSpanFull()
                        ->helperText('تظهر فقط عند اختيار "مدير"'),
                ])->columns(2),

            Section::make('كلمة المرور')
                ->description('اتركها فارغة إذا كنت لا تريد تغيير كلمة المرور')
                ->schema([
                    TextInput::make('password')
                        ->label('كلمة المرور')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->maxLength(255)
                        ->revealable(),

                    TextInput::make('password_confirmation')
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
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('الصورة')
                    ->circular()
                    ->defaultImageUrl(fn (User $record) =>
                        'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=C9A961&color=fff&size=128'),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('نوع المستخدم')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin'    => 'مدير عام',
                        'manager'  => 'مدير',
                        'customer' => 'عميل',
                        default    => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'admin'    => 'danger',
                        'manager'  => 'warning',
                        'customer' => 'info',
                        default    => 'gray',
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
                        'admin'    => 'مدير عام',
                        'manager'  => 'مدير',
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
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}