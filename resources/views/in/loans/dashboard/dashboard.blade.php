@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Loan Analysis Dashboard</h2>
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-download"></i> Export Report
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); exportReport('excel')">
                        <i class="bi bi-file-earmark-excel text-success"></i> Export to Excel
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); exportReport('pdf')">
                        <i class="bi bi-file-earmark-pdf text-danger"></i> Export to PDF
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total Loans</p>
                            <h4 class="mb-0 fw-bold">{{ number_format($stats->total_loans ?? 0) }}</h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-2 rounded">
                            <i class="bi bi-file-text text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Requested Amount</p>
                            <h4 class="mb-0 fw-bold">TZS {{ number_format($stats->total_requested ?? 0, 2) }}</h4>
                        </div>
                        <div class="bg-info bg-opacity-10 p-2 rounded">
                            <i class="bi bi-cash-stack text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Disbursed Amount</p>
                            <h4 class="mb-0 fw-bold">TZS {{ number_format($stats->total_disbursed ?? 0, 2) }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-2 rounded">
                            <i class="bi bi-wallet2 text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Interest Amount</p>
                            <h4 class="mb-0 fw-bold">TZS {{ number_format($stats->total_interest ?? 0, 2) }}</h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-2 rounded">
                            <i class="bi bi-percent text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Loan Fees</p>
                            <h4 class="mb-0 fw-bold">TZS {{ number_format($stats->total_loan_fees ?? 0, 2) }}</h4>
                        </div>
                        <div class="bg-secondary bg-opacity-10 p-2 rounded">
                            <i class="bi bi-tag text-secondary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Other Fees</p>
                            <h4 class="mb-0 fw-bold">TZS {{ number_format($stats->total_other_fees ?? 0, 2) }}</h4>
                        </div>
                        <div class="bg-secondary bg-opacity-10 p-2 rounded">
                            <i class="bi bi-tags text-secondary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Preclosure Fees</p>
                            <h4 class="mb-0 fw-bold">TZS {{ number_format($stats->total_preclosure ?? 0, 2) }}</h4>
                        </div>
                        <div class="bg-dark bg-opacity-10 p-2 rounded">
                            <i class="bi bi-x-circle text-dark fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total Paid</p>
                            <h4 class="mb-0 fw-bold">TZS {{ number_format($stats->total_paid ?? 0, 2) }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-2 rounded">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Outstanding Balance</p>
                            <h4 class="mb-0 fw-bold">TZS {{ number_format($stats->total_outstanding ?? 0, 2) }}</h4>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-2 rounded">
                            <i class="bi bi-exclamation-circle text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Profit/Loss</p>
                            <h4 class="mb-0 fw-bold {{ ($stats->total_profit_loss ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                TZS {{ number_format($stats->total_profit_loss ?? 0, 2) }}
                            </h4>
                        </div>
                        <div class="bg-{{ ($stats->total_profit_loss ?? 0) >= 0 ? 'success' : 'danger' }} bg-opacity-10 p-2 rounded">
                            <i class="bi bi-{{ ($stats->total_profit_loss ?? 0) >= 0 ? 'graph-up-arrow' : 'graph-down-arrow' }} text-{{ ($stats->total_profit_loss ?? 0) >= 0 ? 'success' : 'danger' }} fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('loans_dashboard.dashboard') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Created By</label>
                        <select name="created_by" class="form-select">
                            <option value="">All Users</option>
                            @foreach($creators as $creator)
                                <option value="{{ $creator->id }}" {{ request('created_by') == $creator->id ? 'selected' : '' }}>
                                    {{ $creator->username }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="disbursed" {{ request('status') == 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="defaulted" {{ request('status') == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Apply Filters
                        </button>
                        <a href="{{ route('loans_dashboard.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loans List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0">All Loans</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">Loan #</th>
                            <th>Client</th>
                            <th class="text-end">Requested</th>
                            <th class="text-end">Disbursed</th>
                            <th class="text-end">Interest</th>
                            <th class="text-end">Fees</th>
                            <th class="text-end">Total Paid</th>
                            <th class="text-end">Outstanding</th>
                            <th class="text-end">Profit/Loss</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                        <tr>
                            <td class="px-3">
                                <a href="#" class="text-decoration-none fw-semibold">{{ $loan->loan_number }}</a>
                            </td>
                            <td>{{ $loan->client->first_name ?? 'N/A' }} {{ $loan->client->last_name ?? 'N/A' }}</td>
                            <td class="text-end">{{ number_format($loan->amount_requested, 2) }}</td>
                            <td class="text-end">{{ number_format($loan->amount_disbursed, 2) }}</td>
                            <td class="text-end">{{ number_format($loan->interest_amount, 2) }}</td>
                            <td class="text-end">{{ number_format($loan->loan_fee + $loan->other_fee, 2) }}</td>
                            <td class="text-end">{{ number_format($loan->amount_paid + $loan->penalty_fee + $loan->total_preclosure, 2) }}</td>
                            <td class="text-end">
                                <span class="badge bg-{{ $loan->outstanding_balance > 0 ? 'danger' : 'success' }}-subtle text-{{ $loan->outstanding_balance > 0 ? 'danger' : 'success' }}">
                                    {{ number_format($loan->outstanding_balance, 2) }}
                                </span>
                            </td>
                            <td class="text-end">
                                @php
                                    $profitLoss = $loan->amount_paid + $loan->penalty_fee + $loan->total_preclosure - $loan->amount_disbursed;
                                @endphp
                                <span class="fw-semibold {{ $profitLoss >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($profitLoss, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($loan->status == 'pending') bg-warning-subtle text-warning
                                    @elseif($loan->status == 'approved') bg-info-subtle text-info
                                    @elseif($loan->status == 'disbursed') bg-primary-subtle text-primary
                                    @elseif($loan->status == 'completed') bg-success-subtle text-success
                                    @else bg-danger-subtle text-danger
                                    @endif">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td>{{ $loan->creator->username ?? 'N/A' }}</td>
                            <td>{{ $loan->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No loans found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($loans->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $loans->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function exportReport(format) {
    const form = document.getElementById('filterForm');
    const params = new URLSearchParams(new FormData(form));
    
    let url = '';
    if (format === 'excel') {
        url = '{{ route("loans.export.excel") }}';
    } else if (format === 'pdf') {
        url = '{{ route("loans.export.pdf") }}';
    }
    
    window.location.href = url + '?' + params.toString();
}
</script>

@endsection