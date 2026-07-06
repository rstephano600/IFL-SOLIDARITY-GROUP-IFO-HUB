{{--
    layouts/partials/table-scripts.blade.php
    ─────────────────────────────────────────
    Drop ONCE into app.blade.php before </body>.
    No per-page toolbar HTML needed — the engine builds
    everything automatically from the table's data-* attributes.

    Usage on any blade:
    ───────────────────
    <div class="arbif-table-wrap">
      <table class="arbif-table"
             id="anyUniqueId"
             data-title="My Report Title"
             data-export-name="MyExportFilename">
        <thead><tr>
          <th class="sortable">Name</th>
          <th class="sortable no-export">Actions</th>
        </tr></thead>
        <tbody>…</tbody>
      </table>
    </div>

    • data-title       — used in print header and Excel caption
    • data-export-name — filename for Excel/CSV (no extension needed)
    • no-export class  — excludes that column from all exports
--}}
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
        this.exportName = table.dataset.exportName || 'ArbifDataExported';
        this.title      = table.dataset.title      || 'ArBif Report';

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
            'Exported: ' + new Date().toLocaleString() + ' &mdash; ArBif Management System</p>' +
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
            '<div class="footer"><span>ArBif Management System</span>' +
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
</style>