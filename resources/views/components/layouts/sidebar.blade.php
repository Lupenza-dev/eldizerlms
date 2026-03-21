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
    }
    
    /* Active State */
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
    
    /* Smooth Transitions */
    * {
        transition: all 0.3s ease;
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
        @if (Auth::user()->hasRole(['Admin','Super Admin']))
        <li>
            <a class="{{ Route::is('admin.dashboard') ? "li-active": ""}}" href="{{ route('admin.dashboard')}}" title="Dashboard">
                <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        @else
        <li>
            <a class="{{ Route::is('dashboard') ? "li-active": ""}}" href="{{ route('dashboard')}}" title="Dashboard">
                <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>  
        @endif
       
        <li class="menu-label">Loan Management</li>
        <li>
            <a class="{{ (Route::is('customers.index') or Route::is('customers.show') or Route::is('customers.edit')) ? "li-active": ""}}" href="{{ route('customers.index')}}" title="Customer Management">
                <div class="parent-icon"><i class='bx bx-user'></i></div>
                <div class="menu-title">Customers</div>
            </a>
        </li>
        <li>
            <a class="{{ (Route::is('loan.applications') or Route::is('loan.profile')) ? "li-active": ""}}" href="{{ route('loan.applications')}}" title="Loan Applications">
                <div class="parent-icon"><i class='bx bx-file'></i></div>
                <div class="menu-title">Loan Applications</div>
            </a>
        </li>
        <li>
            <a class="{{ (Route::is('loan.contracts') or Route::is('loan.contract.profile')) ? "li-active": ""}}" href="{{ route('loan.contracts')}}" title="Loan Contracts">
                <div class="parent-icon"><i class='bx bx-file'></i></div>
                <div class="menu-title">Loan Contracts</div>
            </a>
        </li>
        @if (Auth::user()->hasRole(['Admin','Super Admin']))
            
        <li class="menu-label">Payment Management</li>
        <li>
            <a class="{{ Route::is('payment.disbursed') ? "li-active": ""}}" href="{{ route('payment.disbursed')}}" title="Disbursement Management">
                <div class="parent-icon"><i class='bx bx-send'></i></div>
                <div class="menu-title">Disbursements</div>
            </a>
        </li>
        <li>
            <a class="{{ Route::is('payment.management') ? "li-active": ""}}" href="{{ route('payment.management')}}" title="Repayment Management">
                <div class="parent-icon"><i class='bx bx-receipt'></i></div>
                <div class="menu-title">Payments</div>
            </a>
        </li>
        <li>
            <a class="{{ Route::is('nmb.subscribers') ? "li-active": ""}}" href="{{ route('nmb.subscribers')}}" title="NMB Subscribers">
                <div class="parent-icon"><i class='bx bx-user'></i></div>
                <div class="menu-title">NMB Subscribers</div>
            </a>
        </li>
        <li class="menu-label">System Management</li>
        <li>
            <a class="{{ Route::is('app.management') ? "li-active": ""}}" href="{{ route('app.management')}}" title="App Management">
                <div class="parent-icon"><i class='bx bx-mobile-alt'></i></div>
                <div class="menu-title">Mobile App</div>
            </a>
        </li>
        <li>
            <a class="{{ Route::is('devices.index') ? "li-active": ""}}" href="{{ route('devices.index')}}" title="Device Management">
                <div class="parent-icon"><i class='bx bx-mobile'></i></div>
                <div class="menu-title">Devices</div>
            </a>
        </li>
        <li>
            <a class="{{ Route::is('colleges.index') ? "li-active": ""}}" href="{{ route('colleges.index')}}" title="University Management">
                <div class="parent-icon"><i class='bx bx-buildings'></i></div>
                <div class="menu-title">Universities</div>
            </a>
        </li>
        <li>
            <a class="{{ Route::is('beneficaries.index') ? "li-active": ""}}" href="{{ route('beneficaries.index')}}" title="HESLB Beneficiaries">
                <div class="parent-icon"><i class='bx bx-award'></i></div>
                <div class="menu-title">HESLB Beneficiaries</div>
            </a>
        </li>
        <li>
            <a class="{{ Route::is('agents.index') ? "li-active": ""}}" href="{{ route('agents.index')}}" title="Agent Management">
                <div class="parent-icon"><i class='bx bx-user-voice'></i></div>
                <div class="menu-title">Agents</div>
            </a>
        </li>
        <li>
            <a class="{{ Route::is('users.index') ? "li-active": ""}}" href="{{ route('users.index')}}" title="User Management">
                <div class="parent-icon"><i class='bx bx-users'></i></div>
                <div class="menu-title">Users</div>
            </a>
        </li>
        @endif
        <li>
            <a href="{{ route('logout')}}" title="Logout">
                <div class="parent-icon"><i class='bx bx-log-out-circle'></i></div>
                <div class="menu-title">Logout</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>