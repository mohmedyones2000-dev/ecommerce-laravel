<?php

namespace App\Filament\Concerns;

trait HasResourcePermission
{
    protected static function userHasAccess(): bool
    {
        $permissionKey = static::$permissionKey ?? '';

        if (empty($permissionKey)) {
            return true;
        }

        $user = auth()->user();
        if (!$user) {
            return false;
        }

        return $user->hasPermission($permissionKey);
    }

    public static function canAccess(): bool
    {
        return static::userHasAccess();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::userHasAccess();
    }

    public static function canViewAny(): bool
    {
        return static::userHasAccess();
    }

    public static function canCreate(): bool
    {
        return static::userHasAccess();
    }
}