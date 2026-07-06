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
                AIR-BIF Management Dashboard
            </h4>
            <small class="text-muted">
                Welcome {{ Auth::user()->name }} | {{ now()->format('l, d M Y') }}
            </small>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- LOAN INFORMATION --}}
    {{-- ============================= --}}
    <div class="row">

        @can('view-loan')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div>
                            <h6 class="mb-1">Active Loans</h6>
                            <h2 class="fw-bold mb-0">{{ number_format($activeLoans) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('approve-loans')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-2 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-hourglass-half"></i></div>
                        <div>
                            <h6 class="mb-1">Pending Loan Approval</h6>
                            <h2 class="fw-bold mb-0">{{ number_format($pendingLoanApproval) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('view-loan-repayments')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-2 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-money-check-alt"></i></div>
                        <div>
                            <h6 class="mb-1">Pending Repayment</h6>
                            <h2 class="fw-bold mb-0">{{ number_format($pendingRepayment) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('view-loan')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-hand-holding-usd"></i></div>
                        <div>
                            <h6 class="mb-1">Disbursed Loans</h6>
                            <h2 class="fw-bold mb-0">{{ number_format($disbursedLoans) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

    </div>

    {{-- ============================= --}}
    {{-- SECOND ROW --}}
    {{-- ============================= --}}
    <div class="row">

        @can('view-loan')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <h6 class="mb-1">Completed Loans</h6>
                            <h2 class="mb-0">{{ number_format($completedLoans) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('view-loan-refunding')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-undo"></i></div>
                        <div>
                            <h6 class="mb-1">Refunded Loans</h6>
                            <h2 class="mb-0">{{ number_format($refundedLoans) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('view-loan')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-chart-pie"></i></div>
                        <div>
                            <h6 class="mb-1">Loan Portfolio</h6>
                            <h5 class="mb-0">TZS {{ number_format($loanPortfolio,2) }}</h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

    </div>

    {{-- ============================= --}}
    {{-- CLIENTS / USERS --}}
    {{-- ============================= --}}
    <div class="row">

        @can('view-loan-beneficiary')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-users"></i></div>
                        <div>
                            <h6 class="mb-1">Total Clients</h6>
                            <h2 class="mb-0">{{ number_format($totalClients) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('register-employees')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-user-tie"></i></div>
                        <div>
                            <h6 class="mb-1">Active Employees</h6>
                            <h2 class="mb-0">{{ number_format($activeEmployees) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('register-employees')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-user-slash"></i></div>
                        <div>
                            <h6 class="mb-1">Inactive Employees</h6>
                            <h2 class="mb-0">{{ number_format($inactiveEmployees) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('view-system-users')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-user-check"></i></div>
                        <div>
                            <h6 class="mb-1">Active Users</h6>
                            <h2 class="mb-0">{{ number_format($activeUsers) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

    </div>

    {{-- ============================= --}}
    {{-- PAYROLL --}}
    {{-- ============================= --}}
    <div class="row">

        @can('view-salary')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-solid shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon dashboard-icon-solid"><i class="fas fa-money-check-alt"></i></div>
                        <div>
                            <h6 class="mb-1">Monthly Payroll</h6>
                            <h4 class="mb-0">TZS {{ number_format($monthlyPayroll,2) }}</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('pending-salary')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-2 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-clock"></i></div>
                        <div>
                            <h6 class="mb-1">Pending Payroll</h6>
                            <h2 class="mb-0">{{ number_format($pendingPayroll) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('view-weekly-allowance')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-solid shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon dashboard-icon-solid"><i class="fas fa-coins"></i></div>
                        <div>
                            <h6 class="mb-1">Weekly Allowances</h6>
                            <h4 class="mb-0">TZS {{ number_format($weeklyAllowance,2) }}</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('pending-weekly-allowance')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-2 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-exclamation-circle"></i></div>
                        <div>
                            <h6 class="mb-1">Pending Weekly Allowances</h6>
                            <h2 class="mb-0">{{ number_format($pendingWeeklyAllowance) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

    </div>

    {{-- ============================= --}}
    {{-- FINANCE --}}
    {{-- ============================= --}}
    <div class="row">

        @can('view-expenses')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-file-invoice"></i></div>
                        <div>
                            <h6 class="mb-1">Monthly Expenses</h6>
                            <h4 class="mb-0">TZS {{ number_format($monthlyExpenses,2) }}</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('approve-expense')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-hourglass-half"></i></div>
                        <div>
                            <h6 class="mb-1">Pending Expenses</h6>
                            <h2 class="mb-0">{{ number_format($pendingExpenses) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('view-loan-repayments')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-cash-register"></i></div>
                        <div>
                            <h6 class="mb-1">Today's Collections</h6>
                            <h4 class="mb-0">TZS {{ number_format($todayCollections,2) }}</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('view-loan')
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-weight-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon"><i class="fas fa-wallet"></i></div>
                        <div>
                            <h6 class="mb-1">Today's Disbursement</h6>
                            <h4 class="mb-0">TZS {{ number_format($todayDisbursement,2) }}</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

    </div>

    {{-- ============================= --}}
    {{-- PROFIT --}}
    {{-- ============================= --}}
    <div class="row">

        @can('view-expense-report')
        <div class="col-lg-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-solid shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon dashboard-icon-solid"><i class="fas fa-chart-line"></i></div>
                        <div>
                            <h5 class="mb-1">Gross Profit</h5>
                            <h2 class="mb-0">TZS {{ number_format($grossProfit,2) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        @can('view-expense-report')
        <div class="col-lg-6 mb-3">
            <a href="#" class="dashboard-card-link">
                <div class="card dashboard-card dashboard-card-solid-dark shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="dashboard-icon dashboard-icon-solid"><i class="fas fa-chart-line"></i></div>
                        <div>
                            <h5 class="mb-1">Net Profit</h5>
                            <h2 class="mb-0">TZS {{ number_format($netProfit,2) }}</h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endcan

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