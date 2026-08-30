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
    /* Base Container Pill */
    .header-module-nav {
        background: #f1f5f9;
        border-radius: 50rem;
        padding: 4px;
        border: 1px solid #e2e8f0;
    }

    /* Base Link Styles */
    .module-nav-link {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.95rem;
        border-radius: 50rem;
        font-size: 0.8125rem;
        font-weight: 500;
        color: #64748b;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .module-nav-link i {
        font-size: 0.9rem;
        transition: transform 0.2s ease;
    }

    .module-nav-link:hover i {
        transform: scale(1.1);
    }

    /* Module Specific Hover Effects */
    .module-nav-link.configurationside:hover {
        background-color: #f1f5f9;
        color: #475569;

    }

    .module-nav-link.workingside:hover {
        background-color: #eff6ff;
        color: #2563eb;
    }

    .module-nav-link.reportingside:hover {
        background-color: #f0fdf4;
        color: #16a34a;
    }

    /* Active States with Distinct Visual Accents */
    .module-nav-link.active {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        font-weight: 600;
    }

    /* Configuration Active State */
    .module-nav-link .configurationside{
        background-color: #334155;
        color: #ffffff;
        padding:2px;
    }
    .module-nav-link .configurationside{
        color: #94a3b8;
    }

    /* Working Active State */
    .module-nav-link .workingside {
        background-color: #2563eb;
        color: #ffffff;
    }
    .module-nav-link.workingside.active i {
        color: #93c5fd;
    }

    /* Reporting Active State */
    .module-nav-link.reportingside.active {
        background-color: #16a34a;
        color: #ffffff;
    }
    .module-nav-link.reportingside.active i {
        color: #86efac;
    }

    /* Responsive Adaptation */
    @media (max-width: 1199.98px) {
        .header-module-nav { gap: 2px !important; }
        .module-nav-link { padding: 0.4rem 0.75rem; font-size: 0.78rem; }
    }
</style>
@endpush