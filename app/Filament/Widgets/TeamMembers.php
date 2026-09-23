<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TeamMembers extends BaseWidget
{
    protected static ?string $heading = 'فريق الإدارة';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('users') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->where('role', '!=', 'customer')
                    ->latest()
            )
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(fn (User $record) =>
                        'https://ui-avatars.com/api/?name=' . urlencode($record->name)
                        . '&background=14b8a6&color=fff&size=80'),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->weight('semibold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->copyable()
                    ->copyMessage('تم نسخ البريد')
                    ->color('gray')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->placeholder('—')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('role')
                    ->label('الصلاحية')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin'   => 'مدير عام',
                        'manager' => 'مدير',
                        default   => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'admin'   => 'danger',
                        'manager' => 'warning',
                        default   => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الانضمام')
                    ->dateTime('Y-m-d')
                    ->since()
                    ->sortable()
                    ->color('gray')
                    ->toggleable(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('view_all')
                    ->label('إدارة الفريق')
                    ->icon('heroicon-m-arrow-left')
                    ->url(fn () => route('filament.admin.resources.users.index'))
                    ->color('gray')
                    ->size('sm'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('تعديل')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (User $record) =>
                        route('filament.admin.resources.users.edit', $record))
                    ->color('gray')
                    ->size('sm'),
            ])
            ->paginated(false)
            ->emptyStateHeading('لا يوجد فريق إدارة')
            ->emptyStateDescription('لم يتم إضافة أي مدير بعد')
            ->emptyStateIcon('heroicon-o-user-group')
            ->striped(false);
    }
}