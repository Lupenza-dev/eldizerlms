<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'view dashboard', 'view customers', 'view loans', 'view payments',
            'manage mobile app', 'manage devices', 'manage universities',
            'manage hospitals', 'manage beneficiaries', 'manage agents',
            'manage users', 'manage roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Existing administrators retain full access after seeding. Agents keep
        // access to the areas required for their day-to-day work.
        Role::whereIn('name', ['Admin', 'Super Admin'])->get()
            ->each(fn (Role $role) => $role->syncPermissions($permissions));
        Role::where('name', 'Agent')->first()?->syncPermissions([
            'view dashboard', 'view customers', 'view loans',
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
