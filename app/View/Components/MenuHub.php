<?php

namespace App\View\Components;

use App\Services\MenuService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

/**
 * Card grid of a hub menu's visible children: <x-menu-hub key="settings" />.
 */
class MenuHub extends Component
{
    public array $items;

    public function __construct(MenuService $menuService, public string $key)
    {
        $this->items = $menuService->children($key, Auth::user());
    }

    public function render(): View|Closure|string
    {
        return view('components.menu-hub');
    }
}
