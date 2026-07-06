@extends('layouts.app')

@section('title', 'Loan Approvals')
@section('page-title', 'Loan Approvals Queue')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & ACTION BAR --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-shield-check text-success me-2"></i> Loan Approval Queue
        </h2>
        {{-- Optional: Could add a 'Bulk Approve' button here if business logic allows --}}
    </div>

    {{-- ================================================================= --}}
    {{-- SEARCH AND FILTERS CARD --}}
    {{-- ================================================================= --}}
    <form method="GET" action="{{ route('loan-approvals.index') }}" class="mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <h6 class="card-title fw-bold mb-3 text-success">
                    <i class="bi bi-funnel me-1"></i> Filter Loans for Review
                </h6>
                
                <div class="row g-2 align-items-end">
                    
                    <div class="col-12 col-md-5 col-lg-4">
                        <label for="search" class="form-label fw-semibold small">Search</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Client name, loan number, or officer...">
                        </div>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="status" class="form-label fw-semibold small">Status</label>
                        <select name="status" id="status" class="form-select form-select-sm">
                            <option value="pending" {{ !request('status') || request('status') === 'pending' ? 'selected' : '' }}>Pending (Default)</option>
                            <option value="">-- All Statuses --</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-4 col-lg-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-success btn-sm fw-semibold w-50">
                            <i class="bi bi-filter"></i> Apply Filters
                        </button>
                        <a href="{{ route('loan-approvals.index') }}" class="btn btn-outline-secondary btn-sm w-50">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    {{-- ================================================================= --}}
    {{-- LOANS TABLE --}}
    {{-- ================================================================= --}}
    @if ($loans->isEmpty() && request()->filled('status'))
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center" role="alert">
            <i class="bi bi-info-circle-fill flex-shrink-0 me-2"></i>
            <div>
                No loan approvals found matching the selected filter criteria.
            </div>
        </div>
    @elseif ($loans->isEmpty())
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill flex-shrink-0 me-2"></i>
            <div>
                **Congratulations!** The loan approval queue is empty.
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                
                {{-- Results Count in Card Header --}}
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">Loans for Review</h5>
                    <span class="text-muted small">
                        Showing {{ $loans->firstItem() ?? 0 }} - {{ $loans->lastItem() ?? 0 }} of {{ $loans->total() }} results
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 3%;">#</th>
                                <th style="width: 15%;">Loan Number</th>
                                <th style="width: 25%;">Client</th>
                                <th style="width: 15%;">Category</th>
                                <th style="width: 15%;">Am. Requested</th>
                                <th style="width: 10%;">D. Requested</th>
                                <th style="width: 10%;" class="text-center">Status</th>
                                <th style="width: 7%;" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($loans as $loan)
                                <tr>
                                    <td>{{ $loop->iteration + ($loans->currentPage() - 1) * $loans->perPage() }}</td>
                                    <td class="fw-bold text-success">{{ $loan->loan_number }}</td>
                                    <td>
                                        <i class="bi bi-person-circle me-1"></i>
                                        {{ $loan->client?->first_name }} {{ $loan->client?->last_name }}
                                        @if($loan->client?->group)
                                            <div class="small text-muted fst-italic">Group: {{ $loan->client->group->group_name }}</div>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-secondary-subtle text-dark">{{ $loan->loanCategory?->name ?? 'N/A' }}</span></td>
                                    <td class="fw-semibold text-nowrap">
                                        {{ number_format($loan->amount_requested, 2) }} <span class="text-muted">{{ $loan->loanCategory?->currency ?? 'TZS' }}</span>
                                    </td>
                                    <td>{{ $loan->created_at->format('Y-m-d') }}</td>
                                    <td class="text-center">
                                        {{-- Conditional Badge based on status, prioritizing 'pending' for this view --}}
                                        <span class="badge bg-{{ 
                                            $loan->status === 'pending' ? 'warning text-dark' : 
                                            ($loan->status === 'approved' ? 'success-subtle text-success' : 
                                            ($loan->status === 'active' ? 'primary-subtle text-primary' : 'danger-subtle text-danger')) 
                                        }} fw-semibold">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('loan-approvals.show', $loan->id) }}" class="btn btn-sm btn-success fw-semibold shadow-sm">
                                            <i class="bi bi-check-circle me-1"></i> Review
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination in Card Footer --}}
                @if($loans->hasPages())
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-center">
                            {{ $loans->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection