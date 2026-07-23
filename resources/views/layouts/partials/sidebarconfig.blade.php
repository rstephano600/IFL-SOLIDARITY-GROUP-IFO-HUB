<aside class="sidebar" id="sidebar">

    @php
    $user         = Auth::user();
    @endphp


    <div class="d-flex align-items-center justify-content-center gap-2 text-center py-4 border-bottom border-white border-opacity-25">
        <img src="{{ asset('logo/iflsglogo.png') }}" alt="IFL Solidarity Group" style="width:28px;height:28px;object-fit:contain;">
        <h5 class="text-white mb-0">
            <span id="logo-text">IFL Solidarity</span>
        </h5>
    </div>

<nav class="nav flex-column p-3">

    @can('view-confirguration-side')
    <a href="{{ route('configurationside') }}" 
       class="nav-link {{ Request::is('configurationside*') ? 'active' : '' }}">
        <i class="fas fa-sliders me-1"></i> 
        <span>Configuration</span>
    </a>
    @endcan


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

            </div>
        </div>
    </div>
    @endcan


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
            </div>
        </div>
    </div>
    @endcan

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
    @can('view-member-categories-menu')
    <div class="nav-item">
        <a href="#memberscategorySubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-building me-2"></i> <span>Members Categories Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="memberscategorySubmenu">
            <div class="ps-4 mt-2">
                @can('view-member-categories')
                <a href="{{ route('membercategoryinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Members Categories</span></a>
                @endcan
                @can('view-member-categories')
                <a href="{{ route('deletedmembercategoryinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Members Categories</span></a>
                @endcan
                @can('view-member-categories-report')
                <a href="{{ route('membercategoryinformationsreport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Members Categories Report</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan
    @can('view-products-menu')
    <div class="nav-item">
        <a href="#productSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-building me-2"></i> <span>Products Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="productSubmenu">
            <div class="ps-4 mt-2">
                @can('view-products')
                <a href="{{ route('products') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Products</span></a>
                @endcan
                <!-- @can('view-products')
                <a href="{{ route('deletedproducts') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Departments</span></a>
                @endcan
                @can('view-products-report')
                <a href="{{ route('productsreport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Department Report</span></a>
                @endcan -->
                @can('view-products')
                <a href="{{ route('productcategories') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Products Categories</span></a>
                @endcan
                <!-- @can('view-products')
                <a href="{{ route('deletedproductcategories') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Departments</span></a>
                @endcan
                @can('view-products-report')
                <a href="{{ route('productcategoriesreport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Department Report</span></a>
                @endcan -->

            </div>
        </div>
    </div>
    @endcan
    @can('view-company-business-codes-menu')
    <div class="nav-item">
        <a href="#companybusinesscodeSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-building me-2"></i> <span>Company Business Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="companybusinesscodeSubmenu">
            <div class="ps-4 mt-2">
                @can('view-company-business-codes')
                <a href="{{ route('companybusinesscodeinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Company Business</span></a>
                @endcan
                @can('view-company-business-codes')
                <a href="{{ route('deletedcompanybusinesscodeinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Company Business</span></a>
                @endcan
                @can('view-company-business-codes-report')
                <a href="{{ route('companybusinesscodeinformationsreport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Company Business Report</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    @can('view-cost-centres-menu')
    <div class="nav-item">
        <a href="#costcentreSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-building me-2"></i> <span>Cost Center Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="costcentreSubmenu">
            <div class="ps-4 mt-2">
                @can('view-cost-centres')
                <a href="{{ route('costcentreinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Cost Centers</span></a>
                @endcan
                @can('view-cost-centres')
                <a href="{{ route('deletedcostcentreinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Cost Centers</span></a>
                @endcan
                @can('view-cost-centres-report')
                <a href="{{ route('costcentreinformationsreport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Cost Center Report</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    @can('view-department-menu')
    <div class="nav-item">
        <a href="#departmentSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-building me-2"></i> <span>Department Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="departmentSubmenu">
            <div class="ps-4 mt-2">
                @can('view-department')
                <a href="{{ route('departmentinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Departments</span></a>
                @endcan
                @can('view-department')
                <a href="{{ route('deleteddepartmentinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Departments</span></a>
                @endcan
                @can('view-department-report')
                <a href="{{ route('departmentinformationsreport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Department Report</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan
    @can('view-branches-menu')
    <div class="nav-item">
        <a href="#branchesSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-building me-2"></i> <span>Branches Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="branchesSubmenu">
            <div class="ps-4 mt-2">
                @can('view-branches')
                <a href="{{ route('branchiesinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Branches</span></a>
                @endcan
                @can('view-branches')
                <a href="{{ route('deletedbranchiesinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-trash-arrow-up me-2"></i> <span>Deleted Branches</span></a>
                @endcan
                @can('view-branches-report')
                <a href="{{ route('branchiesinformationsreport') }}" class="nav-link d-flex align-items-center"><i class="fas fa-chart-bar me-2"></i> <span>Branches Report</span></a>
                @endcan
            </div>
        </div>
    </div>
    @endcan
    @can('view-companies-menu')
    <div class="nav-item">
        <a href="#companiesSubmenu" class="nav-link" data-bs-toggle="collapse" role="button">
            <i class="fas fa-building me-2"></i> <span>Companies Menu</span><i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="collapse" id="companiesSubmenu">
            <div class="ps-4 mt-2">
                @can('view-companies')
                <a href="{{ route('companiesinformations') }}" class="nav-link d-flex align-items-center"><i class="fas fa-eye me-2"></i> <span>View Companies</span></a>
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