<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use App\Models\Role;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! empty($data['user_id'])) {
            return $data;
        }

        $user = User::query()->firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')),
                'password' => Hash::make(Str::random(16)),
            ]
        );

        $studentRole = Role::query()->firstOrCreate([
            'name' => 'Student',
            'guard_name' => 'web',
        ]);

        $user->assignRole($studentRole);
        $data['user_id'] = $user->id;

        return $data;
    }
}
