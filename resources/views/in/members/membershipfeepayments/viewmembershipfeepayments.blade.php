@extends('layouts.workingside')
@section('title', 'View Membership Fee Payment')
@section('page-title', 'Membership Fee Payment Details')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-eye"></i></div>
        Payment Ref: {{ $feePayment->PaymentRefNo ?? 'N/A' }}
    </h3>
    <div>
        <a href="{{ route('editmembershipfeepayments', [encrypt($feePayment->id)]) }}" class="arbif-btn-edit me-2">
            <i class="fas fa-pencil me-1"></i> Edit Payment
        </a>
        <a href="{{ route('membershipfeepayments') }}" class="arbif-btn-cancel">
            <i class="fas fa-arrow-left me-1"></i> Back to Index
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Main Payment & Member Summary -->
    <div class="col-md-8">
        <div class="arbif-card h-100">
            <div class="arbif-card-header fw-bold text-navy">
                <i class="fas fa-receipt me-2"></i> Payment Details
            </div>
            <div class="arbif-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Payment Reference Number</small>
                        <span class="arbif-badge arbif-badge-navy fs-6">{{ $feePayment->PaymentRefNo ?? '—' }}</span>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">Amount Paid</small>
                        <h4 class="text-navy fw-bold mb-0">{{ number_format($feePayment->AmountPaid, 2) }}</h4>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">Member Name</small>
                        <strong class="text-dark fs-6">{{ $feePayment->member->name ?? '—' }}</strong>
                        <small class="d-block text-muted">{{ $feePayment->member->member_no ? 'No: '.$feePayment->member->member_no : '' }}</small>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">Fee Schedule Ref</small>
                        <strong class="text-dark">{{ $feePayment->feeSchedule->ScheduleRefNo ?? '—' }}</strong>
                        <small class="d-block text-muted">
                            Schedule Amount: {{ isset($feePayment->feeSchedule->FeeAmount) ? number_format($feePayment->feeSchedule->FeeAmount, 2) : '—' }}
                        </small>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block">Payment Date</small>
                        <strong class="text-dark">
                            {{ $feePayment->PaymentDate ? $feePayment->PaymentDate->format('d M Y') : '—' }}
                        </strong>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block">Payment Method</small>
                        <span class="badge bg-secondary text-white">{{ $feePayment->PaymentMethod ?? '—' }}</span>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block">Transaction Ref / Cheque No</small>
                        <span class="text-dark font-monospace">{{ $feePayment->PaymentReference ?? '—' }}</span>
                    </div>

                    <div class="col-md-12">
                        <small class="text-muted d-block">Narration / Notes</small>
                        <p class="text-dark mb-0 bg-light p-2 rounded border">
                            {{ $feePayment->Narration ?? 'No narration provided.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Organizational Scope & Audit Meta -->
    <div class="col-md-4">
        <div class="arbif-card h-100">
            <div class="arbif-card-header fw-bold text-navy">
                <i class="fas fa-building me-2"></i> System & Scope Context
            </div>
            <div class="arbif-card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">
                        <small class="text-muted d-block">Company</small>
                        <strong class="text-dark">{{ $feePayment->company->company_name ?? '—' }}</strong>
                    </li>
                    <li class="list-group-item px-0">
                        <small class="text-muted d-block">Branch</small>
                        <strong class="text-dark">{{ $feePayment->branch->branch_name ?? '—' }}</strong>
                    </li>
                    <li class="list-group-item px-0">
                        <small class="text-muted d-block">Recorded By</small>
                        <span class="text-dark fw-bold">{{ $feePayment->user->name ?? 'System' }}</span>
                    </li>
                    <li class="list-group-item px-0">
                        <small class="text-muted d-block">Record Status</small>
                        <span class="arbif-badge {{ $feePayment->Status === 'Active' ? 'bg-success' : 'bg-danger' }} text-white">
                            {{ $feePayment->Status ?? 'Active' }}
                        </span>
                    </li>
                    <li class="list-group-item px-0 border-0">
                        <small class="text-muted d-block">Created Date</small>
                        <span class="text-dark">{{ $feePayment->created_at ? $feePayment->created_at->format('d M Y H:i A') : '—' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection