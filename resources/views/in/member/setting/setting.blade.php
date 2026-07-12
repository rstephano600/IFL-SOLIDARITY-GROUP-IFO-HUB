@extends('in.member.layouts.app')
@section('title', 'Settings')

@section('content')

    <h1 class="fs-5 fw-semibold text-dark mb-1">Settings</h1>
    <p class="text-muted small mb-4">Manage your account security</p>

    <div class="card" style="max-width:520px;">
        <div class="card-body">
            <h2 class="fs-6 fw-semibold text-ifl-navy mb-3">
                <i class="bi bi-shield-lock me-1"></i> Change Password
            </h2>

            <form method="POST" action="{{ route('member.settings.password') }}" id="passwordForm">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="current_password" class="form-label small fw-semibold text-muted text-uppercase field-label">Current password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" id="current_password" name="current_password"
                               class="form-control @error('current_password') is-invalid @enderror" required>
                        <button class="input-group-text bg-white" type="button" onclick="togglePw('current_password','eye-0')">
                            <i class="bi bi-eye text-muted" id="eye-0"></i>
                        </button>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label small fw-semibold text-muted text-uppercase field-label">New password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock-fill text-muted"></i></span>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror" required>
                        <button class="input-group-text bg-white" type="button" onclick="togglePw('password','eye-1')">
                            <i class="bi bi-eye text-muted" id="eye-1"></i>
                        </button>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label small fw-semibold text-muted text-uppercase field-label">Confirm new password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock-fill text-muted"></i></span>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-control" required>
                        <button class="input-group-text bg-white" type="button" onclick="togglePw('password_confirmation','eye-2')">
                            <i class="bi bi-eye text-muted" id="eye-2"></i>
                        </button>
                    </div>
                    <small id="pwMatchHint" class="d-block mt-1" style="font-size:.75rem;"></small>
                </div>

                <div class="rounded-3 p-3 mb-4" style="background:#f7f5f0;border:1px solid #eee6d6;">
                    <p class="small fw-semibold text-dark mb-2">
                        <i class="bi bi-shield-check text-ifl-gold me-1"></i> Password requirements
                    </p>
                    <ul class="list-unstyled mb-0 small text-muted" id="pwChecklist">
                        <li data-rule="length" class="mb-1"><i class="bi bi-circle me-1"></i> At least 8 characters</li>
                        <li data-rule="upper" class="mb-1"><i class="bi bi-circle me-1"></i> One uppercase letter</li>
                        <li data-rule="lower" class="mb-1"><i class="bi bi-circle me-1"></i> One lowercase letter</li>
                        <li data-rule="number" class="mb-1"><i class="bi bi-circle me-1"></i> One number</li>
                        <li data-rule="special" class="mb-0"><i class="bi bi-circle me-1"></i> One special character</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-ifl-navy w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-check2"></i> Update password
                </button>
            </form>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .field-label { letter-spacing: .05em; font-size: .7rem; text-transform: uppercase; }
    #pwChecklist li.met { color: #1D9E75; }
    #pwChecklist li.met i.bi-circle::before { content: "\f26a"; }
</style>
@endpush

@push('scripts')
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
@endpush