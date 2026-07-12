{{--
    Mobile-only bottom tab bar (d-lg-none).
    Shows the 4 most-used items + a "More" button that opens
    an offcanvas with the full menu (mirrors the desktop sidebar).
--}}

<nav class="d-lg-none fixed-bottom bg-white border-top d-flex"
     style="height:62px; z-index:1040;">

    @php
        $primaryItems = [
            ['route' => 'memberdashboard',   'icon' => 'bi-grid-1x2',   'label' => 'Home'],
            ['route' => 'member.savings',    'icon' => 'bi-piggy-bank', 'label' => 'Savings'],
            ['route' => 'member.loans',      'icon' => 'bi-cash-coin',  'label' => 'Loans'],
            ['route' => 'member.notifications', 'icon' => 'bi-bell',    'label' => 'Alerts'],
        ];
    @endphp

    @foreach($primaryItems as $item)
        <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
           class="flex-fill d-flex flex-column align-items-center justify-content-center text-decoration-none
                  {{ Route::has($item['route']) && request()->routeIs($item['route']) ? 'text-ifl-navy' : 'text-muted' }}"
           style="font-size:.65rem;">
            <i class="bi {{ $item['icon'] }} {{ Route::has($item['route']) && request()->routeIs($item['route']) ? 'fw-semibold' : '' }}"
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

{{-- Full menu, reachable from "More" on mobile --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="memberMoreMenu">
    <div class="offcanvas-header bg-ifl-navy">
        <h5 class="offcanvas-title text-white brand-font fs-6">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="list-unstyled mb-0">
            @php
                $fullItems = [
                    ['route' => 'memberdashboard',      'icon' => 'bi-grid-1x2',       'label' => 'Dashboard'],
                    ['route' => 'member.profile',       'icon' => 'bi-person',         'label' => 'Profile'],
                    ['route' => 'member.shares',        'icon' => 'bi-pie-chart',      'label' => 'Shares'],
                    ['route' => 'member.savings',       'icon' => 'bi-piggy-bank',     'label' => 'Savings'],
                    ['route' => 'member.loans',         'icon' => 'bi-cash-coin',      'label' => 'Loans'],
                    ['route' => 'member.repayments',    'icon' => 'bi-receipt',        'label' => 'Repayments'],
                    ['route' => 'member.dividends',     'icon' => 'bi-graph-up-arrow', 'label' => 'Dividends'],
                    ['route' => 'member.notifications', 'icon' => 'bi-bell',           'label' => 'Notifications'],
                    ['route' => 'member.settings',      'icon' => 'bi-gear',           'label' => 'Settings'],
                ];
            @endphp
            @foreach($fullItems as $item)
                <li class="border-bottom">
                    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                       class="d-flex align-items-center gap-3 px-3 py-3 text-decoration-none
                              {{ Route::has($item['route']) && request()->routeIs($item['route']) ? 'text-ifl-navy fw-semibold' : 'text-dark' }}">
                        <i class="bi {{ $item['icon'] }} fs-5"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
            <li>
                <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}" class="px-3 py-3">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-right"></i> Sign out
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>