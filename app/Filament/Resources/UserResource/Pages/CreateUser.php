<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['permissions'] = $this->extractPermissions($data);

        // احذف الحقول المؤقتة perm_*
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