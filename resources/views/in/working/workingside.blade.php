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