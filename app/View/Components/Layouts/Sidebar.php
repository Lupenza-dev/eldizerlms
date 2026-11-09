<?php

namespace App\View\Components\Layouts;

use App\Services\MenuService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $menus;

    public function __construct(MenuService $menuService)
    {
        $this->menus = $menuService->tree(Auth::user());
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.sidebar');
    }
}
