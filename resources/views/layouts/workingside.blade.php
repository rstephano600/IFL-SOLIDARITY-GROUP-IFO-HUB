<!DOCTYPE html>
<html lang="sw" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFL Solidarity Group — Management System</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('logo/iflsglogo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('logo/iflsglogo.png') }}">

    {{-- Bootstrap 5.3 (CDN) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* ── IFL Solidarity Group brand tokens ── */
            --ifl-navy: #0D2A4A;
            --ifl-navy-deep: #081b30;
            --ifl-navy-hover: #123a63;
            --ifl-gold: #B08D49;
            --ifl-gold-soft: #d9bd85;

            --background-white: #ffffff;
            --light-gray: #f8f9fa;

            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --bs-body-font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, .brand-font { font-family: 'Playfair Display', serif; }

        body {
            background-color: var(--light-gray);
        }

        /* Sidebar Styles - Navy Theme */
        .sidebar {
            background: linear-gradient(180deg, var(--ifl-navy) 0%, var(--ifl-navy-deep) 100%);
            color: var(--background-white);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            transition: all 0.3s ease;
            z-index: 1050;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 4px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .sidebar .nav-link:hover {
            background-color: var(--ifl-navy-hover);
            color: var(--background-white);
        }

        .sidebar .nav-link.active {
            background-color: rgba(176,141,73,0.18);
            border-left: 3px solid var(--ifl-gold);
            color: var(--background-white);
            font-weight: 600;
        }

        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
            font-size: 1.1rem;
            color: var(--ifl-gold-soft);
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        .sidebar .text-white {
            color: var(--background-white) !important;
        }
        .sidebar .border-secondary {
            border-color: rgba(255, 255, 255, 0.2) !important;
        }

        /* Main Content Area */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--light-gray);
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Header Styles - White with Gold Accent */
        .main-header {
            background: var(--background-white);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            z-index: 1040;
            border-bottom: 3px solid var(--ifl-gold);
        }

        /* User Avatar - Navy Theme */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--ifl-navy);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                width: 0;
                left: calc(var(--sidebar-width) * -1);
            }

            .sidebar.mobile-expanded {
                width: var(--sidebar-width);
                left: 0;
                box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.expanded {
                 margin-left: 0;
            }

            .main-content.mobile-expanded {
                /* No change to main content margin when sidebar slides over it */
            }

             .sidebar .nav-link span {
                 display: inline;
             }
        }
        @media (min-width: 992px) {
            .sidebar.collapsed .nav-link span {
                display: none;
            }
        }

        /* Role Badges */
        .role-badge {
            font-size: 0.7em;
            padding: 3px 8px;
            border-radius: 0.25rem;
            color: white !important;
        }

        .badge-admin { background-color: #dc3545; }              /* Red */
        .badge-director { background-color: #6f42c1; }           /* Purple */
        .badge-ceo { background-color: #fd7e14; }                 /* Orange */
        .badge-shareholders { background-color: var(--ifl-gold); } /* Gold */
        .badge-manager { background-color: var(--ifl-navy); }      /* Navy */
        .badge-marketing-officer { background-color: #ffc107; color: #333 !important; }
        .badge-hr { background-color: var(--ifl-gold-soft); color: #333 !important; }
        .badge-accountant { background-color: #6c757d; }
        .badge-secretary { background-color: #0dcaf0; color: #333 !important; }
        .badge-loan-officer { background-color: var(--ifl-navy-hover); }
        .badge-client { background-color: #6610f2; }
        .badge-user { background-color: #343a40; }

        /* Working-side highlight badge */
        .workingside {
            background: rgba(13,42,74,0.08);
            color: var(--ifl-navy);
            border-color: rgba(13,42,74,0.18);
            font-weight: 600;
        }

        /* Flash Message Container */
        #flash-message-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1080;
            max-width: 400px;
            transition: all 0.9s ease-in-out;
        }

        #flash-message-container .alert {
            margin-bottom: 10px;
            border-radius: 12px;
            font-size: 0.95rem;
            padding: 0.75rem 1rem;
            animation: slideIn 0.9s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateX(50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .alert-success {
            background-color: #d1e7dd;
            border-left: 5px solid #0f5132;
            color: #0f5132;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-left: 5px solid #842029;
            color: #842029;
        }
        .alert-warning {
            background-color: #fff3cd;
            border-left: 5px solid #664d03;
            color: #664d03;
        }
        .alert-info {
            background-color: #cff4fc;
            border-left: 5px solid #055160;
            color: #055160;
        }

        /* Style primary buttons to use Navy */
        .btn-primary {
            background-color: var(--ifl-navy);
            border-color: var(--ifl-navy);
        }
        .btn-primary:hover {
            background-color: var(--ifl-navy-hover);
            border-color: var(--ifl-navy-hover);
        }

        /* Select2 Custom Styling */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 8px;
            border: 1px solid #ced4da;
            min-height: 38px;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
            padding-left: 12px;
        }

        /* Searchable select dropdown (used by initSearchableSelects) */
        .searchable-select-wrapper { position: relative; }
        .searchable-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1060;
            max-height: 220px;
            overflow-y: auto;
            margin: 2px 0 0;
            padding: 4px 0;
            list-style: none;
            background: var(--background-white);
            border: 1px solid #ced4da;
            border-radius: 8px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }
        .searchable-dropdown.open { display: block; }
        .searchable-dropdown li {
            padding: 8px 14px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .searchable-dropdown li:hover {
            background: rgba(176,141,73,0.12);
            color: var(--ifl-navy);
        }

        /* Print Styles */
        @media print {
            .sidebar, .main-header, .btn, .no-print {
                display: none !important;
            }
            .main-content {
                margin-left: 0 !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    @include('layouts.partials.sidebarworking')
    <div class="main-content" id="mainContent">
        @include('layouts.partials.header')

        <main class="flex-grow-1 p-3 p-lg-4">
            <div class="container-fluid">
                @hasSection('breadcrumb')
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb bg-white p-3 rounded shadow-sm">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                @endif
                @yield('content')
            </div>
        </main>
        @include('layouts.partials.footer')
    </div>

    {{-- jQuery (Required for Select2) --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    {{-- Bootstrap JS Bundle (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Select2 JS (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Custom Global JS --}}
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('js/sweetalert-ajax.js') }}"></script>
    <script>
        // Toggle Sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const logoText = document.getElementById('logo-text');
            const isMobile = window.innerWidth <= 991.98;

            if (isMobile) {
                sidebar.classList.toggle('mobile-expanded');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                if (logoText) {
                    logoText.style.display = sidebar.classList.contains('collapsed') ? 'none' : 'inline';
                }
            }
        });

        // Auto-collapse sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const isMobile = window.innerWidth <= 991.98;

            if (isMobile &&
                !sidebar.contains(event.target) &&
                !sidebarToggle.contains(event.target) &&
                sidebar.classList.contains('mobile-expanded')) {
                sidebar.classList.remove('mobile-expanded');
            }
        });

        // Update logo text visibility on load for desktop view
        document.addEventListener('DOMContentLoaded', function() {
             const sidebar = document.getElementById('sidebar');
             const logoText = document.getElementById('logo-text');
             if (window.innerWidth > 991.98 && sidebar.classList.contains('collapsed') && logoText) {
                 logoText.style.display = 'none';
             }
        });

        // Update active nav link based on current URL
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar .nav-link');

            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && href !== '' && currentPath.startsWith(href)) {
                    link.classList.add('active');
                }
            });
        });

        // Flash Message Auto-Hide
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('#flash-message-container .alert');

            alerts.forEach(alertElement => {
                setTimeout(() => {
                    if (alertElement) {
                        const bsAlert = bootstrap.Alert.getInstance(alertElement) || new bootstrap.Alert(alertElement);
                        bsAlert.close();
                    }
                }, 5000);
            });
        });

        // Global Variables
        window.csrfToken = '{{ csrf_token() }}';
        window.appUrl = '{{ url("/") }}';
        window.userId = '{{ Auth::id() ?? null }}';

        $(document).ready(function() {
            // Initialize all Select2 elements with search
            $('.select2-search, select[name*="country"], select[name*="Country_id"]').each(function() {
                $(this).select2({
                    placeholder: $(this).data('placeholder') || 'Search...',
                    allowClear: true,
                    theme: 'bootstrap-5',
                    width: '100%',
                    language: {
                        noResults: function() {
                            return 'No results found';
                        },
                        searching: function() {
                            return 'Searching...';
                        }
                    }
                });
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });

            // Auto-hide flash messages
            setTimeout(function() {
                $('#flash-message-container .alert').fadeOut('slow', function() {
                    $(this).alert('close');
                });
            }, 5000);
        });

        // Show Loading
        window.showLoading = function() {
            $('#loadingOverlay').fadeIn(300);
        };

        // Hide Loading
        window.hideLoading = function() {
            $('#loadingOverlay').fadeOut(300);
        };

        // Global AJAX Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // ── Searchable Select (custom lightweight dropdown) ──
        function initSearchableSelects() {
            document.querySelectorAll('select[data-searchable]').forEach(select => {

                const name        = select.name;
                const placeholder = select.dataset.placeholder || 'Search...';
                const options     = Array.from(select.options).filter(o => o.value !== '');

                const wrapper = document.createElement('div');
                wrapper.classList.add('searchable-select-wrapper');

                wrapper.innerHTML = `
                    <input type="hidden" name="${name}">
                    <input type="text"
                           class="form-control searchable-input"
                           placeholder="${placeholder}"
                           autocomplete="off">
                    <ul class="searchable-dropdown">
                        ${options.map(o => `
                            <li data-value="${o.value}" data-label="${o.text}">
                                ${o.text}
                            </li>
                        `).join('')}
                    </ul>
                `;

                select.replaceWith(wrapper);

                const hiddenInput = wrapper.querySelector('input[type="hidden"]');
                const searchInput = wrapper.querySelector('.searchable-input');
                const dropdown    = wrapper.querySelector('.searchable-dropdown');
                const items       = Array.from(dropdown.querySelectorAll('li'));

                const selected = options.find(o => o.selected);
                if (selected) {
                    searchInput.value = selected.text;
                    hiddenInput.value = selected.value;
                }

                searchInput.addEventListener('focus', () => dropdown.classList.add('open'));

                searchInput.addEventListener('input', () => {
                    const q = searchInput.value.toLowerCase();
                    items.forEach(item => {
                        item.style.display = item.dataset.label.toLowerCase().includes(q) ? '' : 'none';
                    });
                    hiddenInput.value = '';
                    dropdown.classList.add('open');
                });

                items.forEach(item => {
                    item.addEventListener('click', () => {
                        searchInput.value = item.dataset.label;
                        hiddenInput.value = item.dataset.value;
                        dropdown.classList.remove('open');
                    });
                });

                document.addEventListener('click', e => {
                    if (!wrapper.contains(e.target)) dropdown.classList.remove('open');
                });
            });
        }

        // Auto-run on page load
        document.addEventListener('DOMContentLoaded', initSearchableSelects);
    </script>
    @include('sweetalert::alert')
</body>
</html>