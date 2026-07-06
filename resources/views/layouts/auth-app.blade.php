<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - IFL Solidarity Group</title>
    <link rel="icon" href="{{ asset('logo/iflsglogo.png') }}" type="image/png">

    {{-- Bootstrap 5.3 (CDN) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Brand tokens only — layout handled by Bootstrap utilities */
        :root{
            --ifl-navy: #0D2A4A;
            --ifl-navy-deep: #081b30;
            --ifl-navy-hover: #123a63;
            --ifl-gold: #B08D49;
            --ifl-gold-soft: #d9bd85;
            --bs-body-font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, .brand-font { font-family: 'Playfair Display', serif; }

        body { background-color: #f8fafc; }

        .bg-ifl-navy { background-color: var(--ifl-navy) !important; }
        .bg-ifl-navy-deep { background-color: var(--ifl-navy-deep) !important; }
        .bg-ifl-gold { background-color: var(--ifl-gold) !important; }
        .text-ifl-navy { color: var(--ifl-navy) !important; }
        .text-ifl-gold { color: var(--ifl-gold) !important; }

        .btn-ifl-navy { background-color: var(--ifl-navy); border-color: var(--ifl-navy); color: #fff; }
        .btn-ifl-navy:hover { background-color: var(--ifl-navy-hover); border-color: var(--ifl-navy-hover); color: #fff; }

        .form-control:focus {
            border-color: var(--ifl-navy);
            box-shadow: 0 0 0 3px rgba(13,42,74,0.15);
        }
        .form-check-input:checked {
            background-color: var(--ifl-navy);
            border-color: var(--ifl-navy);
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .auth-card {
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
            max-width: 440px;
            border-radius: 12px;
            overflow: hidden;
        }
    </style>
</head>
<body>
    @yield('content')

    <script src="{{ asset('js/sweetalert-custom.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/sweetalert-ajax.js') }}"></script>

    {{-- Bootstrap JS + jQuery (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (!alert.classList.contains('alert-permanent')) {
                    setTimeout(() => {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        bsAlert.close();
                    }, 5000);
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>