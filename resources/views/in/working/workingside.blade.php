@extends('layouts.workingside')

@section('content')

<div class="container-fluid dashboard-page">

    {{-- ============================= --}}
    {{-- PAGE HEADER --}}
    {{-- ============================= --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <h4 class="fw-bold">
                <i class="fas fa-tachometer-alt text-primary"></i>
                IFL SOLIDARITY GROUP Management Dashboard
            </h4>
            <small class="text-muted">
                Welcome {{ Auth::user()->name }} | {{ now()->format('l, d M Y') }}
            </small>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- SHARE PURCHASES --}}
    {{-- ============================= --}}
    @can('view-share-purchases')
    <div class="row mb-2">
        <div class="col-12">
            <h6 class="fw-bold text-muted text-uppercase small mb-2"><i class="fas fa-coins me-2"></i>Share Purchases</h6>
        </div>
    </div>
    <div class="row g-3 mb-4">
        @can('create-share-purchases')
        <div class="col-md-3">
            <a href="{{ route('sharepurchasetransactions') }}" class="dashboard-card-link">
                <div class="dashboard-card p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-plus-circle"></i></div>
                    <div><h6 class="mb-1">New Entry</h6><h5 class="mb-0">Share Purchase</h5></div>
                </div>
            </a>
        </div>
        @endcan
        @can('verify-share-purchases')
        <div class="col-md-3">
            <a href="{{ route('unverifiedsharepurchasetransactions') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-2 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-shield-alt"></i></div>
                    <div><h6 class="mb-1">Stage 1</h6><h5 class="mb-0">Unverified</h5></div>
                </div>
            </a>
        </div>
        @endcan
        @can('approve-share-purchases')
        <div class="col-md-3">
            <a href="{{ route('pendingapprovalsharepurchasetransactions') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-2 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-user-check"></i></div>
                    <div><h6 class="mb-1">Stage 2</h6><h5 class="mb-0">Pending Approval</h5></div>
                </div>
            </a>
        </div>
        @endcan
        @can('post-share-purchases')
        <div class="col-md-3">
            <a href="{{ route('pendingpostingsharepurchasetransactions') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-2 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div><h6 class="mb-1">Stage 3</h6><h5 class="mb-0">Pending Posting</h5></div>
                </div>
            </a>
        </div>
        @endcan
        @can('generate-certificate-share-purchases')
        <div class="col-md-3">
            <a href="{{ route('pendingcertificatesharepurchasetransactions') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-2 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-certificate"></i></div>
                    <div><h6 class="mb-1">Stage 4</h6><h5 class="mb-0">Pending Certificate</h5></div>
                </div>
            </a>
        </div>
        @endcan
        @can('view-share-purchases')
        <div class="col-md-3">
            <a href="{{ route('completedsharepurchasetransactions') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-3 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-check-double"></i></div>
                    <div><h6 class="mb-1">Archive</h6><h5 class="mb-0">Completed</h5></div>
                </div>
            </a>
        </div>
        @endcan
        @can('view-share-purchases-report')
        <div class="col-md-3">
            <a href="{{ route('sharepurchasetransactionsreport') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-3 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-chart-bar"></i></div>
                    <div><h6 class="mb-1">Analytics</h6><h5 class="mb-0">Purchases Report</h5></div>
                </div>
            </a>
        </div>
        @endcan
    </div>
    @endcan

    {{-- ============================= --}}
    {{-- SOCIAL CONTRIBUTIONS --}}
    {{-- ============================= --}}
    @can('view-social-contribution')
    <div class="row mb-2">
        <div class="col-12">
            <h6 class="fw-bold text-muted text-uppercase small mb-2"><i class="fas fa-hand-holding-heart me-2"></i>Social Contributions</h6>
        </div>
    </div>
    <div class="row g-3 mb-4">
        @can('create-social-contribution')
        <div class="col-md-3">
            <a href="{{ route('socialcontributions') }}" class="dashboard-card-link">
                <div class="dashboard-card p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-plus-circle"></i></div>
                    <div><h6 class="mb-1">New Entry</h6><h5 class="mb-0">Social Contribution</h5></div>
                </div>
            </a>
        </div>
        @endcan
        @can('verify-social-contribution')
        <div class="col-md-3">
            <a href="{{ route('unverifiedsocialcontributions') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-2 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-shield-alt"></i></div>
                    <div><h6 class="mb-1">Stage 1</h6><h5 class="mb-0">Unverified</h5></div>
                </div>
            </a>
        </div>
        @endcan
        @can('approve-social-contribution')
        <div class="col-md-3">
            <a href="{{ route('pendingapprovalsocialcontributions') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-2 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-user-check"></i></div>
                    <div><h6 class="mb-1">Stage 2</h6><h5 class="mb-0">Pending Approval</h5></div>
                </div>
            </a>
        </div>
        @endcan
        @can('post-social-contribution')
        <div class="col-md-3">
            <a href="{{ route('pendingpostingsocialcontributions') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-2 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div><h6 class="mb-1">Stage 3</h6><h5 class="mb-0">Pending GL Posting</h5></div>
                </div>
            </a>
        </div>
        @endcan
        <div class="col-md-3">
            <a href="{{ route('completedsocialcontributions') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-3 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-check-double"></i></div>
                    <div><h6 class="mb-1">Stage 4</h6><h5 class="mb-0">Completed</h5></div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('socialcontributions') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-3 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-list-alt"></i></div>
                    <div><h6 class="mb-1">Master</h6><h5 class="mb-0">All Contributions</h5></div>
                </div>
            </a>
        </div>
    </div>
    @endcan

    {{-- ============================= --}}
    {{-- MEMBERSHIP FEE PAYMENTS --}}
    {{-- ============================= --}}
    @can('view-membership-fee-payment')
    <div class="row mb-2">
        <div class="col-12">
            <h6 class="fw-bold text-muted text-uppercase small mb-2"><i class="fas fa-id-card me-2"></i>Membership Fee Payments</h6>
        </div>
    </div>
    <div class="row g-3 mb-4">
        @can('create-membership-fee-payment')
        <div class="col-md-3">
            <a href="{{ route('membershipfeepayments') }}" class="dashboard-card-link">
                <div class="dashboard-card p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-plus-circle"></i></div>
                    <div><h6 class="mb-1">New Entry</h6><h5 class="mb-0">Fee Payment</h5></div>
                </div>
            </a>
        </div>
        @endcan
        @can('verify-membership-fee-payment')
        <div class="col-md-3">
            <a href="{{ route('unverifiedmembershipfeepayments') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-2 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-shield-alt"></i></div>
                    <div><h6 class="mb-1">Stage 1</h6><h5 class="mb-0">Unverified</h5></div>
                </div>
            </a>
        </div>
        @endcan
        @can('approve-membership-fee-payment')
        <div class="col-md-3">
            <a href="{{ route('pendingapprovalmembershipfeepayments') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-2 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-user-check"></i></div>
                    <div><h6 class="mb-1">Stage 2</h6><h5 class="mb-0">Pending Approval</h5></div>
                </div>
            </a>
        </div>
        @endcan
        @can('post-membership-fee-payment')
        <div class="col-md-3">
            <a href="{{ route('pendingpostingmembershipfeepayments') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-2 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div><h6 class="mb-1">Stage 3</h6><h5 class="mb-0">Pending Posting</h5></div>
                </div>
            </a>
        </div>
        @endcan
        @can('view-membership-fee-payment')
        <div class="col-md-3">
            <a href="{{ route('completedmembershipfeepayments') }}" class="dashboard-card-link">
                <div class="dashboard-card dashboard-card-weight-3 p-3 shadow-sm d-flex align-items-center">
                    <div class="dashboard-icon"><i class="fas fa-check-double"></i></div>
                    <div><h6 class="mb-1">Archive</h6><h5 class="mb-0">Completed</h5></div>
                </div>
            </a>
        </div>
        @endcan
    </div>
    @endcan

</div>

<style>
    :root{
        --dash-accent: #0d6efd;      /* single base color used throughout */
        --dash-accent-10: rgba(13,110,253,0.08);
        --dash-accent-20: rgba(13,110,253,0.15);
        --dash-accent-dark: #084298;
    }

    .dashboard-card-link{
        text-decoration: none;
        display: block;
    }

    .dashboard-card{
        border: none;
        border-left: 4px solid var(--dash-accent);
        border-radius: 10px;
        background: #fff;
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .dashboard-card:hover{
        transform: translateY(-3px);
        box-shadow: 0 .6rem 1.2rem rgba(13,110,253,0.15) !important;
    }

    .dashboard-card h6{
        color: #6c757d;
        font-weight: 500;
        font-size: .8rem;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .dashboard-card h2, .dashboard-card h4, .dashboard-card h5{
        color: #212529;
    }

    /* Lighter tint variant for secondary-priority metrics, keeps single hue */
    .dashboard-card-weight-2{
        border-left-color: var(--dash-accent);
        opacity: .92;
    }

    .dashboard-card-weight-3{
        border-left-color: #8aa9d6;
    }

    /* Icon badge */
    .dashboard-icon{
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 50%;
        background: var(--dash-accent-10);
        color: var(--dash-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-right: 14px;
    }

    /* Solid filled cards for the headline financial figures only,
       still using the same base hue for consistency */
    .dashboard-card-solid{
        background: var(--dash-accent);
        border-left-color: var(--dash-accent);
        color: #fff;
    }

    .dashboard-card-solid h6,
    .dashboard-card-solid h2,
    .dashboard-card-solid h4,
    .dashboard-card-solid h5{
        color: #fff;
    }

    .dashboard-card-solid-dark{
        background: var(--dash-accent-dark);
        border-left-color: var(--dash-accent-dark);
        color: #fff;
    }

    .dashboard-card-solid-dark h6,
    .dashboard-card-solid-dark h2,
    .dashboard-card-solid-dark h4,
    .dashboard-card-solid-dark h5{
        color: #fff;
    }

    .dashboard-icon-solid{
        background: rgba(255,255,255,0.18);
        color: #fff;
    }
</style>

@endsection