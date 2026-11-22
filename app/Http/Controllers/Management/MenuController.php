<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Management\Menu;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('roles:id,name')->orderBy('sort_order')->orderBy('id')->get();

        return view('managements.menus.list', [
            'rows'        => $this->flatten($menus),
            'parents'     => $menus->whereIn('display_mode', Menu::PARENT_MODES)->values(),
            'modes'       => Menu::MODES,
            'roles'       => Role::orderBy('name')->get(['id', 'name']),
            'permissions' => Permission::orderBy('name')->pluck('name'),
            'routeNames'  => $this->routeNames(),
            'colors'      => ['blue', 'emerald', 'cyan', 'violet', 'amber', 'rose', 'indigo', 'orange', 'slate'],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        try {
            DB::transaction(function () use ($data, $request) {
                $menu = Menu::create($data);
                $menu->roles()->sync($request->input('roles', []));
            });
            MenuService::flush();
        } catch (\Throwable $e) {
            Log::error('Menu store failed: ' . $e->getMessage());
            return $this->fail('Failed to create menu.', $e);
        }

        return response()->json(['success' => true, 'message' => 'Menu created successfully'], 201);
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $this->validated($request, $menu);

        try {
            DB::transaction(function () use ($menu, $data, $request) {
                $menu->update($data);
                $menu->roles()->sync($request->input('roles', []));
            });
            MenuService::flush();
        } catch (\Throwable $e) {
            Log::error('Menu update failed: ' . $e->getMessage());
            return $this->fail('Failed to update menu.', $e);
        }

        return response()->json(['success' => true, 'message' => 'Menu updated successfully']);
    }

    public function toggleStatus(Menu $menu)
    {
        $menu->update(['is_active' => ! $menu->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Menu ' . ($menu->is_active ? 'activated' : 'deactivated'),
        ]);
    }

    /**
     * Move a menu one step up/down among its siblings.
     */
    public function move(Request $request, Menu $menu)
    {
        $request->validate(['direction' => 'required|in:up,down']);

        $siblings = Menu::where('parent_id', $menu->parent_id)->orderBy('sort_order')->orderBy('id')->get()->values();
        $index = $siblings->search(fn ($m) => $m->id === $menu->id);
        $swap = $request->direction === 'up' ? $index - 1 : $index + 1;

        if ($swap >= 0 && $swap < $siblings->count()) {
            $ordered = $siblings->all();
            [$ordered[$index], $ordered[$swap]] = [$ordered[$swap], $ordered[$index]];

            DB::transaction(function () use ($ordered) {
                foreach ($ordered as $position => $sibling) {
                    $sibling->updateQuietly(['sort_order' => $position + 1]);
                }
            });
            MenuService::flush();
        }

        return response()->json(['success' => true, 'message' => 'Menu order updated']);
    }

    public function destroy(Menu $menu)
    {
        if ($menu->children()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This menu has submenus. Delete or move them first.',
            ], 422);
        }

        $menu->delete();

        return response()->json(['success' => true, 'message' => 'Menu deleted successfully']);
    }

    private function validated(Request $request, ?Menu $menu = null): array
    {
        $request->merge(['key' => Str::slug((string) ($request->input('key') ?: $request->input('title')))]);

        $forbiddenParents = $menu ? array_merge([$menu->id], $menu->descendantIds()) : [];

        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'key'           => ['required', 'string', 'max:100', Rule::unique('menus', 'key')->ignore($menu?->id)],
            'description'   => 'nullable|string|max:255',
            'icon'          => 'nullable|string|max:100',
            'color'         => 'nullable|string|max:30',
            'display_mode'  => ['required', Rule::in(array_keys(Menu::MODES))],
            'parent_id'     => [
                'nullable',
                Rule::exists('menus', 'id')->where(fn ($q) => $q->whereIn('display_mode', Menu::PARENT_MODES)),
                Rule::notIn($forbiddenParents),
            ],
            'route_name'    => ['nullable', 'string', 'max:255', function ($attr, $value, $fail) {
                if ($value && ! Route::has($value)) {
                    $fail("Route [{$value}] does not exist.");
                }
            }],
            'url'           => 'nullable|string|max:255',
            'active_routes' => 'nullable|string|max:500',
            'permission'    => 'nullable|string|exists:permissions,name',
            'sort_order'    => 'nullable|integer',
            'is_active'     => 'nullable|boolean',
            'roles'         => 'nullable|array',
            'roles.*'       => 'integer|exists:roles,id',
        ], [
            'parent_id.not_in' => 'A menu cannot be placed under itself or one of its submenus.',
            'parent_id.exists' => 'The parent must be a section, dropdown or hub menu.',
        ]);

        if (in_array($data['display_mode'], [Menu::MODE_LINK, Menu::MODE_HUB], true)
            && empty($data['route_name']) && empty($data['url'])) {
            abort(response()->json([
                'message' => 'A route or URL is required for link and hub menus.',
                'errors'  => ['route_name' => ['A route or URL is required for link and hub menus.']],
            ], 422));
        }

        unset($data['roles']);
        $data['is_active']  = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? ((int) Menu::where('parent_id', $data['parent_id'] ?? null)->max('sort_order') + 1);

        return $data;
    }

    /**
     * Depth-first flattened list for the tree table.
     */
    private function flatten($menus, $parentId = null, int $depth = 0): array
    {
        $rows = [];
        foreach ($menus->where('parent_id', $parentId) as $menu) {
            $rows[] = ['menu' => $menu, 'depth' => $depth];
            $rows = array_merge($rows, $this->flatten($menus, $menu->id, $depth + 1));
        }

        return $rows;
    }

    /**
     * Named GET routes without required parameters (what a menu can link to).
     */
    private function routeNames(): array
    {
        return collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($r) => $r->getName()
                && in_array('GET', $r->methods(), true)
                && ! preg_match('/\{[^?}]+\}/', $r->uri())
                && ! str_starts_with($r->uri(), 'api/')
                && ! str_starts_with($r->getName(), 'ignition.')
                && ! str_starts_with($r->getName(), 'sanctum.'))
            ->map(fn ($r) => $r->getName())
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    private function fail(string $message, \Throwable $e)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'debug'   => config('app.debug') ? $e->getMessage() : null,
        ], 500);
    }
}
