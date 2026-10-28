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

        {{-- Dashboard --}}
        @can('view dashboard')
            @if (Auth::user()->hasRole(['Admin','Super Admin','Internal User']))
            <li>
                <a class="{{ Route::is('admin.dashboard') ? 'li-active' : '' }}" href="{{ route('admin.dashboard') }}" title="Dashboard">
                    <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>
            @else
            <li>
                <a class="{{ Route::is('dashboard') ? 'li-active' : '' }}" href="{{ route('dashboard') }}" title="Dashboard">
                    <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>
            @endif
        @endcan

        {{-- Loan Management label --}}
        @canany(['view customers', 'view loans'])
        <li class="menu-label">Loan Management</li>
        @endcanany

        @can('view customers')
        <li>
            <a class="{{ (Route::is('customers.index') or Route::is('customers.show') or Route::is('customers.edit')) ? 'li-active' : '' }}" href="{{ route('customers.index') }}" title="Customer Management">
                <div class="parent-icon"><i class='bx bx-user'></i></div>
                <div class="menu-title">Customers</div>
            </a>
        </li>
        @endcan

        @can('view loans')
        <li>
            <a class="{{ (Route::is('loan.management') or Route::is('loan.applications') or Route::is('loan.profile') or Route::is('loan.contracts') or Route::is('loan.contract.profile') or Route::is('loan.applications.waiting.mandate')) ? 'li-active' : '' }}" href="{{ route('loan.management') }}" title="Loan Management">
                <div class="parent-icon"><i class='bx bx-file'></i></div>
                <div class="menu-title">Loan Management</div>
            </a>
        </li>
        @endcan

        {{-- Payment Management --}}
        @canany(['view payments', 'manage mobile app', 'manage devices', 'manage universities', 'manage hospitals', 'manage beneficiaries', 'manage agents', 'manage users', 'manage roles'])

        @can('view payments')
        <li class="menu-label">Payment Management</li>
        <li>
            <a class="{{ Route::is('payment.management') ? 'li-active' : '' }}" href="{{ route('payment.management') }}" title="Repayment Management">
                <div class="parent-icon"><i class='bx bx-receipt'></i></div>
                <div class="menu-title">Payments</div>
            </a>
        </li>
        @endcan

        {{-- System Management label --}}
        @canany(['manage mobile app', 'manage devices', 'manage universities', 'manage hospitals', 'manage beneficiaries', 'manage agents', 'manage users', 'manage roles'])
        <li class="menu-label">System Management</li>
        @endcanany

        {{-- ═══════ SETTINGS — single link ═══════ --}}
        @canany(['manage devices', 'manage universities', 'manage hospitals', 'manage beneficiaries'])
        <li>
            <a class="{{ request()->is('settings') || request()->is('settings/*') ? 'li-active' : '' }}"
               href="{{ route('settings.index') }}"
               title="System Settings">
                <div class="parent-icon"><i class='bx bx-cog'></i></div>
                <div class="menu-title">Settings</div>
            </a>
        </li>
        @endcanany

        {{-- AGENTS --}}
        @can('manage agents')
        <li>
            <a class="{{ Route::is('agents.*') ? 'li-active' : '' }}" href="{{ route('agents.index') }}" title="Agent Management">
                <div class="parent-icon"><i class='bx bx-user-voice'></i></div>
                <div class="menu-title">Agents</div>
            </a>
        </li>
        @endcan

        {{-- Mobile App --}}
        @can('manage mobile app')
        <li>
            <a class="{{ Route::is('app.management') ? 'li-active' : '' }}" href="{{ route('app.management') }}" title="App Management">
                <div class="parent-icon"><i class='bx bx-mobile-alt'></i></div>
                <div class="menu-title">Mobile App</div>
            </a>
        </li>
        @endcan

        {{-- Users --}}
        @can('manage users')
        <li>
            <a class="{{ Route::is('users.index') ? 'li-active' : '' }}" href="{{ route('users.index') }}" title="User Management">
                <div class="parent-icon"><i class='bx bx-users'></i></div>
                <div class="menu-title">Users</div>
            </a>
        </li>
        @endcan

        {{-- Roles --}}
        @can('manage roles')
        <li>
            <a class="{{ Route::is('roles.*') ? 'li-active' : '' }}" href="{{ route('roles.index') }}" title="Role Management">
                <div class="parent-icon"><i class='bx bx-shield-quarter'></i></div>
                <div class="menu-title">Roles & Permissions</div>
            </a>
        </li>
        @endcan

        @endcanany

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