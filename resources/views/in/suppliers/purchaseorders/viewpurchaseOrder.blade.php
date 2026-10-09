@extends('layouts.workingside')

@section('title', 'Purchase Order Profile')
@section('page-title', 'Purchase Order Profile')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-invoice"></i>
        </div>
        PO Details ({{ $data->purchase_order_no }})
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('purchaseOrder') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>

        @if(!in_array($data->order_status, ['Approved', 'Issued', 'Completed']))
            <a href="{{ route('editpurchaseOrder', encrypt($data->id)) }}" class="arbif-btn-edit text-decoration-none">
                <i class="fas fa-pencil me-1"></i> Edit PO
            </a>

            <button class="btn btn-success px-3" style="border-radius: 4px;" data-bs-toggle="modal" data-bs-target="#approvePOModal">
                <i class="fas fa-check me-1"></i> Approve PO
            </button>

            <button class="btn btn-danger px-3" style="border-radius: 4px;" data-bs-toggle="modal" data-bs-target="#rejectPOModal">
                <i class="fas fa-times me-1"></i> Cancel PO
            </button>
        @endif
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                    <div>
                        <h4 class="fw-bold text-primary mb-1">{{ $data->purchase_order_no }}</h4>
                        <span class="arbif-badge arbif-badge-navy">{{ $data->supplier->SupplierName ?? 'N/A' }}</span>
                    </div>
                    <div>
                        @php
                            $statusClasses = [
                                'Draft'     => 'bg-secondary',
                                'Approved'  => 'bg-success',
                                'Issued'    => 'bg-info text-dark',
                                'Completed' => 'bg-primary',
                                'Cancelled' => 'bg-danger',
                            ];
                        @endphp
                        <span class="badge fs-6 {{ $statusClasses[$data->order_status] ?? 'bg-secondary' }}">
                            {{ $data->order_status }}
                        </span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Order Date:</strong>
                        <p class="text-muted mb-2">{{ $data->order_date ? $data->order_date->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Expected Delivery:</strong>
                        <p class="text-muted mb-2">{{ $data->expected_delivery_date ? $data->expected_delivery_date->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Branch:</strong>
                        <p class="text-muted mb-2">{{ $data->branch->branch_name ?? 'Central / Global' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Company:</strong>
                        <p class="text-muted mb-2">{{ $data->company->company_name ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Payment Terms:</strong>
                        <p class="text-muted mb-2">{{ $data->payment_terms ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Delivery Terms:</strong>
                        <p class="text-muted mb-2">{{ $data->delivery_terms ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Purchase Request Reference:</strong>
                        <p class="text-muted mb-2">{{ $data->purchaseRequest->request_no ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Award Reference:</strong>
                        <p class="text-muted mb-2">{{ $data->supplierAward->award_no ?? 'N/A' }}</p>
                    </div>

                    <div class="col-12">
                        <strong>Notes / Special Instructions:</strong>
                        <p class="text-muted mb-0">{{ $data->notes ?? 'No special notes.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ITEMS TABLE --}}
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fas fa-boxes me-2 text-primary"></i>Ordered Line Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product / Description</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Tax</th>
                                <th>Discount</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->items as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>
                                    <strong>{{ $item->product->product_name ?? 'Custom Item' }}</strong>
                                    @if($item->description)
                                        <div class="small text-muted">{{ $item->description }}</div>
                                    @endif
                                </td>
                                <td>{{ $item->unitOfMeasure->UnitName ?? 'N/A' }}</td>
                                <td>{{ number_format($item->ordered_quantity, 2) }}</td>
                                <td>{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ number_format($item->tax_amount, 2) }} ({{ $item->tax_rate }}%)</td>
                                <td class="text-danger">{{ number_format($item->discount_amount, 2) }}</td>
                                <td class="fw-bold">{{ number_format($item->total_amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7" class="text-end fw-bold">Subtotal:</td>
                                <td class="fw-bold">{{ number_format($data->subtotal, 2) }} {{ $data->currency_code }}</td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-end fw-bold">Total Discount:</td>
                                <td class="fw-bold text-danger">-{{ number_format($data->discount_amount, 2) }} {{ $data->currency_code }}</td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-end fw-bold">Total Tax:</td>
                                <td class="fw-bold text-info">+{{ number_format($data->tax_amount, 2) }} {{ $data->currency_code }}</td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="7" class="text-end fw-bold fs-6">Grand Total:</td>
                                <td class="fw-bold text-success fs-6">{{ number_format($data->total_amount, 2) }} {{ $data->currency_code }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Approval Metadata</h6>

                <div class="mb-3">
                    <small class="text-muted d-block">Approved By</small>
                    <span class="fw-bold text-dark">{{ $data->approver->name ?? 'Not Approved' }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Approved At</small>
                    <span class="text-muted">{{ $data->approved_at ? $data->approved_at->format('d M, Y H:i A') : 'N/A' }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Created By</small>
                    <span class="fw-bold text-dark">{{ $data->creator->name ?? 'N/A' }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Created At</small>
                    <span class="text-muted">{{ $data->created_at ? $data->created_at->format('d M, Y H:i A') : 'N/A' }}</span>
                </div>

                <div>
                    <small class="text-muted d-block">Last Updated By</small>
                    <span class="fw-bold text-dark">{{ $data->updater->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        {{-- RELATED GOODS RECEIPTS --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fas fa-clipboard-check me-2 text-primary"></i>Goods Receipts</h6>
                <div class="list-group list-group-flush">
                    @forelse($data->goodsReceipts as $gr)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">{{ $gr->grn_number ?? 'GRN #' . $gr->id }}</span>
                                <span class="badge bg-success">{{ $gr->Status ?? 'Received' }}</span>
                            </div>
                            <small class="text-muted d-block">{{ $gr->created_at ? $gr->created_at->format('d M, Y') : '' }}</small>
                        </div>
                    @empty
                        <small class="text-muted">No goods receipts registered yet.</small>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- APPROVAL MODAL --}}
<div class="modal fade" id="approvePOModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('approvepurchaseOrder', encrypt($data->id)) }}">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-check-circle me-1"></i> Approve Purchase Order</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Approval Remarks / Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Optional approval remarks...">{{ $data->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Approval</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- REJECTION/CANCELLATION MODAL --}}
<div class="modal fade" id="rejectPOModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('rejectpurchaseOrder', encrypt($data->id)) }}">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-times-circle me-1"></i> Cancel Purchase Order</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Reason for Cancellation <span class="text-danger">*</span></label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="State clear reasons for cancelling this purchase order..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection