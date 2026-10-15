{{-- Shared by createExpense & editExpense. $data is null on create. --}}
@php
    $defaultItems = [['item_name' => '', 'product_id' => '', 'quantity' => 1, 'unit_cost' => 0, 'supplier_name' => '']];
    $rows = old('items', $data
        ? $data->items->map(fn($i) => [
            'item_name'     => $i->item_name,
            'product_id'    => $i->product_id,
            'quantity'      => (float) $i->quantity,
            'unit_cost'     => (float) $i->unit_cost,
            'supplier_name' => $i->supplier_name,
        ])->values()->toArray()
        : $defaultItems);
@endphp

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- EXPENSE DETAILS --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-1"></i> Expense Details</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Branch <span class="text-danger">*</span></label>
                <select name="branch_id" class="form-select" data-searchable data-placeholder="Search branch..." required>
                    <option value="">-- Select Branch --</option>
                    @foreach($branchies as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id', $data->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->branch_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select name="expense_category_id" class="form-select" data-searchable data-placeholder="Search category..." required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('expense_category_id', $data->expense_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Expense Date <span class="text-danger">*</span></label>
                <input type="date" name="expense_date" class="form-control"
                       value="{{ old('expense_date', isset($data) ? \Carbon\Carbon::parse($data->expense_date)->format('Y-m-d') : date('Y-m-d')) }}" required>
            </div>

            <div class="col-md-8">
                <label class="form-label">Expense Title <span class="text-danger">*</span></label>
                <input type="text" name="expense_title" class="form-control" value="{{ old('expense_title', $data->expense_title ?? '') }}" placeholder="e.g. OFFICE STATIONERY PURCHASE" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Currency <span class="text-danger">*</span></label>
                <select name="currency" class="form-select" required>
                    @foreach(['TZS', 'USD', 'KES', 'EUR'] as $cur)
                        <option value="{{ $cur }}" {{ old('currency', $data->currency ?? 'TZS') == $cur ? 'selected' : '' }}>{{ $cur }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-8">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control" placeholder="Purpose / justification of this expense...">{{ old('description', $data->description ?? '') }}</textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label">Attachment <small class="text-muted">(jpg, png, pdf, doc - max 5MB)</small></label>
                <input type="file" name="attachment" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                @if(!empty($data?->attachment))
                    <small class="d-block mt-1">
                        Current: <a href="{{ asset('storage/' . $data->attachment) }}" target="_blank"><i class="fas fa-paperclip"></i> View file</a>
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- LINE ITEMS --}}
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"><i class="fas fa-list me-1"></i> Expense Items</h6>
            <button type="button" class="btn btn-sm btn-primary" id="addItemRow"><i class="fas fa-plus me-1"></i> Add Item</button>
        </div>

        <div class="arbif-table-wrap">
            <table class="arbif-table" id="itemsTable">
                <thead>
                    <tr>
                        <th style="width:22%">Item Name *</th>
                        <th style="width:20%">Linked Product</th>
                        <th style="width:12%">Quantity *</th>
                        <th style="width:14%">Unit Cost *</th>
                        <th style="width:14%">Supplier</th>
                        <th style="width:12%" class="text-end">Line Total</th>
                        <th style="width:6%"></th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                    @foreach($rows as $i => $row)
                        <tr class="item-row">
                            <td><input type="text" name="items[{{ $i }}][item_name]" class="form-control" value="{{ $row['item_name'] ?? '' }}" required></td>
                            <td>
                                <select name="items[{{ $i }}][product_id]" class="form-select">
                                    <option value="">-- None --</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" {{ ($row['product_id'] ?? '') == $p->id ? 'selected' : '' }}>{{ $p->product_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="0.001" min="0.001" name="items[{{ $i }}][quantity]" class="form-control calc-qty" value="{{ $row['quantity'] ?? 1 }}" required></td>
                            <td><input type="number" step="0.01" min="0" name="items[{{ $i }}][unit_cost]" class="form-control calc-cost" value="{{ $row['unit_cost'] ?? 0 }}" required></td>
                            <td><input type="text" name="items[{{ $i }}][supplier_name]" class="form-control" value="{{ $row['supplier_name'] ?? '' }}"></td>
                            <td class="text-end fw-bold line-total">0.00</td>
                            <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="fas fa-times"></i></button></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end fw-bold">GRAND TOTAL</td>
                        <td class="text-end fw-bold fs-6" id="grandTotal">0.00</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- Row template --}}
<template id="itemRowTemplate">
    <tr class="item-row">
        <td><input type="text" name="items[__IDX__][item_name]" class="form-control" required></td>
        <td>
            <select name="items[__IDX__][product_id]" class="form-select">
                <option value="">-- None --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->product_name }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" step="0.001" min="0.001" name="items[__IDX__][quantity]" class="form-control calc-qty" value="1" required></td>
        <td><input type="number" step="0.01" min="0" name="items[__IDX__][unit_cost]" class="form-control calc-cost" value="0" required></td>
        <td><input type="text" name="items[__IDX__][supplier_name]" class="form-control"></td>
        <td class="text-end fw-bold line-total">0.00</td>
        <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="fas fa-times"></i></button></td>
    </tr>
</template>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const body = document.getElementById('itemsBody');
    const tpl  = document.getElementById('itemRowTemplate').innerHTML;
    let idx = {{ count($rows) }};

    const fmt = n => n.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    function recalc() {
        let total = 0;
        body.querySelectorAll('.item-row').forEach(row => {
            const q = parseFloat(row.querySelector('.calc-qty').value) || 0;
            const c = parseFloat(row.querySelector('.calc-cost').value) || 0;
            const line = q * c;
            row.querySelector('.line-total').textContent = fmt(line);
            total += line;
        });
        document.getElementById('grandTotal').textContent = fmt(total);
    }

    document.getElementById('addItemRow').addEventListener('click', function () {
        body.insertAdjacentHTML('beforeend', tpl.replaceAll('__IDX__', idx++));
        recalc();
    });

    body.addEventListener('input', e => {
        if (e.target.matches('.calc-qty, .calc-cost')) recalc();
    });

    body.addEventListener('click', e => {
        const btn = e.target.closest('.remove-row');
        if (!btn) return;
        if (body.querySelectorAll('.item-row').length > 1) {
            btn.closest('tr').remove();
            recalc();
        } else {
            alert('At least one item is required.');
        }
    });

    recalc();
});
</script>
@endpush