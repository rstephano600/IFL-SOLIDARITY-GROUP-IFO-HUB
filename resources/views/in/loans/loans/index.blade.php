@extends('layouts.app')
@section('title', 'All Loans Details')
@section('page-title', 'All Loans Details')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & ACTION BAR --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-wallet-fill text-primary me-2"></i> All Loan Records
        </h2>
        
        <div class="d-flex gap-2">
            {{-- EXPORT BUTTON DROPDOWN --}}
            <div class="dropdown">
                <button class="btn btn-outline-success dropdown-toggle shadow-sm fw-semibold" type="button" 
                        id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-download me-1"></i> Export Data
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                    {{-- 
                        NOTE: These export links must include the current filters. 
                        In Laravel, you would typically pass the current request query parameters. 
                    --}}
                    <li class="dropdown-header">Apply Current Filters</li>
                    <li>
                        <a class="dropdown-item" 
                           href="{{ route('loans.export.excel', request()->query()) }}">
                           <i class="bi bi-file-earmark-excel me-2 text-success"></i> Export to Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" 
                           href="{{ route('loans.export.pdf', request()->query()) }}">
                           <i class="bi bi-file-earmark-pdf me-2 text-danger"></i> Export to PDF
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        {{-- A simple button to trigger the browser's print dialog, which is cleaner than a separate route --}}
                        <button type="button" class="dropdown-item" onclick="window.print()">
                            <i class="bi bi-printer me-2 text-secondary"></i> Print View
                        </button>
                    </li>
                </ul>
            </div>
            
            {{-- New Loan Button --}}
            {{-- Assuming a route for creating new loans exists (e.g., for new clients, if not covered elsewhere) --}}
            <a href="{{ route('loans.create') }}" class="btn btn-primary shadow-sm d-flex align-items-center fw-semibold">
                <i class="bi bi-plus-circle-fill me-2"></i> New Loan
            </a>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- SEARCH AND FILTERS CARD --}}
    {{-- ================================================================= --}}
    <form method="GET" action="{{ route('loans.index') }}" class="mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <h6 class="card-title fw-bold mb-3 text-primary">
                    <i class="bi bi-funnel me-1"></i> Search & Filter Loans
                </h6>
                
                <div class="row g-2">
                    
                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="search" class="form-label fw-semibold small">Search</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Client, loan number, group, or users...">
                        </div>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="status" class="form-label fw-semibold small">Status</label>
                        <select name="status" id="status" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="created_by" class="form-label fw-semibold small">Created By</label>
                        <select name="created_by" id="created_by" class="form-select form-select-sm">
                            <option value="">All Creators</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}" {{ request('created_by') == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="approved_by" class="form-label fw-semibold small">Approved By</label>
                        <select name="approved_by" id="approved_by" class="form-select form-select-sm">
                            <option value="">All Approvers</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}" {{ request('approved_by') == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="updated_by" class="form-label fw-semibold small">Updated By</label>
                        <select name="updated_by" id="updated_by" class="form-select form-select-sm">
                            <option value="">All Updaters</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}" {{ request('updated_by') == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="disbursed_date_from" class="form-label fw-semibold small">Disbursed From</label>
                        <input type="date" name="disbursed_date_from" id="disbursed_date_from" 
                            value="{{ request('disbursed_date_from') }}" class="form-control form-control-sm">
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="disbursed_date_to" class="form-label fw-semibold small">Disbursed To</label>
                        <input type="date" name="disbursed_date_to" id="disbursed_date_to" 
                            value="{{ request('disbursed_date_to') }}" class="form-control form-control-sm">
                    </div>
                    
                    {{-- Action Buttons --}}
                    <div class="col-12 col-md-6 col-lg-3 d-flex align-items-end gap-2 mt-3 mt-lg-0">
                        <button type="submit" class="btn btn-primary btn-sm fw-semibold w-50">
                            <i class="bi bi-filter"></i> Apply Filters
                        </button>
                        <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary btn-sm w-50">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                        @if(request()->anyFilled(['search', 'created_by', 'approved_by', 'updated_by', 'disbursed_date', 'disbursed_date_from', 'disbursed_date_to', 'status']))
                            <span class="badge bg-info ms-1 d-flex align-items-center">
                                <i class="bi bi-funnel me-1"></i> Active
                            </span>
                        @endif
                    </div>
                    
                    {{-- Removed 'Exact Disbursed Date' for cleaner filter layout --}}

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
                <h5 class="mb-0 fw-bold">Loan Records</h5>
                <span class="text-muted small">
                    Showing {{ $loans->firstItem() ?? 0 }} - {{ $loans->lastItem() ?? 0 }} of {{ $loans->total() }} results
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 3%;">#</th>
                            <th style="width: 10%;">Loan No.</th>
                            <th style="width: 18%;">Client / Group</th>
                            <th style="width: 10%;">Category</th>
                            <th style="width: 12%;">Amount</th>
                            <th style="width: 10%;" class="text-center">Status</th>
                            <th style="width: 12%;">Created By</th>
                            <th style="width: 8%;">Requested On</th>
                            <th style="width: 8%;">Disbursed</th>
                            <th style="width: 9%;" class="text-center text-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $key => $loan)
                            <tr>
                                <td>{{ $loans->firstItem() + $key }}</td>
                                <td class="fw-bold text-primary">{{ $loan->loan_number }}</td>
                                <td>
                                    {{ $loan->client?->first_name }} {{ $loan->client?->last_name }}
                                    @if($loan->group)
                                        <div class="small text-muted fst-italic">Group: {{ $loan->group->group_name }}</div>
                                    @endif
                                </td>
                                <td><span class="badge bg-secondary-subtle text-dark">{{ $loan->loanCategory?->name ?? 'N/A' }}</span></td>
                                <td class="fw-semibold text-nowrap">
                                    {{ number_format($loan->amount_requested, 2) }} <span class="text-muted">{{ $loan->loanCategory?->currency ?? 'TZS' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ 
                                        $loan->status === 'pending' ? 'warning-subtle text-warning' : 
                                        ($loan->status === 'approved' ? 'success-subtle text-success' : 
                                        ($loan->status === 'active' ? 'primary-subtle text-primary' : 
                                        ($loan->status === 'completed' ? 'info-subtle text-info' : 'danger-subtle text-danger'))) 
                                    }} fw-semibold">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-person me-1"></i>
                                    {{ $loan->createdBy?->first_name ?? 'System' }}
                                </td>
                                <td>{{ $loan->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @if($loan->disbursed_date)
                                        <span class="text-success fw-semibold">
                                            {{ \Carbon\Carbon::parse($loan->disbursed_date)->format('Y-m-d') }}
                                        </span>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    <div class="btn-group btn-group-sm" role="group">
                                        {{-- View --}}
                                        <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-outline-info" 
                                           data-bs-toggle="tooltip" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        {{-- Edit (Only if pending or depending on business logic) --}}
                                        @if($loan->status === 'pending')
                                            <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-outline-warning" 
                                               data-bs-toggle="tooltip" title="Edit Loan">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-5">
                                    <i class="bi bi-search display-5 d-block mb-3"></i>
                                    No loan records found matching your criteria.
                                    <br>
                                    <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-primary mt-3">
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
                        <div class="text-muted small mb-2 mb-sm-0">
                            Page {{ $loans->currentPage() }} of {{ $loans->lastPage() }}
                        </div>
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
});
</script>
@endsection