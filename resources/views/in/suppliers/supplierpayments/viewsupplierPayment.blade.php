@extends('layouts.workingside')

@section('title', 'Payment Voucher Details')
@section('page-title', 'Payment Voucher Details')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-invoice"></i>
        </div>
        Payment Receipt ({{ $data->payment_no }})
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('supplierPayment') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Payments
        </a>

        @if($data->attachment_path)
            <a href="{{ asset('storage/' . $data->attachment_path) }}" target="_blank" class="btn btn-outline-primary px-3" style="border-radius: 4px;">
                <i class="fas fa-paperclip me-1"></i> Attachment
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
                        <h4 class="fw-bold text-primary mb-1">{{ $data->payment_no }}</h4>
                        <span class="arbif-badge arbif-badge-navy">{{ $data->supplier->SupplierName ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="badge fs-6 bg-success">
                            {{ $data->payment_status }}
                        </span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Amount Paid:</strong>
                        <p class="text-success fw-bold fs-4 mb-2">{{ number_format($data->payment_amount, 2) }} {{ $data->currency_code }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Payment Date:</strong>
                        <p class="text-muted mb-2">{{ $data->payment_date ? $data->payment_date->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Payment Method:</strong>
                        <p class="text-muted mb-2">{{ $data->payment_method }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Transaction Reference:</strong>
                        <p class="text-muted mb-2">{{ $data->payment_reference ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Bank Account:</strong>
                        <p class="text-muted mb-2">{{ $data->bank_account ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Cheque Number:</strong>
                        <p class="text-muted mb-2">{{ $data->cheque_no ?? 'N/A' }}</p>
                    </div>

                    <div class="col-12">
                        <strong>Comments / Notes:</strong>
                        <p class="text-muted mb-0">{{ $data->comment ?? 'No notes provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- LINKED INVOICE DETAILS --}}
        @if($data->supplierInvoice)
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Applied Invoice Details</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">System Inv #</small>
                        <strong>{{ $data->supplierInvoice->invoice_no }}</strong>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block">Supplier Inv #</small>
                        <strong>{{ $data->supplierInvoice->supplier_invoice_no }}</strong>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block">Original Total</small>
                        <strong>{{ number_format($data->supplierInvoice->total_amount, 2) }} {{ $data->currency_code }}</strong>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block">Total Paid to Date</small>
                        <span class="text-success fw-bold">{{ number_format($data->supplierInvoice->paid_amount, 2) }} {{ $data->currency_code }}</span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block">Remaining Balance</small>
                        <span class="text-danger fw-bold">{{ number_format($data->supplierInvoice->outstanding_amount, 2) }} {{ $data->currency_code }}</span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block">Invoice Status</small>
                        <span class="badge bg-info text-dark">{{ $data->supplierInvoice->invoice_status }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Audit Information</h6>

                <div class="mb-3">
                    <small class="text-muted d-block">Processed / Paid By</small>
                    <span class="fw-bold text-dark">{{ $data->paidBy->name ?? 'N/A' }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Approved By</small>
                    <span class="fw-bold text-dark">{{ $data->approvedBy->name ?? 'System' }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Branch</small>
                    <span class="fw-bold text-dark">{{ $data->branch->branch_name ?? 'Central' }}</span>
                </div>

                <div>
                    <small class="text-muted d-block">Recorded At</small>
                    <span class="text-muted">{{ $data->created_at ? $data->created_at->format('d M, Y H:i A') : 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection