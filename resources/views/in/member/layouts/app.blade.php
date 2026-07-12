<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - IFL Solidarity Group</title>
    <link rel="icon" href="{{ asset('logo/iflsglogo.png') }}" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root{
            --ifl-navy: #0D2A4A;
            --ifl-navy-deep: #081b30;
            --ifl-navy-hover: #123a63;
            --ifl-gold: #B08D49;
            --ifl-gold-soft: #d9bd85;
            --sidebar-w: 250px;
            --bottomnav-h: 62px;
            --bs-body-font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, .brand-font { font-family: 'Playfair Display', serif; }
        body { background-color: #f5f6f8; }

        .bg-ifl-navy { background-color: var(--ifl-navy) !important; }
        .bg-ifl-gold { background-color: var(--ifl-gold) !important; }
        .text-ifl-navy { color: var(--ifl-navy) !important; }
        .text-ifl-gold { color: var(--ifl-gold) !important; }
        .btn-ifl-navy { background-color: var(--ifl-navy); border-color: var(--ifl-navy); color:#fff; }
        .btn-ifl-navy:hover { background-color: var(--ifl-navy-hover); border-color: var(--ifl-navy-hover); color:#fff; }

        /* ── Desktop shell ── */
        .member-shell { display: flex; min-height: 100vh; }
        .member-main {
            flex: 1;
            min-width: 0;
            margin-left: 0;
        }
        @media (min-width: 992px) {
            .member-main { margin-left: var(--sidebar-w); }
        }

        .member-content {
            padding: 1rem;
            padding-bottom: calc(var(--bottomnav-h) + 1rem);
        }
        @media (min-width: 992px) {
            .member-content { padding: 1.75rem 2rem; padding-bottom: 1.75rem; }
        }

        .card { border: 1px solid #eef0f3; border-radius: 14px; }
    </style>
    @stack('styles')
</head>
<body>

    <div class="member-shell">

        @include('in.member.layouts.sidebar')

        <div class="member-main">
            @include('in.member.layouts.header')

            <main class="member-content">
                @yield('content')
            </main>

            @include('in.member.layouts.footer')
        </div>

    </div>

    @include('in.member.layouts.mobilemenu')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
    @include('sweetalert::alert')
</body>
</html>