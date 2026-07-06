@extends('layouts.app')

@section('title', 'Client Loan Management')
@section('page-title', 'Client Loans')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-primary">Client Loans Directory <i class="bi bi-currency-dollar"></i></h2>
        <a href="{{ route('client_loans.create') }}" class="btn btn-primary shadow-sm d-flex align-items-center">
            <i class="bi bi-plus-circle me-2"></i> New Loan Application
        </a>
    </div>

    {{-- Search and Filter Card --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('client_loans.index') }}" class="row g-2 align-items-center">
                {{-- Search Input --}}
                <div class="col-12 col-md-4 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search by client name or loan number..." value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="col-12 col-md-3 col-lg-3">
                    <select name="status" class="form-select">
                        <option value="">— Filter by Status —</option>
                        @foreach(['pending','approved','disbursed','completed','defaulted'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Button --}}
                <div class="col-6 col-md-2 col-lg-2">
                    <button class="btn btn-info w-100" type="submit">
                        <i class="bi bi-funnel me-1 d-md-none"></i>Filter
                    </button>
                </div>

                {{-- Reset Button --}}
                <div class="col-6 col-md-2 col-lg-2">
                    <a href="{{ route('client_loans.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise me-1 d-md-none"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>


    {{-- Loans Table Card --}}
    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 15%;">Loan No.</th>
                            <th style="width: 25%;">Client</th>
                            <th style="width: 20%;">Group Center</th>
                            <th class="text-end" style="width: 15%;">Amount</th>
                            <th class="text-center" style="width: 10%;">Status</th>
                            <th style="width: 10%;">Start Date</th>
                            <th class="text-center" style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-semibold text-primary">{{ $loan->loan_number }}</td>
                                <td>
                                    <a href="{{ route('clients.show', $loan->client_id) }}" class="text-decoration-none fw-medium">
                                        {{ $loan->client->first_name }} {{ $loan->client->last_name }}
                                    </a>
                                </td>
                                <td>{{ $loan->groupCenter->center_name ?? 'N/A' }}</td>
                                <td class="text-end fw-bold">{{ number_format($loan->amount_requested, 2) }}</td>
                                <td class="text-center">
                                    {{-- Dynamic Status Badge --}}
                                    @php
                                        $statusClass = [
                                            'pending' => 'warning',
                                            'approved' => 'info',
                                            'disbursed' => 'success',
                                            'completed' => 'primary',
                                            'defaulted' => 'danger',
                                        ][$loan->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} py-2 px-3">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($loan->start_date)->format('M d, Y') ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('client_loans.show', $loan) }}" class="btn btn-outline-info" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('client_loans.edit', $loan) }}" class="btn btn-outline-warning" title="Edit Loan">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('client_loans.destroy', $loan) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete Loan" onclick="return confirm('WARNING: Are you sure you want to delete Loan No. {{ $loan->loan_number }}? This action is irreversible.');">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">No loans matching your criteria were found. 🙁</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if($loans->hasPages())
                <div class="card-footer bg-light border-0">
                    <div class="d-flex justify-content-center">
                        {{ $loans->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection