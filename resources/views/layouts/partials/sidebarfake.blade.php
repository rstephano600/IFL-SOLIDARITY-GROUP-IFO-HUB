<aside class="sidebar" id="sidebar">
    @php
        // Get the authenticated user
        $user = Auth::user();
        // Define simple permission variables using the new methods
        $canManageAll = $user->isAdmin();
        $isLoanOfficer = $user->isLoanOfficer();
        $canManageHisData = $user->isLoanOfficer();
        $canManageLoans = $user->isAdmin() || $user->isManagement();
        $canViewClients = $user->isAdmin() || $user->isManagement() || $user->hasRole('marketing_officer');
        $canManageGroups = $user->isAdmin() || $user->isManagement() || $user->isLoanOfficer() || $user->hasRole('marketing_officer');
        $canManageFinance = $user->isAdmin() || $user->isManagement() || $user->isFinance();
        $canManageHR = $user->isAdmin() || $user->isManagement() || $user->isHR();
        $isClient = $user->isClient();
    @endphp

    @php
    $user         = Auth::user();
    $isAdmin      = $user->isAdmin();
    $isManagement = $user->isManagement();
    $isLoanOfficer= $user->isLoanOfficer();
    $isFinance    = $user->isFinance();
    $isHR         = $user->isHR();
    $isClient     = $user->isClient();
    $isMarketing  = $user->hasRole('marketing_officer');

    // Derived permission groups
    $canAdmin     = $isAdmin;
    $canLoans     = $isAdmin || $isManagement;
    $canClients   = $isAdmin || $isManagement || $isMarketing;
    $canGroups    = $isAdmin || $isManagement || $isLoanOfficer || $isMarketing;
    $canFinance   = $isAdmin || $isManagement || $isFinance;
    $canHR        = $isAdmin || $isManagement || $isHR;
    @endphp


    <div class="d-flex align-items-center justify-content-center gap-2 text-center py-4 border-bottom border-white border-opacity-25">
        <img src="{{ asset('logo/iflsglogo.png') }}" alt="IFL Solidarity Group" style="width:28px;height:28px;object-fit:contain;">
        <h5 class="text-white mb-0">
            <span id="logo-text">IFL Solidarity</span>
        </h5>
    </div>

<nav class="nav flex-column p-3">

    {{-- ================= 1. SYSTEM USERS (most frequent admin task) ================= --}}
    @can('view-system-users-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu3" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-users-cog me-2"></i> <span>System Users Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="accountingSubmenu3">
            <div class="ps-4 mt-2">
                @can('view-system-users')
                <a href="{{ route('systemUsers') }}" class="nav-link d-flex align-items-center"><i class="fas fa-users me-2"></i> <span>System Users</span></a>
                @endcan
                @if($canAdmin)
                @can('view-system-users')
                <a href="{{ route('systemUsers') }}" class="nav-link d-flex align-items-center"><i class="fas fa-users me-2"></i> <span>System Users</span></a>
                @endcan
                @endif
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 2. PERMISSION ACCESS (regular admin task) ================= --}}
    @can('view-permission-access-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu2" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-user-lock me-2"></i> <span>Permission Access Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="accountingSubmenu2">
            <div class="ps-4 mt-2">
                @can('assign-permission-access')
                <a href="{{ route('usersRole') }}" class="nav-link d-flex align-items-center"><i class="fas fa-key me-2"></i> <span>Assign Permissions</span></a>
                @endcan
                @if($canAdmin)
                @can('assign-permission-access')
                <a href="{{ route('usersRole') }}" class="nav-link d-flex align-items-center"><i class="fas fa-user-shield me-2"></i> <span>Assign Permissions Admin</span></a>
                @endcan
                @endif
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 3. ACCOUNTING SETUP (infrequent - chart of accounts) ================= --}}
    @can('view-accounting-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu" 
           class="nav-link" 
           data-bs-toggle="collapse" 
           role="button">
            <i class="fas fa-calculator me-2"></i> 
            <span>Accounting Menu</span>
            <i class="fas fa-chevron-down ms-auto"></i>
        </a>
        
        <div class="collapse" id="accountingSubmenu">
            <div class="ps-4 mt-2">
                @can('view-country')
                <a href="{{ route('accountCountry') }}" 
                   class="nav-link d-flex align-items-center">
                    <i class="fas fa-flag me-2"></i> 
                    <span>Country Accounts</span>
                </a>
                @endcan
                @can('view-country')
                <a href="{{ route('accountBusiness') }}" class="nav-link d-flex align-items-center"><i class="fas fa-building me-2"></i> <span>Company Branch</span></a>
                @endcan
                @can('view-accounting-codes')
                <a href="{{ route('accountRoot') }}" class="nav-link d-flex align-items-center"><i class="fas fa-sitemap me-2"></i> <span>Root Accounts (1)</span></a>
                <a href="{{ route('accountFirstBranch') }}" class="nav-link d-flex align-items-center"><i class="fas fa-code-branch me-2"></i> <span>Level Two (2)</span></a>
                <a href="{{ route('accountSecondBranch') }}" class="nav-link d-flex align-items-center"><i class="fas fa-folder-tree me-2"></i> <span>Level Three (3)</span></a>
                <a href="{{ route('accountSecondBranch') }}" class="nav-link d-flex align-items-center"><i class="fas fa-diagram-project me-2"></i> <span>Level Four (4) Control</span></a>
                <a href="{{ route('accountSecondBranch') }}" class="nav-link d-flex align-items-center"><i class="fas fa-book me-2"></i> <span>Level Five (5) GL</span></a>
                <a href="{{ route('accountSecondBranch') }}" class="nav-link d-flex align-items-center"><i class="fas fa-layer-group me-2"></i> <span>Level Six (6) Sub GL</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 3a. COMPANIES ================= --}}
    @can('view-companies-menu')
    <div class="nav-item">
        <a href="#companiesSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-building me-2"></i> <span>Companies Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="companiesSubmenu">
            <div class="ps-4 mt-2">
                @can('view-companies')
                <a href="{{ route('companies') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Companies</span></a>
                @endcan
                @can('restore-companies')
                <a href="{{ route('companiesRestore') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Companies</span></a>
                @endcan
                @can('view-companies-report')
                <a href="{{ route('companiesReport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Companies Report</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 3b. BRANCHES ================= --}}
    @can('view-branches-menu')
    <div class="nav-item">
        <a href="#branchesSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-code-branch me-2"></i> <span>Branches Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="branchesSubmenu">
            <div class="ps-4 mt-2">
                @can('view-branches')
                <a href="{{ route('branches') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Branches</span></a>
                @endcan
                @can('restore-branches')
                <a href="{{ route('branchesRestore') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Branches</span></a>
                @endcan
                @can('view-branches-report')
                <a href="{{ route('branchesReport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Branches Report</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 3c. DEPARTMENTS (no menu/view/crud permission defined yet — only restore & report exist) ================= --}}
    @canany(['restore-departments', 'view-departments-report'])
    <div class="nav-item">
        <a href="#departmentsSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-users-gear me-2"></i> <span>Departments Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="departmentsSubmenu">
            <div class="ps-4 mt-2">
                @can('restore-departments')
                <a href="{{ route('departmentsRestore') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Departments</span></a>
                @endcan
                @can('view-departments-report')
                <a href="{{ route('departmentsReport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Departments Report</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcanany

    {{-- ================= 3d. COST CENTRES ================= --}}
    @can('view-cost-centres-menu')
    <div class="nav-item">
        <a href="#costCentresSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-coins me-2"></i> <span>Cost Centres Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="costCentresSubmenu">
            <div class="ps-4 mt-2">
                @can('view-cost-centres')
                <a href="{{ route('costCentres') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Cost Centres</span></a>
                @endcan
                @can('restore-cost-centres')
                <a href="{{ route('costCentresRestore') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Cost Centres</span></a>
                @endcan
                @can('view-cost-centres-report')
                <a href="{{ route('costCentresReport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Cost Centres Report</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 3e. COMPANY BUSINESS CODES ================= --}}
    @can('view-company-business-codes-menu')
    <div class="nav-item">
        <a href="#businessCodesSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-barcode me-2"></i> <span>Company Business Codes</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="businessCodesSubmenu">
            <div class="ps-4 mt-2">
                @can('view-company-business-codes')
                <a href="{{ route('businessCodes') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Business Codes</span></a>
                @endcan
                @can('restore-company-business-codes')
                <a href="{{ route('businessCodesRestore') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Business Codes</span></a>
                @endcan
                @can('view-company-business-codes-report')
                <a href="{{ route('businessCodesReport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Business Codes Report</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 3f. MEMBER CATEGORIES ================= --}}
    @can('view-member-categories-menu')
    <div class="nav-item">
        <a href="#memberCategoriesSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-tags me-2"></i> <span>Member Categories</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="memberCategoriesSubmenu">
            <div class="ps-4 mt-2">
                @can('view-member-categories')
                <a href="{{ route('memberCategories') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Member Categories</span></a>
                @endcan
                @can('restore-member-categories')
                <a href="{{ route('memberCategoriesRestore') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Member Categories</span></a>
                @endcan
                @can('view-member-categories-report')
                <a href="{{ route('memberCategoriesReport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Member Categories Report</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 3g. MEMBERS ================= --}}
    @can('view-members-menu')
    <div class="nav-item">
        <a href="#membersSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-users me-2"></i> <span>Members Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="membersSubmenu">
            <div class="ps-4 mt-2">
                @can('view-members')
                <a href="{{ route('members') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Members</span></a>
                @endcan
                @can('restore-members')
                <a href="{{ route('membersRestore') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Members</span></a>
                @endcan
                @can('view-members-report')
                <a href="{{ route('membersReport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Members Report</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 4. SYSTEM CONFIGURATION (rare) ================= --}}
    @can('view-confirguration-side')
    <a href="{{ route('configurationside') }}" 
       class="nav-link {{ Request::is('configurationside*') ? 'active' : '' }}">
        <i class="fas fa-sliders me-1"></i> 
        <span>Configuration</span>
    </a>
    @endcan


    <hr class="dropdown-divider my-3">

    {{-- Profile with Bootstrap Icon --}}
    <a href="{{ route('profile.show') }}" 
       class="nav-link {{ Request::is('profile*') ? 'active' : '' }}">
        <i class="bi bi-person-circle me-2"></i> 
        <span>My Profile</span>
    </a>

    {{-- Logout with Font Awesome --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-auto">
        @csrf
        <button type="submit" class="nav-link text-start w-100" 
                style="background: none; border: none;">
            <i class="fas fa-sign-out-alt me-2"></i> 
            <span>Logout</span>
        </button>
    </form>
</nav>

<style>
.nav-link {
    padding: 10px 15px;
    color: rgba(255, 255, 255, 0.75);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.nav-link:hover {
    color: white;
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
}

.nav-link.active {
    color: #fff;
    background: linear-gradient(90deg, rgba(176, 141, 73, 0.22), transparent);
    border-left: 3px solid var(--ifl-gold);
}

.nav-link i:first-child {
    width: 20px;
    text-align: center;
    color: var(--ifl-gold-soft);
    transition: transform 0.3s;
}

.nav-link:hover i:first-child {
    transform: scale(1.1);
}

.transition-toggle {
    transition: transform 0.3s;
}

.nav-link[aria-expanded="true"] .transition-toggle {
    transform: rotate(180deg);
}

.collapse .nav-link {
    padding: 8px 15px;
    font-size: 0.9rem;
}

.collapse .nav-link:hover {
    padding-left: 20px !important;
}

button.nav-link {
    background: none;
    border: none;
    cursor: pointer;
}

button.nav-link:hover {
    background: rgba(220, 53, 69, 0.15);
    color: #ff6b6b;
}
</style>
</aside>
<script>
    // Force re-initialize all collapses
$(document).ready(function() {
    // Initialize all collapses manually
    $('[data-bs-toggle="collapse"]').each(function() {
        const target = $(this).data('bs-target');
        if (target) {
            $(target).collapse({
                toggle: false
            });
        }
    });
    
    // Handle click manually
    $('[data-bs-toggle="collapse"]').off('click').on('click', function(e) {
        e.preventDefault();
        const target = $(this).data('bs-target');
        if (target) {
            $(target).collapse('toggle');
            
            // Rotate chevron
            const chevron = $(this).find('.fa-chevron-down, .bi-chevron-down');
            if (chevron.length) {
                chevron.css('transform', $(target).hasClass('show') ? 'rotate(0deg)' : 'rotate(180deg)');
            }
        }
    });
});
</script>





<!-- WORKING AREA -->
 
    {{-- ================= 2. LOAN REPAYMENTS (most frequent daily op) ================= --}}
    @can('view-loan-repayments-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu7" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-hand-holding-usd me-2"></i> 
        <span>Loans Repayments Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu7">
            <div class="ps-4 mt-2">
                @can('view-loan-repayments')
                <a href="{{ route('loansrepayments') }}" class="nav-link d-flex align-items-center"><i class="fas fa-money-bill-wave me-2"></i> <span>Loans Repayments</span></a>
                <a href="{{ route('loansrepaymentsfees') }}" class="nav-link d-flex align-items-center"><i class="fas fa-receipt me-2"></i> <span>Fees Repayments</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 3. LOANS MENU (view / approve loans) ================= --}}
    @can('view-loan-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu4" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-file-invoice-dollar me-2"></i> 
        <span>Loans Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu4">
            <div class="ps-4 mt-2">
                @can('view-loan')
                <a href="{{ route('loansinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-list-alt me-2"></i> <span>Loans Informations</span></a>
                <a href="{{ route('rejectedloansinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-times-circle me-2"></i> <span>Rejected Loans </span></a>
                @endcan
                @can('approve-loans')
                <a href="{{ route('approveloansinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-check-circle me-2"></i> <span>Approve Loans</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 4. CLIENT / BENEFICIARY INFO ================= --}}
    @can('view-loan-beneficiary-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu3" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-users me-2"></i> 
        <span>Loan Beneficiary Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu3">
            <div class="ps-4 mt-2">
                @can('view-loan-beneficiary')
                <a href="{{ route('clientinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-user me-2"></i> <span>client informations</span></a>
                <a href="{{ route('groupMembers') }}" class="nav-link d-flex align-items-center"><i class="fas fa-user-friends me-2"></i> <span>Beneficiary Groups</span></a>
                <a href="{{ route('innactivegroupMembers') }}" class="nav-link d-flex align-items-center"><i class="fas fa-user-slash me-2"></i> <span>Innactive Loan Beneficiary</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 5. GROUP CENTERS ================= --}}
    @can('view-group-centers-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu1" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-sitemap me-2"></i> 
        <span>Group Centers Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu1">
            <div class="ps-4 mt-2">
                @can('view-group-centers')
                <a href="{{ route('groupCenter') }}" class="nav-link d-flex align-items-center"><i class="fas fa-map-marker-alt me-2"></i> <span>Group Centers</span></a>
                <a href="{{ route('innactivegroupCenter') }}" class="nav-link d-flex align-items-center"><i class="fas fa-map-marker-slash me-2"></i> <span>Innactive Group Centers</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 6. LOAN GROUPS ================= --}}
    @can('view-loan-groups-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu2" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-object-group me-2"></i> 
        <span>Loan Groups Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu2">
            <div class="ps-4 mt-2">
                @can('view-loan-groups')
                <a href="{{ route('centerGroups') }}" class="nav-link d-flex align-items-center"><i class="fas fa-layer-group me-2"></i> <span>Loan Groups</span></a>
                <a href="{{ route('innactivecenterGroups') }}" class="nav-link d-flex align-items-center"><i class="fas fa-ban me-2"></i> <span>Innactive Loan Groups</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 7. GUARANTORS ================= --}}
    @can('view-loan-guarantors-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu8" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-user-shield me-2"></i> 
        <span>Guarantors Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu8">
            <div class="ps-4 mt-2">
                @can('view-loan-guarantors')
                <a href="{{ route('guarantors') }}" class="nav-link d-flex align-items-center"><i class="fas fa-user-check me-2"></i> <span>Client Guarantors</span></a>
                <a href="{{ route('loanguarantors') }}" class="nav-link d-flex align-items-center"><i class="fas fa-shield-alt me-2"></i> <span>Loan Guarantors</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 8. LOAN PENALTIES ================= --}}
    @can('view-loan-penalty-categories-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu6" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-exclamation-triangle me-2"></i> 
        <span>Loan Penalities  Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu6">
            <div class="ps-4 mt-2">
                @can('view-loan-penalty-categories')
                <a href="{{ route('loanpenalties') }}" class="nav-link d-flex align-items-center"><i class="fas fa-exclamation-circle me-2"></i> <span>Loan Penalties</span></a>
                <a href="{{ route('unpaidloanpenalties') }}" class="nav-link d-flex align-items-center"><i class="fas fa-check-double me-2"></i> <span>Approve Penalties </span></a>
                <a href="{{ route('loanpenaltycategories') }}" class="nav-link d-flex align-items-center"><i class="fas fa-tags me-2"></i> <span>Penalty Categories</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 9. DOUBLED LOANS ================= --}}
    @can('view-loan-doubling-menu')
    <div class="nav-item">
        <a href="#doublingmenu" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-clone me-2"></i> 
        <span>Doubled Loans Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="doublingmenu">
            <div class="ps-4 mt-2">
                @can('view-loan-doubling')
                <a href="{{ route('doubledloansinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-copy me-2"></i> <span>Doubled Loans</span></a>
                @endcan
                @can('approve-loan-doubling')
                <a href="{{ route('approvedoubledloansinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-check-circle me-2"></i> <span>Approve Doubling Loans</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 10. REFUNDED LOANS ================= --}}
    @can('view-loan-refunding-menu')
    <div class="nav-item">
        <a href="#refundingmenu" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-undo me-2"></i> 
        <span>Refunded Loans Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="refundingmenu">
            <div class="ps-4 mt-2">
                @can('view-loan-refunding')
                <a href="{{ route('refundedloansinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-undo-alt me-2"></i> <span>Refunded Loans </span></a>
                @endcan
                @can('approve-loan-refunding-menu')
                <a href="{{ route('pendingloanrefund') }}" class="nav-link d-flex align-items-center"><i class="fas fa-check-circle me-2"></i> <span>Approve Refunding Loans</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 11. LOAN CATEGORIES (setup - infrequent) ================= --}}
    @can('view-loan-categories-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu5" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-list-ul me-2"></i> 
        <span>Loan Category Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu5">
            <div class="ps-4 mt-2">
                @can('view-loan-categories')
                <a href="{{ route('loancategories') }}" class="nav-link d-flex align-items-center"><i class="fas fa-tag me-2"></i> <span>Loan Categories</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 12. WEEKLY ALLOWANCES (weekly cadence) ================= --}}
    @can('view-weekly-allowance-menu')
    <div class="nav-item">
        <a href="#weeklyallowancemenu" class="nav-link" data-bs-toggle="collapse">
            <i class="fas fa-hand-holding-usd nav-icon"></i>
            
                Weekly Allowances
                <i class="right fas fa-angle-left"></i>
            
        </a>

        <div id="weeklyallowancemenu" class="collapse">

            @can('view-weekly-allowance')
            <a href="{{ route('weeklyallowanceinformations') }}" class="nav-link">
                <i class="fas fa-coins nav-icon"></i>
                Weekly Allowances
            </a>
            @endcan

            @can('generate-weekly-allowance')
            <a href="{{ route('registerweeklyallowance') }}" class="nav-link">
                <i class="fas fa-plus-circle nav-icon"></i>
                Generate Weekly Allowance
            </a>
            @endcan

            @can('pending-weekly-allowance')
            <a href="{{ route('pendingweeklyallowance') }}" class="nav-link">
                <i class="fas fa-clock nav-icon"></i>
                Pending Approval
            </a>
            @endcan

            @can('pay-weekly-allowance')
            <a href="{{ route('payweeklyallowances') }}" class="nav-link">
                <i class="fas fa-money-check-alt nav-icon"></i>
                Pending Payment
            </a>
            @endcan

            @can('weekly-allowance-history')
            <a href="{{ route('weeklyallowancehistory') }}" class="nav-link">
                <i class="fas fa-history nav-icon"></i>
                Allowance History
            </a>
            @endcan

        </div>
    </div>
    @endcan

    {{-- ================= 13. PAYROLL (monthly cadence) ================= --}}
    @can('view-salary-menu')
    <div class="nav-item">
        <a href="#salarymenu" class="nav-link" data-bs-toggle="collapse">
            <i class="fas fa-money-check-alt nav-icon"></i>
            
                Payroll
                <i class="right fas fa-angle-left"></i>
            
        </a>

        <div id="salarymenu" class="collapse">

            @can('view-salary')
            <a href="{{ route('salaryinformations') }}" class="nav-link">
                <i class="fas fa-wallet nav-icon"></i>
                Monthly Salaries
            </a>
            @endcan

            @can('generate-salary')
            <a href="{{ route('registersalary') }}" class="nav-link">
                <i class="fas fa-plus-circle nav-icon"></i>
                Generate Salary
            </a>
            @endcan

            @can('pending-salary')
            <a href="{{ route('pendingsalary') }}" class="nav-link">
                <i class="fas fa-clock nav-icon"></i>
                Pending Approval
            </a>
            @endcan

            @can('pay-salary')
            <a href="{{ route('pendingpaysalary') }}" class="nav-link">
                <i class="fas fa-credit-card nav-icon"></i>
                Pending Payment
            </a>
            @endcan

            @can('salary-history')
            <a href="{{ route('salaryhistory') }}" class="nav-link">
                <i class="fas fa-history nav-icon"></i>
                Salary History
            </a>
            @endcan

        </div>
    </div>
    @endcan

    {{-- ================= 14. EXPENSES ================= --}}
    @can('view-expense-menu')
    <div class="nav-item">
        <a href="#expensemenu" class="nav-link" data-bs-toggle="collapse">
            <i class="fas fa-money-bill-wave me-2"></i>
            <span>Expenses</span>
            <i class="fas fa-chevron-down ms-auto"></i>
        </a>

        <div class="collapse" id="expensemenu">
            <div class="ps-4 mt-2">

                @can('view-expenses')
                <a href="{{ route('expenseinformations') }}" class="nav-link">
                    <i class="fas fa-file-invoice-dollar me-2"></i>
                    Company Expenses
                </a>
                @endcan

                @can('approve-expense')
                <a href="{{ route('pendingexpense') }}" class="nav-link">
                    <i class="fas fa-check-circle me-2"></i>
                    Pending Approvals
                </a>
                @endcan

                @can('view-expense-report')
                <a href="{{ route('expensehistory') }}" class="nav-link">
                    <i class="fas fa-chart-line me-2"></i>
                    Expense Reports
                </a>
                @endcan

            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 15. EXPENSE PAYMENTS ================= --}}
    @can('view-expense-payment-menu')
    <div class="nav-item">
        <a href="#expensepaymentmenu" class="nav-link" data-bs-toggle="collapse">
            <i class="fas fa-wallet me-2"></i>
            <span>Expense Payments</span>
            <i class="fas fa-chevron-down ms-auto"></i>
        </a>

        <div class="collapse" id="expensepaymentmenu">
            <div class="ps-4 mt-2">

                @can('view-unpaid-expenses')
                <a href="{{ route('unpayedexpense') }}" class="nav-link">
                    <i class="fas fa-hourglass-half me-2"></i>
                    Unpaid Expenses
                </a>
                @endcan

                @can('view-expense-payments')
                <a href="{{ route('expensepayments') }}" class="nav-link">
                    <i class="fas fa-credit-card me-2"></i>
                    Expense Payments
                </a>
                @endcan

                @can('view-expense-payment-history')
                <a href="{{ route('expensepaymenthistory') }}" class="nav-link">
                    <i class="fas fa-history me-2"></i>
                    Payment History
                </a>
                @endcan

            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 16. EXPENSE CATEGORIES (setup - infrequent) ================= --}}
    @can('view-expense-category-menu')
    <div class="nav-item">
        <a href="#expensecategorymenu" class="nav-link" data-bs-toggle="collapse">
            <i class="fas fa-tags me-2"></i>
            <span>Expense Categories</span>
            <i class="fas fa-chevron-down ms-auto"></i>
        </a>

        <div class="collapse" id="expensecategorymenu">
            <div class="ps-4 mt-2">

                @can('view-expense-category')
                <a href="{{ route('expensecategoryinformations') }}" class="nav-link">
                    <i class="fas fa-tag me-2"></i>
                    Expense Categories
                </a>
                @endcan

            </div>
        </div>
    </div>
    @endcan

    {{-- ================= 17. EMPLOYEE / HR MENU (least frequent - admin/HR setup) ================= --}}
    @can('view-employee-menu')
    <div class="nav-item">
        <a href="#accountingSubmenu" class="nav-link" data-bs-toggle="collapse" role="button"><i class="fas fa-users-cog me-2"></i> 
        <span>Employee Menu</span><i class="fas fa-chevron-down ms-auto"></i></a>
        <div class="collapse" id="accountingSubmenu">
            <div class="ps-4 mt-2">
                @can('register-employees')
                <a href="{{ route('employeeinfo') }}" class="nav-link d-flex align-items-center"><i class="fas fa-user-tie me-2"></i> <span>Company Employees</span></a>
                <a href="#" class="nav-link d-flex align-items-center"><i class="fas fa-user-slash me-2"></i> <span>Innactive Employees</span></a>
                <a href="#" class="nav-link d-flex align-items-center"><i class="fas fa-address-book me-2"></i> <span>Employee Referees</span></a>
                <a href="#" class="nav-link d-flex align-items-center"><i class="fas fa-people-arrows me-2"></i> <span>Employee Nest Of Kin</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

