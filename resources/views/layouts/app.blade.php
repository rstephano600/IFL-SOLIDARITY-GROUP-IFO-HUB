<!DOCTYPE html>
<html lang="sw" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ArBif Management System</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('images/arbifA.png') }}" type="image/png">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- jQuery (if not included yet) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            /* Blue for primary/branding */
            --primary-blue: #0d6efd; /* Bootstrap Primary Blue */
            --dark-blue: #0a58ca;
            --primary-color: #2c3e50;

            /* Green for accent/success */
            --primary-green: #198754; /* Bootstrap Success Green */
            --light-green: #20c997;
            
            /* White/Light for background and contrast */
            --background-white: #ffffff;
            --light-gray: #f8f9fa;

            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
        }
        
        /* Sidebar Styles - Blue Theme */
        .sidebar {
            /* Background: A deep blue for the sidebar */
            background: linear-gradient(180deg, var(--primary-color) 0%, #1a252f 100%);
            color: var(--background-white);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            transition: all 0.3s ease;
            z-index: 1050; /* Increased z-index to overlay content on mobile */
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
            white-space: nowrap; /* Prevent text wrap */
        }
        
        .sidebar .nav-link:hover {
            background-color: var(--dark-blue); /* Darker blue on hover */
            color: var(--background-white);
        }
        
        .sidebar .nav-link.active {
            /* Active link uses the accent green for distinction */
            background-color: var(--primary-green);
            color: var(--background-white);
            font-weight: 600;
        }
        
        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
            font-size: 1.1rem;
        }
        
        .sidebar.collapsed .nav-link span {
            /* Hide text when collapsed */
            display: none;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        /* Logo/User Info in Sidebar */
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
            background-color: var(--light-gray); /* White/Light background */
        }
        
        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        /* Header Styles - White with Blue Accent */
        .main-header {
            background: var(--background-white);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            z-index: 1040;
        }
        
        /* User Avatar - Blue Theme */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-blue); 
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        /* Mobile Responsive */
        @media (max-width: 991.98px) { /* Changed from 768px to 992px (Bootstrap's lg breakpoint for better tablet support) */
            /* Default to collapsed on smaller devices */
            .sidebar {
                width: 0; /* Hidden by default on mobile */
                left: calc(var(--sidebar-width) * -1); /* Off screen */
            }

            .sidebar.mobile-expanded {
                width: var(--sidebar-width);
                left: 0; /* Slide in */
                box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5); /* Overlay */
            }
            
            .main-content {
                margin-left: 0; /* Main content takes full width */
            }
            
            .main-content.expanded { /* Remove the expanded class effect for mobile */
                 margin-left: 0;
            }

            .main-content.mobile-expanded {
                /* No change to main content margin when sidebar slides over it */
            }

             .sidebar .nav-link span {
                 display: inline; /* Show text when mobile-expanded */
             }
        }
        @media (min-width: 992px) {
            /* Desktop/Tablet (lg) defaults */
            .sidebar.collapsed .nav-link span {
                display: none;
            }
        }

        /* Role Badges - Using Bootstrap's utility classes (bg-primary, bg-success, etc.) or existing colors */
        .role-badge {
            font-size: 0.7em;
            padding: 3px 8px;
            border-radius: 0.25rem;
            color: white !important; /* Ensure text is white for contrast */
        }
        
        /* Adjusted colors for better harmony with the new scheme */
        .badge-admin { background-color: #dc3545; } /* Red */
        .badge-director { background-color: #6f42c1; } /* Purple */
        .badge-ceo { background-color: #fd7e14; } /* Orange */
        .badge-shareholders { background-color: var(--primary-green); } /* Green */
        .badge-manager { background-color: var(--primary-blue); } /* Blue */
        .badge-marketing-officer { background-color: #ffc107; color: #333 !important; } /* Yellow/Dark text */
        .badge-hr { background-color: var(--light-green); } /* Light Green */
        .badge-accountant { background-color: #6c757d; } /* Gray */
        .badge-secretary { background-color: #0dcaf0; color: #333 !important; } /* Cyan/Dark text */
        .badge-loan-officer { background-color: var(--dark-blue); } /* Dark Blue */
        .badge-client { background-color: #6610f2; } /* Indigo */
        .badge-user { background-color: #343a40; } /* Dark Gray */

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

/* Slide-in animation */
@keyframes slideIn {
    from {
        transform: translateX(50px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Specific color enhancements */
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

        /* Style primary buttons to use Blue */
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        .btn-primary:hover {
            background-color: var(--dark-blue);
            border-color: var(--dark-blue);
        }

    </style>
    
    @stack('styles')
</head>
<body>
    @include('layouts.partials.sidebar')

    <div class="main-content" id="mainContent">
        @include('layouts.partials.header')

        <main class="flex-grow-1 p-3 p-lg-4"> <div class="container-fluid">
                @hasSection('breadcrumb')
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb bg-white p-3 rounded shadow-sm"> @yield('breadcrumb')
                        </ol>
                    </nav>
                @endif

<!-- Flash Messages -->
<div id="flash-message-container">
    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Info Message --}}
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Warning Message --}}
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-octagon-fill me-2"></i>
            <strong>Whoops!</strong> Please check the fields below for errors.
            <ul class="mb-0 mt-2 small ps-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

                @yield('content')
            </div>
        </main>

        @include('layouts.partials.footer')
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>

    
    <script>
        // Toggle Sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const logoText = document.getElementById('logo-text');
            const isMobile = window.innerWidth <= 991.98; // Use lg breakpoint

            if (isMobile) {
                sidebar.classList.toggle('mobile-expanded');
                // Removed toggling mobile-expanded on mainContent as sidebar overlays it
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                if (logoText) {
                    // Update logo text visibility for desktop collapse
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
                // No change to mainContent needed since it doesn't shift
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
                // A simple check for URL matching
                if (href && href !== '' && currentPath.startsWith(href)) {
                    // Remove active from others in the same group if needed, but for simple sidebar, this is fine
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
    </script>
    
    @stack('scripts')
</body>
</html>