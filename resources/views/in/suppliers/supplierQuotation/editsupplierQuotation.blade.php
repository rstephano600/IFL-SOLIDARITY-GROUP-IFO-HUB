@extends('layouts.workingside')

@section('title', 'Edit Supplier Quotation')
@section('page-title', 'Edit Supplier Quotation')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-edit"></i>
        </div>
        Edit Quotation: {{ $data->quotation_no }}
    </h3>

    <div>
        <a href="{{ route('supplierQuotation') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to Quotations
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updatesupplierQuotation', encrypt($data->id)) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h5 class="fw-bold mb-3 text-primary border-bottom pb-2">Header Information</h5>

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label">System Quotation No <span class="text-danger">*</span></label>
                    <input type="text" name="quotation_no" class="form-control @error('quotation_no') is-invalid @enderror" value="{{ old('quotation_no', $data->quotation_no) }}" required>
                    @error('quotation_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Supplier Quotation Ref No</label>
                    <input type="text" name="supplier_quotation_no" class="form-control @error('supplier_quotation_no') is-invalid @enderror" value="{{ old('supplier_quotation_no', $data->supplier_quotation_no) }}">
                    @error('supplier_quotation_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" data-searchable required>
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $data->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->supplier_name ?? $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" data-searchable required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $data->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Associated Tender</label>
                    <select name="tender_id" class="form-select @error('tender_id') is-invalid @enderror" data-searchable>
                        <option value="">-- Select Tender (Optional) --</option>
                        @foreach($tenders as $tender)
                            <option value="{{ $tender->id }}" {{ old('tender_id', $data->tender_id) == $tender->id ? 'selected' : '' }}>
                                {{ $tender->tender_no }} - {{$tender->tender_title }}
                            </option>
                        @endforeach
                    </select>
                    @error('tender_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Associated Purchase Request</label>
                    <select name="purchase_request_id" class="form-select @error('purchase_request_id') is-invalid @enderror" data-searchable>
                        <option value="">-- Select PR (Optional) --</option>
                        @foreach($purchaseRequests as$pr)
                            <option value="{{ $pr->id }}" {{ old('purchase_request_id', $data->purchase_request_id) ==$pr->id ? 'selected' : '' }}>
                                {{ $pr->pr_no ?? 'PR #'.$pr->id }}
                            </option>
                        @endforeach
                    </select>
                    @error('purchase_request_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">Quotation Date <span class="text-danger">*</span></label>
                    <input type="date" name="quotation_date" class="form-control @error('quotation_date') is-invalid @enderror" value="{{ old('quotation_date', \Carbon\Carbon::parse($data->quotation_date)->format('Y-m-d')) }}" required>
                    @error('quotation_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">Valid Until</label>
                    <input type="date" name="valid_until" class="form-control @error('valid_until') is-invalid @enderror" value="{{ old('valid_until', $data->valid_until ? \Carbon\Carbon::parse($data->valid_until)->format('Y-m-d') : '') }}">
                    @error('valid_until') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Currency</label>
                    <input type="text" name="currency_code" class="form-control @error('currency_code') is-invalid @enderror" value="{{ old('currency_code', $data->currency_code) }}">
                    @error('currency_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Quotation Status</label>
                    <select name="quotation_status" class="form-select @error('quotation_status') is-invalid @enderror">
                        <option value="Pending" {{ old('quotation_status', $data->quotation_status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ old('quotation_status', $data->quotation_status) == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ old('quotation_status', $data->quotation_status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('quotation_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Attachment Document</label>
                    <input type="file" name="attachment" class="form-control @error('attachment') is-invalid @enderror">
                    @if($data->attachment_path)
                        <small class="text-muted">Current Attachment: <a href="{{ asset('storage/' . $data->attachment_path) }}" target="_blank">View File</a></small>
                    @endif
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

            <div class="table-responsive mb-4">
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
                        @foreach($data->items as $index =>$item)
                        <tr class="item-row">
                            <td>
                                <select name="items[{{ $index }}][product_id]" class="form-select @error('product_id') is-invalid @enderror" data-searchable required>
                                    <option value="">-- Select Product --</option>
                                    @foreach($products as$product)
                                        <option value="{{ $product->id }}" {{ $item->product_id ==$product->id ? 'selected' : '' }}>
                                            {{ $product->product_name ?? $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="items[{{ $index }}][description]" class="form-control" value="{{ $item->description }}" placeholder="Item spec / description">
                            </td>
                            <td>
                                <input type="number" step="0.001" name="items[{{ $index }}][quantity]" class="form-control row-qty" value="{{ $item->quantity }}" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[{{ $index }}][unit_price]" class="form-control row-price" value="{{ $item->unit_price }}" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[{{ $index }}][discount_amount]" class="form-control row-discount" value="{{ $item->discount_amount }}">
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[{{ $index }}][tax_rate]" class="form-control row-taxrate" value="{{ $item->tax_rate }}">
                                <input type="hidden" name="items[{{ $index }}][tax_amount]" class="row-taxamount" value="{{ $item->tax_amount }}">
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[{{ $index }}][total_amount]" class="form-control row-total" value="{{ $item->total_amount }}" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Summary & Terms --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Payment Terms</label>
                    <textarea name="payment_terms" rows="2" class="form-control">{{ old('payment_terms', $data->payment_terms) }}</textarea>

                    <label class="form-label mt-2">Delivery Terms</label>
                    <textarea name="delivery_terms" rows="2" class="form-control">{{ old('delivery_terms', $data->delivery_terms) }}</textarea>

                    <label class="form-label mt-2">General Notes</label>
                    <textarea name="notes" rows="2" class="form-control">{{ old('notes', $data->notes) }}</textarea>
                </div>

                <div class="col-md-6">
                    <div class="card bg-light p-3">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Financial Summary</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <input type="number" step="0.01" name="subtotal" id="summarySubtotal" class="form-control form-control-sm text-end w-50" value="{{ $data->subtotal }}" readonly>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Discount:</span>
                            <input type="number" step="0.01" name="discount_amount" id="summaryDiscount" class="form-control form-control-sm text-end w-50" value="{{ $data->discount_amount }}" readonly>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Tax Amount:</span>
                            <input type="number" step="0.01" name="tax_amount" id="summaryTax" class="form-control form-control-sm text-end w-50" value="{{ $data->tax_amount }}" readonly>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery / Shipping Fee:</span>
                            <input type="number" step="0.01" name="delivery_amount" id="summaryDelivery" class="form-control form-control-sm text-end w-50" value="{{ $data->delivery_amount }}">
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Grand Total:</span>
                            <input type="number" step="0.01" name="total_amount" id="summaryGrandTotal" class="form-control text-end w-50 fw-bold border-0 bg-transparent" value="{{ $data->total_amount }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('supplierQuotation') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Quotation</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let rowIndex = {{ count($data->items) }};

    document.getElementById('addRowBtn').addEventListener('click', function() {
        const tableBody = document.getElementById('itemsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select" data-searchable required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->product_name ?? $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" name="items[${rowIndex}][description]" class="form-control" placeholder="Item spec / description">
            </td>
            <td>
                <input type="number" step="0.001" name="items[${rowIndex}][quantity]" class="form-control row-qty" value="1" required>
            </td>
            <td>
                <input type="number" step="0.01" name="items[${rowIndex}][unit_price]" class="form-control row-price" value="0.00" required>
            </td>
            <td>
                <input type="number" step="0.01" name="items[${rowIndex}][discount_amount]" class="form-control row-discount" value="0.00">
            </td>
            <td>
                <input type="number" step="0.01" name="items[${rowIndex}][tax_rate]" class="form-control row-taxrate" value="0.00">
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
        rowIndex++;
        calculateTotals();
    });

    document.getElementById('itemsTable').addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) {
                e.target.closest('.item-row').remove();
                calculateTotals();
            } else {
                alert('At least one item is required.');
            }
        }
    });

    document.getElementById('itemsTable').addEventListener('input', calculateTotals);
    document.getElementById('summaryDelivery').addEventListener('input', calculateTotals);

    function calculateTotals() {
        let grandSubtotal = 0;
        let grandDiscount = 0;
        let grandTax = 0;

        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.row-qty').value) || 0;
            const price = parseFloat(row.querySelector('.row-price').value) || 0;
            const discount = parseFloat(row.querySelector('.row-discount').value) || 0;
            const taxRate = parseFloat(row.querySelector('.row-taxrate').value) || 0;

            const baseAmount = qty * price;
            const netBase = Math.max(0, baseAmount - discount);
            const taxAmount = netBase * (taxRate / 100);
            const rowTotal = netBase + taxAmount;

            row.querySelector('.row-taxamount').value = taxAmount.toFixed(2);
            row.querySelector('.row-total').value = rowTotal.toFixed(2);

            grandSubtotal += baseAmount;
            grandDiscount += discount;
            grandTax += taxAmount;
        });

        const delivery = parseFloat(document.getElementById('summaryDelivery').value) || 0;
        const grandTotal = grandSubtotal - grandDiscount + grandTax + delivery;

        document.getElementById('summarySubtotal').value = grandSubtotal.toFixed(2);
        document.getElementById('summaryDiscount').value = grandDiscount.toFixed(2);
        document.getElementById('summaryTax').value = grandTax.toFixed(2);
        document.getElementById('summaryGrandTotal').value = grandTotal.toFixed(2);
    }
</script>
@endpush