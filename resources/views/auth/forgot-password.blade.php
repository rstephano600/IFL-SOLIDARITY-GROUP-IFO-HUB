@extends('layouts.auth-app')
@section('title', 'Change Password')

@section('content')
<div style="max-width:460px;margin:0 auto;">

  {{-- Blue header bar --}}
  <div style="background:#0C447C;border-radius:12px 12px 0 0;padding:24px 28px;
              display:flex;align-items:center;gap:16px;">
    <div style="width:44px;height:44px;border-radius:10px;background:rgba(255,255,255,0.12);
                display:flex;align-items:center;justify-content:center;
                border:0.5px solid rgba(255,255,255,0.2);flex-shrink:0;">
      <i class="bi bi-key" style="color:#fff;font-size:18px;"></i>
    </div>
    <div>
      <h2 style="color:#fff;font-size:16px;font-weight:500;margin-bottom:3px;">Set a new password</h2>
      <p style="color:rgba(255,255,255,0.5);font-size:12px;">Your account security is important to us</p>
    </div>
  </div>

  <div style="background:#fff;border:0.5px solid #e5e7eb;border-top:none;
              border-radius:0 0 12px 12px;padding:24px 28px 32px;">

    @if(session('warning'))
    <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;
                background:#fffbeb;border:0.5px solid #fcd34d;color:#92400e;
                font-size:12px;margin-bottom:20px;">
      <i class="bi bi-exclamation-triangle" style="flex-shrink:0;"></i>
      {{ session('warning') }}
    </div>
    @endif

    @if($errors->any())
    <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;
                background:#fef2f2;border:0.5px solid #fca5a5;color:#b91c1c;
                font-size:12px;margin-bottom:20px;">
      <i class="bi bi-exclamation-circle" style="flex-shrink:0;"></i>
      {{ $errors->first() }}
    </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST">
      @csrf

      {{-- Current password --}}
      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:11px;font-weight:500;color:#6b7280;
                      text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
          Current password
        </label>
        <div style="position:relative;">
          <i class="bi bi-lock" style="position:absolute;left:11px;top:50%;
             transform:translateY(-50%);color:#9ca3af;font-size:14px;"></i>
          <input type="password" name="current_password" required id="f1"
                 placeholder="Enter current password"
                 style="width:100%;padding:9px 36px;font-size:13px;
                        border:0.5px solid #d1d5db;border-radius:8px;outline:none;"
                 onfocus="this.style.borderColor='#378ADD';this.style.boxShadow='0 0 0 3px rgba(55,138,221,0.1)'"
                 onblur="this.style.borderColor='#d1d5db';this.style.boxShadow='none'">
          <button type="button" onclick="toggleField('f1','e1')"
                  style="position:absolute;right:11px;top:50%;transform:translateY(-50%);
                         background:none;border:none;cursor:pointer;color:#9ca3af;padding:0;">
            <i id="e1" class="bi bi-eye" style="font-size:14px;"></i>
          </button>
        </div>
      </div>

      {{-- New password --}}
      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:11px;font-weight:500;color:#6b7280;
                      text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
          New password
        </label>
        <div style="position:relative;">
          <i class="bi bi-lock" style="position:absolute;left:11px;top:50%;
             transform:translateY(-50%);color:#9ca3af;font-size:14px;"></i>
          <input type="password" name="password" required id="f2"
                 placeholder="At least 8 characters" oninput="checkStrength(this.value)"
                 style="width:100%;padding:9px 36px;font-size:13px;
                        border:0.5px solid #d1d5db;border-radius:8px;outline:none;"
                 onfocus="this.style.borderColor='#378ADD';this.style.boxShadow='0 0 0 3px rgba(55,138,221,0.1)'"
                 onblur="this.style.borderColor='#d1d5db';this.style.boxShadow='none'">
          <button type="button" onclick="toggleField('f2','e2')"
                  style="position:absolute;right:11px;top:50%;transform:translateY(-50%);
                         background:none;border:none;cursor:pointer;color:#9ca3af;padding:0;">
            <i id="e2" class="bi bi-eye" style="font-size:14px;"></i>
          </button>
        </div>

        {{-- Strength bar --}}
        <div style="margin-top:8px;">
          <div style="height:3px;background:#e5e7eb;border-radius:2px;overflow:hidden;margin-bottom:5px;">
            <div id="str-fill" style="height:100%;width:0%;border-radius:2px;transition:width 0.3s,background 0.3s;"></div>
          </div>
          <span id="str-label" style="font-size:11px;color:#9ca3af;">Enter a password</span>
        </div>

        {{-- Rules --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:5px;margin-top:10px;">
          @foreach([['r-len','8+ characters'],['r-upper','Uppercase letter'],['r-num','Number'],['r-spec','Special character']] as [$id,$text])
          <div id="{{ $id }}" style="display:flex;align-items:center;gap:6px;font-size:11px;color:#9ca3af;">
            <span class="rule-dot" style="width:6px;height:6px;border-radius:50%;
                  background:#d1d5db;flex-shrink:0;transition:background 0.2s;"></span>
            {{ $text }}
          </div>
          @endforeach
        </div>
      </div>

      {{-- Confirm password --}}
      <div style="margin-bottom:8px;">
        <label style="display:block;font-size:11px;font-weight:500;color:#6b7280;
                      text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
          Confirm new password
        </label>
        <div style="position:relative;">
          <i class="bi bi-lock" style="position:absolute;left:11px;top:50%;
             transform:translateY(-50%);color:#9ca3af;font-size:14px;"></i>
          <input type="password" name="password_confirmation" required id="f3"
                 placeholder="Repeat new password" oninput="checkMatch()"
                 style="width:100%;padding:9px 36px;font-size:13px;
                        border:0.5px solid #d1d5db;border-radius:8px;outline:none;"
                 onfocus="this.style.borderColor='#378ADD';this.style.boxShadow='0 0 0 3px rgba(55,138,221,0.1)'"
                 onblur="this.style.borderColor='#d1d5db';this.style.boxShadow='none'">
          <button type="button" onclick="toggleField('f3','e3')"
                  style="position:absolute;right:11px;top:50%;transform:translateY(-50%);
                         background:none;border:none;cursor:pointer;color:#9ca3af;padding:0;">
            <i id="e3" class="bi bi-eye" style="font-size:14px;"></i>
          </button>
        </div>
        <div id="match-msg" style="font-size:11px;margin-top:5px;min-height:16px;"></div>
      </div>

      <div style="display:flex;gap:10px;margin-top:20px;">
        <a href="{{ route('home') }}"
           style="flex:1;padding:10px;border:0.5px solid #d1d5db;border-radius:8px;
                  font-size:13px;color:#6b7280;text-align:center;text-decoration:none;
                  display:flex;align-items:center;justify-content:center;">
          Cancel
        </a>
        <button type="submit"
                style="flex:2;padding:10px;background:#0C447C;color:#fff;border:none;
                       border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;
                       display:flex;align-items:center;justify-content:center;gap:8px;"
                onmouseover="this.style.background='#185FA5'"
                onmouseout="this.style.background='#0C447C'">
          <i class="bi bi-check-lg"></i> Update password
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function toggleField(fid, eid) {
  var f = document.getElementById(fid);
  var e = document.getElementById(eid);
  f.type = f.type === 'password' ? 'text' : 'password';
  e.className = (f.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash');
  e.style.fontSize = '14px';
}

function checkStrength(v) {
  var r = { len: v.length >= 8, upper: /[A-Z]/.test(v), num: /[0-9]/.test(v), spec: /[^A-Za-z0-9]/.test(v) };
  var score = Object.values(r).filter(Boolean).length;
  var colors = ['#E24B4A','#E24B4A','#EF9F27','#1D9E75','#1D9E75'];
  var labels = ['Too weak','Too weak','Fair','Strong','Very strong'];
  var fill = document.getElementById('str-fill');
  var lbl  = document.getElementById('str-label');
  fill.style.width = (score * 25) + '%';
  fill.style.background = colors[score];
  lbl.textContent = v.length ? labels[score] : 'Enter a password';
  lbl.style.color = v.length ? colors[score] : '#9ca3af';
  var ids = ['r-len','r-upper','r-num','r-spec'];
  var vals = [r.len, r.upper, r.num, r.spec];
  ids.forEach(function(id, i) {
    var el  = document.getElementById(id);
    var dot = el.querySelector('.rule-dot');
    if (vals[i]) { el.style.color='#15803d'; dot.style.background='#1D9E75'; }
    else          { el.style.color='#9ca3af'; dot.style.background='#d1d5db'; }
  });
}

function checkMatch() {
  var nw  = document.getElementById('f2').value;
  var cn  = document.getElementById('f3').value;
  var msg = document.getElementById('match-msg');
  if (!cn) { msg.textContent = ''; return; }
  if (nw === cn) { msg.textContent = 'Passwords match'; msg.style.color = '#15803d'; }
  else           { msg.textContent = 'Passwords do not match'; msg.style.color = '#b91c1c'; }
}
</script>
@endsection