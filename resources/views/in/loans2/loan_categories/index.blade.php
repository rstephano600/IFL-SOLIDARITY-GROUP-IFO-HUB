@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">💰 Loan Categories</h1>
        <a href="{{ route('loan_categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Category
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Search and Filter Form --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('loan_categories.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search (Name/Conditions)</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="currency" class="form-label">Currency</label>
                    <input type="text" name="currency" id="currency" class="form-control" value="{{ request('currency') }}">
                </div>
                <div class="col-md-2">
                    <label for="frequency" class="form-label">Repayment Frequency</label>
                    <select name="frequency" id="frequency" class="form-select">
                        <option value="">All</option>
                        @php
                            $frequencies = ['daily', 'weekly', 'bi_weekly', 'monthly', 'quarterly'];
                        @endphp
                        @foreach ($frequencies as $freq)
                            <option value="{{ $freq }}" {{ request('frequency') === $freq ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $freq)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info w-100"><i class="fas fa-filter"></i> Filter</button>
                    @if (request()->hasAny(['search', 'status', 'currency', 'frequency']))
                        <a href="{{ route('loan_categories.index') }}" class="btn btn-outline-secondary w-100 mt-2">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Loan Category Table --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Interest Rate</th>
                            <th>Max Term</th>
                            <th>Min/Max Amount</th>
                            <th>Frequency</th>
                            <th>Status</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loanCategories as $category)
                            <tr>
                                <td>{{ $loop->iteration + ($loanCategories->currentPage() - 1) * $loanCategories->perPage() }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->interest_rate }}%</td>
                                <td>{{ $category->max_term_months }} M</td>
                                <td>{{ $category->currency }} {{ number_format($category->min_amount) }} / {{ number_format($category->max_amount) }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $category->repayment_frequency)) }}</td>
                                <td>
                                    @if ($category->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('loan_categories.show', $category) }}" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('loan_categories.edit', $category) }}" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    
                                    <form action="{{ route('loan_categories.toggleStatus', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $category->is_active ? 'btn-secondary' : 'btn-success' }}" title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('loan_categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No loan categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {{ $loanCategories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection