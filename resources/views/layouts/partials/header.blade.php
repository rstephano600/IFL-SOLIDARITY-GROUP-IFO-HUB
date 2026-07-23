<header class="main-header">
    {{-- ── Toggle ── --}}
    <button class="toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
        <i class="fas fa-list"></i>
    </button>

    {{-- ── Page title (injected per-page via @section('page-title')) ── --}}
    <span class="header-title d-none d-md-block">@yield('page-title', 'Dashboard')</span>

    {{-- ── Top-level module nav (desktop only) ── --}}
    <nav class="header-module-nav d-none d-lg-flex align-items-center gap-1 ms-4">
        @can('view-confirguration-side')
        <a href="{{ route('configurationside') }}"
           class="module-nav-link configurationside {{ request()->routeIs('configurationside') ? 'active' : '' }}">
            <i class="fas fa-sliders me-1"></i> Configuration
        </a>
        @endcan
        @can('view-working-side')
        <a href="{{ route('workingside') }}"
           class="module-nav-link workingside {{ request()->routeIs('workingside') ? 'active' : '' }}">
            <i class="fas fa-tasks me-1"></i> Working
        </a>
        @endcan
        @can('view-reporting-side')
        <a href="{{ route('reportingside') }}"
           class="module-nav-link reportingside {{ request()->routeIs('reportingside') ? 'active' : '' }}">
            <i class="fas fa-chart-line me-1"></i> Reports
        </a>
        @endcan
    </nav>

    <div class="header-spacer"></div>

    <div class="header-actions">

        {{-- ── Session timer ── --}}
        @auth
        <div id="session-timer-widget" class="session-timer-widget" title="Session time remaining">
            <svg viewBox="0 0 36 36" class="session-timer-svg">
                <path class="session-timer-bg"
                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                <path id="session-timer-arc" class="session-timer-arc"
                      stroke-dasharray="100, 100"
                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
            </svg>
            <span id="session-timer-label" class="session-timer-label">10:00</span>
        </div>
        @endauth

        {{-- ── Notifications ── --}}
        <div class="dropdown">
            <button class="header-icon-btn" type="button" data-bs-toggle="dropdown" title="Notifications">
                <i class="fas fa-bell"></i>
                {{-- Uncomment when you have real notifications --}}
                {{-- <span class="badge-dot"></span> --}}
            </button>
            <ul class="dropdown-menu dropdown-menu-end header-dropdown shadow mt-2">
                <li class="dropdown-header fw-semibold px-3 py-2">Notifications</li>
                <li><hr class="dropdown-divider m-0"></li>
                <li><span class="dropdown-item text-muted small py-3 text-center">No new notifications</span></li>
                <li><hr class="dropdown-divider m-0"></li>
                <li><a class="dropdown-item text-center small py-2" href="#"
                       style="color:var(--navy);font-weight:600;">View all</a></li>
            </ul>
        </div>

        {{-- ── User menu ── --}}
        <div class="dropdown">
            <button class="header-user-btn" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <div class="header-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="d-none d-md-flex flex-column text-start lh-1 ms-2">
                    <span style="font-size:13px;font-weight:600;color:#1e293b;">
                        {{ Auth::user()->name }}
                        
                    </span>
                    <span style="font-size:10px;color:#94a3b8;">
                        {{ Auth::user()->username }}
                    </span>
                </div>
                <i class="fas fa-chevron-down d-none d-md-block ms-2"
                   style="font-size:11px;color:#94a3b8;"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end header-dropdown shadow mt-2" style="min-width:220px;">
                {{-- User summary --}}
                <li class="px-3 pt-3 pb-2 text-center">
                    <div class="header-avatar mx-auto mb-2"
                         style="width:48px;height:48px;font-size:16px;">
                        {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 2)) }}
                    </div>
                    <div style="font-size:14px;font-weight:600;color:#1e293b;">
                        {{ Auth::user()->username }}
                    </div>
                    <div style="font-size:12px;color:#94a3b8;">
                        {{ Auth::user()->email }}
                    </div>
                </li>
                <li><hr class="dropdown-divider m-0"></li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                        <i class="fas fa-person me-2" style="color:var(--navy);"></i> My Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider m-0"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" id="header-logout-form">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger w-100 text-start border-0 bg-transparent">
                            <i class="fas fa-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>{{-- /.header-actions --}}
</header>

{{-- ══════════════════════════════════════════
     Session timeout — script + styles
══════════════════════════════════════════ --}}
@auth
<script>
(function () {
    'use strict';

    // 15 minutes = 900 seconds
    var TIMEOUT  = 900;   
    var WARN_AT  = 150;   // seconds remaining when warning shows
    var PING_INT = 100;   // ping server every 100 seconds if active

    var warningShown = false;
    var warningEl    = null;

    var LOGOUT_ROUTE = "{{ route('logout') }}";
    var CSRF         = "{{ csrf_token() }}";

    // Seed local storage with current time if not present
    if (!localStorage.getItem('hub_last_activity')) {
        localStorage.setItem('hub_last_activity', Date.now());
    }

    /* ── Activity tracking ── */
    ['mousemove','mousedown','keydown','touchstart','scroll','click']
        .forEach(function(e){ document.addEventListener(e, onActivity, { passive: true }); });

    function onActivity() {
        localStorage.setItem('hub_last_activity', Date.now());
        if (warningShown) hideWarning();
    }

    /* ── Ping server while active ── */
    setInterval(function () {
        var globalLastActivity = parseInt(localStorage.getItem('hub_last_activity') || Date.now(), 10);
        // Only ping if the user has been active *somewhere* in the browser within the interval window
        if ((Date.now() - globalLastActivity) / 1000 < PING_INT) {
            fetch('/session/ping', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
            }).catch(function(){});
        }
    }, PING_INT * 1000);

    /* ── Main tick (every 1s) ── */
    setInterval(function () {
        var globalLastActivity = parseInt(localStorage.getItem('hub_last_activity') || Date.now(), 10);
        var idle      = (Date.now() - globalLastActivity) / 1000;
        var remaining = Math.max(0, TIMEOUT - idle);

        updateWidget(remaining);

        if (remaining <= 0) {
            // Only trigger logout if we haven't already been redirected by another tab
            localStorage.removeItem('hub_last_activity');
            doLogout();
        } else if (remaining <= WARN_AT) {
            if (!warningShown) {
                showWarning(Math.ceil(remaining));
            } else {
                var el = document.getElementById('timeout-countdown');
                if (el) el.textContent = Math.ceil(remaining);
            }
        } else if (remaining > WARN_AT && warningShown) {
            hideWarning();
        }
    }, 1000);

    /* ── Widget ── */
    function updateWidget(remaining) {
        var arc    = document.getElementById('session-timer-arc');
        var label  = document.getElementById('session-timer-label');
        var widget = document.getElementById('session-timer-widget');
        if (!arc || !label) return;

        var pct   = (remaining / TIMEOUT) * 100;
        var mins  = Math.floor(remaining / 60);
        var secs  = Math.floor(remaining % 60);
        arc.setAttribute('stroke-dasharray', pct.toFixed(1) + ', 100');
        label.textContent = mins + ':' + (secs < 10 ? '0' : '') + secs;

        var color = remaining > WARN_AT * 2 ? '#5DCAA5' 
                  : remaining > WARN_AT     ? '#f59e0b' 
                                            : '#ef4444'; 
        arc.style.stroke   = color;
        label.style.color  = color;

        if(widget) widget.classList.toggle('timer-danger', remaining <= WARN_AT);
    }

    /* ── Logout ── */
    function doLogout() {
        hideWarning();
        var form   = document.createElement('form');
        form.method = 'POST';
        form.action = LOGOUT_ROUTE;
        var t = document.createElement('input');
        t.type = 'hidden'; t.name = '_token'; t.value = CSRF;
        form.appendChild(t);
        document.body.appendChild(form);
        form.submit();
    }

    /* ── Warning banner ── */
    function showWarning(secs) {
        if (warningShown) return;
        warningShown = true;
        warningEl = document.createElement('div');
        warningEl.id = 'session-timeout-warning';
        warningEl.innerHTML =
            '<div style="position:fixed;top:0;left:0;right:0;z-index:99999;' +
            'background:#ef4444;color:#fff;text-align:center;padding:12px 20px;' +
            'font-size:13px;font-weight:600;box-shadow:0 2px 8px rgba(0,0,0,.25);' +
            'display:flex;align-items:center;justify-content:center;gap:16px;">' +
            '<i class="bi bi-exclamation-triangle-fill"></i>' +
            '<span>Session expiring in <strong><span id="timeout-countdown">' + secs + '</span>s</strong> due to inactivity.</span>' +
            '<button onclick="sessionKeepalive()" style="background:#fff;color:#ef4444;border:none;' +
            'padding:4px 14px;border-radius:6px;cursor:pointer;font-weight:700;font-size:12px;">' +
            'Stay Logged In</button></div>';
        document.body.prepend(warningEl);
    }

    function hideWarning() {
        warningShown = false;
        if (warningEl) { warningEl.remove(); warningEl = null; }
    }

    window.sessionKeepalive = function () {
        onActivity();
        fetch('/session/ping', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
        }).catch(function(){});
    };

})();
</script>
@endauth
<style>
    /* ── Brand tokens (mirror app.blade.php) ── */
    :root {
        --navy:   #0C447C;
        --accent: #5DCAA5;
    }

    /* ── Header shell ── */
    .main-header {
        height: 60px;
        background: #fff;
        border-bottom: 1px solid #e5eaf0;
        display: flex;
        align-items: center;
        padding: 0 20px;
        gap: 10px;
        position: sticky;
        top: 0;
        z-index: 1040;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }

    .header-spacer { flex: 1; }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ── Module nav pills ── */
    .header-module-nav { gap: 4px; }

    .module-nav-link {
        display: inline-flex;
        align-items: center;
        padding: 6px 13px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        text-decoration: none;
        transition: background 0.15s ease, color 0.15s ease;
        border: 1px solid transparent;
    }

    .module-nav-link:hover {
        background: #f1f5f9;
        color: var(--navy);
    }

    .module-nav-link.active {
        background: rgba(12,68,124,0.08);
        color: var(--navy);
        border-color: rgba(12,68,124,0.15);
        font-weight: 600;
    }

    /* ── Icon buttons ── */
    .header-icon-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: transparent;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #64748b;
        font-size: 17px;
        text-decoration: none;
        transition: background 0.15s ease, color 0.15s ease;
        position: relative;
    }

    .header-icon-btn:hover { background: #f1f5f9; color: var(--navy); }

    .badge-dot {
        position: absolute;
        top: 7px; right: 7px;
        width: 7px; height: 7px;
        background: #ef4444;
        border-radius: 50%;
        border: 1.5px solid #fff;
    }

    /* ── User button ── */
    .header-user-btn {
        display: flex;
        align-items: center;
        background: transparent;
        border: 1px solid #e5eaf0;
        border-radius: 8px;
        padding: 4px 10px 4px 5px;
        cursor: pointer;
        transition: background 0.15s ease, border-color 0.15s ease;
    }

    .header-user-btn:hover {
        background: #f8fafc;
        border-color: rgba(12,68,124,0.2);
    }

    /* ── Avatar ── */
    .header-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--navy);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 2px solid rgba(93,202,165,0.5);
    }

    /* ── Dropdown ── */
    .header-dropdown {
        border: 1px solid #e5eaf0 !important;
        border-radius: 10px !important;
        animation: dropIn 0.18s ease;
    }

    @keyframes dropIn {
        from { transform: translateY(6px); opacity: 0; }
        to   { transform: translateY(0);   opacity: 1; }
    }

    .header-dropdown .dropdown-item {
        font-size: 13px;
        border-radius: 6px;
        margin: 1px 6px;
        padding-left: 10px;
        width: calc(100% - 12px);
        transition: background 0.12s ease;
    }

    .header-dropdown .dropdown-item:hover { background: #f1f5f9; }

    /* ── Session timer ── */
    .session-timer-widget {
        position: relative;
        width: 40px; height: 40px;
        flex-shrink: 0;
    }

    .session-timer-svg {
        width: 40px; height: 40px;
        transform: rotate(-90deg);
    }

    .session-timer-bg {
        fill: none;
        stroke: #e5eaf0;
        stroke-width: 3.5;
    }

    .session-timer-arc {
        fill: none;
        stroke: var(--accent);
        stroke-width: 3.5;
        stroke-linecap: round;
        transition: stroke-dasharray 0.9s linear, stroke 0.5s ease;
    }

    .session-timer-label {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        font-size: 9px;
        font-weight: 700;
        color: var(--accent);
        white-space: nowrap;
        transition: color 0.5s ease;
    }

    .timer-danger .session-timer-svg {
        animation: timerPulse 0.8s ease-in-out infinite alternate;
    }

    @keyframes timerPulse {
        from { opacity: 1; }
        to   { opacity: 0.45; }
    }

/* ── Page header row ───────────────────────────────────────── */
.arbif-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.arbif-page-header h3 {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.arbif-page-header h3 .page-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: rgba(12,68,124,0.10);
    display: flex; align-items: center; justify-content: center;
    color: var(--navy);
    font-size: 16px;
    flex-shrink: 0;
}

/* ── Card wrapper ──────────────────────────────────────────── */
.arbif-card {
    background: #fff;
    border: 1px solid #e5eaf0;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    margin-bottom: 24px;
}

.arbif-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 14px 20px;
    border-bottom: 1px solid #e5eaf0;
    background: #f8fafc;
}

.arbif-card-header-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
}

.arbif-card-header-title i {
    color: var(--navy);
    font-size: 15px;
}

.arbif-card-body {
    padding: 20px;
}

/* ── Toolbar (search + export) ─────────────────────────────── */
.arbif-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 16px;
}

.arbif-search-wrap {
    position: relative;
    flex: 1;
    min-width: 200px;
    max-width: 320px;
}

.arbif-search-wrap i {
    position: absolute;
    left: 11px; top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 14px;
    pointer-events: none;
}

.arbif-search {
    width: 100%;
    padding: 8px 12px 8px 34px;
    font-size: 13px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    outline: none;
    background: #fff;
    color: #1e293b;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.arbif-search:focus {
    border-color: var(--navy);
    box-shadow: 0 0 0 3px rgba(12,68,124,0.10);
}

.arbif-export-group {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.arbif-export-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 13px;
    font-size: 12px;
    font-weight: 500;
    border-radius: 7px;
    border: 1px solid #d1d5db;
    background: #fff;
    color: #475569;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    white-space: nowrap;
}

.arbif-export-btn:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
    color: #1e293b;
}

.arbif-export-btn.btn-excel  { color: #166534; border-color: #bbf7d0; background: #f0fdf4; }
.arbif-export-btn.btn-excel:hover  { background: #dcfce7; }
.arbif-export-btn.btn-pdf    { color: #991b1b; border-color: #fecaca; background: #fef2f2; }
.arbif-export-btn.btn-pdf:hover    { background: #fee2e2; }
.arbif-export-btn.btn-print  { color: #1e40af; border-color: #bfdbfe; background: #eff6ff; }
.arbif-export-btn.btn-print:hover  { background: #dbeafe; }

/* ── Table ─────────────────────────────────────────────────── */
.arbif-table-wrap {
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid #e5eaf0;
}

.arbif-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    color: #334155;
}

.arbif-table thead tr {
    background: #f1f5fb;
    border-bottom: 2px solid #e5eaf0;
}

.arbif-table thead th {
    padding: 11px 14px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #64748b;
    white-space: nowrap;
    user-select: none;
}

/* Sortable header */
.arbif-table thead th.sortable {
    cursor: pointer;
}

.arbif-table thead th.sortable::after {
    content: ' ↕';
    color: #cbd5e1;
    font-size: 10px;
}

.arbif-table thead th.sort-asc::after  { content: ' ↑'; color: var(--navy); }
.arbif-table thead th.sort-desc::after { content: ' ↓'; color: var(--navy); }

.arbif-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.12s ease;
}

.arbif-table tbody tr:last-child { border-bottom: none; }

.arbif-table tbody tr:hover { background: #f8fafc; }

.arbif-table tbody td {
    padding: 11px 14px;
    vertical-align: middle;
}

/* Zebra stripe */
.arbif-table tbody tr:nth-child(even) { background: #fafbfc; }
.arbif-table tbody tr:nth-child(even):hover { background: #f1f5f9; }

/* Empty state */
.arbif-table-empty {
    text-align: center;
    padding: 48px 20px;
    color: #94a3b8;
    font-size: 13px;
}

.arbif-table-empty i {
    font-size: 32px;
    display: block;
    margin-bottom: 10px;
    color: #cbd5e1;
}

/* ── Pagination ────────────────────────────────────────────── */
.arbif-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    padding: 12px 0 0;
    font-size: 13px;
    color: #64748b;
}

.arbif-pagination-info { font-size: 12px; }

.arbif-pagination-pages {
    display: flex;
    gap: 4px;
}

.arbif-page-btn {
    min-width: 32px; height: 32px;
    padding: 0 8px;
    border: 1px solid #e5eaf0;
    border-radius: 6px;
    background: #fff;
    font-size: 12px;
    font-weight: 500;
    color: #475569;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.12s ease, border-color 0.12s ease, color 0.12s ease;
}

.arbif-page-btn:hover:not(:disabled) {
    background: #f1f5f9;
    border-color: #94a3b8;
}

.arbif-page-btn.active {
    background: var(--navy);
    border-color: var(--navy);
    color: #fff;
}

.arbif-page-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

/* ── Action buttons inside table ───────────────────────────── */
.arbif-action-btns {
    display: flex;
    gap: 6px;
    align-items: center;
    flex-wrap: nowrap;
}

.arbif-btn-edit,
.arbif-btn-delete,
.arbif-btn-view {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    border: 1px solid transparent;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s ease;
    white-space: nowrap;
}

.arbif-btn-edit {
    background: rgba(12,68,124,0.08);
    color: var(--navy);
    border-color: rgba(12,68,124,0.18);
}
.arbif-btn-edit:hover {
    background: var(--navy);
    color: #fff;
}

.arbif-btn-delete {
    background: rgba(239,68,68,0.08);
    color: #dc2626;
    border-color: rgba(239,68,68,0.20);
}
.arbif-btn-delete:hover {
    background: #dc2626;
    color: #fff;
}

.arbif-btn-view {
    background: rgba(93,202,165,0.10);
    color: #0d7a5a;
    border-color: rgba(93,202,165,0.30);
}
.arbif-btn-view:hover {
    background: var(--accent);
    color: #fff;
}

/* ── Status badges ─────────────────────────────────────────── */
.arbif-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.arbif-badge-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.arbif-badge-danger  { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.arbif-badge-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.arbif-badge-info    { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
.arbif-badge-navy    { background: rgba(12,68,124,0.08); color: var(--navy); border: 1px solid rgba(12,68,124,0.18); }

/* ══════════════════════════════════════════════════════════════
   MODAL SYSTEM
══════════════════════════════════════════════════════════════ */
.arbif-modal .modal-content {
    border: none;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.18);
    overflow: hidden;
}

.arbif-modal .modal-header {
    background: var(--navy);
    padding: 18px 24px;
    border-bottom: none;
    display: flex;
    align-items: center;
    gap: 12px;
}

.arbif-modal .modal-header .modal-icon {
    width: 36px; height: 36px;
    background: rgba(255,255,255,0.15);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    font-size: 16px;
    flex-shrink: 0;
}

.arbif-modal .modal-title {
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    flex: 1;
}

.arbif-modal .btn-close {
    filter: invert(1) brightness(2);
    opacity: 0.75;
}

.arbif-modal .modal-body {
    padding: 24px;
    background: #fff;
}

.arbif-modal .modal-footer {
    padding: 14px 24px;
    background: #f8fafc;
    border-top: 1px solid #e5eaf0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

/* ── Form fields inside modal ─────────────────────────── */
.arbif-modal .form-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #64748b;
    margin-bottom: 6px;
}

.arbif-modal .form-control,
.arbif-modal .form-select {
    font-size: 13px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 9px 12px;
    color: #1e293b;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.arbif-modal .form-control:focus,
.arbif-modal .form-select:focus {
    border-color: var(--navy);
    box-shadow: 0 0 0 3px rgba(12,68,124,0.10);
}

.arbif-modal .form-control.is-invalid {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239,68,68,0.10);
}

.arbif-modal .invalid-feedback {
    font-size: 11px;
    color: #dc2626;
}

/* ── Modal submit button ──────────────────────────────── */
.arbif-btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 22px;
    background: var(--navy);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease;
}

.arbif-btn-submit:hover { background: #185FA5; }
.arbif-btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }

.arbif-btn-cancel {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
    background: transparent;
    color: #64748b;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.15s ease;
}

.arbif-btn-cancel:hover { background: #f1f5f9; }

/* ── Delete confirm modal ─────────────────────────────── */
.arbif-modal.delete-modal .modal-header {
    background: #dc2626;
}

/* ── Loading spinner inside modal ────────────────────── */
.arbif-modal-loading {
    display: none;
    text-align: center;
    padding: 32px 0;
    color: #64748b;
    font-size: 13px;
}

.arbif-modal-loading .spinner-border {
    width: 28px; height: 28px;
    border-width: 3px;
    color: var(--navy);
    margin-bottom: 10px;
}

/* ── Responsive ───────────────────────────────────────── */
@media (max-width: 575px) {
    .arbif-toolbar { flex-direction: column; align-items: stretch; }
    .arbif-search-wrap { max-width: 100%; }
    .arbif-export-group { justify-content: flex-start; }
    .arbif-page-header { flex-direction: column; align-items: flex-start; }
    .arbif-action-btns { flex-wrap: wrap; }
}


</style>
@push('css') {{-- Using push ensures it goes to the bottom of the <head> --}}
<style>
    /* ── Use !important sparingly, but prefix with 'body' to win specificity ── */
    
    body :root {
        --navy:   #0C447C;
        --accent: #5DCAA5;
    }

    body .main-header {
        height: 60px !important;
        background: #fff !important;
        border-bottom: 1px solid #e5eaf0 !important;
        display: flex !important;
        align-items: center !important;
        position: sticky !important;
        top: 0 !important;
        z-index: 1040 !important;
    }

    /* ── Select2 Fix (Since you are using local assets) ── */
    /* Select2 often injects its own styles at the end of <body>. 
       We must use !important here to ensure our brand overrides it. */
    .select2-container--default .select2-selection--single {
        border: 1px solid #d1d5db !important;
        height: 42px !important; 
        border-radius: 8px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px !important;
        padding-left: 15px !important;
        color: #1e293b !important;
    }

    /* ── Modal Fix ── */
    .arbif-modal .modal-header {
        background-color: #0C447C !important;
        color: #ffffff !important;
    }
    
    /* Ensure the "X" button is visible on navy background */
    .arbif-modal .btn-close {
        filter: invert(1) brightness(2) !important;
    }
</style>
@endpush
<script>
(function () {
    'use strict';

    /* ═══════════════════════════════════════════════════════════════
       ARBIF TABLE ENGINE  v2
    ═══════════════════════════════════════════════════════════════ */
    function ArbifTable(table) {
        this.table      = table;
        this.id         = table.id;
        this.tbody      = table.querySelector('tbody');
        this.headers    = Array.from(table.querySelectorAll('thead th'));
        this.allRows    = [];
        this.filtered   = [];
        this.sortCol    = -1;
        this.sortDir    = 'asc';
        this.page       = 0;
        this.pageSize   = 25;
        this.exportName = table.dataset.exportName || 'IFLSGDataExported';
        this.title      = table.dataset.title      || 'IFLSG Report';

        this._buildToolbar();
        this._init();
    }

    /* ── Build toolbar HTML above the table wrapper ──────────── */
    ArbifTable.prototype._buildToolbar = function () {
        var self    = this;
        var wrapper = this.table.closest('.arbif-table-wrap');
        if (!wrapper) return;

        var toolbar = document.createElement('div');
        toolbar.className = 'arbif-toolbar';

        /* ── Left: entries selector + search ── */
        var left = document.createElement('div');
        left.className = 'arbif-toolbar-left';
        left.innerHTML =
            '<div class="arbif-entries-wrap">' +
                '<label class="arbif-entries-label">Show\u00a0' +
                    '<select class="arbif-entries-select" id="entries-' + this.id + '">' +
                        '<option value="25">25</option>' +
                        '<option value="50">50</option>' +
                        '<option value="75">75</option>' +
                        '<option value="100">100</option>' +
                        '<option value="all">All</option>' +
                    '</select>' +
                '\u00a0entries</label>' +
            '</div>' +
            '<div class="arbif-search-wrap">' +
                '<i class="bi bi-search"></i>' +
                '<input type="text" class="arbif-search" id="search-' + this.id + '" placeholder="Search\u2026">' +
            '</div>';

        /* ── Right: column chooser + export buttons ── */
        var right = document.createElement('div');
        right.className = 'arbif-toolbar-right';

        /* Column chooser */
        var chooserWrap = document.createElement('div');
        chooserWrap.className = 'dropdown';

        var chooserBtn = document.createElement('button');
        chooserBtn.className = 'arbif-export-btn arbif-col-chooser-btn';
        chooserBtn.setAttribute('data-bs-toggle', 'dropdown');
        chooserBtn.setAttribute('aria-expanded', 'false');
        chooserBtn.id = 'col-chooser-' + this.id;
        chooserBtn.innerHTML = '<i class="bi bi-layout-three-columns"></i> Columns';

        var menu = document.createElement('ul');
        menu.className = 'dropdown-menu arbif-col-chooser-menu';
        menu.id = 'col-menu-' + this.id;
        menu.setAttribute('aria-labelledby', chooserBtn.id);

        var hdr = document.createElement('li');
        hdr.className = 'arbif-col-menu-header';
        hdr.textContent = 'Export columns';
        menu.appendChild(hdr);

        this.headers.forEach(function (th, i) {
            if (th.classList.contains('no-export')) return;
            var label = th.textContent.trim();
            if (!label) return;
            var li = document.createElement('li');
            li.innerHTML =
                '<label class="arbif-col-item">' +
                    '<input type="checkbox" checked data-col="' + i + '" data-table="' + self.id + '"> ' +
                    label +
                '</label>';
            menu.appendChild(li);
        });

        /* Prevent dropdown closing on checkbox click */
        menu.addEventListener('click', function (e) { e.stopPropagation(); });

        chooserWrap.appendChild(chooserBtn);
        chooserWrap.appendChild(menu);

        /* Export buttons */
        var exportGroup = document.createElement('div');
        exportGroup.className = 'arbif-export-group';
        exportGroup.appendChild(chooserWrap);

        function makeExportBtn(cls, icon, label, handler) {
            var b = document.createElement('button');
            b.className = 'arbif-export-btn ' + cls;
            b.innerHTML = '<i class="bi ' + icon + '"></i> ' + label;
            b.addEventListener('click', handler);
            return b;
        }

        exportGroup.appendChild(makeExportBtn('btn-excel', 'bi-file-earmark-excel', 'Excel',
            function () { ArbifTable.instances[self.id].exportExcel(); }));
        exportGroup.appendChild(makeExportBtn('btn-csv', 'bi-filetype-csv', 'CSV',
            function () { ArbifTable.instances[self.id].exportCSV(); }));
        exportGroup.appendChild(makeExportBtn('btn-print', 'bi-printer', 'Print',
            function () { ArbifTable.instances[self.id].print(); }));

        right.appendChild(exportGroup);
        toolbar.appendChild(left);
        toolbar.appendChild(right);
        wrapper.parentNode.insertBefore(toolbar, wrapper);

        /* ── Entries change ── */
        var entSel = document.getElementById('entries-' + this.id);
        if (entSel) {
            entSel.addEventListener('change', function () {
                self.pageSize = this.value === 'all' ? Infinity : parseInt(this.value, 10);
                self.page = 0;
                self._renderPage();
                self._updatePagination();
            });
        }

        /* ── Search ── */
        var searchEl = document.getElementById('search-' + this.id);
        if (searchEl) {
            searchEl.addEventListener('input', function () {
                var q = this.value.toLowerCase().trim();
                self.filtered = q
                    ? self.allRows.filter(function (r) { return r.textContent.toLowerCase().includes(q); })
                    : self.allRows.slice();
                self.page = 0;
                self._renderPage();
                self._updatePagination();
            });
        }
    };

    /* ── Init ─────────────────────────────────────────────────── */
    ArbifTable.prototype._init = function () {
        this.allRows  = Array.from(this.tbody.querySelectorAll('tr'));
        this.filtered = this.allRows.slice();
        this._bindSort();
        this._buildPaginationEl();
        this._renderPage();
    };

    /* ── Sort ─────────────────────────────────────────────────── */
    ArbifTable.prototype._bindSort = function () {
        var self = this;
        this.headers.forEach(function (th, idx) {
            if (!th.classList.contains('sortable')) return;
            th.style.cursor = 'pointer';
            th.addEventListener('click', function () {
                self.sortDir = (self.sortCol === idx && self.sortDir === 'asc') ? 'desc' : 'asc';
                self.sortCol = idx;
                self.headers.forEach(function (h) { h.classList.remove('sort-asc', 'sort-desc'); });
                th.classList.add(self.sortDir === 'asc' ? 'sort-asc' : 'sort-desc');
                self.filtered.sort(function (a, b) {
                    var aT = (a.cells[idx] ? a.cells[idx].textContent : '').trim();
                    var bT = (b.cells[idx] ? b.cells[idx].textContent : '').trim();
                    var aN = parseFloat(aT.replace(/,/g, ''));
                    var bN = parseFloat(bT.replace(/,/g, ''));
                    var cmp = (!isNaN(aN) && !isNaN(bN)) ? aN - bN : aT.localeCompare(bT);
                    return self.sortDir === 'asc' ? cmp : -cmp;
                });
                self.page = 0;
                self._renderPage();
                self._updatePagination();
            });
        });
    };

    /* ── Render current page ──────────────────────────────────── */
    ArbifTable.prototype._renderPage = function () {
        var ps      = this.pageSize === Infinity ? this.filtered.length || 1 : this.pageSize;
        var start   = this.page * ps;
        var visible = this.filtered.slice(start, start + ps);
        var cols    = this.headers.length;

        this.allRows.forEach(function (r) { if (r.parentNode) r.parentNode.removeChild(r); });
        var emptyEl = this.tbody.querySelector('.arbif-table-empty-row');
        if (emptyEl) emptyEl.remove();

        if (visible.length === 0) {
            var empty = document.createElement('tr');
            empty.className = 'arbif-table-empty-row';
            empty.innerHTML =
                '<td colspan="' + cols + '" class="arbif-table-empty">' +
                '<i class="bi bi-inbox"></i>No records found</td>';
            this.tbody.appendChild(empty);
        } else {
            visible.forEach(function (r) { this.tbody.appendChild(r); }, this);
        }

        if (this._infoEl) {
            var total = this.filtered.length;
            var from  = total === 0 ? 0 : start + 1;
            var to    = Math.min(start + ps, total);
            this._infoEl.textContent = 'Showing ' + from + '\u2013' + to + ' of ' + total + ' records';
        }
    };

    /* ── Pagination ───────────────────────────────────────────── */
    ArbifTable.prototype._buildPaginationEl = function () {
        var wrapper = this.table.closest('.arbif-table-wrap');
        if (!wrapper) return;
        var pag = document.createElement('div');
        pag.className = 'arbif-pagination';
        pag.innerHTML =
            '<span class="arbif-pagination-info" id="info-' + this.id + '"></span>' +
            '<div class="arbif-pagination-pages" id="pages-' + this.id + '"></div>';
        wrapper.parentNode.insertBefore(pag, wrapper.nextSibling);
        this._infoEl  = document.getElementById('info-'  + this.id);
        this._pagesEl = document.getElementById('pages-' + this.id);
        this._updatePagination();
    };

    ArbifTable.prototype._updatePagination = function () {
        var wrap = this._pagesEl;
        if (!wrap) return;
        var self  = this;
        var ps    = this.pageSize === Infinity ? (this.filtered.length || 1) : this.pageSize;
        var total = Math.ceil(this.filtered.length / ps) || 1;
        wrap.innerHTML = '';

        function btn(label, page, disabled, active) {
            var b = document.createElement('button');
            b.className = 'arbif-page-btn' + (active ? ' active' : '');
            b.innerHTML = label;
            b.disabled  = disabled;
            b.addEventListener('click', function () {
                self.page = page;
                self._renderPage();
                self._updatePagination();
            });
            return b;
        }

        wrap.appendChild(btn('<i class="bi bi-chevron-double-left"></i>', 0, this.page === 0, false));
        wrap.appendChild(btn('<i class="bi bi-chevron-left"></i>', this.page - 1, this.page === 0, false));
        var s = Math.max(0, this.page - 2), e = Math.min(total, s + 5);
        for (var i = s; i < e; i++) wrap.appendChild(btn(i + 1, i, false, i === this.page));
        wrap.appendChild(btn('<i class="bi bi-chevron-right"></i>', this.page + 1, this.page >= total - 1, false));
        wrap.appendChild(btn('<i class="bi bi-chevron-double-right"></i>', total - 1, this.page >= total - 1, false));
    };

    /* ── Get selected export columns ─────────────────────────── */
    ArbifTable.prototype._getExportCols = function () {
        var menu = document.getElementById('col-menu-' + this.id);
        if (!menu) {
            return this.headers.map(function (_, i) { return i; })
                               .filter(function (i) { return !this.headers[i].classList.contains('no-export'); }, this);
        }
        return Array.from(menu.querySelectorAll('input[type=checkbox]:checked'))
                    .map(function (cb) { return parseInt(cb.dataset.col, 10); });
    };

    /* ── Get clean export data (all filtered rows × selected cols) */
    ArbifTable.prototype._getExportData = function () {
        var cols = this._getExportCols();
        var hdrs = this.headers;
        var headers = cols.map(function (i) {
            return hdrs[i] ? hdrs[i].textContent.trim() : '';
        });
        var rows = this.filtered.map(function (row) {
            return cols.map(function (ci) {
                var cell = row.cells[ci];
                return cell ? cell.textContent.replace(/\s+/g, ' ').trim() : '';
            });
        });
        return { headers: headers, rows: rows };
    };

    /* ── Excel export ─────────────────────────────────────────── */
    ArbifTable.prototype.exportExcel = function () {
        var data = this._getExportData();

        var thHtml = data.headers.map(function (h) {
            return '<th style="background:#0C447C;color:#fff;padding:8px 10px;' +
                   'border:1px solid #aaa;font-size:12px;text-align:left;">' + _esc(h) + '</th>';
        }).join('');

        var tbodyHtml = data.rows.map(function (row, ri) {
            var bg = ri % 2 === 0 ? '#ffffff' : '#f0f4f8';
            var tds = row.map(function (cell) {
                return '<td style="padding:7px 10px;border:1px solid #ddd;' +
                       'font-size:12px;background:' + bg + ';vertical-align:middle;">' + _esc(cell) + '</td>';
            }).join('');
            return '<tr>' + tds + '</tr>';
        }).join('');

        var html =
            '<html xmlns:o="urn:schemas-microsoft-com:office:office" ' +
            'xmlns:x="urn:schemas-microsoft-com:office:excel" ' +
            'xmlns="http://www.w3.org/TR/REC-html40">' +
            '<head><meta charset="UTF-8">' +
            '<style>' +
            'table{border-collapse:collapse;width:100%;font-family:Segoe UI,sans-serif;}' +
            'caption{font-size:14px;font-weight:700;padding:10px 0;color:#0C447C;text-align:left;}' +
            '</style></head><body>' +
            '<table><caption>' + _esc(this.title) + '</caption>' +
            '<thead><tr>' + thHtml + '</tr></thead>' +
            '<tbody>' + tbodyHtml + '</tbody>' +
            '</table>' +
            '<p style="font-size:10px;color:#999;margin-top:8px;">' +
            'Exported: ' + new Date().toLocaleString() + ' &mdash; IFLSG Management System</p>' +
            '</body></html>';

        _download('\ufeff' + html, this.exportName + '.xls', 'application/vnd.ms-excel');
    };

    /* ── CSV export ───────────────────────────────────────────── */
    ArbifTable.prototype.exportCSV = function () {
        var data = this._getExportData();
        var csv  = [data.headers].concat(data.rows).map(function (row) {
            return row.map(function (c) {
                return '"' + String(c).replace(/"/g, '""') + '"';
            }).join(',');
        }).join('\r\n');
        _download('\ufeff' + csv, this.exportName + '.csv', 'text/csv');
    };

    /* ── Print ────────────────────────────────────────────────── */
    ArbifTable.prototype.print = function () {
        var data   = this._getExportData();
        var thHtml = data.headers.map(function (h) { return '<th>' + _esc(h) + '</th>'; }).join('');
        var tbHtml = data.rows.map(function (row, ri) {
            return '<tr class="' + (ri % 2 ? 'even' : '') + '">' +
                   row.map(function (c) { return '<td>' + _esc(c) + '</td>'; }).join('') + '</tr>';
        }).join('');

        var win = window.open('', '_blank', 'width=960,height=700');
        win.document.write(
            '<!DOCTYPE html><html><head><title>' + _esc(this.title) + '</title>' +
            '<style>' +
            'body{font-family:Segoe UI,sans-serif;font-size:12px;color:#1e293b;padding:20px;}' +
            '.print-header{display:flex;align-items:center;justify-content:space-between;' +
            'margin-bottom:16px;border-bottom:3px solid #0C447C;padding-bottom:12px;}' +
            '.print-header h2{font-size:18px;color:#0C447C;margin:0;}' +
            '.print-header small{font-size:11px;color:#64748b;}' +
            'table{width:100%;border-collapse:collapse;}' +
            'th{background:#0C447C;color:#fff;padding:8px 10px;text-align:left;' +
            'font-size:11px;text-transform:uppercase;letter-spacing:.05em;border:1px solid #0a3a6a;}' +
            'td{padding:7px 10px;border-bottom:1px solid #e5eaf0;vertical-align:middle;}' +
            'tr.even td{background:#f8fafc;}' +
            '.footer{margin-top:14px;font-size:10px;color:#94a3b8;border-top:1px solid #e5eaf0;' +
            'padding-top:8px;display:flex;justify-content:space-between;}' +
            '@media print{body{padding:0;}}' +
            '</style></head><body>' +
            '<div class="print-header">' +
            '<h2>' + _esc(this.title) + '</h2>' +
            '<small>Total records: ' + data.rows.length + '</small></div>' +
            '<table><thead><tr>' + thHtml + '</tr></thead>' +
            '<tbody>' + tbHtml + '</tbody></table>' +
            '<div class="footer"><span>IFLSG Management System</span>' +
            '<span>Printed: ' + new Date().toLocaleString() + '</span></div>' +
            '</body></html>'
        );
        win.document.close();
        win.focus();
        setTimeout(function () { win.print(); win.close(); }, 400);
    };

    /* ── Helpers ──────────────────────────────────────────────── */
    function _esc(str) {
        return String(str || '')
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function _download(content, filename, mimeType) {
        var blob = new Blob([content], { type: mimeType });
        var url  = URL.createObjectURL(blob);
        var a    = document.createElement('a');
        a.href = url; a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        setTimeout(function () { URL.revokeObjectURL(url); }, 1000);
    }

    /* ═══════════════════════════════════════════════════════════════
       INSTANCE REGISTRY
    ═══════════════════════════════════════════════════════════════ */
    ArbifTable.instances = {};

    function initAllTables() {
        document.querySelectorAll('table.arbif-table[id]').forEach(function (tbl) {
            ArbifTable.instances[tbl.id] = new ArbifTable(tbl);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAllTables);
    } else {
        initAllTables();
    }

    window.ArbifTable = ArbifTable;

    /* ═══════════════════════════════════════════════════════════════
       MODAL HELPERS
    ═══════════════════════════════════════════════════════════════ */
    window.arbifOpenModal = function (modalId, title, fetchUrl) {
        var modal   = document.getElementById(modalId);
        if (!modal) return;
        var titleEl = modal.querySelector('.modal-title');
        var bodyEl  = modal.querySelector('.arbif-modal-body');
        var loadEl  = modal.querySelector('.arbif-modal-loading');
        var bsModal = bootstrap.Modal.getOrCreateInstance(modal);

        if (titleEl && title) titleEl.textContent = title;

        if (fetchUrl && bodyEl && loadEl) {
            loadEl.style.display = 'flex';
            bodyEl.style.display = 'none';
            bsModal.show();
            fetch(fetchUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
            })
            .then(function (r) { if (!r.ok) throw r.status; return r.text(); })
            .then(function (html) {
                bodyEl.innerHTML     = html;
                loadEl.style.display = 'none';
                bodyEl.style.display = 'block';
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $(bodyEl).find('.select2').select2({
                        theme: 'default', width: '100%', dropdownParent: $(modal)
                    });
                }
            })
            .catch(function () {
                loadEl.style.display = 'none';
                bodyEl.style.display = 'block';
                bodyEl.innerHTML =
                    '<div class="alert alert-danger m-3">' +
                    '<i class="bi bi-exclamation-triangle me-2"></i>Failed to load form. Please try again.' +
                    '</div>';
            });
        } else {
            /* Reset to add-form state */
            if (loadEl) loadEl.style.display = 'none';
            if (bodyEl) bodyEl.style.display = 'block';
            bsModal.show();
        }
    };

    window.arbifConfirmDelete = function (url, itemName) {
        var modal  = document.getElementById('deleteConfirmModal');
        if (!modal) return;
        var nameEl = modal.querySelector('#delete-item-name');
        var form   = modal.querySelector('#delete-confirm-form');
        if (nameEl) nameEl.textContent = itemName || 'this record';
        if (form) {
            form.action = url;
            var csrf = document.querySelector('meta[name="csrf-token"]');
            var tk   = form.querySelector('[name="_token"]');
            if (!tk) {
                tk = document.createElement('input');
                tk.type = 'hidden'; tk.name = '_token';
                form.appendChild(tk);
            }
            if (csrf) tk.value = csrf.content;
        }
        bootstrap.Modal.getOrCreateInstance(modal).show();
    };

})();


// resources/js/searchable-select.js  (or paste in your main layout before </body>)

function initSearchableSelects() {
    document.querySelectorAll('select[data-searchable]').forEach(select => {

        const name        = select.name;
        const placeholder = select.dataset.placeholder || 'Search...';
        const options     = Array.from(select.options).filter(o => o.value !== '');

        // Build replacement HTML
        const wrapper = document.createElement('div');
        wrapper.classList.add('searchable-select-wrapper');
        wrapper.style.position = 'relative';

        wrapper.innerHTML = `
            <input type="hidden" name="${name}">
            <input type="text"
                   class="form-control searchable-input"
                   placeholder="${placeholder}"
                   autocomplete="off">
            <ul class="searchable-dropdown">
                ${options.map(o => `
                    <li data-value="${o.value}" data-label="${o.text}">
                        ${o.text}
                    </li>
                `).join('')}
            </ul>
        `;

        // Replace original select
        select.replaceWith(wrapper);

        const hiddenInput = wrapper.querySelector('input[type="hidden"]');
        const searchInput = wrapper.querySelector('.searchable-input');
        const dropdown    = wrapper.querySelector('.searchable-dropdown');
        const items       = Array.from(dropdown.querySelectorAll('li'));

        // Set existing selected value if any
        const selected = options.find(o => o.selected);
        if (selected) {
            searchInput.value = selected.text;
            hiddenInput.value = selected.value;
        }

        searchInput.addEventListener('focus', () => dropdown.classList.add('open'));

        searchInput.addEventListener('input', () => {
            const q = searchInput.value.toLowerCase();
            items.forEach(item => {
                item.style.display = item.dataset.label.toLowerCase().includes(q) ? '' : 'none';
            });
            hiddenInput.value = '';
            dropdown.classList.add('open');
        });

        items.forEach(item => {
            item.addEventListener('click', () => {
                searchInput.value = item.dataset.label;
                hiddenInput.value = item.dataset.value;
                dropdown.classList.remove('open');
            });
        });

        document.addEventListener('click', e => {
            if (!wrapper.contains(e.target)) dropdown.classList.remove('open');
        });
    });
}

// Auto-run on page load
document.addEventListener('DOMContentLoaded', initSearchableSelects);
</script>

{{-- ══════════════════════════════════════════
     TOOLBAR + COLUMN CHOOSER STYLES
══════════════════════════════════════════ --}}
<style>
.arbif-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 14px;
}
.arbif-toolbar-left,
.arbif-toolbar-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.arbif-entries-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #64748b;
    white-space: nowrap;
}
.arbif-entries-select {
    padding: 5px 8px;
    font-size: 12px;
    border: 1px solid #d1d5db;
    border-radius: 7px;
    background: #fff;
    color: #1e293b;
    outline: none;
    cursor: pointer;
    transition: border-color 0.15s;
}
.arbif-entries-select:focus {
    border-color: var(--navy);
    box-shadow: 0 0 0 3px rgba(12,68,124,0.08);
}
.arbif-search-wrap {
    position: relative;
    min-width: 180px;
    max-width: 280px;
}
.arbif-search-wrap i {
    position: absolute;
    left: 10px; top: 50%;
    transform: translateY(-50%);
    color: #94a3b8; font-size: 13px;
    pointer-events: none;
}
.arbif-search {
    width: 100%;
    padding: 7px 12px 7px 32px;
    font-size: 13px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    outline: none;
    background: #fff;
    color: #1e293b;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.arbif-search:focus {
    border-color: var(--navy);
    box-shadow: 0 0 0 3px rgba(12,68,124,0.10);
}
.arbif-export-group {
    display: flex;
    align-items: center;
    gap: 5px;
    flex-wrap: wrap;
}
.arbif-export-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 500;
    border-radius: 7px;
    border: 1px solid #d1d5db;
    background: #fff;
    color: #475569;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.14s, border-color 0.14s, color 0.14s;
    white-space: nowrap;
}
.arbif-export-btn:hover { background: #f1f5f9; border-color: #94a3b8; color: #1e293b; }
.arbif-export-btn.btn-excel  { color:#166534; border-color:#bbf7d0; background:#f0fdf4; }
.arbif-export-btn.btn-excel:hover  { background:#dcfce7; }
.arbif-export-btn.btn-csv    { color:#1e40af; border-color:#bfdbfe; background:#eff6ff; }
.arbif-export-btn.btn-csv:hover    { background:#dbeafe; }
.arbif-export-btn.btn-print  { color:#6b21a8; border-color:#e9d5ff; background:#faf5ff; }
.arbif-export-btn.btn-print:hover  { background:#f3e8ff; }
.arbif-col-chooser-btn {
    color: var(--navy);
    border-color: rgba(12,68,124,0.25);
    background: rgba(12,68,124,0.06);
}
.arbif-col-chooser-btn:hover { background: rgba(12,68,124,0.12); }
.arbif-col-chooser-menu {
    min-width: 200px;
    padding: 8px 0;
    border: 1px solid #e5eaf0 !important;
    border-radius: 10px !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.10) !important;
}
.arbif-col-menu-header {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #94a3b8;
    padding: 4px 14px 8px;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 4px;
    list-style: none;
}
.arbif-col-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    font-size: 13px;
    color: #334155;
    cursor: pointer;
    transition: background 0.12s;
    margin: 0;
}
.arbif-col-item:hover { background: #f8fafc; }
.arbif-col-item input[type=checkbox] {
    width: 14px; height: 14px;
    accent-color: var(--navy);
    cursor: pointer;
    flex-shrink: 0;
}
@media (max-width: 640px) {
    .arbif-toolbar { flex-direction: column; align-items: stretch; }
    .arbif-toolbar-left, .arbif-toolbar-right { justify-content: flex-start; }
    .arbif-search-wrap { max-width: 100%; min-width: unset; }
}



/* resources/css/searchable-select.css */

.searchable-select-wrapper {
    position: relative;
}

.searchable-dropdown {
    display: none;
    position: absolute;
    z-index: 999;
    width: 100%;
    max-height: 220px;
    overflow-y: auto;
    background: #fff;
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 0;
    margin: 0;
    list-style: none;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.searchable-dropdown.open {
    display: block;
}

.searchable-dropdown li {
    padding: 8px 12px;
    cursor: pointer;
    font-size: 0.9rem;
}

.searchable-dropdown li:hover {
    background: #f0f0f0;
}
</style>

@include('layouts.partials.flash-alerts')
@include('layouts.partials.flash-alertesuccess')