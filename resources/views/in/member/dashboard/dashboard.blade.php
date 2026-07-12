@extends('in.member.layouts.app')
@section('title', 'Dashboard')

@section('content')

    @if(!$member)
        <div class="alert alert-warning d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-triangle mt-1"></i>
            <div>
                <strong>No membership record found.</strong>
                <p class="mb-0 small">Your account isn't linked to a member profile yet. Please contact the office.</p>
            </div>
        </div>
    @else

        {{-- Welcome + status --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h1 class="fs-5 fw-semibold text-dark mb-1">
                    Welcome, {{ $member->memberUser->name ?? $member->member_name }}
                </h1>
                <p class="text-muted small mb-0">
                    Member code: <span class="fw-semibold text-dark">{{ $member->member_code }}</span>
                </p>
            </div>
            <span class="badge bg-ifl-navy px-3 py-2">
                {{ $member->memberCategory->member_category_name ?? 'Member' }}
            </span>
        </div>

        {{-- Profile summary card --}}
        <div class="card mb-3">
            <div class="card-body">
                <h2 class="fs-6 fw-semibold text-ifl-navy mb-3">
                    <i class="bi bi-person-badge me-1"></i> Profile Summary
                </h2>
                <div class="row g-3">
                    <div class="col-6 col-lg-3">
                        <p class="text-muted mb-1" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.05em;">Branch</p>
                        <p class="fw-semibold text-dark mb-0 small">{{ $member->branch->branch_name ?? '—' }}</p>
                    </div>
                    <div class="col-6 col-lg-3">
                        <p class="text-muted mb-1" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.05em;">Company</p>
                        <p class="fw-semibold text-dark mb-0 small">{{ $member->company->name ?? '—' }}</p>
                    </div>
                    <div class="col-6 col-lg-3">
                        <p class="text-muted mb-1" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.05em;">Admission Date</p>
                        <p class="fw-semibold text-dark mb-0 small">
                            {{ $member->admission_date ? \Carbon\Carbon::parse($member->admission_date)->format('d M Y') : '—' }}
                        </p>
                    </div>
                    <div class="col-6 col-lg-3">
                        <p class="text-muted mb-1" style="font-size:.7rem; text-transform:uppercase; letter-spacing:.05em;">NIDA</p>
                        <p class="fw-semibold text-dark mb-0 small">{{ $member->nida ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick links to other member sections --}}
        <h2 class="fs-6 fw-semibold text-dark mb-2 mt-4">Quick access</h2>
        <div class="row g-3">
            @php
                $tiles = [
                    ['route' => 'member.shares',     'icon' => 'bi-pie-chart',      'label' => 'Shares',      'desc' => 'View your share balance'],
                    ['route' => 'member.savings',    'icon' => 'bi-piggy-bank',     'label' => 'Savings',     'desc' => 'View savings account'],
                    ['route' => 'member.loans',      'icon' => 'bi-cash-coin',      'label' => 'Loans',       'desc' => 'View active loans'],
                    ['route' => 'member.repayments', 'icon' => 'bi-receipt',        'label' => 'Repayments',  'desc' => 'View repayment history'],
                    ['route' => 'member.dividends',  'icon' => 'bi-graph-up-arrow', 'label' => 'Dividends',   'desc' => 'View dividend history'],
                    ['route' => 'member.profile',    'icon' => 'bi-person',         'label' => 'Profile',     'desc' => 'View your details'],
                ];
            @endphp

            @foreach($tiles as $tile)
                <div class="col-6 col-lg-4">
                    <a href="{{ Route::has($tile['route']) ? route($tile['route']) : '#' }}"
                       class="card h-100 text-decoration-none p-3 d-flex flex-row align-items-center gap-3">
                        <div class="bg-ifl-navy bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:44px;height:44px;">
                            <i class="bi {{ $tile['icon'] }} text-ifl-navy fs-5"></i>
                        </div>
                        <div class="text-truncate">
                            <p class="fw-semibold text-dark mb-0 small">{{ $tile['label'] }}</p>
                            <p class="text-muted mb-0 text-truncate" style="font-size:.72rem;">{{ $tile['desc'] }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

    @endif

@endsection