@extends('layouts.app')

@section('title', 'Employee Salaries')
@section('page-title', 'Employee Salary Records')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & ACTIONS --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-wallet-fill me-2 text-primary"></i> Employee Salary Records
        </h2>
        <a href="{{ route('employee_salaries.create') }}" class="btn btn-primary shadow-sm fw-semibold">
            <i class="bi bi-plus-circle-fill me-1"></i> Add New Salary Record
        </a>
    </div>

    {{-- ================================================================= --}}
    {{-- FILTER & SEARCH CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('employee_salaries.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <label for="search" class="form-label small fw-semibold">Search Employee</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Name or employee ID...">
                </div>
                
                <div class="col-12 col-md-3">
                    <label for="status" class="form-label small fw-semibold">Status</label>
                    <select name="status" id="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        {{-- Add other relevant statuses here if applicable --}}
                    </select>
                </div>

                <div class="col-6 col-md-2 d-grid mt-4">
                    <button class="btn btn-primary btn-sm fw-semibold"><i class="bi bi-search"></i> Filter</button>
                </div>
                <div class="col-6 col-md-2 d-grid mt-4">
                    <a href="{{ route('employee_salaries.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>
    
    {{-- ================================================================= --}}
    {{-- ALERT MESSAGE --}}
    {{-- ================================================================= --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    {{-- ================================================================= --}}
    {{-- SALARIES TABLE --}}
    {{-- ================================================================= --}}
    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 25%;">Employee</th>
                            <th style="width: 15%;">Salary Level</th>
                            <th class="text-end" style="width: 10%;">Basic</th>
                            <th class="text-end" style="width: 10%;">Bonus/Allowance</th>
                            <th class="text-end text-success" style="width: 15%;">Net Pay</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 10%;">Effective From</th>
                            <th class="text-end" style="width: 5%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaries as $s)
                            <tr>
                                <td class="fw-semibold text-dark">
                                    <i class="bi bi-person-circle me-1"></i> {{ $s->employee->first_name }} {{ $s->employee->last_name }}
                                </td>
                                <td><span class="badge bg-secondary-subtle text-secondary py-1">{{ $s->salaryLevel->name ?? 'Custom' }}</span></td>
                                <td class="text-end fw-medium">{{ number_format($s->basic_amount, 2) }}</td>
                                <td class="text-end text-info small">{{ number_format($s->bonus, 2) }}</td>
                                <td class="text-end fw-bold text-success">{{ number_format($s->net_amount_due, 2) }}</td>
                                <td>
                                    <span class="badge rounded-pill py-2 px-3 bg-{{ $s->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($s->status) }}
                                    </span>
                                </td>
                                <td>{{ $s->effective_from ? \Carbon\Carbon::parse($s->effective_from)->format('Y-m-d') : '—' }}</td>
                                <td class="text-end text-nowrap">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('employee_salaries.show', $s->id) }}" class="btn btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('employee_salaries.edit', $s->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('employee_salaries.destroy', $s->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete" 
                                                onclick="return confirm('Delete salary record for {{ $s->employee->first_name }} {{ $s->employee->last_name }}?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">No employee salary records found matching your criteria.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($salaries->hasPages())
            <div class="card-footer bg-light border-top">
                <div class="d-flex justify-content-center">
                    {{ $salaries->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection