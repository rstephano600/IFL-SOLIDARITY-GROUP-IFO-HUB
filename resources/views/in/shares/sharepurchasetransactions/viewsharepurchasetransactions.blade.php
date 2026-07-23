@extends('layouts.workingside')
@section('title', 'View Share Purchase Transaction')
@section('page-title', 'Share Purchase Details')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-eye"></i></div>
        Transaction Details: {{ $transaction->TransactionRefNo ?? 'N/A' }}
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('sharepurchasetransactions') }}" class="arbif-btn-cancel">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
        <a href="{{ route('editsharepurchasetransactions', [encrypt($transaction->id)]) }}" class="arbif-btn-submit">
            <i class="fas fa-pencil me-1"></i> Edit Transaction
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Transaction & Financial Overview -->
    <div class="col-lg-8">
        <div class="arbif-card mb-4">
            <div class="arbif-card-header fw-bold text-navy border-bottom p-3">
                <i class="fas fa-receipt me-2"></i> Transaction Summary
            </div>
            <div class="arbif-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted fs-7 d-block">Reference Number</label>
                        <span class="arbif-badge arbif-badge-navy fs-6">{{ $transaction->TransactionRefNo ?? '—' }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted fs-7 d-block">Transaction Status</label>
                        @php
                            $statusClass = match($transaction->Status) {
                                'Active' => 'bg-success',
                                'Pending' => 'bg-warning text-dark',
                                'Cancelled' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="arbif-badge {{ $statusClass }} text-white">{{ $transaction->Status ?? 'Active' }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted fs-7 d-block">Transaction Type</label>
                        <strong class="text-dark">{{ $transaction->TransactionType ?? 'Purchase' }}</strong>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted fs-7 d-block">Transaction Date</label>
                        <strong class="text-dark">{{ $transaction->TransactionDate ? $transaction->TransactionDate->format('d M, Y') : '—' }}</strong>
                    </div>
                </div>

                <hr class="my-3">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="text-muted fs-7 d-block">Shares Quantity</label>
                        <h4 class="fw-bold text-dark mb-0">{{ number_format($transaction->SharesQuantity, 2) }}</h4>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted fs-7 d-block">Price Per Share</label>
                        <h4 class="fw-bold text-dark mb-0">{{ number_format($transaction->PricePerShare, 2) }}</h4>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted fs-7 d-block">Total Purchase Value</label>
                        <h4 class="fw-bold text-success mb-0">{{ number_format($transaction->SharesQuantity * $transaction->PricePerShare, 2) }}</h4>
                    </div>
                </div>

                <hr class="my-3">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted fs-7 d-block">Payment Method</label>
                        <span class="fw-semibold text-dark">{{ $transaction->PaymentMethod ?? '—' }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted fs-7 d-block">Payment Reference ID</label>
                        <span class="fw-semibold text-dark">{{ $transaction->PaymentReference ?? '—' }}</span>
                    </div>
                    <div class="col-md-12">
                        <label class="text-muted fs-7 d-block">Narration / Remarks</label>
                        <p class="text-secondary bg-light p-2 rounded mb-0">{{ $transaction->Narration ?? 'No remarks specified.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Related Entities & Audit Trail -->
    <div class="col-lg-4">
        <!-- Member Information -->
        <div class="arbif-card mb-4">
            <div class="arbif-card-header fw-bold text-navy border-bottom p-3">
                <i class="fas fa-user-tie me-2"></i> Member Info
            </div>
            <div class="arbif-card-body">
                <div class="mb-2">
                    <small class="text-muted d-block">Member Name</small>
                    <strong class="text-dark">{{ $transaction->member->name ?? '—' }}</strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Member Code</small>
                    <span class="badge bg-light text-navy border">{{ $transaction->member->member_code ?? '—' }}</span>
                </div>
            </div>
        </div>

        <!-- Share Offering Details -->
        <div class="arbif-card mb-4">
            <div class="arbif-card-header fw-bold text-navy border-bottom p-3">
                <i class="fas fa-layer-group me-2"></i> Offering & Share Type
            </div>
            <div class="arbif-card-body">
                <div class="mb-2">
                    <small class="text-muted d-block">Offering Reference</small>
                    <strong class="text-dark">{{ $transaction->shareOffering->OfferingRefNo ?? '—' }}</strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Share Type</small>
                    <strong class="text-dark">{{ $transaction->shareType->TypeName ?? $transaction->shareType->TypeCode ?? '—' }}</strong>
                </div>
            </div>
        </div>

        <!-- Entity Audit Info -->
        <div class="arbif-card">
            <div class="arbif-card-header fw-bold text-navy border-bottom p-3">
                <i class="fas fa-info-circle me-2"></i> Audit Info
            </div>
            <div class="arbif-card-body">
                <div class="mb-2">
                    <small class="text-muted d-block">Company Entity</small>
                    <span class="text-dark fw-semibold">{{ $transaction->company->company_name ?? '—' }}</span>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Operational Branch</small>
                    <span class="text-dark fw-semibold">{{ $transaction->branch->branch_name ?? '—' }}</span>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Recorded By</small>
                    <span class="text-dark fw-semibold">{{ $transaction->user->name ?? 'System' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection