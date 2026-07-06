
<style>
/* ══════════════════════════════════════════════════════════════
   ARBIF — TABLE & MODAL SYSTEM
   Brand: --navy #0C447C  |  --accent #5DCAA5
══════════════════════════════════════════════════════════════ */

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