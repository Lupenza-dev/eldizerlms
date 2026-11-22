<?php

namespace Database\Seeders;

use App\Models\Management\Menu;
use App\Services\MenuService;
use Database\Seeders\Menus\MenuDefinitions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Idempotent: menus are upserted by `key`, and roles are only ever attached (never detached),
 * so changes made by admins through the UI survive re-seeding.
 */
class MenuSeeder extends Seeder
{
    private const ADMIN_ROLES = ['Admin', 'Super Admin'];

    private $roles;

    public function run(): void
    {
        $this->roles = Role::with('permissions')->get();

        foreach (MenuDefinitions::tree() as $order => $item) {
            $this->seedItem($item, null, $order + 1);
        }

        MenuService::flush();
    }

    /**
     * @return array role ids attached to this menu
     */
    private function seedItem(array $item, ?int $parentId, int $order): array
    {
        $menu = Menu::updateOrCreate(
            ['key' => $item['key']],
            [
                'title'         => $item['title'],
                'icon'          => $item['icon'],
                'display_mode'  => $item['mode'],
                'route_name'    => $item['route'],
                'active_routes' => $item['active'],
                'permission'    => $item['permission'],
                'description'   => $item['description'],
                'color'         => $item['color'],
                'parent_id'     => $parentId,
                'sort_order'    => $order,
            ]
        );

        $childRoleIds = [];
        foreach ($item['children'] as $i => $child) {
            $childRoleIds = array_merge($childRoleIds, $this->seedItem($child, $menu->id, $i + 1));
        }

        $roleIds = $this->roleIdsFor($item);

        // Containers must be visible to everyone who can see one of their children.
        if ($item['children']) {
            $roleIds = array_merge($roleIds, $childRoleIds);
        }

        $roleIds = array_values(array_unique($roleIds));
        $menu->roles()->syncWithoutDetaching($roleIds);

        return $roleIds;
    }

    private function roleIdsFor(array $item): array
    {
        $roles = collect();

        if ($item['adminRoles']) {
            $roles = $roles->merge($this->roles->whereIn('name', self::ADMIN_ROLES));
        }

        if (is_array($item['roles'])) {
            $roles = $roles->merge($this->roles->whereIn('name', $item['roles']));
        } elseif ($item['permission']) {
            // Mirror today's @can() visibility: every role that holds the permission.
            $roles = $roles->merge(
                $this->roles->filter(fn (Role $r) => $r->permissions->contains('name', $item['permission']))
            );
        }

        return $roles->pluck('id')->all();
    }
}
