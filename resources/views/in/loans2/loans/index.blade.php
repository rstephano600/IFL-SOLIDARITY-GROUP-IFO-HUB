@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">💳 Loan Management</h1>
        <a href="{{ route('loans.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Loan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Search and Filter Form --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('loans.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search (Loan No./Client)</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Statuses</option>
                        @php
                            $statuses = ['pending', 'approved', 'active', 'completed', 'defaulted', 'closed'];
                        @endphp
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                {{ ucwords($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label">Loan Category</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (int)request('category') === $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Loans Table --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover small">
                    <thead>
                        <tr>
                            <th>Loan No.</th>
                            <th>Client</th>
                            <th>Category</th>
                            <th>Principal</th>
                            <th>Outstanding</th>
                            <th>Status</th>
                            <th>Disbursement Date</th>
                            <th style="width: 250px;">Actions</th> {{-- Increased width for new buttons --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans as $loan)
                            <tr>
                                <td><a href="{{ route('loans.show', $loan) }}">{{ $loan->loan_number }}</a></td>
                                <td>{{ $loan->client->first_name ?? 'N/A' }}</td>
                                <td>{{ $loan->category->name ?? 'N/A' }}</td>
                                <td>{{ number_format($loan->principal_amount, 2) }}</td>
                                <td>{{ number_format($loan->total_outstanding, 2) }}</td>
                                <td><span class="badge bg-{{ ['pending' => 'warning', 'approved' => 'info', 'active' => 'success', 'completed' => 'primary', 'defaulted' => 'danger', 'closed' => 'secondary'][$loan->status] ?? 'dark' }}">{{ ucwords($loan->status) }}</span></td>
                                <td>{{ $loan->disbursement_date ? $loan->disbursement_date->format('Y-m-d') : 'N/A' }}</td>
                                <td>
                                    {{-- NEW: View Repayment Schedule --}}
                                    <a href="{{ route('schedules.index', $loan) }}" class="btn btn-sm btn-secondary" title="Schedule">
                                        <i class="fas fa-calendar-alt"></i>
                                    </a>

                                    {{-- NEW: Record Payment (only if loan is approved or active) --}}
                                    @if (in_array($loan->status, ['approved', 'active', 'defaulted']))
                                    <a href="{{ route('payments.create', $loan) }}" class="btn btn-sm btn-success" title="Record Payment">
                                        <i class="fas fa-plus-circle"></i> Pay
                                    </a>
                                    @endif

                                    {{-- Existing Actions --}}
                                    <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('loans.edit', $loan) }}" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    
                                    <form action="{{ route('loans.destroy', $loan) }}" method="POST" class="d-inline" onsubmit="return confirm('WARNING: Are you sure you want to delete this loan? This action is generally irreversible.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No loans found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {{ $loans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
