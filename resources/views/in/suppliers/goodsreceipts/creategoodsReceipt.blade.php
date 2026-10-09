@extends('layouts.workingside')

@section('title', 'Receive Goods')
@section('page-title', 'Receive Goods')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-truck-ramp-box"></i>
        </div>
        Receive Goods for PO: {{ $purchaseOrder->purchase_order_no }}
    </h3>

    <div>
        <a href="{{ route('unreceivedPurchaseOrders') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>
</div>

<div class="arbif-card mb-4">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('storegoodsReceipt', encrypt($purchaseOrder->id)) }}" enctype="multipart/form-data" id="grnForm">
            @csrf

            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-file-signature me-1"></i> Delivery Header & Receipt Details</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Supplier</label>
                    <input type="text" class="form-control" value="{{ $purchaseOrder->supplier->SupplierName ?? 'N/A' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Receipt Date <span class="text-danger">*</span></label>
                    <input type="date" name="receipt_date" class="form-control" value="{{ old('receipt_date', date('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Supplier Delivery Note / Waybill No.</label>
                    <input type="text" name="supplier_delivery_note" class="form-control" value="{{ old('supplier_delivery_note') }}" placeholder="e.g. DN-90812">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Vehicle Registration No.</label>
                    <input type="text" name="vehicle_no" class="form-control" value="{{ old('vehicle_no') }}" placeholder="e.g. T 452 ABC">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Attach Delivery Note / Scanned GRN</label>
                    <input type="file" name="attachment" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Receiving Branch</label>
                    <input type="text" class="form-control" value="{{ $purchaseOrder->branch->branch_name ?? 'Central' }}" readonly>
                </div>

                <div class="col-12">
                    <label class="form-label">Inspection Remarks / Comments</label>
                    <textarea name="remarks" class="form-control" rows="2" placeholder="Overall delivery inspection notes...">{{ old('remarks') }}</textarea>
                </div>
            </div>

            {{-- PENDING ITEMS RECEIVING TABLE --}}
            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="fas fa-boxes me-1"></i> Receive Line Items</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20%;">Product Name</th>
                            <th style="width: 8%;">Ordered</th>
                            <th style="width: 8%;">Prev. Recv</th>
                            <th style="width: 8%;">Pending</th>
                            <th style="width: 12%;">Delivered Qty <span class="text-danger">*</span></th>
                            <th style="width: 12%;">Accepted Qty <span class="text-danger">*</span></th>
                            <th style="width: 10%;">Rejected Qty</th>
                            <th style="width: 12%;">Unit Price ({{ $purchaseOrder->currency_code }}) <span class="text-danger">*</span></th>
                            <th style="width: 10%;">Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingItems as $idx => $item)
                        <tr class="item-receiving-row">
                            <td>
                                <strong>{{ $item->product->product_name ?? 'Item' }}</strong>
                                <small class="d-block text-muted">Unit: {{ $item->unitOfMeasure->UnitName ?? 'N/A' }}</small>
                                <input type="hidden" name="items[{{ $idx }}][po_item_id]" value="{{ $item->id }}">
                            </td>
                            <td><span class="fw-bold">{{ number_format($item->ordered_quantity, 2) }}</span></td>
                            <td><span class="text-muted">{{ number_format($item->received_quantity, 2) }}</span></td>
                            <td><span class="badge bg-warning text-dark pending-qty">{{ number_format($item->pending_quantity, 2) }}</span></td>
                            
                            {{-- DELIVERED QUANTITY --}}
                            <td>
                                <input type="number" step="0.001" name="items[{{ $idx }}][received_qty]" class="form-control delivered-input" value="{{ old("items.$idx.received_qty", $item->pending_quantity) }}" min="0" max="{{ $item->pending_quantity }}" required>
                            </td>

                            {{-- ACCEPTED QUANTITY --}}
                            <td>
                                <input type="number" step="0.001" name="items[{{ $idx }}][accepted_qty]" class="form-control accepted-input" value="{{ old("items.$idx.accepted_qty", $item->pending_quantity) }}" min="0" max="{{ $item->pending_quantity }}" required>
                            </td>

                            {{-- REJECTED QUANTITY --}}
                            <td>
                                <input type="number" step="0.001" name="items[{{ $idx }}][rejected_qty]" class="form-control rejected-input" value="{{ old("items.$idx.rejected_qty", 0) }}" min="0" readonly>
                            </td>

                            {{-- UNIT PRICE (EDITABLE) --}}
                            <td>
                                <input type="number" step="0.01" name="items[{{ $idx }}][unit_price]" class="form-control price-input" value="{{ old("items.$idx.unit_price", $item->unit_price) }}" min="0" required>
                            </td>

                            {{-- LINE TOTAL DISPLAY --}}
                            <td>
                                <input type="text" class="form-control line-total" value="0.00" readonly>
                            </td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="9" class="p-2">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" name="items[{{ $idx }}][rejection_reason]" class="form-control form-control-sm" placeholder="Reason for rejection (if any)">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="items[{{ $idx }}][remarks]" class="form-control form-control-sm" placeholder="Item notes/batch number/expiry">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="8" class="text-end fw-bold fs-6">Received Items Total Value:</td>
                            <td class="fw-bold text-success fs-6" id="grandReceivedTotal">0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('unreceivedPurchaseOrders') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Submit Goods Receipt</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function updateRowCalculations() {
        let totalVal = 0;

        document.querySelectorAll('.item-receiving-row').forEach(row => {
            const pendingQty = parseFloat(row.querySelector('.pending-qty').innerText.replace(/,/g, '')) || 0;
            const deliveredInput = row.querySelector('.delivered-input');
            const acceptedInput = row.querySelector('.accepted-input');
            const rejectedInput = row.querySelector('.rejected-input');
            const priceInput = row.querySelector('.price-input');
            const totalDisplay = row.querySelector('.line-total');

            let delivered = parseFloat(deliveredInput.value) || 0;
            let accepted = parseFloat(acceptedInput.value) || 0;
            let price = parseFloat(priceInput.value) || 0;

            if (delivered > pendingQty) {
                delivered = pendingQty;
                deliveredInput.value = delivered;
            }

            if (accepted > delivered) {
                accepted = delivered;
                acceptedInput.value = accepted;
            }

            const rejected = Math.max(0, delivered - accepted);
            rejectedInput.value = rejected.toFixed(3);

            const lineTotal = accepted * price;
            totalDisplay.value = lineTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            totalVal += lineTotal;
        });

        document.getElementById('grandReceivedTotal').innerText = totalVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' {{ $purchaseOrder->currency_code }}';
    }

    document.querySelectorAll('.delivered-input, .accepted-input, .price-input').forEach(input => {
        input.addEventListener('input', updateRowCalculations);
    });

    updateRowCalculations();
</script>
@endpush