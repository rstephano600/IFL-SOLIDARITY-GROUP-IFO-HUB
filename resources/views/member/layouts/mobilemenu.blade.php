{{-- Mobile-only bottom tab bar (d-lg-none) --}}
<nav class="d-lg-none fixed-bottom bg-white border-top d-flex shadow-sm"
     style="height:62px; z-index:1040;">

    @php
        $primaryItems = [
            ['route' => 'memberdashboard',    'icon' => 'bi-grid-1x2',   'label' => 'Home'],
            ['route' => 'member.savings',     'icon' => 'bi-piggy-bank', 'label' => 'Savings'],
            ['route' => 'member.loans',       'icon' => 'bi-cash-coin',  'label' => 'Loans'],
            ['route' => 'member.notifications', 'icon' => 'bi-bell',    'label' => 'Alerts'],
        ];
    @endphp

    @foreach($primaryItems as $item)
        <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
           class="flex-fill d-flex flex-column align-items-center justify-content-center text-decoration-none
                  {{ Route::has($item['route']) && request()->routeIs($item['route']) ? 'text-ifl-navy fw-semibold' : 'text-muted' }}"
           style="font-size:.65rem;">
            <i class="bi {{ $item['icon'] }} {{ Route::has($item['route']) && request()->routeIs($item['route']) ? 'fw-bold' : '' }}"
               style="font-size:1.2rem; line-height:1;"></i>
            <span class="mt-1">{{ $item['label'] }}</span>
        </a>
    @endforeach

    <button type="button" class="flex-fill d-flex flex-column align-items-center justify-content-center text-muted bg-transparent border-0"
            style="font-size:.65rem;" data-bs-toggle="offcanvas" data-bs-target="#memberMoreMenu">
        <i class="bi bi-grid-3x3-gap" style="font-size:1.15rem; line-height:1;"></i>
        <span class="mt-1">More</span>
    </button>

</nav>

{{-- Full menu offcanvas (mirrors desktop sidebar) --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="memberMoreMenu" style="width:280px;">
    <div class="offcanvas-header bg-ifl-navy text-white">
        <div class="d-flex align-items-center gap-2">
            <div class="bg-white bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:32px;height:32px;">
                <img src="{{ asset('logo/iflsglogo.png') }}" alt="IFL" style="width:20px;height:20px;object-fit:contain;">
            </div>
            <div>
                <h5 class="offcanvas-title brand-font fs-6 mb-0">IFL Solidarity</h5>
                <span class="text-white-50 d-block" style="font-size:.7rem;">Member Portal</span>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body p-2 bg-ifl-navy">
        <div class="nav nav-pills flex-column gap-1">

            <!-- Dashboard -->
            <div class="nav-item">
                <a href="{{ Route::has('memberdashboard') ? route('memberdashboard') : '#' }}" 
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2.5 {{ request()->routeIs('memberdashboard') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-grid-1x2 fs-6"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Profile -->
            <div class="nav-item">
                <a href="{{ Route::has('member.profile') ? route('member.profile') : '#' }}" 
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2.5 {{ request()->routeIs('member.profile') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-person fs-6"></i>
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
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2.5 {{ request()->routeIs('member.loans') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-cash-coin fs-6"></i>
                    <span>Loans</span>
                </a>
            </div>

            <!-- Repayments -->
            <div class="nav-item">
                <a href="{{ Route::has('member.repayments') ? route('member.repayments') : '#' }}" 
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2.5 {{ request()->routeIs('member.repayments') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-receipt fs-6"></i>
                    <span>Repayments</span>
                </a>
            </div>

            <!-- Dividends -->
            <div class="nav-item">
                <a href="{{ Route::has('member.dividends') ? route('member.dividends') : '#' }}" 
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2.5 {{ request()->routeIs('member.dividends') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-graph-up-arrow fs-6"></i>
                    <span>Dividends</span>
                </a>
            </div>

            <!-- Notifications -->
            <div class="nav-item">
                <a href="{{ Route::has('member.notifications') ? route('member.notifications') : '#' }}" 
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2.5 {{ request()->routeIs('member.notifications') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-bell fs-6"></i>
                    <span>Notifications</span>
                </a>
            </div>

            <!-- Settings -->
            <div class="nav-item">
                <a href="{{ Route::has('member.settings') ? route('member.settings') : '#' }}" 
                   class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2.5 {{ request()->routeIs('member.settings') ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                   style="font-size:.875rem;">
                    <i class="bi bi-gear fs-6"></i>
                    <span>Settings</span>
                </a>
            </div>

            <!-- Logout -->
            <div class="pt-3 mt-2 border-top border-white border-opacity-10">
                <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
                    @csrf
                    <button type="submit" class="btn btn-sm w-100 d-flex align-items-center justify-content-center gap-2"
                            style="background:rgba(255,255,255,.08); color:#fff; border:1px solid rgba(255,255,255,.15);">
                        <i class="bi bi-box-arrow-right"></i> Sign out
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>