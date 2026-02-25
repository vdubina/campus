<?php

namespace App\Filament\Resources\Instructors\Pages;

use App\Filament\Resources\Instructors\InstructorResource;
use App\Models\Role;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EditInstructor extends EditRecord
{
    protected static string $resource = InstructorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

        $instructorRole = Role::query()->firstOrCreate([
            'name' => 'Instructor',
            'guard_name' => 'web',
        ]);

        $user->assignRole($instructorRole);
        $data['user_id'] = $user->id;

        return $data;
    }
}
