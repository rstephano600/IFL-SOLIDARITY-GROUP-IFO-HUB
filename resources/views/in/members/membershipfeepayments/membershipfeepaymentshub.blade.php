@extends('layouts.workingside')
@section('title', 'Membership Fee Payments Management Hub')
@section('page-title', 'Membership Fee Payments Hub')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-id-card"></i></div>
        Membership Fee Payment Operations Hub
    </h3>
    @can('create-membership-fee-payment')
    <a href="{{ route('membershipfeepayments') }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-plus-circle me-1"></i> New Fee Payment
    </a>
    @endcan
</div>

<div class="row g-4">
    {{-- Stage 1: Verification --}}
    @can('verify-membership-fee-payment')
    <div class="col-md-6 col-lg-4">
        <div class="arbif-card h-100 shadow-sm border-0">
            <div class="arbif-card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 bg-warning text-dark me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-shield-alt fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">Pending Verification</h5>
                            <small class="text-muted">Stage 1: Audit Payment & Data</small>
                        </div>
                    </div>
                    <p class="text-secondary small">Review submitted membership fee payments and verify payment attachments and details.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('unverifiedmembershipfeepayments') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> Go to Verification
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Stage 2: Approval --}}
    @can('approve-membership-fee-payment')
    <div class="col-md-6 col-lg-4">
        <div class="arbif-card h-100 shadow-sm border-0">
            <div class="arbif-card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 bg-success text-white me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-check fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">Pending Approval</h5>
                            <small class="text-muted">Stage 2: Management Authorization</small>
                        </div>
                    </div>
                    <p class="text-secondary small">Approve verified membership fee payments prior to ledger posting and accounting entries.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('pendingapprovalmembershipfeepayments') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> Go to Approvals
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Stage 3: Posting --}}
    @can('post-membership-fee-payment')
    <div class="col-md-6 col-lg-4">
        <div class="arbif-card h-100 shadow-sm border-0">
            <div class="arbif-card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 bg-primary text-white me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-file-invoice-dollar fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">Pending Posting</h5>
                            <small class="text-muted">Stage 3: General Ledger Posting</small>
                        </div>
                    </div>
                    <p class="text-secondary small">Post approved membership fee payments into member accounts and general ledger accounts.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('pendingpostingmembershipfeepayments') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> Execute Posting
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Completed Payments Archive --}}
    @can('view-membership-fee-payment')
    <div class="col-md-6 col-lg-4">
        <div class="arbif-card h-100 shadow-sm border-0">
            <div class="arbif-card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 bg-dark text-white me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check-double fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">Completed Payments</h5>
                            <small class="text-muted">Finalized & Fully Posted Records</small>
                        </div>
                    </div>
                    <p class="text-secondary small">Access the complete archive of finalized membership fee payment transactions.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('completedmembershipfeepayments') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> View Archive
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection