<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFL Solidarity Group — Management System</title>

    <link rel="icon" href="{{ asset('logo/iflsglogo.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('logo/iflsglogo.png') }}">

    {{-- Bootstrap 5.3 (CDN) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Brand tokens only — layout handled by Bootstrap utilities */
        :root{
            --ifl-navy: #0D2A4A;
            --ifl-navy-deep: #081b30;
            --ifl-gold: #B08D49;
            --ifl-gold-soft: #d9bd85;
            --bs-body-font-family: 'Inter', sans-serif;
        }
        h1, .brand-font { font-family: 'Playfair Display', serif; }

        body {
            background: radial-gradient(ellipse 70% 55% at 15% 20%, rgba(176,141,73,0.12) 0%, transparent 60%),
                        radial-gradient(ellipse 60% 50% at 85% 80%, rgba(13,42,74,0.35) 0%, transparent 60%),
                        var(--ifl-navy);
            min-height: 100vh;
        }

        .bg-ifl-navy { background-color: var(--ifl-navy) !important; }
        .text-ifl-gold { color: var(--ifl-gold) !important; }
        .bg-ifl-gold-soft { background-color: rgba(176,141,73,0.15) !important; }
        .border-ifl-gold-soft { border-color: rgba(176,141,73,0.3) !important; }

        .hub-card {
            width: 240px;
            cursor: pointer;
            transition: transform .2s ease, background .2s ease, border-color .2s ease;
            border-top: 3px solid transparent !important;
        }
        .hub-card:hover {
            transform: translateY(-4px);
            background: rgba(255,255,255,0.11) !important;
            border-color: var(--ifl-gold) !important;
            border-top-color: var(--ifl-gold) !important;
        }
        .hub-card button { display: none; }

        .card-icon {
            width: 52px;
            height: 52px;
            font-size: 22px;
        }

        @media (max-width: 560px) {
            .hub-card { width: 100%; max-width: 320px; }
        }
    </style>
</head>
<body class="d-flex flex-column align-items-center justify-content-center py-5 px-3">
    @include('sweetalert::alert')

    {{-- ── Header ── --}}
    <header class="text-center mb-5">
        <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 d-inline-flex align-items-center justify-content-center mb-3"
             style="width:72px;height:72px;">
            <img src="{{ asset('logo/iflsglogo.png') }}" alt="IFL Solidarity Group" style="width:44px;height:44px;object-fit:contain;">
        </div>
        <h1 class="text-white fs-4 fw-semibold brand-font mb-1">IFL Solidarity Group</h1>
        <p class="text-white-50 small mb-2">Select a module to continue</p>
        <div class="bg-ifl-gold mx-auto" style="width:40px;height:2px;"></div>
    </header>

    {{-- ── Module selector ── --}}
    <form method="POST" action="{{ route('settings') }}" id="module-form">
        @csrf
        <div class="d-flex flex-wrap gap-4 justify-content-center" style="max-width:900px;">

            @can('view-confirguration-side')
            <div class="hub-card bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 d-flex flex-column align-items-center text-center gap-3 p-4"
                 onclick="submitModule('configuration')">
                <div class="card-icon bg-ifl-gold-soft border border-ifl-gold-soft text-ifl-gold rounded-3 d-flex align-items-center justify-content-center">
                    <i class="bi bi-sliders"></i>
                </div>
                <span class="text-white fw-semibold">Configuration Side</span>
                <span class="text-white-50 small">System settings, roles &amp; access control</span>
                <button type="submit" name="module" value="configuration"></button>
            </div>
            @endcan

            @can('view-working-side')
            <div class="hub-card bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 d-flex flex-column align-items-center text-center gap-3 p-4"
                 onclick="submitModule('working')">
                <div class="card-icon bg-ifl-gold-soft border border-ifl-gold-soft text-ifl-gold rounded-3 d-flex align-items-center justify-content-center">
                    <i class="bi bi-briefcase"></i>
                </div>
                <span class="text-white fw-semibold">Working Side</span>
                <span class="text-white-50 small">Day-to-day loan operations &amp; transactions</span>
                <button type="submit" name="module" value="working"></button>
            </div>
            @endcan

            @can('view-reporting-side')
            <div class="hub-card bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 d-flex flex-column align-items-center text-center gap-3 p-4"
                 onclick="submitModule('reports')">
                <div class="card-icon bg-ifl-gold-soft border border-ifl-gold-soft text-ifl-gold rounded-3 d-flex align-items-center justify-content-center">
                    <i class="bi bi-bar-chart-line"></i>
                </div>
                <span class="text-white fw-semibold">Reporting Side</span>
                <span class="text-white-50 small">Analytics, statements &amp; branch reports</span>
                <button type="submit" name="module" value="reports"></button>
            </div>
            @endcan

        </div>
    </form>

    {{-- ── Footer ── --}}
    <footer class="text-white-50 small text-center mt-5">
        &copy; {{ date('Y') }} IFL Solidarity Group. All rights reserved.
    </footer>

    {{-- Bootstrap JS + jQuery (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/sweetalert-custom.js') }}"></script>
    <script src="{{ asset('js/sweetalert-ajax.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        function submitModule(value) {
            var btn = document.querySelector('button[value="' + value + '"]');
            if (btn) btn.click();
        }
    </script>

    @stack('scripts')
</body>
</html>