@extends('layouts.workingside')
@section('title', 'Share Purchases Management Hub')
@section('page-title', 'Share Purchases Hub')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-cubes"></i></div>
        Share Purchase Operations Hub
    </h3>
    @can('create-share-purchases')
    <a href="{{ route('sharepurchasetransactions') }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-plus-circle me-1"></i> New Share Purchase
    </a>
    @endcan
</div>

<div class="row g-4">
    {{-- Stage 1: Verification --}}
    @can('verify-share-purchases')
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
                    <p class="text-secondary small">Review initial share applications, verify payment attachments, and validate transaction details.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('unverifiedsharepurchasetransactions') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> Go to Verification
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Stage 2: Approval --}}
    @can('approve-share-purchases')
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
                    <p class="text-secondary small">Approve verified share purchase requests prior to ledger posting and accounting entries.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('pendingapprovalsharepurchasetransactions') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> Go to Approvals
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Stage 3: Posting --}}
    @can('post-share-purchases')
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
                    <p class="text-secondary small">Post approved transactions directly into member accounts and general ledger accounts.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('pendingpostingsharepurchasetransactions') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> Execute Posting
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Stage 4: Certificate Generation --}}
    @can('generate-certificate-share-purchases')
    <div class="col-md-6 col-lg-4">
        <div class="arbif-card h-100 shadow-sm border-0">
            <div class="arbif-card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 bg-info text-white me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-certificate fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">Pending Certificate</h5>
                            <small class="text-muted">Stage 4: Certificate Issuance</small>
                        </div>
                    </div>
                    <p class="text-secondary small">Generate and issue official share certificates for fully posted purchases.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('pendingcertificatesharepurchasetransactions') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> Issue Certificates
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Complete Purchases Archive --}}
    @can('view-share-purchases')
    <div class="col-md-6 col-lg-4">
        <div class="arbif-card h-100 shadow-sm border-0">
            <div class="arbif-card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 bg-dark text-white me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check-double fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">Completed Purchases</h5>
                            <small class="text-muted">Finalized Records & PDF Downloads</small>
                        </div>
                    </div>
                    <p class="text-secondary small">Access the complete archive of finalized share transactions and download certificates.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('completedsharepurchasetransactions') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> View Archive
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Reports Card --}}
    @can('view-share-purchases-report')
    <div class="col-md-6 col-lg-4">
        <div class="arbif-card h-100 shadow-sm border-0">
            <div class="arbif-card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 bg-secondary text-white me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-chart-bar fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">Purchases Report</h5>
                            <small class="text-muted">Analytics & Statement Export</small>
                        </div>
                    </div>
                    <p class="text-secondary small">Generate full audit reports, balance summaries, and date-filtered share transaction statements.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('sharepurchasetransactionsreport') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> Open Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection