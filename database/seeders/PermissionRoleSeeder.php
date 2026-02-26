<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $resourcePrefixes = [
            'account',
            'contact',
            'lead',
            'opportunity',
            'activity',
            'user',
            'role',
            'permission',
            'instructor',
            'student',
            'course',
            'topic',
            'quiz',
            'quiz_attempt',
            'enrollment',
            'certification',
        ];

        $resourceActions = ['view', 'create', 'update', 'delete'];

        $permissions = collect([
            'admin.access',
            'crm.dashboard.view',
            'crm.calendar.view',
            'lms.dashboard.view',
        ]);

        foreach ($resourcePrefixes as $prefix) {
            foreach ($resourceActions as $action) {
                $permissions->push("{$prefix}.{$action}");
            }
        }

        $permissions->unique()->each(function (string $permissionName): void {
            Permission::query()->firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        });

        $superAdminRole = Role::query()->firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $instructorRole = Role::query()->firstOrCreate([
            'name' => 'Instructor',
            'guard_name' => 'web',
        ]);

        $studentRole = Role::query()->firstOrCreate([
            'name' => 'Student',
            'guard_name' => 'web',
        ]);

        $superAdminRole->syncPermissions(Permission::query()->pluck('name')->all());

        $instructorRole->syncPermissions([
            'admin.access',
            'lms.dashboard.view',
            'instructor.view',
            'student.view',
            'course.view',
            'topic.view',
            'quiz.view',
            'quiz_attempt.view',
            'enrollment.view',
            'certification.view',
        ]);

        $studentRole->syncPermissions([]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
