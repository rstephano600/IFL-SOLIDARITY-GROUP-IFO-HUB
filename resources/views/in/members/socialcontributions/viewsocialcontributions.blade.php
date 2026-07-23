@extends('layouts.workingside')
@section('title', 'View Social Contribution')
@section('page-title', 'Social Contribution Details')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-file-invoice-dollar"></i></div>
        Contribution Ref: <span class="text-primary">{{ $contribution->ContributionRefNo ?? 'N/A' }}</span>
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('socialcontributions') }}" class="arbif-btn-cancel">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
        <a href="{{ route('editsocialcontributions', [encrypt($contribution->id)]) }}" class="arbif-btn-submit">
            <i class="fas fa-pencil me-1"></i> Edit Details
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Main Information Summary -->
    <div class="col-lg-8">
        <!-- Contribution & Member Overview -->
        <div class="arbif-card mb-4">
            <div class="arbif-card-header bg-light fw-bold text-navy">
                <i class="fas fa-user-tag me-2"></i> Member & Schedule Overview
            </div>
            <div class="arbif-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small d-block">Member Name</label>
                        <strong class="fs-6 text-dark">{{ $contribution->member->name ?? 'N/A' }}</strong>
                        <small class="d-block text-muted">Code: {{ $contribution->member->member_code ?? 'N/A' }}</small>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block">Schedule Ref No.</label>
                        <span class="arbif-badge arbif-badge-navy">{{ $contribution->schedule->ScheduleRefNo ?? 'N/A' }}</span>
                        <small class="d-block text-muted">Fee Type: {{ $contribution->schedule->FeeType ?? 'N/A' }}</small>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block">Contribution Month</label>
                        <strong class="text-dark">
                            {{ $contribution->ContributionMonth ? \Carbon\Carbon::parse($contribution->ContributionMonth)->format('F Y') : 'N/A' }}
                        </strong>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block">Payment Status</label>
                        @php
                            $statusClass = match($contribution->PaymentStatus) {
                                'Paid' => 'bg-success',
                                'Partial' => 'bg-warning text-dark',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="arbif-badge {{ $statusClass }} text-white">
                            {{ $contribution->PaymentStatus ?? 'Pending' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Breakdown Card -->
        <div class="arbif-card mb-4">
            <div class="arbif-card-header bg-light fw-bold text-navy">
                <i class="fas fa-coins me-2"></i> Financial & Payment Details
            </div>
            <div class="arbif-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Expected Amount</label>
                        <span class="fs-5 fw-bold text-secondary">
                            {{ number_format($contribution->ExpectedAmount ?? 0, 2) }}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Amount Paid</label>
                        <span class="fs-5 fw-bold text-success">
                            {{ number_format($contribution->AmountPaid ?? 0, 2) }}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Balance Due</label>
                        @php
                            $balance = ($contribution->ExpectedAmount ?? 0) - ($contribution->AmountPaid ?? 0);
                        @endphp
                        <span class="fs-5 fw-bold {{ $balance > 0 ? 'text-danger' : 'text-muted' }}">
                            {{ number_format(max(0, $balance), 2) }}
                        </span>
                    </div>

                    <hr class="my-2">

                    <div class="col-md-4">
                        <label class="text-muted small d-block">Payment Date</label>
                        <strong class="text-dark">
                            {{ $contribution->PaymentDate ? \Carbon\Carbon::parse($contribution->PaymentDate)->format('d M, Y') : 'N/A' }}
                        </strong>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Payment Method</label>
                        <span class="badge bg-secondary text-white">{{ $contribution->PaymentMethod ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Transaction Ref</label>
                        <strong class="text-dark">{{ $contribution->PaymentReference ?? 'N/A' }}</strong>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small d-block">Narration / Notes</label>
                        <div class="p-2 rounded bg-light border text-dark">
                            {{ $contribution->Narration ?? 'No narration provided.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Metadata Sidebar -->
    <div class="col-lg-4">
        <div class="arbif-card">
            <div class="arbif-card-header bg-light fw-bold text-navy">
                <i class="fas fa-building me-2"></i> Organizational Metadata
            </div>
            <div class="arbif-card-body">
                <div class="mb-3">
                    <label class="text-muted small d-block">Company</label>
                    <strong class="text-dark">{{ $contribution->company->company_name ?? 'N/A' }}</strong>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Branch</label>
                    <strong class="text-dark">{{ $contribution->branch->branch_name ?? 'N/A' }}</strong>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Recorded By</label>
                    <strong class="text-dark">{{ $contribution->user->name ?? 'System/Unknown' }}</strong>
                </div>
                <hr>
                <div class="mb-2">
                    <label class="text-muted small d-block">Created At</label>
                    <small class="text-muted">{{ $contribution->created_at ? $contribution->created_at->format('d M Y, h:i A') : 'N/A' }}</small>
                </div>
                <div>
                    <label class="text-muted small d-block">Last Updated</label>
                    <small class="text-muted">{{ $contribution->updated_at ? $contribution->updated_at->format('d M Y, h:i A') : 'N/A' }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection