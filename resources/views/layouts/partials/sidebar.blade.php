<aside class="sidebar" id="sidebar">
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

    {{-- ── Brand ── --}}
    <a href="{{ route('home') }}" class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('images/arbifA.png') }}" alt="ArBif">
        </div>
        <div class="sidebar-brand-text">
            <strong>ArBif</strong>
            <small>Management System</small>
        </div>
    </a>

    {{-- ── Logged-in user ── --}}
    <div class="sidebar-user">
        <div class="user-avatar">
            {{ strtoupper(substr($user->username ?? 'U', 0, 2)) }}
        </div>
        <div class="user-info">
            <div class="user-name">{{ $user->username ?? 'User' }}</div>
            <div class="user-role">
                {{ ucfirst(str_replace('_', ' ', $user->role ?? 'Staff')) }}
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         NAVIGATION
    ══════════════════════════════════════════ --}}
    <nav class="sidebar-nav">

        {{-- ── Dashboard (everyone) ── --}}
        <a href="{{ route('dashboard') }}"
           class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}"
           data-label="Dashboard">
            <i class="bi bi-speedometer2"></i>
            <span class="nav-label">Dashboard</span>
        </a>

        {{-- ═══════════════ LOAN OFFICER SECTION ═══════════════
             Only for loan officers who are NOT admin/management
             (admins see a fuller loans section below)
        ══════════════════════════════════════════════════════ --}}
        @if($isLoanOfficer && !$canLoans)

            <div class="nav-section-label">Collections</div>

            <a href="{{ route('collections.summary.index') }}"
               class="nav-link {{ Request::is('collections/summary*') ? 'active' : '' }}"
               data-label="Today's Collection">
                <i class="bi bi-collection"></i>
                <span class="nav-label">Today's Collection</span>
            </a>

            <a href="{{ route('loans.collections.summary.index') }}"
               class="nav-link {{ Request::is('loans/collections/summary*') ? 'active' : '' }}"
               data-label="Daily Collections">
                <i class="bi bi-wallet2"></i>
                <span class="nav-label">Daily Collections</span>
            </a>

            <div class="nav-section-label">Loan Requests</div>

            <a href="{{ route('loan_request_new_client.index') }}"
               class="nav-link {{ Request::is('loan_request_new_client*') ? 'active' : '' }}"
               data-label="New Client Loans">
                <i class="bi bi-person-plus"></i>
                <span class="nav-label">New Client Loans</span>
            </a>

            <a href="{{ route('loan_request_continueng_client.index') }}"
               class="nav-link {{ Request::is('loan_request_continueng_client*') ? 'active' : '' }}"
               data-label="Continuing Loans">
                <i class="bi bi-person-check"></i>
                <span class="nav-label">Continuing Client Loans</span>
            </a>

            <div class="nav-section-label">My Work</div>

            <a href="{{ route('loans.index') }}"
               class="nav-link {{ Request::is('loans') ? 'active' : '' }}"
               data-label="Loans">
                <i class="bi bi-cash-stack"></i>
                <span class="nav-label">All Loans</span>
            </a>

            <a href="{{ route('clients.index') }}"
               class="nav-link {{ Request::is('clients*') ? 'active' : '' }}"
               data-label="Clients">
                <i class="bi bi-people"></i>
                <span class="nav-label">All Clients</span>
            </a>

        @endif

        {{-- ═══════════════ GROUPS (shared) ════════════════════ --}}
        @if($canGroups)

            <div class="nav-section-label">Groups</div>

            <a href="{{ route('groups.index') }}"
               class="nav-link {{ Request::is('groups*') ? 'active' : '' }}"
               data-label="Groups">
                <i class="bi bi-people"></i>
                <span class="nav-label">Groups</span>
            </a>

            <a href="{{ route('group_centers.index') }}"
               class="nav-link {{ Request::is('group_centers*') ? 'active' : '' }}"
               data-label="Group Centers">
                <i class="bi bi-geo-alt"></i>
                <span class="nav-label">Group Centers</span>
            </a>

        @endif

        {{-- ═══════════════ LOANS (admin/management) ══════════ --}}
        @if($canLoans)

            <div class="nav-section-label">Loans</div>

            <a href="{{ route('loans.index') }}"
               class="nav-link {{ Request::is('loans') ? 'active' : '' }}"
               data-label="All Loans">
                <i class="bi bi-cash-stack"></i>
                <span class="nav-label">All Loans</span>
            </a>

            <a href="{{ route('loan_request_new_client.index') }}"
               class="nav-link {{ Request::is('loan_request_new_client*') ? 'active' : '' }}"
               data-label="New Client Loans">
                <i class="bi bi-person-plus"></i>
                <span class="nav-label">New Client Loans</span>
            </a>

            <a href="{{ route('loan_request_continueng_client.index') }}"
               class="nav-link {{ Request::is('loan_request_continueng_client*') ? 'active' : '' }}"
               data-label="Continuing Loans">
                <i class="bi bi-person-check"></i>
                <span class="nav-label">Continuing Client Loans</span>
            </a>

            <a href="{{ route('loan-approvals.index') }}"
               class="nav-link {{ Request::is('loan-approvals*') ? 'active' : '' }}"
               data-label="Loan Approvals">
                <i class="bi bi-check2-circle"></i>
                <span class="nav-label">Loan Approvals</span>
            </a>

            <a href="{{ route('loan_categories.index') }}"
               class="nav-link {{ Request::is('loan_categories*') ? 'active' : '' }}"
               data-label="Loan Categories">
                <i class="bi bi-tags"></i>
                <span class="nav-label">Loan Categories</span>
            </a>

            <a href="{{ route('client-loan-photos.index') }}"
               class="nav-link {{ Request::is('client-loan-photos*') ? 'active' : '' }}"
               data-label="Loan Photos">
                <i class="bi bi-images"></i>
                <span class="nav-label">Client Loan Photos</span>
            </a>

            <a href="{{ route('loans_dashboard.dashboard') }}"
               class="nav-link {{ Request::is('loans_dashboard*') ? 'active' : '' }}"
               data-label="Loans Dashboard">
                <i class="bi bi-bar-chart"></i>
                <span class="nav-label">Loans Dashboard</span>
            </a>

        @endif

        {{-- ═══════════════ CLIENTS ════════════════════════════ --}}
        @if($canClients)

            <div class="nav-section-label">Clients</div>

            <a href="{{ route('clients.index') }}"
               class="nav-link {{ Request::is('clients*') ? 'active' : '' }}"
               data-label="Clients">
                <i class="bi bi-person-vcard"></i>
                <span class="nav-label">All Clients</span>
            </a>

        @endif

        {{-- ═══════════════ FINANCE ════════════════════════════ --}}
        @if($canFinance)

            <div class="nav-section-label">Finance</div>

            <a href="{{ route('collections.summary.index') }}"
               class="nav-link {{ Request::is('collections/summary*') ? 'active' : '' }}"
               data-label="Today's Collection">
                <i class="bi bi-collection"></i>
                <span class="nav-label">Today's Collection</span>
            </a>

            <a href="{{ route('daily_collections.index') }}"
               class="nav-link {{ Request::is('daily_collections*') ? 'active' : '' }}"
               data-label="Daily Collections">
                <i class="bi bi-wallet2"></i>
                <span class="nav-label">Daily Collections</span>
            </a>

            <a href="{{ route('expenses.index') }}"
               class="nav-link {{ Request::is('expenses*') ? 'active' : '' }}"
               data-label="Expenses">
                <i class="bi bi-arrow-up-right-square"></i>
                <span class="nav-label">Expenses</span>
            </a>

            <a href="{{ route('expense-categories.index') }}"
               class="nav-link {{ Request::is('expense-categories*') ? 'active' : '' }}"
               data-label="Expense Categories">
                <i class="bi bi-card-list"></i>
                <span class="nav-label">Expense Categories</span>
            </a>

            <a href="{{ route('donations.index') }}"
               class="nav-link {{ Request::is('donations*') ? 'active' : '' }}"
               data-label="Donations">
                <i class="bi bi-heart"></i>
                <span class="nav-label">Donations &amp; Support</span>
            </a>

        @endif

        {{-- ═══════════════ HR ══════════════════════════════════ --}}
        @if($canHR)

            <div class="nav-section-label">HR &amp; Payroll</div>

            <a href="{{ route('reports.loans.index') }}"
               class="nav-link {{ Request::is('reports/loans*') ? 'active' : '' }}"
               data-label="Loan Reports">
                <i class="bi bi-file-bar-graph"></i>
                <span class="nav-label">Loan Reports</span>
            </a>

            <a href="{{ route('employees.index') }}"
               class="nav-link {{ Request::is('employees*') ? 'active' : '' }}"
               data-label="Employees">
                <i class="bi bi-person-lines-fill"></i>
                <span class="nav-label">Employees</span>
            </a>

            <a href="{{ route('employee_weekly_allowances.index') }}"
               class="nav-link {{ Request::is('employee_weekly_allowances*') ? 'active' : '' }}"
               data-label="Allowances">
                <i class="bi bi-gift"></i>
                <span class="nav-label">Weekly Allowances</span>
            </a>

            <a href="{{ route('employee_salaries.index') }}"
               class="nav-link {{ Request::is('employee_salaries*') ? 'active' : '' }}"
               data-label="Salaries">
                <i class="bi bi-cash"></i>
                <span class="nav-label">Employee Salaries</span>
            </a>

            <a href="{{ route('employee_salary_payments.index') }}"
               class="nav-link {{ Request::is('employee_salary_payments*') ? 'active' : '' }}"
               data-label="Salary Payments">
                <i class="bi bi-credit-card"></i>
                <span class="nav-label">Salary Payments</span>
            </a>

            <a href="{{ route('salary_levels.index') }}"
               class="nav-link {{ Request::is('salary_levels*') ? 'active' : '' }}"
               data-label="Salary Levels">
                <i class="bi bi-clipboard-data"></i>
                <span class="nav-label">Salary Levels</span>
            </a>

        @endif

        {{-- ═══════════════ ADMIN / SYSTEM ════════════════════ --}}
        @if($canAdmin)

            <div class="nav-section-label">Administration</div>

            <a href="{{ route('users.index') }}"
               class="nav-link {{ Request::is('users*') ? 'active' : '' }}"
               data-label="System Users">
                <i class="bi bi-person-gear"></i>
                <span class="nav-label">System Users</span>
            </a>

            <a href="{{ route('usersRole') }}"
               class="nav-link {{ Request::is('usersRole*') ? 'active' : '' }}"
               data-label="User Roles">
                <i class="bi bi-shield-check"></i>
                <span class="nav-label">User Roles</span>
            </a>

        @endif

        {{-- ═══════════════ ACCOUNT (everyone) ═══════════════ --}}
        <div class="nav-section-label">Account</div>

        <a href="{{ route('profile.show') }}"
           class="nav-link {{ Request::is('profile*') ? 'active' : '' }}"
           data-label="My Profile">
            <i class="bi bi-person-circle"></i>
            <span class="nav-label">My Profile</span>
        </a>

    </nav>{{-- /.sidebar-nav --}}

    {{-- ── Logout pinned at bottom ── --}}
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="nav-link w-100 text-start border-0"
                    data-label="Logout"
                    style="background:transparent;cursor:pointer;">
                <i class="bi bi-box-arrow-left"></i>
                <span class="nav-label">Logout</span>
            </button>
        </form>
    </div>

</aside>