@extends('layouts.workingside')
@section('title', 'Social Contributions Management Hub')
@section('page-title', 'Social Contributions Hub')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-hand-holding-heart"></i></div>
        Social Contribution Operations Hub
    </h3>
    @can('create-social-contribution')
    <a href="{{ route('socialcontributions') }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-plus-circle me-1"></i> New Social Contribution
    </a>
    @endcan
</div>

<div class="row g-4">
    {{-- Stage 1: Verification --}}
    @can('verify-social-contribution')
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
                            <small class="text-muted">Stage 1: Audit Documentation</small>
                        </div>
                    </div>
                    <p class="text-secondary small">Review submitted social contribution entries and verify supporting documentation.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('unverifiedsocialcontributions') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> Go to Verification
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Stage 2: Approval --}}
    @can('approve-social-contribution')
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
                            <small class="text-muted">Stage 2: Executive Authorization</small>
                        </div>
                    </div>
                    <p class="text-secondary small">Approve verified social contribution requests prior to ledger posting.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('pendingapprovalsocialcontributions') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> Go to Approvals
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Stage 3: Posting --}}
    @can('post-social-contribution')
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
                    <p class="text-secondary small">Post approved contributions into cash and social contribution ledger accounts.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('pendingpostingsocialcontributions') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> Execute Posting
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Completed Contributions Archive --}}
    @can('view-social-contribution')
    <div class="col-md-6 col-lg-4">
        <div class="arbif-card h-100 shadow-sm border-0">
            <div class="arbif-card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 bg-dark text-white me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check-double fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">Completed Contributions</h5>
                            <small class="text-muted">Finalized & Fully Posted Records</small>
                        </div>
                    </div>
                    <p class="text-secondary small">Access the archive of contributions that have completed the full verification, approval, and posting cycle.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('completedsocialcontributions') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> View Archive
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Master Listing --}}
    @can('view-social-contribution')
    <div class="col-md-6 col-lg-4">
        <div class="arbif-card h-100 shadow-sm border-0">
            <div class="arbif-card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 bg-secondary text-white me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-list-alt fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">All Contributions</h5>
                            <small class="text-muted">Master Listing, All Stages</small>
                        </div>
                    </div>
                    <p class="text-secondary small">Browse the master record of all social contributions across every workflow stage.</p>
                </div>
                <div class="pt-3 border-top">
                    <a href="{{ route('socialcontributions') }}" class="arbif-btn-submit w-100 text-center text-decoration-none">
                        <i class="fas fa-arrow-right me-1"></i> View All
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection