<?php

namespace App\Filament\Resources\Concerns;

use App\Models\User;

trait HasPermissionAccess
{
    protected static function hasPermission(string $action): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        return (bool) $user?->getAllPermissions()->contains('name', static::$permissionPrefix . '.' . $action);
    }

    public static function canViewAny(): bool
    {
        return static::hasPermission('view');
    }

    public static function canCreate(): bool
    {
        return static::hasPermission('create');
    }

    public static function canEdit($record): bool
    {
        return static::hasPermission('update');
    }

    public static function canDelete($record): bool
    {
        return static::hasPermission('delete');
    }
}
