@extends('layouts.workingside')

@section('title', 'Goods Receipt Details')
@section('page-title', 'Goods Receipt Details')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-clipboard-check"></i>
        </div>
        GRN Profile ({{ $data->receipt_no }})
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('goodsReceipt') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to GRNs
        </a>

        @if($data->attachment_path)
            <a href="{{ asset('storage/' . $data->attachment_path) }}" target="_blank" class="btn btn-outline-primary px-3" style="border-radius: 4px;">
                <i class="fas fa-paperclip me-1"></i> Delivery Attachment
            </a>
        @endif
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                    <div>
                        <h4 class="fw-bold text-primary mb-1">{{ $data->receipt_no }}</h4>
                        <span class="arbif-badge arbif-badge-navy">{{ $data->supplier->SupplierName ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="badge fs-6 bg-success">
                            {{ $data->receipt_status }}
                        </span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Purchase Order Reference:</strong>
                        <p class="text-primary fw-bold mb-2">{{ $data->purchaseOrder->purchase_order_no ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Receipt Date:</strong>
                        <p class="text-muted mb-2">{{ $data->receipt_date ? $data->receipt_date->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Delivery Note / Waybill:</strong>
                        <p class="text-muted mb-2">{{ $data->supplier_delivery_note ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Vehicle Registration No:</strong>
                        <p class="text-muted mb-2">{{ $data->vehicle_no ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Branch:</strong>
                        <p class="text-muted mb-2">{{ $data->branch->branch_name ?? 'Central' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Inspection Status:</strong>
                        <p class="mb-2"><span class="badge bg-info text-dark">{{ $data->inspection_status }}</span></p>
                    </div>

                    <div class="col-12">
                        <strong>Remarks / Inspection Notes:</strong>
                        <p class="text-muted mb-0">{{ $data->remarks ?? 'No notes recorded.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- RECEIVED LINE ITEMS TABLE --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fas fa-boxes me-2 text-primary"></i>Received Items Breakdown</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Unit</th>
                                <th>Delivered Qty</th>
                                <th>Accepted Qty</th>
                                <th>Rejected Qty</th>
                                <th>Unit Price</th>
                                <th>Total Accepted Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grandTotal = 0; @endphp
                            @foreach($data->items as $idx => $item)
                            @php 
                                $lineVal = $item->accepted_quantity * ($item->purchaseOrderItem->unit_price ?? 0);
                                $grandTotal += $lineVal;
                            @endphp
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>
                                    <strong>{{ $item->product->product_name ?? 'Item' }}</strong>
                                    @if($item->rejection_reason)
                                        <div class="small text-danger">Rejection Note: {{ $item->rejection_reason }}</div>
                                    @endif
                                </td>
                                <td>{{ $item->unitOfMeasure->UnitName ?? 'N/A' }}</td>
                                <td>{{ number_format($item->received_quantity, 2) }}</td>
                                <td><span class="badge bg-success">{{ number_format($item->accepted_quantity, 2) }}</span></td>
                                <td>
                                    @if($item->rejected_quantity > 0)
                                        <span class="badge bg-danger">{{ number_format($item->rejected_quantity, 2) }}</span>
                                    @else
                                        <span class="text-muted">0.00</span>
                                    @endif
                                </td>
                                <td>{{ number_format($item->purchaseOrderItem->unit_price ?? 0, 2) }}</td>
                                <td class="fw-bold text-success">{{ number_format($lineVal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="7" class="text-end fw-bold fs-6">Grand Received Value:</td>
                                <td class="fw-bold text-success fs-6">{{ number_format($grandTotal, 2) }} {{ $data->purchaseOrder->currency_code ?? 'TZS' }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Audit Information</h6>

                <div class="mb-3">
                    <small class="text-muted d-block">Received By</small>
                    <span class="fw-bold text-dark">{{ $data->receivedBy->name ?? 'N/A' }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Created By</small>
                    <span class="fw-bold text-dark">{{ $data->creator->name ?? 'N/A' }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Created At</small>
                    <span class="text-muted">{{ $data->created_at ? $data->created_at->format('d M, Y H:i A') : 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection