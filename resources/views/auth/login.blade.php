@extends('layouts.auth-app')
@section('title', 'Login')

@section('content')
<div class="row g-0 min-vh-100">

    {{-- ══════════════════ LEFT PANEL ══════════════════ --}}
    <div class="col-lg-5 bg-ifl-navy d-flex flex-column justify-content-center align-items-center text-center p-5 position-relative overflow-hidden">

        {{-- Decorative circles --}}
        <div class="position-absolute rounded-circle bg-white bg-opacity-10"
             style="width:260px;height:260px;top:-80px;left:-80px;"></div>
        <div class="position-absolute rounded-circle bg-white bg-opacity-10"
             style="width:200px;height:200px;bottom:-60px;right:-60px;"></div>

        <div class="position-relative">
            {{-- Logo icon --}}
            <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 d-inline-flex align-items-center justify-content-center mb-4"
                 style="width:72px;height:72px;">
                <img src="{{ asset('logo/iflsglogo.png') }}" alt="IFL Solidarity Group" style="width:46px;height:46px;object-fit:contain;">
            </div>

            <h2 class="text-white fw-semibold fs-4 brand-font mb-2">IFL Solidarity Group</h2>
            <div class="bg-ifl-gold mx-auto mb-3" style="width:50px;height:3px;"></div>
            <p class="text-white-50 small mx-auto mb-4" style="max-width:260px;">
                Secure management system for our catering, microfinance,
                agency banking, and agricultural trading operations.
            </p>

            <div class="d-flex flex-wrap justify-content-center gap-2">
                <span class="badge bg-white bg-opacity-10 border border-white border-opacity-25 text-white-50 fw-normal px-3 py-2">
                    <i class="bi bi-cup-hot text-ifl-gold me-1"></i> Catering
                </span>
                <span class="badge bg-white bg-opacity-10 border border-white border-opacity-25 text-white-50 fw-normal px-3 py-2">
                    <i class="bi bi-people-fill text-ifl-gold me-1"></i> SACCOS/VICOBA
                </span>
                <span class="badge bg-white bg-opacity-10 border border-white border-opacity-25 text-white-50 fw-normal px-3 py-2">
                    <i class="bi bi-phone text-ifl-gold me-1"></i> Agency Banking
                </span>
                <span class="badge bg-white bg-opacity-10 border border-white border-opacity-25 text-white-50 fw-normal px-3 py-2">
                    <i class="bi bi-basket text-ifl-gold me-1"></i> Agri-Trading
                </span>
            </div>
        </div>
    </div>

    {{-- ══════════════════ RIGHT PANEL ══════════════════ --}}
    <div class="col-lg-7 d-flex flex-column justify-content-center align-items-center bg-white p-4 p-lg-5">
        <div class="w-100" style="max-width:420px;">

            <h1 class="fs-3 fw-semibold text-dark mb-1 brand-font">Welcome back</h1>
            <p class="text-muted small mb-4">Sign in to your account to continue</p>

            {{-- Error alert --}}
            @if($errors->any())
            <div class="alert alert-danger d-flex align-items-start gap-2 small py-2 px-3" role="alert">
                <i class="bi bi-exclamation-circle mt-1"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            {{-- Status alert --}}
            @if(session('status'))
            <div class="alert alert-success d-flex align-items-start gap-2 small py-2 px-3" role="alert">
                <i class="bi bi-check-circle mt-1"></i>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                {{-- Email / Username --}}
                <div class="mb-3">
                    <label for="login" class="form-label small fw-semibold text-muted text-uppercase" style="letter-spacing:.05em; font-size:.7rem;">
                        Email or Username
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-person text-muted"></i></span>
                        <input
                            type="text"
                            id="login"
                            name="login"
                            value="{{ old('login') }}"
                            autofocus
                            required
                            placeholder="Enter your email or username"
                            class="form-control">
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="pw-field" class="form-label small fw-semibold text-muted text-uppercase" style="letter-spacing:.05em; font-size:.7rem;">
                        Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
                        <input
                            type="password"
                            name="password"
                            required
                            id="pw-field"
                            placeholder="Enter your password"
                            class="form-control">
                        <button class="input-group-text bg-white" type="button" onclick="togglePw()">
                            <i class="bi bi-eye text-muted" id="pw-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember me + Forgot password --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small text-muted" for="remember">
                            Remember me for 30 days
                        </label>
                    </div>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-ifl-navy text-decoration-none">
                        Forgot password?
                    </a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-ifl-navy w-100 d-flex align-items-center justify-content-center gap-2 py-2 fw-medium">
                    <i class="bi bi-box-arrow-in-right"></i> Sign in
                </button>
            </form>

            <!-- <p class="text-center text-muted small mt-4">
                Having trouble? Contact your
                <a href="#" class="text-ifl-navy text-decoration-none">system administrator</a>
            </p> -->
            <p class="text-center text-muted small mt-4">
                You have no Account ?
                <a href="{{ route('showregisterForm') }}" class="text-ifl-navy text-decoration-none">Register here</a>
            </p>

        </div>
    </div>

</div>

<script>
function togglePw() {
    var f = document.getElementById('pw-field');
    var e = document.getElementById('pw-eye');
    f.type = f.type === 'password' ? 'text' : 'password';
    e.className = f.type === 'password' ? 'bi bi-eye text-muted' : 'bi bi-eye-slash text-muted';
}
</script>
@endsection