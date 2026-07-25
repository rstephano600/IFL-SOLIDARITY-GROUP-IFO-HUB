@extends('layouts.configside')
@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-gear"></i></div>
        Configuration Dashboard
    </h3>
</div>

{{-- ============ ORGANIZATION STRUCTURE ============ --}}

@can('view-companies-menu')
<div class="mb-4">
    <h6 class="fw-bold text-muted text-uppercase small mb-3"><i class="fas fa-building me-2"></i>Companies</h6>
    <div class="row g-3">
        @can('view-companies')
        <div class="col-md-4">
            <a href="{{ route('companiesinformations') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-eye"></i></div>
                <span class="fw-semibold text-dark">View Companies</span>
            </a>
        </div>
        @endcan
        @can('restore-companies')
        <div class="col-md-4">
            <a href="{{ route('companiesRestore') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-danger bg-opacity-10 text-danger me-3"><i class="fas fa-trash-arrow-up"></i></div>
                <span class="fw-semibold text-dark">Deleted Companies</span>
            </a>
        </div>
        @endcan
        @can('view-companies-report')
        <div class="col-md-4">
            <a href="{{ route('companiesReport') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-secondary bg-opacity-10 text-secondary me-3"><i class="fas fa-chart-bar"></i></div>
                <span class="fw-semibold text-dark">Companies Report</span>
            </a>
        </div>
        @endcan
    </div>
</div>
@endcan

@can('view-branches-menu')
<div class="mb-4">
    <h6 class="fw-bold text-muted text-uppercase small mb-3"><i class="fas fa-code-branch me-2"></i>Branches</h6>
    <div class="row g-3">
        @can('view-branches')
        <div class="col-md-4">
            <a href="{{ route('branchiesinformations') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-eye"></i></div>
                <span class="fw-semibold text-dark">View Branches</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('deletedbranchiesinformations') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-danger bg-opacity-10 text-danger me-3"><i class="fas fa-trash-arrow-up"></i></div>
                <span class="fw-semibold text-dark">Deleted Branches</span>
            </a>
        </div>
        @endcan
        @can('view-branches-report')
        <div class="col-md-4">
            <a href="{{ route('branchiesinformationsreport') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-secondary bg-opacity-10 text-secondary me-3"><i class="fas fa-chart-bar"></i></div>
                <span class="fw-semibold text-dark">Branches Report</span>
            </a>
        </div>
        @endcan
    </div>
</div>
@endcan

@can('view-department-menu')
<div class="mb-4">
    <h6 class="fw-bold text-muted text-uppercase small mb-3"><i class="fas fa-sitemap me-2"></i>Departments</h6>
    <div class="row g-3">
        @can('view-department')
        <div class="col-md-4">
            <a href="{{ route('departmentinformations') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-eye"></i></div>
                <span class="fw-semibold text-dark">View Departments</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('deleteddepartmentinformations') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-danger bg-opacity-10 text-danger me-3"><i class="fas fa-trash-arrow-up"></i></div>
                <span class="fw-semibold text-dark">Deleted Departments</span>
            </a>
        </div>
        @endcan
        @can('view-department-report')
        <div class="col-md-4">
            <a href="{{ route('departmentinformationsreport') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-secondary bg-opacity-10 text-secondary me-3"><i class="fas fa-chart-bar"></i></div>
                <span class="fw-semibold text-dark">Department Report</span>
            </a>
        </div>
        @endcan
    </div>
</div>
@endcan

@can('view-cost-centres-menu')
<div class="mb-4">
    <h6 class="fw-bold text-muted text-uppercase small mb-3"><i class="fas fa-coins me-2"></i>Cost Centers</h6>
    <div class="row g-3">
        @can('view-cost-centres')
        <div class="col-md-4">
            <a href="{{ route('costcentreinformations') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-eye"></i></div>
                <span class="fw-semibold text-dark">View Cost Centers</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('deletedcostcentreinformations') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-danger bg-opacity-10 text-danger me-3"><i class="fas fa-trash-arrow-up"></i></div>
                <span class="fw-semibold text-dark">Deleted Cost Centers</span>
            </a>
        </div>
        @endcan
        @can('view-cost-centres-report')
        <div class="col-md-4">
            <a href="{{ route('costcentreinformationsreport') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-secondary bg-opacity-10 text-secondary me-3"><i class="fas fa-chart-bar"></i></div>
                <span class="fw-semibold text-dark">Cost Center Report</span>
            </a>
        </div>
        @endcan
    </div>
</div>
@endcan

@can('view-company-business-codes-menu')
<div class="mb-4">
    <h6 class="fw-bold text-muted text-uppercase small mb-3"><i class="fas fa-briefcase me-2"></i>Business Codes</h6>
    <div class="row g-3">
        @can('view-company-business-codes')
        <div class="col-md-4">
            <a href="{{ route('companybusinesscodeinformations') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-eye"></i></div>
                <span class="fw-semibold text-dark">View Business Codes</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('deletedcompanybusinesscodeinformations') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-danger bg-opacity-10 text-danger me-3"><i class="fas fa-trash-arrow-up"></i></div>
                <span class="fw-semibold text-dark">Deleted Business Codes</span>
            </a>
        </div>
        @endcan
        @can('view-company-business-codes-report')
        <div class="col-md-4">
            <a href="{{ route('companybusinesscodeinformationsreport') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-secondary bg-opacity-10 text-secondary me-3"><i class="fas fa-chart-bar"></i></div>
                <span class="fw-semibold text-dark">Business Codes Report</span>
            </a>
        </div>
        @endcan
    </div>
</div>
@endcan

{{-- ============ ACCOUNTING ============ --}}

@can('view-accounting-menu')
<div class="mb-4">
    <h6 class="fw-bold text-muted text-uppercase small mb-3"><i class="fas fa-calculator me-2"></i>Chart of Accounts</h6>
    <div class="row g-3">
        @can('view-country')
        <div class="col-md-4">
            <a href="{{ route('accountCountry') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-flag"></i></div>
                <span class="fw-semibold text-dark">Country Accounts</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('accountBusiness') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-building"></i></div>
                <span class="fw-semibold text-dark">Company Branch</span>
            </a>
        </div>
        @endcan
        @can('view-accounting-codes')
        <div class="col-md-4">
            <a href="{{ route('accountRoot') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-info bg-opacity-10 text-info me-3"><i class="fas fa-sitemap"></i></div>
                <span class="fw-semibold text-dark">Root Accounts (1)</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('accountFirstBranch') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-info bg-opacity-10 text-info me-3"><i class="fas fa-code-branch"></i></div>
                <span class="fw-semibold text-dark">Level Two (2)</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('accountSecondBranch') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-info bg-opacity-10 text-info me-3"><i class="fas fa-folder-tree"></i></div>
                <span class="fw-semibold text-dark">Level Three (3)</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('accountSecondBranch') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-info bg-opacity-10 text-info me-3"><i class="fas fa-diagram-project"></i></div>
                <span class="fw-semibold text-dark">Level Four (4) Control</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('accountSecondBranch') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-info bg-opacity-10 text-info me-3"><i class="fas fa-book"></i></div>
                <span class="fw-semibold text-dark">Level Five (5) GL</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('accountSecondBranch') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-info bg-opacity-10 text-info me-3"><i class="fas fa-layer-group"></i></div>
                <span class="fw-semibold text-dark">Level Six (6) Sub GL</span>
            </a>
        </div>
        @endcan
    </div>
</div>
@endcan

{{-- ============ MEMBERS, FEES & SHARES SETUP ============ --}}

@can('view-member-categories-menu')
<div class="mb-4">
    <h6 class="fw-bold text-muted text-uppercase small mb-3"><i class="fas fa-users-rectangle me-2"></i>Member Categories</h6>
    <div class="row g-3">
        @can('view-member-categories')
        <div class="col-md-4">
            <a href="{{ route('membercategoryinformations') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-eye"></i></div>
                <span class="fw-semibold text-dark">View Members Categories</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('deletedmembercategoryinformations') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-danger bg-opacity-10 text-danger me-3"><i class="fas fa-trash-arrow-up"></i></div>
                <span class="fw-semibold text-dark">Deleted Members Categories</span>
            </a>
        </div>
        @endcan
        @can('view-member-categories-report')
        <div class="col-md-4">
            <a href="{{ route('membercategoryinformationsreport') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-secondary bg-opacity-10 text-secondary me-3"><i class="fas fa-chart-bar"></i></div>
                <span class="fw-semibold text-dark">Members Categories Report</span>
            </a>
        </div>
        @endcan
    </div>
</div>
@endcan

@can('view-membership-fees-menu')
<div class="mb-4">
    <h6 class="fw-bold text-muted text-uppercase small mb-3"><i class="fas fa-money-check-dollar me-2"></i>Fees & Contributions Setup</h6>
    <div class="row g-3">
        @can('view-membership-fees')
        <div class="col-md-4">
            <a href="{{ route('membershipfeeschedules') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-eye"></i></div>
                <span class="fw-semibold text-dark">Membership Fees Schedule</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('socialcontributionschedules') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-eye"></i></div>
                <span class="fw-semibold text-dark">Social Fees Schedule</span>
            </a>
        </div>
        @endcan
        <!-- @can('view-membership-fees')
        <div class="col-md-4">
            <a href="{{ route('deletedmembershipfeeschedules') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-danger bg-opacity-10 text-danger me-3"><i class="fas fa-trash-arrow-up"></i></div>
                <span class="fw-semibold text-dark">Deleted Members</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('deletedsocialcontributionschedules') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-danger bg-opacity-10 text-danger me-3"><i class="fas fa-trash-arrow-up"></i></div>
                <span class="fw-semibold text-dark">Deleted Members</span>
            </a>
        </div>
        @endcan
        @can('view-membership-fees-report')
        <div class="col-md-4">
            <a href="{{ route('membershipfeeschedulesreport') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-secondary bg-opacity-10 text-secondary me-3"><i class="fas fa-chart-bar"></i></div>
                <span class="fw-semibold text-dark">Members Report</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('socialcontributionschedulesreport') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-secondary bg-opacity-10 text-secondary me-3"><i class="fas fa-chart-bar"></i></div>
                <span class="fw-semibold text-dark">Members Report</span>
            </a>
        </div>
        @endcan -->
    </div>
</div>
@endcan

@can('view-share-types-menu')
<div class="mb-4">
    <h6 class="fw-bold text-muted text-uppercase small mb-3"><i class="fas fa-chart-pie me-2"></i>Shares Setup</h6>
    <div class="row g-3">
        @can('view-share-types')
        <div class="col-md-4">
            <a href="{{ route('shareofferings') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-eye"></i></div>
                <span class="fw-semibold text-dark">View Share Offerring</span>
            </a>
        </div>
        @endcan
        <!-- @can('view-share-types')
        <div class="col-md-4">
            <a href="{{ route('deletedshareofferings') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-danger bg-opacity-10 text-danger me-3"><i class="fas fa-trash-arrow-up"></i></div>
                <span class="fw-semibold text-dark">Deleted Departments</span>
            </a>
        </div>
        @endcan
        @can('view-share-types-report')
        <div class="col-md-4">
            <a href="{{ route('shareofferingsreport') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-secondary bg-opacity-10 text-secondary me-3"><i class="fas fa-chart-bar"></i></div>
                <span class="fw-semibold text-dark">Department Report</span>
            </a>
        </div>
        @endcan -->
        @can('view-share-types')
        <div class="col-md-4">
            <a href="{{ route('sharetypes') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-eye"></i></div>
                <span class="fw-semibold text-dark">View Share Types</span>
            </a>
        </div>
        @endcan
        <!-- @can('view-share-types')
        <div class="col-md-4">
            <a href="{{ route('deletedsharetypes') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-danger bg-opacity-10 text-danger me-3"><i class="fas fa-trash-arrow-up"></i></div>
                <span class="fw-semibold text-dark">Deleted Share Types</span>
            </a>
        </div>
        @endcan
        @can('view-share-types-report')
        <div class="col-md-4">
            <a href="{{ route('sharetypesreport') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-secondary bg-opacity-10 text-secondary me-3"><i class="fas fa-chart-bar"></i></div>
                <span class="fw-semibold text-dark">Share Types Report</span>
            </a>
        </div>
        @endcan -->
    </div>
</div>
@endcan

{{-- ============ PRODUCTS ============ --}}

@can('view-products-menu')
<div class="mb-4">
    <h6 class="fw-bold text-muted text-uppercase small mb-3"><i class="fas fa-boxes-stacked me-2"></i>Products</h6>
    <div class="row g-3">
        @can('view-products')
        <div class="col-md-4">
            <a href="{{ route('products') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-eye"></i></div>
                <span class="fw-semibold text-dark">View Products</span>
            </a>
        </div>
        @endcan
        <!-- @can('view-products')
        <div class="col-md-4">
            <a href="{{ route('deletedproducts') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-danger bg-opacity-10 text-danger me-3"><i class="fas fa-trash-arrow-up"></i></div>
                <span class="fw-semibold text-dark">Deleted Departments</span>
            </a>
        </div>
        @endcan
        @can('view-products-report')
        <div class="col-md-4">
            <a href="{{ route('productsreport') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-secondary bg-opacity-10 text-secondary me-3"><i class="fas fa-chart-bar"></i></div>
                <span class="fw-semibold text-dark">Department Report</span>
            </a>
        </div>
        @endcan -->
        @can('view-products')
        <div class="col-md-4">
            <a href="{{ route('productcategories') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-eye"></i></div>
                <span class="fw-semibold text-dark">View Products Categories</span>
            </a>
        </div>
        @endcan
        <!-- @can('view-products')
        <div class="col-md-4">
            <a href="{{ route('deletedproductcategories') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-danger bg-opacity-10 text-danger me-3"><i class="fas fa-trash-arrow-up"></i></div>
                <span class="fw-semibold text-dark">Deleted Departments</span>
            </a>
        </div>
        @endcan
        @can('view-products-report')
        <div class="col-md-4">
            <a href="{{ route('productcategoriesreport') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-secondary bg-opacity-10 text-secondary me-3"><i class="fas fa-chart-bar"></i></div>
                <span class="fw-semibold text-dark">Department Report</span>
            </a>
        </div>
        @endcan -->
    </div>
</div>
@endcan

{{-- ============ SYSTEM ADMINISTRATION ============ --}}

@can('view-system-users-menu')
<div class="mb-4">
    <h6 class="fw-bold text-muted text-uppercase small mb-3"><i class="fas fa-users-cog me-2"></i>System Users</h6>
    <div class="row g-3">
        @can('view-system-users')
        <div class="col-md-4">
            <a href="{{ route('systemUsers') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-users"></i></div>
                <span class="fw-semibold text-dark">System Users</span>
            </a>
        </div>
        @endcan
    </div>
</div>
@endcan

@can('view-permission-access-menu')
<div class="mb-4">
    <h6 class="fw-bold text-muted text-uppercase small mb-3"><i class="fas fa-user-lock me-2"></i>Roles & Permissions</h6>
    <div class="row g-3">
        @can('assign-permission-access')
        <div class="col-md-4">
            <a href="{{ route('usersRole') }}" class="arbif-card d-flex align-items-center p-3 shadow-sm text-decoration-none">
                <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary me-3"><i class="fas fa-key"></i></div>
                <span class="fw-semibold text-dark">Assign Permissions</span>
            </a>
        </div>
        @endcan
    </div>
</div>
@endcan

@endsection