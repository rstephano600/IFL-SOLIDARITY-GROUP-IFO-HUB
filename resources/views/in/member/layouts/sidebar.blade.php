{{--
    Desktop sidebar only (d-none d-lg-flex).
    Mobile nav lives entirely in mobilemenu.blade.php.
    Adjust route names below to match your actual routes file.
--}}
<aside class="d-none d-lg-flex flex-column bg-ifl-navy position-fixed top-0 start-0 h-100"
       style="width:250px; z-index:1030;">

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

    <nav class="flex-grow-1 overflow-auto py-2">
        <ul class="nav nav-pills flex-column px-2 gap-1">

            @php
                $navItems = [
                    ['route' => 'memberdashboard',      'icon' => 'bi-grid-1x2',      'label' => 'Dashboard'],
                    ['route' => 'member.profile',       'icon' => 'bi-person',        'label' => 'Profile'],
                    ['route' => 'member.shares',        'icon' => 'bi-pie-chart',     'label' => 'Shares'],
                    ['route' => 'member.savings',       'icon' => 'bi-piggy-bank',    'label' => 'Savings'],
                    ['route' => 'member.loans',         'icon' => 'bi-cash-coin',     'label' => 'Loans'],
                    ['route' => 'member.repayments',    'icon' => 'bi-receipt',       'label' => 'Repayments'],
                    ['route' => 'member.dividends',     'icon' => 'bi-graph-up-arrow','label' => 'Dividends'],
                    ['route' => 'member.notifications', 'icon' => 'bi-bell',          'label' => 'Notifications'],
                    ['route' => 'member.settings',      'icon' => 'bi-gear',          'label' => 'Settings'],
                ];
            @endphp

            @foreach($navItems as $item)
                <li class="nav-item">
                    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                       class="nav-link d-flex align-items-center gap-2 rounded-3 px-3 py-2
                              {{ Route::has($item['route']) && request()->routeIs($item['route']) ? 'active bg-ifl-gold text-ifl-navy fw-semibold' : 'text-white-50' }}"
                       style="font-size:.875rem;">
                        <i class="bi {{ $item['icon'] }}"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach

        </ul>
    </nav>

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