<aside class="sidebar" id="sidebar">
    @php
        // Get the authenticated user
        $user = Auth::user();
    
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
        @can('view-share-purchases-menu')
        <div class="nav-item">
            <a href="#sharepurchasetransactions" class="nav-link" data-bs-toggle="collapse" role="button">
                <i class="fas fa-coins me-2"></i> <span>Share Purchases</span><i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="sharepurchasetransactions">
                <div class="ps-4 mt-2">
                    {{-- Main Overview Hub --}}
                    @can('view-share-purchases')
                    <a href="{{ route('sharepurchasetransactionshub') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-cubes me-2"></i> <span>Share Purchases Hub</span>
                    </a>
                    @endcan
                    @can('view-share-purchases')
                    <a href="{{ route('sharepurchasetransactions') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-cubes me-2"></i> <span>Share Purchases</span>
                    </a>
                    @endcan

                    {{-- Workflow Stage 1: Verification --}}
                    @can('verify-share-purchases')
                    <a href="{{ route('unverifiedsharepurchasetransactions') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-shield-alt me-2"></i> <span>Pending Verification</span>
                    </a>
                    @endcan

                    {{-- Workflow Stage 2: Approval --}}
                    @can('approve-share-purchases')
                    <a href="{{ route('pendingapprovalsharepurchasetransactions') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-user-check me-2"></i> <span>Pending Approval</span>
                    </a>
                    @endcan

                    {{-- Workflow Stage 3: Posting --}}
                    @can('post-share-purchases')
                    <a href="{{ route('pendingpostingsharepurchasetransactions') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-file-invoice-dollar me-2"></i> <span>Pending Posting</span>
                    </a>
                    @endcan

                    {{-- Workflow Stage 4: Certificate Generation --}}
                    @can('generate-certificate-share-purchases')
                    <a href="{{ route('pendingcertificatesharepurchasetransactions') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-certificate me-2"></i> <span>Pending Certificate</span>
                    </a>
                    @endcan

                    {{-- Final Stage: Archive & Completed --}}
                    @can('view-share-purchases')
                    <a href="{{ route('completedsharepurchasetransactions') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-check-double me-2"></i> <span>Completed Purchases</span>
                    </a>
                    @endcan

                    {{-- Soft-Deleted Purchases --}}
                    @can('view-share-purchases')
                    <a href="{{ route('deletedsharepurchasetransactions') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-trash-restore me-2"></i> <span>Deleted Purchases</span>
                    </a>
                    @endcan

                    {{-- Reports --}}
                    @can('view-share-purchases-report')
                    <a href="{{ route('sharepurchasetransactionsreport') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-chart-bar me-2"></i> <span>Purchases Report</span>
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        @endcan

        @can('view-membership-fees-menu')
        <div class="nav-item">
            <a href="#membershipfeepayments" class="nav-link" data-bs-toggle="collapse" role="button">
                <i class="fas fa-hand-holding-dollar me-2"></i> <span>Fees & Contributions</span><i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="membershipfeepayments">
                <div class="ps-4 mt-2">
                    @can('view-membership-fees')
                    <a href="{{ route('socialcontributionshub') }}" class="nav-link d-flex align-items-center"><i class="fas fa-hand-holding-heart me-2"></i> <span>Social Conts Hub</span></a>
                    <a href="{{ route('membershipfeepaymentshub') }}" class="nav-link d-flex align-items-center"><i class="fas fa-id-card me-2"></i> <span>Membership Fees Hub</span></a>
                    <a href="{{ route('socialcontributions') }}" class="nav-link d-flex align-items-center"><i class="fas fa-hand-holding-heart me-"></i> <span>Monthly Social Conts</span></a>
                    <a href="{{ route('membershipfeepayments') }}" class="nav-link d-flex align-items-center"><i class="fas fa-id-card me-2"></i> <span>Mbembership Fees</span></a>
                    @endcan
                    <!-- @can('view-membership-fees')
                    <a href="{{ route('deletedsocialcontributions') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Members</span></a>
                    <a href="{{ route('deletedmembershipfeepayments') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Members</span></a>
                    @endcan
                    @can('view-membership-fees-report')
                    <a href="{{ route('socialcontributionsreport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Members Report</span></a>
                    <a href="{{ route('membershipfeepaymentsreport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Members Report</span></a>
                    @endcan -->
                </div>
            </div>
        </div>
        @endcan
        @can('view-members-menu')
        <div class="nav-item">
            <a href="#membersSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
                <i class="fas fa-users me-2"></i> <span>Members Information</span><i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="membersSubmenu">
                <div class="ps-4 mt-2">
                    @can('view-members')
                    <a href="{{ route('memberinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Members</span></a>
                    @endcan
                    @can('view-members')
                    <a href="{{ route('deletedmemberinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Members</span></a>
                    @endcan
                    @can('view-members-report')
                    <a href="{{ route('memberinformationsreport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Members Report</span></a>
                    @endcan
                </div>
            </div>
        </div>
        @endcan
                {{-- ── Expenses Menu ── --}}
        @can('view-expenses-menu')
        <div class="nav-item">
            <a href="#expensesSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
                <i class="fas fa-receipt me-2"></i>
                <span>Expenses Menu</span>
                <i class="fas fa-chevron-down ms-auto transition-toggle"></i>
            </a>
            <div class="collapse" id="expensesSubmenu">
                <div class="ps-4 mt-2">
                    @can('view-expenses')
                    <a href="{{ route('expenseinformations') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-file-invoice-dollar me-2"></i> <span>Expenses Info</span>
                    </a>
                    @endcan
                    @can('approve-expenses')
                    <a href="{{ route('approveexpenses') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-check me-2"></i> <span>Approve Expenses</span>
                    </a>
                    @endcan
                    @can('view-expense-categories')
                    <a href="{{ route('expensecategoryinformations') }}" class="nav-link d-flex align-items-center">
                        <i class="fas fa-tags me-2"></i> <span>Expense Categories</span>
                    </a>
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