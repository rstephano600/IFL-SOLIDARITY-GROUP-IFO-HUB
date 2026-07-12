@extends('layouts.auth-app')
@section('title', 'Register')

@section('content')
<div class="row g-0 min-vh-100">

    {{-- ══════════════════ LEFT PANEL (hidden on mobile) ══════════════════ --}}
    <div class="col-lg-4 d-none d-lg-flex bg-ifl-navy flex-column justify-content-center align-items-center text-center p-5 position-relative overflow-hidden">

        <div class="position-absolute rounded-circle bg-white bg-opacity-10"
             style="width:260px;height:260px;top:-80px;left:-80px;"></div>
        <div class="position-absolute rounded-circle bg-white bg-opacity-10"
             style="width:200px;height:200px;bottom:-60px;right:-60px;"></div>

        <div class="position-relative">
            <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 d-inline-flex align-items-center justify-content-center mb-4"
                 style="width:72px;height:72px;">
                <img src="{{ asset('logo/iflsglogo.png') }}" alt="IFL Solidarity Group" style="width:46px;height:46px;object-fit:contain;">
            </div>

            <h2 class="text-white fw-semibold fs-4 brand-font mb-2">IFL Solidarity Group</h2>
            <div class="bg-ifl-gold mx-auto mb-3" style="width:50px;height:3px;"></div>
            <p class="text-white-50 small mx-auto mb-4" style="max-width:260px;">
                Create your account to access our catering, microfinance,
                agency banking, and agricultural trading systems.
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
    <div class="col-lg-8 d-flex flex-column align-items-center bg-white px-3 px-sm-4 px-lg-5 py-4 py-lg-5">
        <div class="w-100" style="max-width:640px;">

            {{-- Mobile-only compact header --}}
            <div class="d-flex d-lg-none align-items-center gap-2 mb-4">
                <div class="bg-ifl-navy rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:44px;height:44px;">
                    <img src="{{ asset('logo/iflsglogo.png') }}" alt="IFL Solidarity Group" style="width:28px;height:28px;object-fit:contain;">
                </div>
                <div>
                    <p class="fw-semibold text-dark brand-font mb-0" style="font-size:.95rem;line-height:1.2;">IFL Solidarity Group</p>
                    <p class="text-muted small mb-0" style="font-size:.75rem;">Management System</p>
                </div>
            </div>

            <h1 class="fs-3 fw-semibold text-dark mb-1 brand-font">Create your account</h1>
            <p class="text-muted small mb-4">Fill in your details below to register</p>

            @if($errors->any())
            <div class="alert alert-danger d-flex align-items-start gap-2 small py-2 px-3" role="alert">
                <i class="bi bi-exclamation-circle mt-1"></i>
                <div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form action="{{ route('storeregisterdata') }}" method="POST" id="registerForm" novalidate>
                @csrf

                {{-- Names --}}
                <div class="row g-3 mb-1">
                    <div class="col-12 col-md-4">
                        <label for="FirstName" class="form-label small fw-semibold text-muted text-uppercase field-label">
                            First name <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="FirstName" name="FirstName"
                               value="{{ old('FirstName') }}"
                               class="form-control @error('FirstName') is-invalid @enderror"
                               placeholder="e.g. Robert" required>
                        @error('FirstName')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="MiddleName" class="form-label small fw-semibold text-muted text-uppercase field-label">
                            Middle name
                        </label>
                        <input type="text" id="MiddleName" name="MiddleName"
                               value="{{ old('MiddleName') }}"
                               class="form-control @error('MiddleName') is-invalid @enderror"
                               placeholder="Optional">
                        @error('MiddleName')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="LastName" class="form-label small fw-semibold text-muted text-uppercase field-label">
                            Last name <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="LastName" name="LastName"
                               value="{{ old('LastName') }}"
                               class="form-control @error('LastName') is-invalid @enderror"
                               placeholder="e.g. Stephano" required>
                        @error('LastName')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Contact --}}
                <div class="row g-3 mb-1 mt-1">
                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label small fw-semibold text-muted text-uppercase field-label">
                            Email address <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="name@example.com" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="phone" class="form-label small fw-semibold text-muted text-uppercase field-label">
                            Phone number <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-telephone text-muted"></i></span>
                            <input type="tel" id="phone" name="phone"
                                   value="{{ old('phone') }}"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   placeholder="0657856790 or +255657856790" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>


                {{-- Passwords --}}
                <div class="row g-3 mb-1 mt-1">
                    <div class="col-12 col-md-6">
                        <label for="password" class="form-label small fw-semibold text-muted text-uppercase field-label">
                            Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Create a password" required>
                            <button class="input-group-text bg-white" type="button" onclick="togglePw('password','pw-eye-1')">
                                <i class="bi bi-eye text-muted" id="pw-eye-1"></i>
                            </button>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="password_confirmation" class="form-label small fw-semibold text-muted text-uppercase field-label">
                            Confirm password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-lock-fill text-muted"></i></span>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-control" placeholder="Repeat password" required>
                            <button class="input-group-text bg-white" type="button" onclick="togglePw('password_confirmation','pw-eye-2')">
                                <i class="bi bi-eye text-muted" id="pw-eye-2"></i>
                            </button>
                        </div>
                        <small id="pwMatchHint" class="d-block mt-1" style="font-size:.75rem;"></small>
                    </div>
                </div>

                {{-- Password requirements checklist --}}
                <div class="rounded-3 p-3 mt-2 mb-4" style="background:#f7f5f0;border:1px solid #eee6d6;">
                    <p class="small fw-semibold text-dark mb-2">
                        <i class="bi bi-shield-check text-ifl-gold me-1"></i> Password requirements
                    </p>
                    <ul class="list-unstyled mb-0 small text-muted" id="pwChecklist" style="columns:1;">
                        <li data-rule="length" class="mb-1"><i class="bi bi-circle me-1"></i> At least 8 characters</li>
                        <li data-rule="upper" class="mb-1"><i class="bi bi-circle me-1"></i> One uppercase letter</li>
                        <li data-rule="lower" class="mb-1"><i class="bi bi-circle me-1"></i> One lowercase letter</li>
                        <li data-rule="number" class="mb-1"><i class="bi bi-circle me-1"></i> One number</li>
                        <li data-rule="special" class="mb-0"><i class="bi bi-circle me-1"></i> One special character</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-ifl-navy w-100 d-flex align-items-center justify-content-center gap-2 py-2 fw-medium">
                    <i class="bi bi-person-check"></i> Register account
                </button>
            </form>

            <p class="text-center text-muted small mt-4">
                Already have an account?
                <a href="{{ route('login') }}" class="text-ifl-navy text-decoration-none fw-semibold">Sign in</a>
            </p>

        </div>
    </div>

</div>

<style>
    .field-label { letter-spacing: .05em; font-size: .7rem; }

    @media (max-width: 575.98px) {
        .form-control, .form-select, .input-group-text { font-size: 16px; }
        .btn-ifl-navy { font-size: .95rem; }
    }

    #pwChecklist li.met { color: #1D9E75; }
    #pwChecklist li.met i.bi-circle::before { content: "\f26a"; }
</style>

<script>
function togglePw(fieldId, eyeId) {
    var f = document.getElementById(fieldId);
    var e = document.getElementById(eyeId);
    f.type = f.type === 'password' ? 'text' : 'password';
    e.className = f.type === 'password' ? 'bi bi-eye text-muted' : 'bi bi-eye-slash text-muted';
}

document.addEventListener('DOMContentLoaded', function () {
    var pw = document.getElementById('password');
    var confirmPw = document.getElementById('password_confirmation');
    var hint = document.getElementById('pwMatchHint');

    var rules = {
        length:  function (v) { return v.length >= 8; },
        upper:   function (v) { return /[A-Z]/.test(v); },
        lower:   function (v) { return /[a-z]/.test(v); },
        number:  function (v) { return /[0-9]/.test(v); },
        special: function (v) { return /[^A-Za-z0-9]/.test(v); }
    };

    function updateChecklist() {
        var v = pw.value;
        Object.keys(rules).forEach(function (rule) {
            var li = document.querySelector('[data-rule="' + rule + '"]');
            if (!li) return;
            li.classList.toggle('met', rules[rule](v));
        });
    }

    function updateMatchHint() {
        if (!confirmPw.value) { hint.textContent = ''; return; }
        if (confirmPw.value === pw.value) {
            hint.textContent = 'Passwords match';
            hint.className = 'd-block mt-1 text-success';
        } else {
            hint.textContent = 'Passwords do not match';
            hint.className = 'd-block mt-1 text-danger';
        }
    }

    if (pw) pw.addEventListener('input', function () { updateChecklist(); updateMatchHint(); });
    if (confirmPw) confirmPw.addEventListener('input', updateMatchHint);
});
</script>
@endsection