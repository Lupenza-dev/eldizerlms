<?php

namespace App\Providers;

use App\Models\Management\Menu;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Inverse of Menu::roles() without subclassing Spatie's Role model.
        Role::resolveRelationUsing('menus', function (Role $role) {
            return $role->belongsToMany(Menu::class, 'menu_role')->withTimestamps();
        });
    }
}
