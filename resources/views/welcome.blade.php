<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFL Solidarity Group — Manyara, Tanzania</title>
    <link rel="icon" href="{{ asset('logo/iflsglogo.png') }}" type="image/png">

    {{-- Bootstrap 5.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Brand tokens only — layout & spacing handled by Bootstrap utilities */
        :root{
            --ifl-navy: #0D2A4A;
            --ifl-navy-deep: #081b30;
            --ifl-gold: #B08D49;
            --ifl-gold-soft: #d9bd85;
            --bs-body-font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, .brand-font { font-family: 'Playfair Display', serif; }

        .bg-ifl-navy { background-color: var(--ifl-navy) !important; }
        .bg-ifl-navy-deep { background-color: var(--ifl-navy-deep) !important; }
        .bg-ifl-gold { background-color: var(--ifl-gold) !important; }
        .text-ifl-navy { color: var(--ifl-navy) !important; }
        .text-ifl-gold { color: var(--ifl-gold) !important; }
        .btn-ifl-gold { background-color: var(--ifl-gold); border-color: var(--ifl-gold); color: #fff; }
        .btn-ifl-gold:hover { background-color: var(--ifl-gold-soft); border-color: var(--ifl-gold-soft); color: #fff; }
        .btn-outline-ifl-navy { border-color: var(--ifl-navy); color: var(--ifl-navy); }
        .btn-outline-ifl-navy:hover { background-color: var(--ifl-navy); color: #fff; }
        .icon-circle {
            width: 4rem; height: 4rem;
            background: linear-gradient(135deg, var(--ifl-navy), var(--ifl-navy-deep));
        }
        .icon-circle i { color: var(--ifl-gold-soft); }
        .hero-section { background: radial-gradient(circle at 15% 20%, var(--ifl-navy) 0%, var(--ifl-navy-deep) 65%); }
        .gold-underline { width: 60px; height: 3px; background-color: var(--ifl-gold); }
        .activity-card { border-top: 3px solid var(--ifl-gold); transition: transform .25s ease, box-shadow .25s ease; }
        .activity-card:hover { transform: translateY(-6px); }
    </style>
</head>
<body class="bg-white">

    {{-- ══════════════════════════════════════════
         NAVBAR
    ══════════════════════════════════════════ --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-ifl-navy shadow-sm sticky-top py-3">
        <div class="container">
            <a href="#" class="navbar-brand d-flex align-items-center gap-2">
                <img src="{{ asset('logo/iflsglogo.png') }}" alt="IFL Solidarity Group" height="42">
                <span class="d-none d-sm-inline">
                    <span class="fw-bold brand-font">IFL Solidarity</span>
                    <small class="d-block text-ifl-gold" style="font-size:.7rem; letter-spacing:.15em;">GROUP</small>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navMenu">
                <ul class="navbar-nav align-items-lg-center gap-lg-4">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#activities">Our Activities</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-ifl-gold rounded-pill px-4 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- ══════════════════════════════════════════
         HERO
    ══════════════════════════════════════════ --}}
    <section class="hero-section text-white" id="home">
        <div class="container py-5">
            <div class="row align-items-center py-5">

                <div class="col-lg-7">
                    <span class="badge bg-ifl-gold text-white rounded-pill px-3 py-2 mb-3 fw-normal">
                        <i class="bi bi-award me-1"></i> Solidarity in Business, Growth for Communities
                    </span>
                    <h1 class="display-4 fw-bold mb-4">
                        Empowering Communities Through
                        <span class="text-ifl-gold">Trade, Finance &amp; Service</span>
                    </h1>
                    <p class="lead text-white-50 mb-4">
                        IFL Solidarity Group brings together catering services, community
                        microfinance (SACCOS/VICOBA), mobile agency banking, and agricultural
                        trading under one trusted name — driving shared prosperity across
                        Manyara and beyond.
                    </p>

                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <span class="d-flex align-items-center gap-2 text-white-50">
                            <i class="bi bi-geo-alt-fill text-ifl-gold"></i>
                            Itracom Fertilizers Ltd, Manyara, Tanzania
                        </span>
                        <span class="d-flex align-items-center gap-2 text-white-50">
                            <i class="bi bi-telephone-fill text-ifl-gold"></i>
                            +255 686 803 114
                        </span>
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('login') }}" class="btn btn-ifl-gold btn-lg rounded-pill px-4 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Access the System
                        </a>
                        <a href="#activities" class="btn btn-outline-light btn-lg rounded-pill px-4">
                            Explore Our Activities
                        </a>
                    </div>
                </div>

                <div class="col-lg-5 mt-5 mt-lg-0">
                    <div class="card border-0 shadow-lg rounded-4 p-2">
                        <div class="card-body">
                            <h5 class="fw-bold text-ifl-navy mb-4 brand-font">What We Do</h5>

                            <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                                <div class="icon-circle rounded-3 d-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="bi bi-cup-hot fs-4"></i>
                                </div>
                                <div>
                                    <strong class="text-ifl-navy">Catering Services</strong>
                                    <p class="small text-muted mb-0">Canteen &amp; supply operations for institutions</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                                <div class="icon-circle rounded-3 d-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="bi bi-people-fill fs-4"></i>
                                </div>
                                <div>
                                    <strong class="text-ifl-navy">Microfinance</strong>
                                    <p class="small text-muted mb-0">SACCOS &amp; VICOBA group lending</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                                <div class="icon-circle rounded-3 d-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="bi bi-phone fs-4"></i>
                                </div>
                                <div>
                                    <strong class="text-ifl-navy">Agency Banking</strong>
                                    <p class="small text-muted mb-0">Mobile network money services</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-3">
                                <div class="icon-circle rounded-3 d-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="bi bi-basket fs-4"></i>
                                </div>
                                <div>
                                    <strong class="text-ifl-navy">Agricultural Trading</strong>
                                    <p class="small text-muted mb-0">Buying &amp; selling cereals</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         ACTIVITIES
    ══════════════════════════════════════════ --}}
    <section class="py-5 py-lg-6" id="activities">
        <div class="container py-5">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-7">
                    <span class="text-ifl-gold fw-semibold text-uppercase" style="letter-spacing:.15em; font-size:.85rem;">Our Core Activities</span>
                    <div class="gold-underline mx-auto my-3"></div>
                    <h2 class="fw-bold text-ifl-navy brand-font">Four Pillars, One Mission</h2>
                    <p class="text-muted">Diverse operations, unified by a commitment to community solidarity and sustainable growth.</p>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6 col-lg-3">
                    <div class="card activity-card h-100 border-0 shadow-sm rounded-4 p-2">
                        <div class="card-body text-center">
                            <div class="icon-circle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                <i class="bi bi-cup-hot fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-ifl-navy">Catering Services</h5>
                            <p class="text-muted small mb-0">
                                Canteen operations and supplier coordination delivering quality
                                food service to institutions and staff.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card activity-card h-100 border-0 shadow-sm rounded-4 p-2">
                        <div class="card-body text-center">
                            <div class="icon-circle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                <i class="bi bi-people-fill fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-ifl-navy">Microfinance</h5>
                            <p class="text-muted small mb-0">
                                SACCOS and VICOBA group savings &amp; lending that build financial
                                resilience within local communities.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card activity-card h-100 border-0 shadow-sm rounded-4 p-2">
                        <div class="card-body text-center">
                            <div class="icon-circle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                <i class="bi bi-phone fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-ifl-navy">Agency Banking</h5>
                            <p class="text-muted small mb-0">
                                Mobile network money agency services bringing convenient
                                banking access closer to our communities.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card activity-card h-100 border-0 shadow-sm rounded-4 p-2">
                        <div class="card-body text-center">
                            <div class="icon-circle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                <i class="bi bi-basket fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-ifl-navy">Agricultural Trading</h5>
                            <p class="text-muted small mb-0">
                                Buying and selling cereals, connecting farmers to reliable
                                markets across the Manyara region.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         ABOUT / STATS
    ══════════════════════════════════════════ --}}
    <section class="py-5 py-lg-6 bg-ifl-navy text-white" id="about">
        <div class="container py-4">
            <div class="row align-items-center g-5">

                <div class="col-lg-6">
                    <span class="text-ifl-gold fw-semibold text-uppercase" style="letter-spacing:.15em; font-size:.85rem;">About Us</span>
                    <h2 class="fw-bold mt-2 mb-3 brand-font">Solidarity Built on Trust</h2>
                    <p class="text-white-50">
                        Based at Itracom Fertilizers Ltd in Manyara, Tanzania, IFL Solidarity
                        Group unites four complementary business lines under one roof — catering,
                        microfinance, agency banking, and agricultural trading — each managed with
                        transparency, accountability, and a shared commitment to community growth.
                    </p>
                    <div class="d-flex align-items-center gap-2 text-white-50">
                        <i class="bi bi-geo-alt-fill text-ifl-gold"></i>
                        Itracom Fertilizers Ltd, Manyara, Tanzania
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row g-4 text-center">
                        <div class="col-6">
                            <div class="p-4 rounded-4 bg-ifl-navy-deep h-100">
                                <i class="bi bi-cup-hot fs-2 text-ifl-gold mb-2 d-block"></i>
                                <strong class="d-block fs-5">Catering</strong>
                                <small class="text-white-50">Canteen Services</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 rounded-4 bg-ifl-navy-deep h-100">
                                <i class="bi bi-people-fill fs-2 text-ifl-gold mb-2 d-block"></i>
                                <strong class="d-block fs-5">SACCOS/VICOBA</strong>
                                <small class="text-white-50">Microfinance</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 rounded-4 bg-ifl-navy-deep h-100">
                                <i class="bi bi-phone fs-2 text-ifl-gold mb-2 d-block"></i>
                                <strong class="d-block fs-5">Agency Banking</strong>
                                <small class="text-white-50">Mobile Networks</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 rounded-4 bg-ifl-navy-deep h-100">
                                <i class="bi bi-basket fs-2 text-ifl-gold mb-2 d-block"></i>
                                <strong class="d-block fs-5">Cereal Trading</strong>
                                <small class="text-white-50">Agriculture</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         CTA BAND
    ══════════════════════════════════════════ --}}
    <section class="py-5 bg-ifl-gold text-white text-center">
        <div class="container py-3">
            <h2 class="fw-bold mb-2 brand-font">Ready to Get Started?</h2>
            <p class="mb-4 text-white-50">Sign in to access your dashboard and manage your operations.</p>
            <a href="{{ route('login') }}" class="btn btn-lg btn-ifl-navy bg-ifl-navy text-white rounded-pill px-5 fw-semibold">
                <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to IFL Solidarity Group
            </a>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         CONTACT / FOOTER
    ══════════════════════════════════════════ --}}
    <footer class="bg-ifl-navy-deep text-white pt-5" id="contact">
        <div class="container">
            <div class="row g-4 pb-4">

                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('logo/iflsglogo.png') }}" alt="IFL Solidarity Group" height="38">
                        <div>
                            <strong class="d-block brand-font">IFL Solidarity Group</strong>
                            <small class="text-ifl-gold">Solidarity in Business</small>
                        </div>
                    </div>
                    <p class="text-white-50 small mb-0">
                        Catering, Microfinance, Agency Banking, and Agricultural Trading —
                        empowering communities across Manyara, Tanzania.
                    </p>
                </div>

                <div class="col-lg-4">
                    <h6 class="text-ifl-gold text-uppercase mb-3" style="letter-spacing:.1em; font-size:.8rem;">Our Activities</h6>
                    <ul class="list-unstyled text-white-50 small d-flex flex-column gap-2 mb-0">
                        <li><i class="bi bi-cup-hot me-2 text-ifl-gold"></i>Catering Services</li>
                        <li><i class="bi bi-people-fill me-2 text-ifl-gold"></i>Microfinance (SACCOS/VICOBA)</li>
                        <li><i class="bi bi-phone me-2 text-ifl-gold"></i>Agency Banking</li>
                        <li><i class="bi bi-basket me-2 text-ifl-gold"></i>Agricultural Trading</li>
                    </ul>
                </div>

                <div class="col-lg-4">
                    <h6 class="text-ifl-gold text-uppercase mb-3" style="letter-spacing:.1em; font-size:.8rem;">Contact Us</h6>
                    <ul class="list-unstyled text-white-50 small d-flex flex-column gap-3 mb-0">
                        <li class="d-flex gap-2">
                            <i class="bi bi-geo-alt-fill text-ifl-gold mt-1"></i>
                            Itracom Fertilizers Ltd, Manyara, Tanzania
                        </li>
                        <li class="d-flex gap-2">
                            <i class="bi bi-telephone-fill text-ifl-gold mt-1"></i>
                            +255 686 803 114
                        </li>
                        <li class="d-flex gap-2">
                            <i class="bi bi-envelope-fill text-ifl-gold mt-1"></i>
                            iflsolidaritygroup@info.co.tz
                        </li>
                        <li class="d-flex gap-2">
                            <i class="bi bi-globe text-ifl-gold mt-1"></i>
                            iflsolidaritygroup.co.tz
                        </li>
                    </ul>
                </div>

            </div>

            <hr class="border-secondary opacity-25">

            <div class="row py-3">
                <div class="col-md-6 small text-white-50">
                    &copy; {{ date('Y') }} IFL Solidarity Group. All rights reserved.
                </div>
                <div class="col-md-6 text-md-end small text-white-50">
                    Itracom Fertilizers Ltd &middot; Manyara, Tanzania
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>