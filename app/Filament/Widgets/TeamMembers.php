<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TeamMembers extends BaseWidget
{
    protected static ?string $heading = 'فريق الإدارة';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->hasPermission('users') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->where('role', '!=', 'customer'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->copyable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('role')
                    ->label('الصلاحية')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'مدير عام',
                        'manager' => 'مدير',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'manager' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الانضمام')
                    ->dateTime('Y-m-d'),
            ]);
    }
}