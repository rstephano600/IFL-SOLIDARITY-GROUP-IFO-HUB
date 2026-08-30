<aside class="d-none d-lg-flex flex-column bg-ifl-navy position-fixed top-0 start-0 h-100"
       style="width:250px; z-index:1030;">

    <!-- Brand Header -->
    <div class="d-flex align-items-center gap-2 px-3 py-3 border-bottom border-white border-opacity-10">
        <div class="bg-white bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
             style="width:38px;height:38px;">
            <img src="{{ asset('logo/iflsglogo.png') }}" alt="IFL" style="width:24px;height:24px;object-fit:contain;">
        </div>
        <div class="text-truncate">
            <p class="text-white fw-semibold mb-0 brand-font" style="font-size:.85rem; line-height:1.2;">IFL Solidarity</p>
            <p class="text-white-50 mb-0" style="font-size:.7rem;">Member Portal</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-grow-1 overflow-auto py-2">
        <div class="nav nav-pills flex-column px-2 gap-1">

            <!-- Dashboard -->
            <div class="nav-item">
                <a href="{{ Route::has('memberdashboard') ? route('memberdashboard') : '#' }}" 
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2 {{ request()->routeIs('memberdashboard') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-grid-1x2"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Profile -->
            <div class="nav-item">
                <a href="{{ Route::has('member.profile') ? route('member.profile') : '#' }}" 
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2 {{ request()->routeIs('member.profile') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
            </div>

            <!-- Savings -->
            <div class="nav-item">
                <a href="{{ Route::has('myShares') ? route('myShares') : '#' }}" 
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2 {{ request()->routeIs('myShares') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-piggy-bank"></i>
                    <span>My Shares</span>
                </a>
            </div>

            <!-- Loans -->
            <div class="nav-item">
                <a href="{{ Route::has('member.loans') ? route('member.loans') : '#' }}" 
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2 {{ request()->routeIs('member.loans') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-cash-coin"></i>
                    <span>Loans</span>
                </a>
            </div>

            <!-- Repayments -->
            <div class="nav-item">
                <a href="{{ Route::has('member.repayments') ? route('member.repayments') : '#' }}" 
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2 {{ request()->routeIs('member.repayments') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-receipt"></i>
                    <span>Repayments</span>
                </a>
            </div>

            <!-- Dividends -->
            <div class="nav-item">
                <a href="{{ Route::has('member.dividends') ? route('member.dividends') : '#' }}" 
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2 {{ request()->routeIs('member.dividends') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Dividends</span>
                </a>
            </div>

            <!-- Notifications -->
            <div class="nav-item">
                <a href="{{ Route::has('member.notifications') ? route('member.notifications') : '#' }}" 
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2 {{ request()->routeIs('member.notifications') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-bell"></i>
                    <span>Notifications</span>
                </a>
            </div>

            <!-- Settings -->
            <div class="nav-item">
                <a href="{{ Route::has('member.settings') ? route('member.settings') : '#' }}" 
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2 {{ request()->routeIs('member.settings') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </div>

        </div>
    </nav>

    <!-- Footer Sign Out -->
    <div class="px-3 py-3 border-top border-white border-opacity-10">
        <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
            @csrf
            <button type="submit" class="btn btn-sm w-100 d-flex align-items-center justify-content-center gap-2"
                    style="background:rgba(255,255,255,.08); color:#fff; border:1px solid rgba(255,255,255,.15);">
                <i class="bi bi-box-arrow-right"></i> Sign out
            </button>
        </form>
    </div>

</aside>