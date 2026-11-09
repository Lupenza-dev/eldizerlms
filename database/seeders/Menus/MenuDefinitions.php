<?php

namespace Database\Seeders\Menus;

/**
 * Default menu tree.
 *
 * Each entry:
 *   key, title, icon, mode (link|section|dropdown|hub), route, active (Route::is patterns),
 *   permission, roles (array, or null = every role holding `permission`), description, color, children
 *
 * Admin and Super Admin are always attached by the seeder.
 */
class MenuDefinitions
{
    public static function tree(): array
    {
        return [
            self::item('dashboard-admin', 'Dashboard', 'bx-home-alt', 'link', 'admin.dashboard', null, 'view dashboard', ['Internal User']),
            self::item('dashboard', 'Dashboard', 'bx-home-alt', 'link', 'dashboard', null, 'view dashboard', ['Agent', 'Customer'], adminRoles: false),

            self::item('loan-section', 'Loan Management', null, 'section', children: [
                self::item('customers', 'Customers', 'bx-user', 'link', 'customers.index', 'customers.*', 'view customers'),
                self::item('loan-management', 'Loan Management', 'bx-file', 'hub', 'loan.management', 'loan.*', 'view loans', children: [
                    self::item('waiting-mandate', 'Waiting Mandate Confirmation', 'bx-time', 'link', 'loan.applications.waiting.mandate', null, 'view loans', description: 'Loan Applications Pending Mandate', color: 'violet'),
                    self::item('loan-applications', 'Loan Applications', 'bx-file', 'link', 'loan.applications', 'loan.applications,loan.profile', 'view loans', description: 'Manage Loan Applications', color: 'blue'),
                    self::item('loan-contracts', 'Loan Contracts', 'bx-file', 'link', 'loan.contracts', 'loan.contracts,loan.contract.profile', 'view loans', description: 'Manage Loan Contracts', color: 'indigo'),
                ]),
            ]),

            self::item('payment-section', 'Payment Management', null, 'section', children: [
                self::item('payments', 'Payments', 'bx-receipt', 'link', 'payment.management', null, 'view payments'),
            ]),

            self::item('system-section', 'System Management', null, 'section', children: [
                self::item('settings', 'Settings', 'bx-cog', 'hub', 'settings.index',
                    'settings.*,devices.*,colleges.*,campuses.*,beneficaries.*,hospitals.*,professionals.*,loan-products.*,menus.*', null, children: [
                    self::item('devices', 'Devices', 'bx-mobile-alt', 'link', 'devices.index', 'devices.*', 'manage devices', description: 'Manage device catalogue', color: 'blue'),
                    self::item('universities', 'Universities', 'bx-building-house', 'link', 'colleges.index', 'colleges.*', 'manage universities', description: 'Manage universities', color: 'emerald'),
                    self::item('campuses', 'Campuses', 'bx-water', 'link', 'campuses.index', 'campuses.*', 'manage universities', description: 'Eco Water campuses', color: 'cyan'),
                    self::item('beneficiaries', 'HESLB Beneficiaries', 'bx-group', 'link', 'beneficaries.index', 'beneficaries.*', 'manage beneficiaries', description: 'Manage HESLB beneficiaries', color: 'amber'),
                    self::item('hospitals', 'Hospitals', 'bx-plus-medical', 'link', 'hospitals.index', 'hospitals.*', 'manage hospitals', description: 'Manage hospitals', color: 'rose'),
                    self::item('professionals', 'Professionals', 'bx-briefcase', 'link', 'professionals.index', 'professionals.*', null, description: 'Manage professional profiles', color: 'violet'),
                    self::item('loan-products', 'Loan Products', 'bx-package', 'link', 'loan-products.index', 'loan-products.*', 'manage loan products', description: 'Configure loan types, pricing, fees & penalty', color: 'indigo'),
                    self::item('menus', 'Menus', 'bx-menu', 'link', 'menus.index', 'menus.*', 'manage menus', description: 'Manage sidebar menus and role access', color: 'slate'),
                ]),
                self::item('agents', 'Agents', 'bx-user-voice', 'link', 'agents.index', 'agents.*', 'manage agents'),
                self::item('mobile-app', 'Mobile App', 'bx-mobile-alt', 'link', 'app.management', null, 'manage mobile app'),
                self::item('users', 'Users', 'bx-user-circle', 'link', 'users.index', 'users.*', 'manage users'),
                self::item('roles', 'Roles & Permissions', 'bx-shield-quarter', 'link', 'roles.index', 'roles.*', 'manage roles'),
            ]),
        ];
    }

    private static function item(
        string $key,
        string $title,
        ?string $icon,
        string $mode,
        ?string $route = null,
        ?string $active = null,
        ?string $permission = null,
        ?array $roles = null,
        ?string $description = null,
        ?string $color = null,
        array $children = [],
        bool $adminRoles = true,
    ): array {
        return compact('key', 'title', 'icon', 'mode', 'route', 'active', 'permission', 'roles', 'description', 'color', 'children', 'adminRoles');
    }
}
