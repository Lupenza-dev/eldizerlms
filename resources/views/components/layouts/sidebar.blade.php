<style>
    /* Modern Sidebar Styles */
    .sidebar-wrapper {
        background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }

    .sidebar-header {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding: 1.5rem;
    }

    .sidebar-header .logo-icon {
        border-radius: 8px;
        transition: transform 0.3s ease;
    }

    .sidebar-header .logo-icon:hover {
        transform: scale(1.05);
    }

    .toggle-icon {
        background: rgba(255,255,255,0.1);
        border-radius: 8px;
        padding: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .toggle-icon:hover {
        background: rgba(255,255,255,0.2);
        transform: translateX(-2px);
    }

    /* Navigation Styles */
    .metismenu {
        padding: 1rem 0;
    }

    .metismenu > li {
        margin-bottom: 0.25rem;
        position: relative;
    }

    .metismenu > li > a {
        color: #ecf0f1;
        text-decoration: none;
        padding: 0.875rem 1.5rem;
        display: flex;
        align-items: center;
        border-radius: 0 25px 25px 0;
        margin: 0 0.5rem 0.25rem 0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .metismenu > li > a::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: #3498db;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .metismenu > li > a:hover {
        background: rgba(255,255,255,0.1);
        color: #ffffff;
        transform: translateX(3px);
    }

    .metismenu > li > a:hover::before {
        transform: translateX(0);
    }

    .metismenu > li > a .parent-icon {
        font-size: 1.25rem;
        margin-right: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(255,255,255,0.1);
        transition: all 0.3s ease;
        color: #ffffff;
    }

    .metismenu > li > a:hover .parent-icon {
        background: rgba(52,152,219,0.3);
        color: #3498db;
    }

    .menu-title {
        font-weight: 500;
        font-size: 0.9rem;
        letter-spacing: 0.025em;
        color: #ffffff;
        flex: 1;
    }

    /* Active State (top level) */
    .li-active {
        background: linear-gradient(90deg, rgba(52,152,219,0.2) 0%, rgba(52,152,219,0.1) 100%) !important;
        color: #3498db !important;
        font-weight: 600;
        transform: translateX(3px);
    }

    .li-active::before {
        transform: translateX(0) !important;
        background: #3498db !important;
    }

    .li-active .parent-icon {
        background: rgba(52,152,219,0.3) !important;
        color: #3498db !important;
    }

    .li-active:hover {
        background: linear-gradient(90deg, rgba(52,152,219,0.3) 0%, rgba(52,152,219,0.2) 100%) !important;
        color: #3498db !important;
    }

    /* Menu Labels */
    .menu-label {
        padding: 0.5rem 1.5rem;
        color: #95a5a6;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin: 1rem 0 0.5rem 0;
        position: relative;
    }

    .menu-label::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 1.5rem;
        right: 1.5rem;
        height: 1px;
        background: linear-gradient(90deg, rgba(149,165,166,0.3) 0%, transparent 100%);
    }

    /* Logout Button */
    .metismenu > li:last-child > a {
        margin-top: 2rem;
        background: rgba(231,76,60,0.1);
        border: 1px solid rgba(231,76,60,0.2);
        color: #e74c3c;
    }

    .metismenu > li:last-child > a:hover {
        background: rgba(231,76,60,0.2);
        color: #e74c3c;
        transform: translateX(3px);
    }

    .metismenu > li:last-child > a .parent-icon {
        background: rgba(231,76,60,0.2);
        color: #e74c3c;
    }

    .metismenu > li:last-child > a:hover .parent-icon {
        background: rgba(231,76,60,0.3);
    }

    /* Dropdown submenus (DB-driven menus) */
    .metismenu .sidebar-submenu {
        padding: 0.25rem 0 0.5rem 2.75rem;
        list-style: none;
    }

    .metismenu .sidebar-submenu > li > a {
        display: flex;
        align-items: center;
        color: #cfd8dc;
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
        border-radius: 0 20px 20px 0;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .metismenu .sidebar-submenu > li > a:hover,
    .metismenu .sidebar-submenu > li > a.li-active {
        color: #ffffff;
        background: rgba(255,255,255,0.08);
    }

    .metismenu > li > a.has-arrow::after {
        border-color: #ecf0f1;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar-wrapper {
            background: #2c3e50;
        }

        .metismenu > li > a {
            padding: 1rem;
            margin: 0 0.25rem 0.25rem 0;
        }

        .menu-title {
            font-size: 0.85rem;
        }
    }
</style>

<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <img style="width: 180px; height: auto;" src="{{ asset('assets/images/eldizerlogo.jpeg')}}" class="logo-icon" alt="ElDIZER Logo">
            </div>
            <div class="toggle-icon" title="Toggle Sidebar">
                <i class='bx bx-arrow-back'></i>
            </div>
        </div>
    </div>

    <!--navigation-->
    <ul class="metismenu" id="menu">
        @foreach ($menus as $item)
            @include('components.layouts.sidebar-item', ['item' => $item])
        @endforeach

        {{-- Logout --}}
        <li>
            <a href="{{ route('logout') }}" title="Logout">
                <div class="parent-icon"><i class='bx bx-log-out-circle'></i></div>
                <div class="menu-title">Logout</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>