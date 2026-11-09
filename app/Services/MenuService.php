<?php

namespace App\Services;

use App\Models\Management\Menu;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/**
 * Builds the role/permission-filtered menu tree (sidebar + hub pages).
 * Adapted from NAALI-PUB's MenuService, with immediate cache invalidation.
 */
class MenuService
{
    private const VERSION_KEY = 'menus:version';

    /**
     * Invalidate every cached tree (called whenever menus, menu_role or role permissions change).
     */
    public static function flush(): void
    {
        Cache::forever(self::VERSION_KEY, ((int) Cache::get(self::VERSION_KEY, 1)) + 1);
    }

    /**
     * Visible menu tree for the user. Each node is a plain array:
     * [id, key, title, description, icon, color, mode, url, patterns, children].
     */
    public function tree(?User $user): array
    {
        if (! $user) {
            return [];
        }

        $roleIds = $user->roles->pluck('id')->sort()->values()->all();
        if (! $roleIds) {
            return [];
        }

        $fingerprint = md5(json_encode([
            $roleIds,
            $user->getAllPermissions()->pluck('name')->sort()->values()->all(),
        ]));
        $version = (int) Cache::get(self::VERSION_KEY, 1);

        try {
            return Cache::remember("menus:v{$version}:{$fingerprint}", now()->addDay(), function () use ($user, $roleIds) {
                return $this->build($user, $roleIds);
            });
        } catch (\Throwable $e) {
            Log::warning('Menu tree unavailable: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Visible children of a hub/section/dropdown, looked up by its key.
     */
    public function children(string $key, ?User $user): array
    {
        $find = function (array $nodes) use (&$find, $key) {
            foreach ($nodes as $node) {
                if ($node['key'] === $key) {
                    return $node['children'];
                }
                if ($found = $find($node['children'])) {
                    return $found;
                }
            }
            return null;
        };

        return $find($this->tree($user)) ?? [];
    }

    /**
     * Whether any of the node's patterns (or a descendant's) match the current route.
     */
    public static function isActive(array $node): bool
    {
        if ($node['patterns'] && Route::currentRouteName() && Route::is(...$node['patterns'])) {
            return true;
        }

        foreach ($node['children'] as $child) {
            if (self::isActive($child)) {
                return true;
            }
        }

        return false;
    }

    private function build(User $user, array $roleIds): array
    {
        if (! Schema::hasTable('menus')) {
            return [];
        }

        $menus = Menu::query()
            ->where('is_active', true)
            ->whereHas('roles', fn ($q) => $q->whereIn('roles.id', $roleIds))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->filter(fn (Menu $menu) => ! $menu->permission || $user->can($menu->permission));

        $byParent = $menus->groupBy(fn (Menu $m) => $m->parent_id ?? 0);

        $buildTree = function ($parentId) use (&$buildTree, $byParent): Collection {
            return ($byParent[$parentId] ?? collect())
                ->map(function (Menu $menu) use ($buildTree) {
                    $children = $buildTree($menu->id)->values()->all();

                    // Containers with nothing visible inside are hidden.
                    if (in_array($menu->display_mode, Menu::PARENT_MODES, true) && ! $children) {
                        return null;
                    }

                    return [
                        'id'          => $menu->id,
                        'key'         => $menu->key,
                        'title'       => $menu->title,
                        'description' => $menu->description,
                        'icon'        => $menu->icon,
                        'color'       => $menu->color,
                        'mode'        => $menu->display_mode,
                        'url'         => $menu->display_mode === Menu::MODE_SECTION ? null : $menu->resolveUrl(),
                        'patterns'    => $menu->activePatterns(),
                        'children'    => $children,
                    ];
                })
                ->filter();
        };

        // Children whose parent is hidden from this user are unreachable and dropped with it.
        return $buildTree(0)->values()->all();
    }
}
