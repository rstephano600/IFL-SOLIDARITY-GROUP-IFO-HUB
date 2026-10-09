@extends('layouts.workingside')

@section('title', 'Supplier Invoice Profile')
@section('page-title', 'Supplier Invoice Profile')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        Invoice Profile ({{ $data->invoice_no }})
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('supplierInvoice') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>

        @if($data->verification_status !== 'Verified' && $data->paid_amount == 0)
            <a href="{{ route('editsupplierInvoice', encrypt($data->id)) }}" class="arbif-btn-edit text-decoration-none">
                <i class="fas fa-pencil me-1"></i> Edit Invoice
            </a>

            <button class="btn btn-success px-3" style="border-radius: 4px;" data-bs-toggle="modal" data-bs-target="#verifyInvoiceModal">
                <i class="fas fa-check-double me-1"></i> Verify Invoice
            </button>
        @endif

        @if($data->attachment_path)
            <a href="{{ asset('storage/' . $data->attachment_path) }}" target="_blank" class="btn btn-outline-primary px-3" style="border-radius: 4px;">
                <i class="fas fa-paperclip me-1"></i> Scanned Invoice
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
                        <h4 class="fw-bold text-primary mb-1">{{ $data->invoice_no }}</h4>
                        <span class="arbif-badge arbif-badge-navy">Supp. Ref: {{ $data->supplier_invoice_no }}</span>
                    </div>
                    <div>
                        <span class="badge fs-6 {{ $data->verification_status == 'Verified' ? 'bg-success' : 'bg-warning text-dark' }} me-1">
                            {{ $data->verification_status }}
                        </span>
                        <span class="badge fs-6 {{ $data->invoice_status == 'Paid' ? 'bg-success' : 'bg-danger' }}">
                            {{ $data->invoice_status }}
                        </span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Supplier:</strong>
                        <p class="text-muted mb-2">{{ $data->supplier->SupplierName ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Branch:</strong>
                        <p class="text-muted mb-2">{{ $data->branch->branch_name ?? 'Central' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Invoice Date:</strong>
                        <p class="text-muted mb-2">{{ $data->invoice_date ? $data->invoice_date->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Due Date:</strong>
                        <p class="text-muted mb-2">{{ $data->due_date ? $data->due_date->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>PO Reference:</strong>
                        <p class="text-muted mb-2">{{ $data->purchaseOrder->purchase_order_no ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>GRN Reference:</strong>
                        <p class="text-muted mb-2">{{ $data->goodsReceipt->receipt_no ?? 'N/A' }}</p>
                    </div>

                    <div class="col-12">
                        <strong>Notes:</strong>
                        <p class="text-muted mb-0">{{ $data->notes ?? 'No notes recorded.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- INVOICE ITEMS BREAKDOWN --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fas fa-boxes me-2 text-primary"></i>Invoiced Line Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Tax Amount</th>
                                <th>Discount</th>
                                <th>Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->items as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>
                                    <strong>{{ $item->product->product_name ?? 'Item' }}</strong>
                                    <small class="d-block text-muted">{{ $item->unitOfMeasure->UnitName ?? '' }}</small>
                                </td>
                                <td>{{ number_format($item->quantity, 2) }}</td>
                                <td>{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ number_format($item->tax_amount, 2) }} ({{ $item->tax_rate }}%)</td>
                                <td class="text-danger">{{ number_format($item->discount_amount, 2) }}</td>
                                <td class="fw-bold">{{ number_format($item->total_amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-end fw-bold">Subtotal:</td>
                                <td class="fw-bold">{{ number_format($data->subtotal, 2) }} {{ $data->currency_code }}</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end fw-bold">Tax Total:</td>
                                <td class="fw-bold text-info">+{{ number_format($data->tax_amount, 2) }} {{ $data->currency_code }}</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end fw-bold">Discount Total:</td>
                                <td class="fw-bold text-danger">-{{ number_format($data->discount_amount, 2) }} {{ $data->currency_code }}</td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="6" class="text-end fw-bold fs-6">Grand Total:</td>
                                <td class="fw-bold text-success fs-6">{{ number_format($data->total_amount, 2) }} {{ $data->currency_code }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- FINANCIAL BALANCES CARD --}}
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Financial Balances</h6>

                <div class="mb-3">
                    <small class="text-muted d-block">Paid Amount</small>
                    <span class="fw-bold text-success fs-5">{{ number_format($data->paid_amount, 2) }} {{ $data->currency_code }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Outstanding Balance</small>
                    <span class="fw-bold text-danger fs-5">{{ number_format($data->outstanding_amount, 2) }} {{ $data->currency_code }}</span>
                </div>
            </div>
        </div>

        {{-- AUDIT CARD --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Audit Information</h6>

                <div class="mb-3">
                    <small class="text-muted d-block">Verified By</small>
                    <span class="fw-bold text-dark">{{ $data->verifier->name ?? 'Not Verified' }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Verified At</small>
                    <span class="text-muted">{{ $data->verified_at ? $data->verified_at->format('d M, Y H:i A') : 'N/A' }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Created By</small>
                    <span class="fw-bold text-dark">{{ $data->creator->name ?? 'N/A' }}</span>
                </div>

                <div>
                    <small class="text-muted d-block">Created At</small>
                    <span class="text-muted">{{ $data->created_at ? $data->created_at->format('d M, Y H:i A') : 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- VERIFICATION MODAL --}}
<div class="modal fade" id="verifyInvoiceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('verifysupplierInvoice', encrypt($data->id)) }}">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-check-double me-1"></i> Verify Supplier Invoice</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">By verifying this invoice, you confirm that the prices and billed items match the Goods Receipt and Purchase Order terms.</p>
                    <div class="mb-3">
                        <label class="form-label">Verification Remarks</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Optional approval remarks...">{{ $data->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Verification</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection