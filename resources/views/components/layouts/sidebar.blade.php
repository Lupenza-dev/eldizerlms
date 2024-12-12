<style>
    .li-active{
        background-color: #272F3B;
        color: white !important;
    }
    .li-active:hover{
        background-color: #272F3B !important;
        color: white !important;
    }
</style>
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img style="width: 200px" src="{{ asset('assets/images/eldizerlogo.jpeg')}}" class="logo-icon" alt="logo icon">
        </div>
        {{-- <div>
            <h4 class="logo-text">ElDIZER</h4>
        </div> --}}
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
     </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        @if (Auth::user()->hasRole(['Admin','Super Admin']))
        <li>
            <a class="{{ Route::is('admin.dashboard') ? "li-active": ""}}" href="{{ route('admin.dashboard')}}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Home</div>
            </a>
        </li>
        @else
        <li>
            <a class="{{ Route::is('dashboard') ? "li-active": ""}}" href="{{ route('dashboard')}}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Home</div>
            </a>
        </li>  
        @endif
       
        <li class="menu-label">Loan Management</li>
        <li>
            <a  class="{{ (Route::is('customers.index') or Route::is('customers.show') or Route::is('customers.edit')) ? "li-active": ""}}" href="{{ route('customers.index')}}">
                <div class="parent-icon"><i class='bx bx-user'></i>
                </div>
                <div class="menu-title">Customers</div>
            </a>
        </li>
        <li>
            <a class="{{ (Route::is('loan.applications') or Route::is('loan.profile')) ? "li-active": ""}}" href="{{ route('loan.applications')}}">
                <div class="parent-icon"><i class='bx bx-envelope'></i>
                </div>
                <div class="menu-title">Loan Applications</div>
            </a>
        </li>
        <li>
            <a class="{{ (Route::is('loan.contracts') or Route::is('loan.contract.profile')) ? "li-active": ""}}" href="{{ route('loan.contracts')}}">
                <div class="parent-icon"><i class='bx bx-list-ol'></i>
                </div>
                <div class="menu-title">Loan Contracts</div>
            </a>
        </li>
        @if (Auth::user()->hasRole(['Admin','Super Admin']))
            
        <li class="menu-label">Payment</li>
        <li>
            <a class="{{ Route::is('payment.disbursed') ? "li-active": ""}}" href="{{ route('payment.disbursed')}}">
                <div class="parent-icon"><i class='bx bx-money'></i>
                </div>
                <div class="menu-title">Disbursment</div>
            </a>
        </li>
        <li>
            <a class="{{ Route::is('payments') ? "li-active": ""}}" href="{{ route('payments')}}">
                <div class="parent-icon"><i class='lni lni-money-location'></i>
                </div>
                <div class="menu-title">Repayments</div>
            </a>
        </li>
        <li class="menu-label">Managements</li>
        <li>
            <a class="{{ Route::is('devices.index') ? "li-active": ""}}" href="{{ route('devices.index')}}">
                <div class="parent-icon"> <i class="bx bx-phone"></i>
                </div>
                <div class="menu-title">Devices</div>
            </a>
        </li>
        <li>
            <a class="{{ Route::is('colleges.index') ? "li-active": ""}}" href="{{ route('colleges.index')}}">
                <div class="parent-icon"> <i class="bx bx-buildings"></i>
                </div>
                <div class="menu-title">Universities</div>
            </a>
        </li>
        <li>
            <a class="{{ Route::is('beneficaries.index') ? "li-active": ""}}" href="{{ route('beneficaries.index')}}">
                <div class="parent-icon"><i class="bx bx-list-ul"></i>
                </div>
                <div class="menu-title">Loan Beneficaries</div>
            </a>
        </li>
        <li>
            <a class="{{ Route::is('agents.index') ? "li-active": ""}}" href="{{ route('agents.index')}}">
                <div class="parent-icon"><i class="bx bx-user-circle"></i>
                </div>
                <div class="menu-title">Agents</div>
            </a>
        </li>
       
        <li>
            <a class="{{ Route::is('users.index') ? "li-active": ""}}" href="{{ route('users.index')}}">
                <div class="parent-icon"><i class="bx bx-user"></i>
                </div>
                <div class="menu-title">Users</div>
            </a>
        </li>
        @endif
        <li>
            <a href="{{ route('logout')}}">
                <div class="parent-icon"><i class="bx bx-log-out-circle"></i>
                </div>
                <div class="menu-title">Logout</div>
            </a>
        </li>
        
        
      
      
    </ul>
    <!--end navigation-->
</div>