@extends('layouts.workingside')

@section('title', 'Edit Purchase Order')
@section('page-title', 'Edit Purchase Order')

@push('styles')
<style>
    /* Let the searchable dropdown show outside the table wrapper */
    .items-wrapper { overflow: visible; }
    #poItemsTable td { position: relative; }
    #poItemsTable .searchable-dropdown { min-width: 220px; }
</style>
@endpush

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-pencil-alt"></i>
        </div>
        Edit Purchase Order ({{ $data->purchase_order_no }})
    </h3>

    <div>
        <a href="{{ route('viewpurchaseOrder', encrypt($data->id)) }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('updatepurchaseOrder', encrypt($data->id)) }}">
            @csrf

            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> Header & Associations</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" data-searchable data-placeholder="Search branch..." required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $data->branch_id) ==$branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select" data-searchable data-placeholder="Search supplier..." required>
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $data->supplier_id) ==$supplier->id ? 'selected' : '' }}>
                                {{ $supplier->SupplierName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Currency <span class="text-danger">*</span></label>
                    <select name="currency_code" class="form-select" data-searchable data-placeholder="Search currency..." required>
                        <option value="TZS" {{ old('currency_code', $data->currency_code) == 'TZS' ? 'selected' : '' }}>TZS</option>
                        <option value="USD" {{ old('currency_code', $data->currency_code) == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ old('currency_code', $data->currency_code) == 'EUR' ? 'selected' : '' }}>EUR</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Purchase Request Reference</label>
                    <select name="purchase_request_id" class="form-select" data-searchable data-placeholder="Search PR...">
                        <option value="">-- Select Approved PR (Optional) --</option>
                        @foreach($purchaseRequests as $pr)
                            <option value="{{ $pr->id }}" {{ old('purchase_request_id', $data->purchase_request_id) ==$pr->id ? 'selected' : '' }}>
                                {{ $pr->request_no }} - {{$pr->purpose ?? 'PR' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Supplier Award Reference</label>
                    <select name="supplier_award_id" class="form-select" data-searchable data-placeholder="Search award...">
                        <option value="">-- Select Approved Award (Optional) --</option>
                        @foreach($supplierAwards as $award)
                            <option value="{{ $award->id }}" {{ old('supplier_award_id', $data->supplier_award_id) ==$award->id ? 'selected' : '' }}>
                                {{ $award->award_no }} - {{$award->supplier->SupplierName ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Order Date <span class="text-danger">*</span></label>
                    <input type="date" name="order_date" class="form-control" value="{{ old('order_date', $data->order_date ? $data->order_date->format('Y-m-d') : '') }}" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Expected Delivery</label>
                    <input type="date" name="expected_delivery_date" class="form-control" value="{{ old('expected_delivery_date', $data->expected_delivery_date ? $data->expected_delivery_date->format('Y-m-d') : '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Payment Terms</label>
                    <input type="text" name="payment_terms" class="form-control" value="{{ old('payment_terms', $data->payment_terms) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Delivery Terms</label>
                    <input type="text" name="delivery_terms" class="form-control" value="{{ old('delivery_terms', $data->delivery_terms) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Order Notes / Instructions</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $data->notes) }}</textarea>
                </div>
            </div>

            {{-- ITEMS SECTION --}}
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h6 class="fw-bold text-primary mb-0"><i class="fas fa-boxes me-1"></i> Order Line Items</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addItemRow">
                    <i class="fas fa-plus me-1"></i> Add Item Row
                </button>
            </div>

            <div class="table-responsive items-wrapper mb-4" style="min-height: 260px;">
                <table class="table table-bordered align-middle" id="poItemsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 35%;">Product / Item <span class="text-danger">*</span></th>
                            <th style="width: 12%;">Qty <span class="text-danger">*</span></th>
                            <th style="width: 15%;">Unit Price <span class="text-danger">*</span></th>
                            <th style="width: 12%;">Tax Rate (%)</th>
                            <th style="width: 12%;">Discount</th>
                            <th style="width: 14%;">Line Total</th>
                            <th style="width: 5%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="poItemsBody">
                        @foreach($data->items as $index =>$item)
                        <tr class="item-row">
                            <td>
                                <select name="items[{{ $index }}][product_id]" class="form-select product-select" data-searchable data-placeholder="Search product..." required>
                                    <option value="">-- Select Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ $item->product_id ==$product->id ? 'selected' : '' }}>
                                            {{ $product->product_name ?? $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="items[{{ $index }}][description]" class="form-control form-control-sm mt-1" value="{{ $item->description }}" placeholder="Item description/specs (optional)">
                            </td>
                            <td>
                                <input type="number" step="0.001" name="items[{{ $index }}][ordered_quantity]" class="form-control qty-input" value="{{ $item->ordered_quantity }}" min="0.001" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[{{ $index }}][unit_price]" class="form-control price-input" value="{{ $item->unit_price }}" min="0" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[{{ $index }}][tax_rate]" class="form-control tax-input" value="{{ $item->tax_rate }}" min="0" max="100">
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[{{ $index }}][discount_amount]" class="form-control discount-input" value="{{ $item->discount_amount }}" min="0">
                            </td>
                            <td>
                                <input type="text" class="form-control line-total" value="{{ number_format($item->total_amount, 2) }}" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-row" {{ count($data->items) <= 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-end fw-bold">Subtotal:</td>
                            <td colspan="2" class="fw-bold" id="subtotalDisplay">0.00</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end fw-bold">Total Discount:</td>
                            <td colspan="2" class="fw-bold text-danger" id="discountDisplay">0.00</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end fw-bold">Total Tax:</td>
                            <td colspan="2" class="fw-bold text-info" id="taxDisplay">0.00</td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="5" class="text-end fw-bold fs-6">Grand Total:</td>
                            <td colspan="2" class="fw-bold text-success fs-6" id="grandTotalDisplay">0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('viewpurchaseOrder', encrypt($data->id)) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Purchase Order</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let rowIndex = {{ count($data->items) }};

    // Pre-rendered option list for clean dynamic row insertion
    const productOptionsHtml = `
        <option value="">-- Select Product --</option>
        @foreach($products as $product)
            <option value="{{ $product->id }}">{{ $product->product_name ?? $product->name }}</option>
        @endforeach
    `;

    document.getElementById('addItemRow').addEventListener('click', function () {
        const tbody = document.getElementById('poItemsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select product-select" data-searchable data-placeholder="Search product..." required>
                    ${productOptionsHtml}
                </select>
                <input type="text" name="items[${rowIndex}][description]" class="form-control form-control-sm mt-1" placeholder="Item description/specs (optional)">
            </td>
            <td>
                <input type="number" step="0.001" name="items[${rowIndex}][ordered_quantity]" class="form-control qty-input" value="1" min="0.001" required>
            </td>
            <td>
                <input type="number" step="0.01" name="items[${rowIndex}][unit_price]" class="form-control price-input" value="0" min="0" required>
            </td>
            <td>
                <input type="number" step="0.01" name="items[${rowIndex}][tax_rate]" class="form-control tax-input" value="0" min="0" max="100">
            </td>
            <td>
                <input type="number" step="0.01" name="items[${rowIndex}][discount_amount]" class="form-control discount-input" value="0" min="0">
            </td>
            <td>
                <input type="text" class="form-control line-total" value="0.00" readonly>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(newRow);

        // Turn the new row's <select data-searchable> into the searchable dropdown
        if (typeof window.initSearchableSelects === 'function') {
            window.initSearchableSelects(newRow);
        }

        rowIndex++;
        calculateTotals();
    });

    document.getElementById('poItemsTable').addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-row');
        if (!btn) return;

        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) {
            btn.closest('.item-row').remove();
            calculateTotals();
        } else {
            alert('At least one item is required.');
        }
    });

    document.getElementById('poItemsTable').addEventListener('input', function (e) {
        if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input') || e.target.classList.contains('tax-input') || e.target.classList.contains('discount-input')) {
            calculateTotals();
        }
    });

    function calculateTotals() {
        let overallSubtotal = 0;
        let overallTax = 0;
        let overallDiscount = 0;

        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const taxRate = parseFloat(row.querySelector('.tax-input').value) || 0;
            const discount = parseFloat(row.querySelector('.discount-input').value) || 0;

            const lineSubtotal = qty * price;
            const lineTax = Math.max(0, (lineSubtotal - discount) * (taxRate / 100));
            const lineTotal = (lineSubtotal - discount) + lineTax;

            row.querySelector('.line-total').value = lineTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            overallSubtotal += lineSubtotal;
            overallTax += lineTax;
            overallDiscount += discount;
        });

        const grandTotal = (overallSubtotal - overallDiscount) + overallTax;

        document.getElementById('subtotalDisplay').innerText = overallSubtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('discountDisplay').innerText = overallDiscount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('taxDisplay').innerText = overallTax.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('grandTotalDisplay').innerText = grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Initialize searchable selects for existing options on load
    if (typeof window.initSearchableSelects === 'function') {
        window.initSearchableSelects(document);
    }

    calculateTotals();
</script>
@endpush