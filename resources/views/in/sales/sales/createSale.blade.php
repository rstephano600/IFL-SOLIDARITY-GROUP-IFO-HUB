@extends('layouts.workingside')

@section('title', 'Create New Sale')
@section('page-title', 'Create New Sale')

@push('styles')
<style>
    /* Let the searchable dropdown show outside the table wrapper */
    .items-wrapper { overflow: visible; }
    #itemsTable td { position: relative; }
    #itemsTable .searchable-dropdown { min-width: 220px; }
</style>
@endpush

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-cart-plus"></i>
        </div>
        New Sale Transaction
    </h3>

    <div>
        <a href="{{ route('sales') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('storeSale') }}" id="saleForm">
            @csrf

            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> General Sale Details</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" data-searchable data-placeholder="Search branch..." required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sales Type <span class="text-danger">*</span></label>
                    <select name="sales_type_id" class="form-select" data-searchable data-placeholder="Search sales type..." required>
                        <option value="">-- Select Sales Type --</option>
                        @foreach($salesTypes as $type)
                            <option value="{{ $type->id }}" {{ old('sales_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->type_name }} ({{$type->type_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Customer</label>
                    <select name="customer_id" class="form-select" data-searchable data-placeholder="Search customer...">
                        <option value="">-- Walk-In Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->customer_name }} ({{$customer->customer_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sale Date <span class="text-danger">*</span></label>
                    <input type="date" name="sale_date" class="form-control" value="{{ old('sale_date', date('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Payment Terms <span class="text-danger">*</span></label>
                    <select name="payment_terms" class="form-select" data-searchable data-placeholder="Search payment terms..." required>
                        <option value="IMMEDIATE" {{ old('payment_terms') == 'IMMEDIATE' ? 'selected' : '' }}>IMMEDIATE</option>
                        <option value="CREDIT" {{ old('payment_terms') == 'CREDIT' ? 'selected' : '' }}>CREDIT</option>
                        <option value="COMPANY_ACCOUNT" {{ old('payment_terms') == 'COMPANY_ACCOUNT' ? 'selected' : '' }}>COMPANY ACCOUNT</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Currency Code</label>
                    <input type="text" name="currency_code" class="form-control" value="{{ old('currency_code', 'TZS') }}" required>
                </div>
            </div>

            {{-- ITEMS SECTION --}}
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h6 class="fw-bold text-primary mb-0"><i class="fas fa-boxes me-1"></i> Sale Line Items</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addItemRow">
                    <i class="fas fa-plus me-1"></i> Add Row
                </button>
            </div>

            <div class="table-responsive items-wrapper mb-4" style="min-height: 260px;">
                <table class="table table-bordered align-middle" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 35%;">Product / Item <span class="text-danger">*</span></th>
                            <th style="width: 15%;">Quantity <span class="text-danger">*</span></th>
                            <th style="width: 18%;">Unit Price (TZS) <span class="text-danger">*</span></th>
                            <th style="width: 12%;">Discount (TZS)</th>
                            <th style="width: 15%;">Line Total (TZS)</th>
                            <th style="width: 5%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <tr class="item-row">
                            <td>
                                <select name="items[0][product_id]" class="form-select product-select" data-searchable data-placeholder="Search product..." required>
                                    <option value="">-- Select Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->product_name ?? $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="items[0][notes]" class="form-control form-control-sm mt-1" placeholder="Item notes/specs (optional)">
                            </td>
                            <td>
                                <input type="number" step="0.001" name="items[0][quantity]" class="form-control qty-input" value="1" min="0.001" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[0][unit_price]" class="form-control price-input" value="0" min="0" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[0][discount_amount]" class="form-control discount-input" value="0" min="0">
                            </td>
                            <td>
                                <input type="text" class="form-control line-total" value="0.00" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Grand Total:</td>
                            <td colspan="2" class="fw-bold text-success fs-6" id="grandTotalDisplay">0.00 TZS</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-wallet me-1"></i> Payment Settlement</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Initial Payment Amount (TZS)</label>
                    <input type="number" step="0.01" name="initial_payment_amount" class="form-control" value="{{ old('initial_payment_amount', 0) }}" min="0">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Transaction / Payment Ref.</label>
                    <input type="text" name="reference_transaction" class="form-control" value="{{ old('reference_transaction') }}" placeholder="e.g. MPESA / CHEQUE NO">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sale Notes</label>
                    <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="Additional comments...">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('sales') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Process & Complete Sale</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let rowIndex = 1;

    // Pre-rendered option list for dynamic row insertion
    const productOptionsHtml = `
        <option value="">-- Select Product --</option>
        @foreach($products as $product)
            <option value="{{ $product->id }}">{{ $product->product_name ?? $product->name }}</option>
        @endforeach
    `;

    document.getElementById('addItemRow').addEventListener('click', function () {
        const tbody = document.getElementById('itemsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select product-select" data-searchable data-placeholder="Search product..." required>
                    ${productOptionsHtml}
                </select>
                <input type="text" name="items[${rowIndex}][notes]" class="form-control form-control-sm mt-1" placeholder="Item notes/specs (optional)">
            </td>
            <td>
                <input type="number" step="0.001" name="items[${rowIndex}][quantity]" class="form-control qty-input" value="1" min="0.001" required>
            </td>
            <td>
                <input type="number" step="0.01" name="items[${rowIndex}][unit_price]" class="form-control price-input" value="0" min="0" required>
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

        // Initialize searchable select for the newly added row
        if (typeof window.initSearchableSelects === 'function') {
            window.initSearchableSelects(newRow);
        }

        rowIndex++;
        calculateTotals();
    });

    document.getElementById('itemsTable').addEventListener('click', function (e) {
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

    document.getElementById('itemsTable').addEventListener('input', function (e) {
        if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input') || e.target.classList.contains('discount-input')) {
            calculateTotals();
        }
    });

    function calculateTotals() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const discount = parseFloat(row.querySelector('.discount-input').value) || 0;
            
            const lineTotal = (qty * price) - discount;
            const finalLineTotal = lineTotal > 0 ? lineTotal : 0;

            row.querySelector('.line-total').value = finalLineTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            grandTotal += finalLineTotal;
        });
        document.getElementById('grandTotalDisplay').innerText = grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' TZS';
    }

    // Initialize searchable selects for existing options on load
    if (typeof window.initSearchableSelects === 'function') {
        window.initSearchableSelects(document);
    }

    calculateTotals();
</script>
@endpush