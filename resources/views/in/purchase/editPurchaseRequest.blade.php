@extends('layouts.workingside')

@section('title', 'Edit Purchase Request')
@section('page-title', 'Edit Purchase Request')

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
            <i class="fas fa-pencil-alt"></i>
        </div>
        Edit Purchase Request ({{ $data->request_no }})
    </h3>

    <div>
        <a href="{{ route('viewpurchaseRequest', encrypt($data->id)) }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('updatepurchaseRequest', encrypt($data->id)) }}">
            @csrf

            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> Request General Information</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Procurement Type <span class="text-danger">*</span></label>
                    <select name="procurement_type_id" class="form-select" data-searchable data-placeholder="Search procurement type..." required>
                        <option value="">-- Select Procurement Type --</option>
                        @foreach($procurementTypes as $type)
                            <option value="{{ $type->id }}" {{ old('procurement_type_id', $data->procurement_type_id) ==$type->id ? 'selected' : '' }}>
                                {{ $type->procurement_name }} ({{$type->procurement_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Request Date <span class="text-danger">*</span></label>
                    <input type="date" name="request_date" class="form-control" value="{{ old('request_date', $data->request_date ? $data->request_date->format('Y-m-d') : '') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Required By Date</label>
                    <input type="date" name="required_date" class="form-control" value="{{ old('required_date', $data->required_date ? $data->required_date->format('Y-m-d') : '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Priority <span class="text-danger">*</span></label>
                    <select name="priority" class="form-select" data-searchable data-placeholder="Search priority..." required>
                        <option value="Low" {{ old('priority', $data->priority) == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority', $data->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority', $data->priority) == 'High' ? 'selected' : '' }}>High</option>
                        <option value="Urgent" {{ old('priority', $data->priority) == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Budget Allocation (TZS)</label>
                    <input type="number" step="0.01" name="budget_amount" class="form-control" value="{{ old('budget_amount', $data->budget_amount) }}" min="0">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Purpose / Overview</label>
                    <input type="text" name="purpose" class="form-control" value="{{ old('purpose', $data->purpose) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Justification / Reason</label>
                    <textarea name="justification" class="form-control" rows="2">{{ old('justification', $data->justification) }}</textarea>
                </div>
            </div>

            {{-- ITEMS SECTION --}}
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h6 class="fw-bold text-primary mb-0"><i class="fas fa-boxes me-1"></i> Requested Line Items</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addItemRow">
                    <i class="fas fa-plus me-1"></i> Add Row
                </button>
            </div>

            <div class="table-responsive items-wrapper mb-4" style="min-height: 260px;">
                <table class="table table-bordered align-middle" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40%;">Product / Item <span class="text-danger">*</span></th>
                            <th style="width: 15%;">Quantity <span class="text-danger">*</span></th>
                            <th style="width: 20%;">Est. Unit Price (TZS)</th>
                            <th style="width: 20%;">Est. Total (TZS)</th>
                            <th style="width: 5%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
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
                                <input type="text" name="items[{{ $index }}][description]" class="form-control form-control-sm mt-1" value="{{ $item->description }}" placeholder="Item notes/specs (optional)">
                            </td>
                            <td>
                                <input type="number" step="0.001" name="items[{{ $index }}][requested_quantity]" class="form-control qty-input" value="{{ $item->requested_quantity }}" min="0.001" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" name="items[{{ $index }}][estimated_unit_price]" class="form-control price-input" value="{{ $item->estimated_unit_price }}" min="0">
                            </td>
                            <td>
                                <input type="text" class="form-control line-total" value="{{ number_format($item->estimated_total, 2) }}" readonly>
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
                            <td colspan="3" class="text-end fw-bold">Overall Estimated Total:</td>
                            <td colspan="2" class="fw-bold text-success fs-6" id="grandTotalDisplay">0.00 TZS</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('viewpurchaseRequest', encrypt($data->id)) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Purchase Request</button>
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
        const tbody = document.getElementById('itemsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select product-select" data-searchable data-placeholder="Search product..." required>
                    ${productOptionsHtml}
                </select>
                <input type="text" name="items[${rowIndex}][description]" class="form-control form-control-sm mt-1" placeholder="Item notes/specs (optional)">
            </td>
            <td>
                <input type="number" step="0.001" name="items[${rowIndex}][requested_quantity]" class="form-control qty-input" value="1" min="0.001" required>
            </td>
            <td>
                <input type="number" step="0.01" name="items[${rowIndex}][estimated_unit_price]" class="form-control price-input" value="0" min="0">
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
        if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
            calculateTotals();
        }
    });

    function calculateTotals() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const total = qty * price;
            row.querySelector('.line-total').value = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            grandTotal += total;
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