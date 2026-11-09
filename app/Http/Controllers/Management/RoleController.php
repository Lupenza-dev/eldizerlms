<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Management\Menu;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    public function index()
    {
        return view('managements.roles.index', [
            'roles' => Role::with('permissions')->orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:125', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);
        $role->syncPermissions($data['permissions'] ?? []);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        MenuService::flush();

        return back()->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role->syncPermissions($data['permissions'] ?? []);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        MenuService::flush();

        return back()->with('success', "Permissions for {$role->name} updated.");
    }

    public function destroy(Role $role)
    {
        abort_if(in_array($role->name, ['Admin', 'Super Admin', 'Agent', 'Customer', 'Internal User']), 422, 'System roles cannot be deleted.');

        $role->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        MenuService::flush();

        return back()->with('success', 'Role deleted successfully.');
    }

    /**
     * Menu tree with the role's assigned menu ids (for the Roles -> Menus modal).
     */
    public function menus(Role $role)
    {
        $menus = Menu::orderBy('sort_order')->orderBy('id')
            ->get(['id', 'parent_id', 'title', 'icon', 'display_mode', 'is_active']);

        $build = function ($parentId) use (&$build, $menus) {
            return $menus->where('parent_id', $parentId)->map(fn (Menu $m) => [
                'id'        => $m->id,
                'title'     => $m->title,
                'icon'      => $m->icon,
                'mode'      => $m->display_mode,
                'is_active' => $m->is_active,
                'children'  => $build($m->id),
            ])->values();
        };

        return response()->json([
            'success'  => true,
            'role'     => $role->name,
            'tree'     => $build(null),
            'assigned' => $role->menus()->pluck('menus.id'),
        ]);
    }

    /**
     * Replace the role's menu assignments. Ancestors of checked menus are added
     * automatically so a checked submenu is always reachable.
     */
    public function syncMenus(Request $request, Role $role)
    {
        $data = $request->validate([
            'menu_ids'   => ['nullable', 'array'],
            'menu_ids.*' => ['integer', 'exists:menus,id'],
        ]);

        $parents = Menu::pluck('parent_id', 'id');
        $ids = collect($data['menu_ids'] ?? [])->map(fn ($id) => (int) $id);

        foreach ($ids->all() as $id) {
            while ($parentId = $parents[$id] ?? null) {
                $ids->push($parentId);
                $id = $parentId;
            }
        }

        $role->menus()->sync($ids->unique()->values()->all());
        MenuService::flush();

        return response()->json([
            'success' => true,
            'message' => "Menus for {$role->name} updated.",
        ]);
    }
}
