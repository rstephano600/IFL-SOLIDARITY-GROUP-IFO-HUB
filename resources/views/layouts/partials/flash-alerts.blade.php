{{--
    layouts/partials/flash-alerts.blade.php
    ─────────────────────────────────────────────────────────────────
    Include ONCE in app.blade.php, right before </body>:

        @include('layouts.partials.flash-alerts')

    That's all. Every controller that calls Alert::success() / Alert::error()
    / Alert::warning() / Alert::info() will automatically show a branded
    SweetAlert2 popup on the next page — no per-blade code needed.

    Also handles:
      • $errors (validation) — auto popup listing all field errors
      • Customises the SweetAlert2 skin to match ArBif brand colours
─────────────────────────────────────────────────────────────────
--}}

{{--
    realrashid/sweet-alert fires its own JS via this include.
    It reads the session key 'sweetalert.alert' set by Alert::
    and calls Swal.fire() automatically.
--}}
@include('sweetalert::alert')

{{-- Validation errors → SweetAlert2 popup --}}
@if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon:              'error',
        title:             'Please fix the following errors',
        html:              '<ul style="text-align:left;margin:0;padding-left:18px;font-size:13px;color:#334155;">' +
                           @foreach ($errors->all() as $error)
                               '<li>{{ addslashes($error) }}</li>' +
                           @endforeach
                           '</ul>',
        confirmButtonColor: '#0C447C',
        customClass: { popup: 'arbif-swal-popup', title: 'arbif-swal-title', confirmButton: 'arbif-swal-confirm' },
    });
});
</script>
@endif

{{--
    Brand skin for every SweetAlert2 popup — applies to Alert:: calls too
    because realrashid/sweet-alert uses the global Swal instance.
--}}
<style>
.arbif-swal-popup,
.swal2-popup {
    border-radius: 16px !important;
    font-family: 'Segoe UI', system-ui, sans-serif !important;
    padding: 28px 24px !important;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15) !important;
}

.arbif-swal-title,
.swal2-title {
    font-size: 16px !important;
    font-weight: 700 !important;
    color: #0f172a !important;
}

.swal2-html-container {
    font-size: 13px !important;
    color: #475569 !important;
}

/* Confirm button — navy */
.arbif-swal-confirm,
.swal2-confirm {
    background-color: #0C447C !important;
    border-radius: 8px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    padding: 9px 22px !important;
    border: none !important;
}

.swal2-confirm:hover { background-color: #185FA5 !important; }

/* Cancel button */
.swal2-cancel {
    background-color: #f1f5f9 !important;
    color: #475569 !important;
    border-radius: 8px !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    padding: 9px 18px !important;
    border: none !important;
}

/* Progress bar — accent green */
.swal2-timer-progress-bar { background: #5DCAA5 !important; }

/* Icon colours */
.swal2-icon.swal2-success { border-color: #5DCAA5 !important; color: #5DCAA5 !important; }
.swal2-icon.swal2-success .swal2-success-ring { border-color: rgba(93,202,165,0.3) !important; }
.swal2-icon.swal2-success [class^='swal2-success-line'] { background: #5DCAA5 !important; }
</style>








