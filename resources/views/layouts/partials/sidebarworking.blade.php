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

    <div class="d-flex align-items-center justify-content-center gap-2 text-center py-4 border-bottom border-white border-opacity-25">
        <img src="{{ asset('logo/iflsglogo.png') }}" alt="IFL Solidarity Group" style="width:28px;height:28px;object-fit:contain;">
        <h5 class="text-white mb-0">
            <span id="logo-text">IFL Solidarity</span>
        </h5>
    </div>
    <!-- <div class="px-3 py-4 border-bottom border-white border-opacity-25">
        <div class="d-flex align-items-center">
            <div class="user-avatar me-3">
                {{ strtoupper(substr($user->username ?? 'U', 0, 2)) }}
            </div>
            <div class="user-info d-flex flex-column">
                <h6 class="mb-0 text-white">{{ $user->username }}</h6>
                <small class="text-light">
                    @php
                        $role = $user->role;
                        $roleClass = 'badge-' . str_replace('_', '-', strtolower($role));
                    @endphp
                </small>
            </div>
        </div>
    </div> -->

    <nav class="nav flex-column p-3">

    {{-- ================= 1. DAILY WORK DASHBOARD ================= --}}
    @can('view-working-side')
    <a href="{{ route('workingside') }}" 
       class="nav-link {{ Request::is('workingside*') ? 'active' : '' }}">
        <i class="fas fa-tasks me-1"></i> 
        <span>Working Side</span>
    </a>
    @endcan

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

        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit" 
                class="nav-link text-start w-100" style="background: none; border: none;">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</aside>