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