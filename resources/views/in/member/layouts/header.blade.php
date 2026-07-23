@php
    $authUser = session('auth_user', []);
@endphp

<header class="d-flex align-items-center justify-content-between bg-white border-bottom px-3 py-2 sticky-top"
        style="z-index:1020;">

    <div class="d-flex align-items-center gap-2 d-lg-none">
        <div class="bg-ifl-navy rounded-3 d-flex align-items-center justify-content-center"
             style="width:34px;height:34px;">
            <img src="{{ asset('logo/iflsglogo.png') }}" alt="IFL" style="width:20px;height:20px;object-fit:contain;">
        </div>
        <span class="fw-semibold text-dark brand-font" style="font-size:.9rem;">IFL Solidarity</span>
    </div>

    <h1 class="fs-6 fw-semibold text-dark mb-0 d-none d-lg-block">@yield('title')</h1>

    <nav class="header-module-nav d-none d-lg-flex align-items-center gap-1 ms-4">
        @can('view-confirguration-side')
        <a href="{{ route('configurationside') }}"
           class="module-nav-link configurationside {{ request()->routeIs('configurationside') ? 'active' : '' }}">
            <i class="bi bi-sliders"></i> Configuration
        </a>
        @endcan
        @can('view-working-side')
        <a href="{{ route('workingside') }}"
           class="module-nav-link workingside {{ request()->routeIs('workingside') ? 'active' : '' }}">
            <i class="bi bi-list-check"></i> Working
        </a>
        @endcan
        @can('view-reporting-side')
        <a href="{{ route('reportingside') }}"
           class="module-nav-link reportingside {{ request()->routeIs('reportingside') ? 'active' : '' }}">
            <i class="bi bi-graph-up"></i> Reports
        </a>
        @endcan
    </nav>

    <div class="d-flex align-items-center gap-2 gap-lg-3">

        <a href="{{ Route::has('member.notifications') ? route('member.notifications') : '#' }}"
           class="position-relative text-muted d-flex align-items-center justify-content-center"
           style="width:38px;height:38px;">
            <i class="bi bi-bell fs-5"></i>
            {{-- Optional unread badge — wire up when notifications count is available
            <span class="position-absolute top-0 start-75 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;">3</span>
            --}}
        </a>

        <div class="dropdown">
            <button class="btn d-flex align-items-center gap-2 p-1 pe-2" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <div class="bg-ifl-navy text-white rounded-circle d-flex align-items-center justify-content-center fw-semibold flex-shrink-0"
                     style="width:34px;height:34px;font-size:.8rem;">
                    {{ strtoupper(substr($authUser['name'] ?? 'M', 0, 1)) }}
                </div>
                <span class="d-none d-lg-block text-start" style="line-height:1.15;">
                    <span class="d-block small fw-semibold text-dark">{{ $authUser['name'] ?? 'Member' }}</span>
                    <span class="d-block text-muted" style="font-size:.7rem;">{{ $authUser['username'] ?? '' }}</span>
                </span>
                <i class="bi bi-chevron-down text-muted d-none d-lg-block" style="font-size:.7rem;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><a class="dropdown-item" href="{{ Route::has('member.profile') ? route('member.profile') : '#' }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="{{ Route::has('member.settings') ? route('member.settings') : '#' }}"><i class="bi bi-gear me-2"></i>Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Sign out</button>
                    </form>
                </li>
            </ul>
        </div>

    </div>

</header>

@push('styles')
<style>
    .header-module-nav {
        background: #f3f4f6;
        border-radius: 999px;
        padding: 4px;
    }

    .module-nav-link {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .4rem .9rem;
        border-radius: 999px;
        font-size: .8rem;
        font-weight: 500;
        color: #5b6470;
        text-decoration: none;
        white-space: nowrap;
        transition: background-color .15s ease, color .15s ease;
    }

    .module-nav-link i {
        font-size: .85rem;
        line-height: 1;
    }

    .module-nav-link:hover {
        background-color: rgba(13, 42, 74, 0.08);
        color: var(--ifl-navy);
    }

    .module-nav-link.active {
        background-color: var(--ifl-navy);
        color: #fff;
        box-shadow: 0 2px 6px rgba(13, 42, 74, 0.25);
    }

    .module-nav-link.active i {
        color: var(--ifl-gold);
    }

    /* Keep the module nav from crowding the header on narrower desktop widths */
    @media (max-width: 1199.98px) {
        .header-module-nav { gap: 0 !important; }
        .module-nav-link { padding: .4rem .65rem; font-size: .75rem; }
        .module-nav-link span { display: none; }
    }
</style>
@endpush