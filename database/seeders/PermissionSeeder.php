<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
   
    public const PERMISSIONS = [
        // Module access
        'view dashboard', 'view customers', 'view loans', 'view payments',
        'manage mobile app', 'manage devices', 'manage universities',
        'manage hospitals', 'manage beneficiaries', 'manage agents',
        'manage users', 'manage roles', 'manage loan products', 'manage menus',

        // Users
        'create users', 'edit users', 'delete users', 'change users status',
        // Roles
        'create roles', 'edit roles', 'delete roles', 'assign role menus',
        // Customers
        'create customers', 'edit customers', 'export customers',
        // Agents
        'create agents', 'edit agents', 'delete agents', 'change agents status',
        // Universities / campuses
        'create universities', 'edit universities', 'delete universities',
        'change universities status', 'sync campuses',
        // Hospitals
        'create hospitals', 'edit hospitals', 'delete hospitals', 'change hospitals status',
        // Devices
        'create devices', 'edit devices', 'delete devices',
        // Beneficiaries
        'upload beneficiaries',
        // Loan products
        'create loan products', 'edit loan products', 'delete loan products',
        'change loan products status',
        // Menus
        'create menus', 'edit menus', 'delete menus', 'change menus status', 'reorder menus',
        // Professionals
        'create professionals', 'edit professionals', 'delete professionals',
        // Mobile app: assignments, groups, adverts
        'create assignments', 'edit assignments', 'delete assignments',
        'manage assignment questions',
        'create groups', 'edit groups',
        'create adverts', 'edit adverts',
        // Dashboard & filters
        'view dashboard statistics', 'filter by college', 'filter by region',
        // Loans
        'create loan applications', 'export loan applications', 'confirm loan mandate', 'reject loans',
        'approve loans', 'agent approve loans', 'view loan contracts',
        'export loan contracts', 'add loan repayment',
        // Payments
        'sync mandates', 'cancel mandates', 'sync mandate collections',
        'verify mandate otp', 'nmb transact',
        // Payment requests
        'view payment requests', 'approve payment requests', 'reject payment requests',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Administrators retain full access after seeding.
        Role::whereIn('name', ['Admin', 'Super Admin'])->get()
            ->each(fn (Role $role) => $role->syncPermissions(self::PERMISSIONS));

        // Agents keep access to the areas required for their day-to-day work.
        Role::where('name', 'Agent')->first()?->syncPermissions([
            'view dashboard', 'view customers', 'view loans',
            'reject loans', 'agent approve loans', 'confirm loan mandate',
            'create loan applications',
        ]);

        // Internal users keep the actions they previously had through role checks.
        Role::where('name', 'Internal User')->first()?->givePermissionTo([
            'view loan contracts', 'reject loans', 'agent approve loans', 'confirm loan mandate',
            'create loan applications',
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
