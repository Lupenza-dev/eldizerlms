<?php

namespace App\Models\Management;

use App\Services\MenuService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class Menu extends Model
{
    public const MODE_LINK     = 'link';
    public const MODE_SECTION  = 'section';
    public const MODE_DROPDOWN = 'dropdown';
    public const MODE_HUB      = 'hub';

    public const MODES = [
        self::MODE_LINK     => 'Link',
        self::MODE_SECTION  => 'Section label (children listed under it)',
        self::MODE_DROPDOWN => 'Dropdown (children expand in sidebar)',
        self::MODE_HUB      => 'Hub page (children shown as cards)',
    ];

    /** Modes that can hold children. */
    public const PARENT_MODES = [self::MODE_SECTION, self::MODE_DROPDOWN, self::MODE_HUB];

    protected $fillable = [
        'uuid', 'key', 'title', 'description', 'icon', 'color', 'parent_id', 'display_mode',
        'route_name', 'url', 'active_routes', 'permission', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'parent_id'  => 'integer',
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected static function booted(): void
    {
        static::creating(function (Menu $menu) {
            if (empty($menu->uuid)) {
                $menu->uuid = (string) Str::orderedUuid();
            }
        });

        static::saved(fn () => MenuService::flush());
        static::deleted(fn () => MenuService::flush());
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order')->orderBy('id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'menu_role')->withTimestamps();
    }

    public function resolveUrl(): string
    {
        if ($this->route_name && Route::has($this->route_name)) {
            return route($this->route_name);
        }

        return $this->url ? url($this->url) : '#';
    }

    /**
     * Route::is patterns used to highlight this menu.
     */
    public function activePatterns(): array
    {
        $patterns = array_filter(array_map('trim', explode(',', (string) $this->active_routes)));

        if (! $patterns && $this->route_name) {
            $patterns = [$this->route_name];
        }

        return array_values($patterns);
    }

    /**
     * Ids of every descendant (used to stop a menu being moved under itself).
     */
    public function descendantIds(): array
    {
        $ids = [];
        $queue = [$this->id];

        while ($queue) {
            $childIds = static::whereIn('parent_id', $queue)->pluck('id')->all();
            $childIds = array_diff($childIds, $ids);
            $ids = array_merge($ids, $childIds);
            $queue = $childIds;
        }

        return $ids;
    }
}
