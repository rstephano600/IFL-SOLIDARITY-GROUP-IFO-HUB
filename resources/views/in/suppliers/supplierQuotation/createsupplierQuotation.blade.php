@extends('layouts.workingside')

@section('title', 'Create Supplier Quotation')
@section('page-title', 'Create Supplier Quotation')

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
            <i class="fas fa-plus-circle"></i>
        </div>
        New Supplier Quotation
    </h3>

    <div>
        <a href="{{ route('supplierQuotation') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to Quotations
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('storesupplierQuotation') }}" enctype="multipart/form-data">
            @csrf

            <h5 class="fw-bold mb-3 text-primary border-bottom pb-2">Header Information</h5>

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label">System Quotation No <span class="text-danger">*</span></label>
                    <input type="text" name="quotation_no" class="form-control @error('quotation_no') is-invalid @enderror" value="{{ old('quotation_no', 'SQ-'.time()) }}" required>
                    @error('quotation_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Supplier Quotation Ref No</label>
                    <input type="text" name="supplier_quotation_no" class="form-control @error('supplier_quotation_no') is-invalid @enderror" value="{{ old('supplier_quotation_no') }}">
                    @error('supplier_quotation_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select" data-searchable data-placeholder="Search supplier..." required>
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->SupplierName ?? $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" data-searchable data-placeholder="Search branch..." required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Associated Tender</label>
                    <select name="tender_id" class="form-select" data-searchable data-placeholder="Search tender (optional)...">
                        <option value="">-- Select Tender (Optional) --</option>
                        @foreach($tenders as $tender)
                            <option value="{{ $tender->id }}" {{ old('tender_id') == $tender->id ? 'selected' : '' }}>
                                {{ $tender->tender_no }} - {{ $tender->tender_title }}
                            </option>
                        @endforeach
                    </select>
                    @error('tender_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Associated Purchase Request</label>
                    <select name="purchase_request_id" class="form-select" data-searchable data-placeholder="Search PR (optional)...">
                        <option value="">-- Select PR (Optional) --</option>
                        @foreach($purchaseRequests as $pr)
                            <option value="{{ $pr->id }}" {{ old('purchase_request_id') == $pr->id ? 'selected' : '' }}>
                                {{ $pr->pr_no ?? 'PR #'.$pr->id }}
                            </option>
                        @endforeach
                    </select>
                    @error('purchase_request_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">Quotation Date <span class="text-danger">*</span></label>
                    <input type="date" name="quotation_date" class="form-control @error('quotation_date') is-invalid @enderror" value="{{ old('quotation_date', date('Y-m-d')) }}" required>
                    @error('quotation_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">Valid Until</label>
                    <input type="date" name="valid_until" class="form-control @error('valid_until') is-invalid @enderror" value="{{ old('valid_until') }}">
                    @error('valid_until') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Currency</label>
                    <input type="text" name="currency_code" class="form-control @error('currency_code') is-invalid @enderror" value="{{ old('currency_code', 'TZS') }}">
                    @error('currency_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Quotation Status</label>
                    <select name="quotation_status" class="form-select @error('quotation_status') is-invalid @enderror">
                        <option value="Pending" {{ old('quotation_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ old('quotation_status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ old('quotation_status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('quotation_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Attachment Document (PDF, Image, Doc)</label>
                    <input type="file" name="attachment" class="form-control @error('attachment') is-invalid @enderror">
                    @error('attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Line Items --}}
            <h5 class="fw-bold mb-3 text-primary border-bottom pb-2 d-flex justify-content-between align-items-center">
                <span>Quotation Items</span>
                <button type="button" class="btn btn-sm btn-success" id="addRowBtn">
                    <i class="fas fa-plus me-1"></i> Add Item Row
                </button>
            </h5>

            @if($errors->has('items') || $errors->has('items.*'))
                <div class="alert alert-danger py-2">
                    @foreach($errors->get('items.*') as $messages)
                        @foreach($messages as $message)
                            <div>{{ $message }}</div>
                        @endforeach
                    @endforeach
                    @error('items') <div>{{ $message }}</div> @enderror
                </div>
            @endif

            <div class="table-responsive items-wrapper mb-4" style="min-height: 260px;">
                <table class="table table-bordered align-middle" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 25%;">Product <span class="text-danger">*</span></th>
                            <th>Description</th>
                            <th style="width: 10%;">Qty <span class="text-danger">*</span></th>
                            <th style="width: 12%;">Unit Price <span class="text-danger">*</span></th>
                            <th style="width: 10%;">Discount</th>
                            <th style="width: 10%;">Tax Rate (%)</th>
                            <th style="width: 12%;">Total Amount</th>
                            <th style="width: 5%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <tr class="item-row">
                            <td>
                                <select name="items[0][product_id]" class="form-select" data-searchable data-placeholder="Search product..." required>
                                    <option value="">-- Select Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->product_name ?? $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="items[0][description]" class="form-control" placeholder="Item spec / description">
                            </td>
                            <td>
                                <input type="number" step="0.001" min="0" name="items[0][quantity]" class="form-control row-qty" value="1" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" name="items[0][unit_price]" class="form-control row-price" value="0.00" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" name="items[0][discount_amount]" class="form-control row-discount" value="0.00">
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" name="items[0][tax_rate]" class="form-control row-taxrate" value="0.00">
                                <input type="hidden" name="items[0][tax_amount]" class="row-taxamount" value="0.00">
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[0][total_amount]" class="form-control row-total" value="0.00" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Summary & Terms --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Payment Terms</label>
                    <textarea name="payment_terms" rows="2" class="form-control" placeholder="e.g. Net 30 days, 50% Advance">{{ old('payment_terms') }}</textarea>

                    <label class="form-label mt-2">Delivery Terms</label>
                    <textarea name="delivery_terms" rows="2" class="form-control" placeholder="e.g. FOB Destination, 14 Days Lead Time">{{ old('delivery_terms') }}</textarea>

                    <label class="form-label mt-2">General Notes</label>
                    <textarea name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
                </div>

                <div class="col-md-6">
                    <div class="card bg-light p-3">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Financial Summary</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <input type="number" step="0.01" name="subtotal" id="summarySubtotal" class="form-control form-control-sm text-end w-50" value="0.00" readonly>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Discount:</span>
                            <input type="number" step="0.01" name="discount_amount" id="summaryDiscount" class="form-control form-control-sm text-end w-50" value="0.00" readonly>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Tax Amount:</span>
                            <input type="number" step="0.01" name="tax_amount" id="summaryTax" class="form-control form-control-sm text-end w-50" value="0.00" readonly>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery / Shipping Fee:</span>
                            <input type="number" step="0.01" min="0" name="delivery_amount" id="summaryDelivery" class="form-control form-control-sm text-end w-50" value="0.00">
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Grand Total:</span>
                            <input type="number" step="0.01" name="total_amount" id="summaryGrandTotal" class="form-control text-end w-50 fw-bold border-0 bg-transparent" value="0.00" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('supplierQuotation') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Save Quotation</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let rowIndex = 1;

    // Product options built once, reused for every new row
    const productOptionsHtml = `
        <option value="">-- Select Product --</option>
        @foreach($products as $product)
            <option value="{{ $product->id }}">{{ $product->product_name ?? $product->name }}</option>
        @endforeach
    `;

    // Add new dynamic row
    document.getElementById('addRowBtn').addEventListener('click', function () {
        const tableBody = document.getElementById('itemsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select" data-searchable data-placeholder="Search product..." required>
                    ${productOptionsHtml}
                </select>
            </td>
            <td>
                <input type="text" name="items[${rowIndex}][description]" class="form-control" placeholder="Item spec / description">
            </td>
            <td>
                <input type="number" step="0.001" min="0" name="items[${rowIndex}][quantity]" class="form-control row-qty" value="1" required>
            </td>
            <td>
                <input type="number" step="0.01" min="0" name="items[${rowIndex}][unit_price]" class="form-control row-price" value="0.00" required>
            </td>
            <td>
                <input type="number" step="0.01" min="0" name="items[${rowIndex}][discount_amount]" class="form-control row-discount" value="0.00">
            </td>
            <td>
                <input type="number" step="0.01" min="0" name="items[${rowIndex}][tax_rate]" class="form-control row-taxrate" value="0.00">
                <input type="hidden" name="items[${rowIndex}][tax_amount]" class="row-taxamount" value="0.00">
            </td>
            <td>
                <input type="number" step="0.01" name="items[${rowIndex}][total_amount]" class="form-control row-total" value="0.00" readonly>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="fas fa-times"></i></button>
            </td>
        `;
        tableBody.appendChild(newRow);

        // Turn the new row's <select data-searchable> into the searchable dropdown
        if (typeof window.initSearchableSelects === 'function') {
            window.initSearchableSelects(newRow);
        }

        rowIndex++;
        calculateTotals();
    });

    // Remove row
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

    // Recalculate values on change
    document.getElementById('itemsTable').addEventListener('input', calculateTotals);
    document.getElementById('summaryDelivery').addEventListener('input', calculateTotals);

    function calculateTotals() {
        let grandSubtotal = 0;
        let grandDiscount = 0;
        let grandTax = 0;

        document.querySelectorAll('.item-row').forEach(row => {
            const qty      = parseFloat(row.querySelector('.row-qty').value) || 0;
            const price    = parseFloat(row.querySelector('.row-price').value) || 0;
            const discount = parseFloat(row.querySelector('.row-discount').value) || 0;
            const taxRate  = parseFloat(row.querySelector('.row-taxrate').value) || 0;

            const baseAmount = qty * price;
            const netBase    = Math.max(0, baseAmount - discount);
            const taxAmount  = netBase * (taxRate / 100);
            const rowTotal   = netBase + taxAmount;

            row.querySelector('.row-taxamount').value = taxAmount.toFixed(2);
            row.querySelector('.row-total').value     = rowTotal.toFixed(2);

            grandSubtotal += baseAmount;
            grandDiscount += discount;
            grandTax      += taxAmount;
        });

        const delivery   = parseFloat(document.getElementById('summaryDelivery').value) || 0;
        const grandTotal = grandSubtotal - grandDiscount + grandTax + delivery;

        document.getElementById('summarySubtotal').value   = grandSubtotal.toFixed(2);
        document.getElementById('summaryDiscount').value   = grandDiscount.toFixed(2);
        document.getElementById('summaryTax').value        = grandTax.toFixed(2);
        document.getElementById('summaryGrandTotal').value = grandTotal.toFixed(2);
    }
</script>
@endpush