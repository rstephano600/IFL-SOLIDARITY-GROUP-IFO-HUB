@extends('layouts.app')
@section('title', 'Continuing Clients Loan Details')
@section('page-title', 'Continuing Clients Loan Details')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & ACTION BAR --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-arrow-repeat text-info me-2"></i> Continuing Client Loan Requests
        </h2>
        <a href="{{ route('loan_request_continueng_client.create') }}" class="btn btn-info shadow-sm d-flex align-items-center text-white fw-semibold">
            <i class="bi bi-plus-circle-fill me-2"></i> New Loan Request
        </a>
    </div>

    {{-- ================================================================= --}}
    {{-- SEARCH AND FILTERS CARD --}}
    {{-- ================================================================= --}}
    <form method="GET" action="{{ route('loan_request_continueng_client.index') }}" class="mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <h6 class="card-title fw-bold mb-3 text-info">
                    <i class="bi bi-funnel me-1"></i> Search & Filter Loans
                </h6>
                
                <div class="row g-2 align-items-end">
                    
                    <div class="col-12 col-md-5 col-lg-3">
                        <label for="search" class="form-label fw-semibold">Search</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Client, loan number, or category...">
                        </div>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select name="status" id="status" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-4 col-lg-2">
                        <label for="disbursed_date_from" class="form-label fw-semibold">Disbursed From</label>
                        <input type="date" name="disbursed_date_from" id="disbursed_date_from" 
                            value="{{ request('disbursed_date_from') }}" class="form-control form-control-sm">
                    </div>

                    <div class="col-6 col-md-4 col-lg-2">
                        <label for="disbursed_date_to" class="form-label fw-semibold">Disbursed To</label>
                        <input type="date" name="disbursed_date_to" id="disbursed_date_to" 
                            value="{{ request('disbursed_date_to') }}" class="form-control form-control-sm">
                    </div>
                    
                    {{-- Removed 'Exact Disbursed Date' input for a cleaner, more responsive filter row (2 date ranges is usually enough) --}}

                    <div class="col-6 col-md-8 col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-info btn-sm fw-semibold text-white w-50">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                        <a href="{{ route('loan_request_continueng_client.index') }}" class="btn btn-outline-secondary btn-sm w-50">
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
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            
            {{-- Results Count in Card Header --}}
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold">Loan Results</h5>
                <span class="text-muted small">
                    Showing {{ $loans->firstItem() ?? 0 }} - {{ $loans->lastItem() ?? 0 }} of {{ $loans->total() }} results
                    @if(request()->anyFilled(['search', 'status', 'disbursed_date', 'disbursed_date_from', 'disbursed_date_to']))
                        <i class="bi bi-funnel-fill text-info ms-2" data-bs-toggle="tooltip" title="Filters are Active"></i>
                    @endif
                </span>
            </div>

            <div class="table-responsive">
                {{-- Added .table-sm for better fit on small screens --}}
                <table class="table table-striped table-hover table-sm align-middle mb-0"> 
                    <thead class="table-light">
                        <tr>
                            <th style="width: 3%;">#</th>
                            <th style="width: 12%;">Loan Number</th>
                            <th style="width: 15%;">Client</th>
                            <th style="width: 10%;">Category</th>
                            <th style="width: 15%;">A. Requested</th>
                            <th style="width: 10%;" class="text-center">Status</th>
                            <!-- <th style="width: 10%;">Requested By</th> -->
                            <!-- <th style="width: 10%;">Requested On</th> -->
                            <!-- <th style="width: 10%;">Disbursed Date</th> -->
                            <th style="width: 5%;" class="text-center text-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans as $key => $loan)
                            <tr>
                                <td>{{ $loans->firstItem() + $key }}</td>
                                <td class="fw-bold text-info">{{ $loan->loan_number }}</td>
                                <td>{{ $loan->client?->first_name }} {{ $loan->client?->last_name }}</td>
                                <td><span class="badge bg-secondary-subtle text-dark">{{ $loan->loanCategory?->name ?? 'N/A' }}</span></td>
                                <td class="fw-semibold">
                                    {{ number_format($loan->amount_requested, 2) }} <span class="text-muted">{{ $loan->loanCategory?->currency ?? 'TZS' }}</span>
                                </td>
                                <td class="text-center">
                                    {{-- Using subtle badges for a cleaner look --}}
                                    <span class="badge bg-{{ 
                                        $loan->status === 'pending' ? 'warning-subtle text-warning' : 
                                        ($loan->status === 'approved' ? 'success-subtle text-success' : 
                                        ($loan->status === 'active' ? 'primary-subtle text-primary' : 
                                        ($loan->status === 'rejected' ? 'danger-subtle text-danger' : 'secondary-subtle text-secondary'))) 
                                    }} fw-semibold">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <!-- <td>
                                    <i class="bi bi-person me-1"></i>
                                    {{ $loan->createdBy->first_name ?? 'System' }}
                                </td> -->
                                <!-- <td>{{ $loan->created_at->format('Y-m-d') }}</td> -->
                                <!-- <td>
                                    @if($loan->disbursed_date)
                                        <span class="text-success fw-semibold">
                                            {{ \Carbon\Carbon::parse($loan->disbursed_date)->format('Y-m-d') }}
                                        </span>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td> -->
                                <td class="text-center text-nowrap">
                                    <div class="btn-group btn-group-sm" role="group">
                                        {{-- View --}}
                                        <a href="{{ route('loan_request_continueng_client.show', $loan->id) }}" 
                                           class="btn btn-outline-info" data-bs-toggle="tooltip" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        {{-- Edit --}}
                                        <!-- <a href="{{ route('loan_request_continueng_client.edit', $loan->id) }}" 
                                           class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Edit Loan">
                                            <i class="bi bi-pencil-square"></i>
                                        </a> -->
                                        {{-- Delete --}}
                                        <!-- <form action="{{ route('loan_request_continueng_client.destroy', $loan->id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete loan #{{ $loan->loan_number }}?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Delete Loan">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form> -->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-5">
                                    <i class="bi bi-search display-5 d-block mb-3"></i>
                                    No continuing client loan requests found matching your criteria.
                                    <br>
                                    <a href="{{ route('loan_request_continueng_client.index') }}" class="btn btn-sm btn-outline-primary mt-3">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Clear all filters
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination in Card Footer --}}
            @if($loans->hasPages())
                <div class="card-footer bg-light border-top">
                    <div class="d-flex justify-content-between align-items-center flex-column flex-sm-row">

                        {{-- Ensure the pagination links carry the current query parameters --}}
                        {{ $loans->appends(request()->query())->links('pagination::bootstrap-5') }} 
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips for the action icons
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // The date input clearing logic (simplified since the 'Exact Disbursed Date' input was removed for a cleaner filter layout)
    const disbursedFrom = document.getElementById('disbursed_date_from');
    const disbursedTo = document.getElementById('disbursed_date_to');
    
    // Note: The original JS logic about clearing 'disbursed_date' is commented out 
    // or removed as that input was removed from the new HTML.
});
</script>
@endsection