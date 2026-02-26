<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionRoleSeeder::class,
        ]);

        $admin = User::query()->updateOrCreate([
            'email' => 'admin@crm.local',
        ], [
            'name' => 'CRM Admin',
            'password' => Hash::make('admin12345'),
        ]);

        $adminRole = Role::query()->firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $admin->syncRoles([$adminRole]);

        $siteAdmin = User::query()->updateOrCreate([
            'email' => 'siteadmin@zgtec.com',
        ], [
            'name' => 'Site Admin',
            'password' => Hash::make('password'),
        ]);

        $siteAdmin->syncRoles([$adminRole]);

        $this->call([
            LmsDemoSeeder::class,
            CrmDemoSeeder::class,
            CmsPageSeeder::class,
            CmsContentSeeder::class,
        ]);
    }
}
