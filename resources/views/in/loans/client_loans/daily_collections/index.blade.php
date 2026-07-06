@extends('layouts.app')

@section('title', 'Daily Loan Collections')
@section('page-title', 'Daily Collections Management')

@section('content')
<div class="container py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-primary">Daily Loan Collections <i class="bi bi-cash-stack"></i></h2>
        <a href="{{ route('daily_collections.create') }}" class="btn btn-primary btn-lg shadow-sm d-flex align-items-center">
            <i class="bi bi-plus-circle me-2"></i> Add New Collection
        </a>
    </div>

    {{-- Filter/Search Card --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 text-dark-emphasis">Filter Collections</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('daily_collections.index') }}" class="row g-3">
                
                {{-- Search Input --}}
                <div class="col-md-4">
                    <label for="search" class="form-label visually-hidden">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Client Name, Loan #, or Payment Method...">
                </div>

                {{-- Start Date Filter --}}
                <div class="col-md-3">
                    <label for="start_date" class="form-label visually-hidden">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>

                {{-- End Date Filter --}}
                <div class="col-md-3">
                    <label for="end_date" class="form-label visually-hidden">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>

                {{-- Filter and Reset Buttons --}}
                <div class="col-md-2 d-flex">
                    <button type="submit" class="btn btn-primary w-100 me-2">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('daily_collections.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    {{-- Collections Table --}}
    <div class="card shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Loan #</th>
                            <th scope="col">Client</th>
                            <th scope="col" class="text-end">Amount Paid</th>
                            <th scope="col" class="text-end">Penalty Fee</th>
                            <th scope="col" class="text-end">Preclosure</th>
                            <th scope="col">Date Paid</th>
                            <th scope="col">Method</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($collections as $c)
                            <tr>
                                <td class="fw-bold">{{ $c->loan->loan_number ?? 'N/A' }}</td>
                                <td>{{ $c->loan->client->first_name ?? 'N/A' }} {{ $c->loan->client->last_name ?? 'N/A' }}</td>
                                <td class="text-end text-success fw-bold">{{ number_format($c->amount_paid, 2) }}</td>
                                <td class="text-end">{{ number_format($c->penalty_fee, 2) }}</td>
                                <td class="text-end">{{ number_format($c->total_preclosure, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($c->date_of_payment)->format('Y-m-d') }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $c->payment_method)) }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('daily_collections.show', $c->id) }}" class="btn btn-sm btn-info text-white" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bi bi-info-circle me-2"></i> No collection records found for the applied filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $collections->links() }}
    </div>

</div>
@endsection