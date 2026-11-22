@php
    /** @var array $item  node from App\Services\MenuService::tree() */
    $active = \App\Services\MenuService::isActive($item);
    $icon   = $item['icon'] ?: 'bx-circle';
    $nested = $nested ?? false;
@endphp

@if ($item['mode'] === 'section')
    <li class="menu-label">{{ $item['title'] }}</li>
    @foreach ($item['children'] as $child)
        @include('components.layouts.sidebar-item', ['item' => $child, 'nested' => $nested])
    @endforeach

@elseif ($item['mode'] === 'dropdown')
    <li class="{{ $active ? 'mm-active' : '' }}">
        <a href="javascript:;" class="has-arrow {{ $active && ! $nested ? 'li-active' : '' }}" title="{{ $item['title'] }}">
            <div class="parent-icon"><i class="bx {{ $icon }}"></i></div>
            <div class="menu-title">{{ $item['title'] }}</div>
        </a>
        <ul class="sidebar-submenu {{ $active ? 'mm-collapse mm-show' : 'mm-collapse' }}">
            @foreach ($item['children'] as $child)
                @include('components.layouts.sidebar-item', ['item' => $child, 'nested' => true])
            @endforeach
        </ul>
    </li>

@else
    {{-- link / hub --}}
    <li class="{{ $active && $nested ? 'mm-active' : '' }}">
        <a class="{{ $active ? 'li-active' : '' }}" href="{{ $item['url'] }}" title="{{ $item['title'] }}">
            @if ($nested)
                <i class="bx {{ $icon }} me-2"></i>{{ $item['title'] }}
            @else
                <div class="parent-icon"><i class="bx {{ $icon }}"></i></div>
                <div class="menu-title">{{ $item['title'] }}</div>
            @endif
        </a>
    </li>
@endif
