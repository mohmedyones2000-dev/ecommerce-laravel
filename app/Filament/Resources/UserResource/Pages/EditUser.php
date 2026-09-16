<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // حوّل permissions (JSON) إلى toggles
        $permissions = $data['permissions'] ?? [];
        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true) ?? [];
        }

        foreach (array_keys(\App\Models\User::PERMISSIONS) as $key) {
            $data['perm_' . $key] = in_array($key, $permissions, true);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['permissions'] = $this->extractPermissions($data);

        foreach (array_keys(\App\Models\User::PERMISSIONS) as $key) {
            unset($data['perm_' . $key]);
        }

        return $data;
    }

    protected function extractPermissions(array $data): array
    {
        $permissions = [];
        foreach (array_keys(\App\Models\User::PERMISSIONS) as $key) {
            if (!empty($data['perm_' . $key])) {
                $permissions[] = $key;
            }
        }
        return $permissions;
    }
}