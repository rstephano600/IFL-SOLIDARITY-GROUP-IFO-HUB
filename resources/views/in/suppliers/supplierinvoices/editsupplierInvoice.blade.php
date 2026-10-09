@extends('layouts.workingside')

@section('title', 'Edit Supplier Invoice')
@section('page-title', 'Edit Supplier Invoice')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-pencil-alt"></i>
        </div>
        Edit Invoice ({{ $data->invoice_no }})
    </h3>

    <div>
        <a href="{{ route('viewsupplierInvoice', encrypt($data->id)) }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('updatesupplierInvoice', encrypt($data->id)) }}">
            @csrf

            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> Invoice Details</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Supplier Invoice Number <span class="text-danger">*</span></label>
                    <input type="text" name="supplier_invoice_no" class="form-control" value="{{ old('supplier_invoice_no', $data->supplier_invoice_no) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Currency <span class="text-danger">*</span></label>
                    <input type="text" name="currency_code" class="form-control" value="{{ old('currency_code', $data->currency_code) }}" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                    <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', $data->invoice_date ? $data->invoice_date->format('Y-m-d') : '') }}" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Due Date <span class="text-danger">*</span></label>
                    <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $data->due_date ? $data->due_date->format('Y-m-d') : '') }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $data->notes) }}</textarea>
                </div>
            </div>

            {{-- INVOICE ITEMS TABLE --}}
            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="fas fa-boxes me-1"></i> Line Items</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 30%;">Product Name</th>
                            <th style="width: 12%;">Qty <span class="text-danger">*</span></th>
                            <th style="width: 15%;">Unit Price <span class="text-danger">*</span></th>
                            <th style="width: 12%;">Tax Rate (%)</th>
                            <th style="width: 12%;">Discount</th>
                            <th style="width: 19%;">Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->items as $idx => $item)
                        <tr class="item-invoice-row">
                            <td>
                                <strong>{{ $item->product->product_name ?? 'Product' }}</strong>
                                <input type="hidden" name="items[{{ $idx }}][item_id]" value="{{ $item->id }}">
                            </td>
                            <td>
                                <input type="number" step="0.001" name="items[{{ $idx }}][quantity]" class="form-control qty-input" value="{{ old("items.$idx.quantity", $item->quantity) }}" min="0.001" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[{{ $idx }}][unit_price]" class="form-control price-input" value="{{ old("items.$idx.unit_price", $item->unit_price) }}" min="0" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[{{ $idx }}][tax_rate]" class="form-control tax-input" value="{{ old("items.$idx.tax_rate", $item->tax_rate) }}" min="0" max="100">
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[{{ $idx }}][discount_amount]" class="form-control discount-input" value="{{ old("items.$idx.discount_amount", $item->discount_amount) }}" min="0">
                            </td>
                            <td>
                                <input type="text" class="form-control line-total" value="{{ number_format($item->total_amount, 2) }}" readonly>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-end fw-bold">Subtotal:</td>
                            <td class="fw-bold" id="subtotalDisplay">0.00</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end fw-bold">Total Discount:</td>
                            <td class="fw-bold text-danger" id="discountDisplay">0.00</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end fw-bold">Total Tax:</td>
                            <td class="fw-bold text-info" id="taxDisplay">0.00</td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="5" class="text-end fw-bold fs-6">Grand Total:</td>
                            <td class="fw-bold text-success fs-6" id="grandTotalDisplay">0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('viewsupplierInvoice', encrypt($data->id)) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Invoice</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function calculateInvoiceTotals() {
        let subtotal = 0;
        let taxTotal = 0;
        let discountTotal = 0;

        document.querySelectorAll('.item-invoice-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const taxRate = parseFloat(row.querySelector('.tax-input').value) || 0;
            const discount = parseFloat(row.querySelector('.discount-input').value) || 0;

            const lineSubtotal = qty * price;
            const lineTax = Math.max(0, (lineSubtotal - discount) * (taxRate / 100));
            const lineTotal = (lineSubtotal - discount) + lineTax;

            row.querySelector('.line-total').value = lineTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            subtotal += lineSubtotal;
            taxTotal += lineTax;
            discountTotal += discount;
        });

        const grandTotal = (subtotal - discountTotal) + taxTotal;

        document.getElementById('subtotalDisplay').innerText = subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('discountDisplay').innerText = discountTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('taxDisplay').innerText = taxTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('grandTotalDisplay').innerText = grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    document.querySelectorAll('.qty-input, .price-input, .tax-input, .discount-input').forEach(input => {
        input.addEventListener('input', calculateInvoiceTotals);
    });

    calculateInvoiceTotals();
</script>
@endpush