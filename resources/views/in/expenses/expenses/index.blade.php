@extends('layouts.app')

@section('title', 'Expense Records')
@section('page-title', 'Company Expenses')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & TOTAL SUMMARY --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-wallet2 me-2 text-danger"></i> Expense Records
        </h2>
        <a href="{{ route('expenses.create') }}" class="btn btn-danger shadow-sm fw-semibold">
            <i class="bi bi-plus-circle-fill me-2"></i> Add New Expense
        </a>
    </div>

    {{-- ================================================================= --}}
    {{-- FILTERS & ACTIONS CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <h6 class="card-title fw-bold mb-3 text-danger"><i class="bi bi-funnel me-1"></i> Filter Expenses</h6>
            
            {{-- Filters Form --}}
            <form method="GET" action="{{ route('expenses.index') }}" class="row g-3 align-items-end">
                
                {{-- Search --}}
                <div class="col-12 col-lg-3 col-md-4">
                    <label class="form-label small fw-semibold">Search Title/Description</label>
                    <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Title or category...">
                </div>

                {{-- Category --}}
                <div class="col-12 col-lg-3 col-md-4">
                    <label class="form-label small fw-semibold">Category</label>
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">-- All Categories --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Date Range --}}
                <div class="col-6 col-lg-2 col-md-2">
                    <label class="form-label small fw-semibold">Start Date</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                </div>
                <div class="col-6 col-lg-2 col-md-2">
                    <label class="form-label small fw-semibold">End Date</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                </div>

                {{-- Action Buttons --}}
                <div class="col-12 col-lg-2 col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill fw-semibold">
                        <i class="bi bi-filter"></i> Apply
                    </button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    {{-- ================================================================= --}}
    {{-- DATA TABLE CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-lg border-0">
        
        {{-- Total Summary & Export Buttons in Header --}}
        <div class="card-header bg-white border-bottom d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3">
            
            <h5 class="mb-2 mb-md-0 fw-bold">
                Total Amount Used: 
                <span class="text-danger">Tsh {{ number_format($totalUsed, 2) }}</span>
            </h5>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('expenses.export.excel', request()->query()) }}" class="btn btn-success btn-sm fw-semibold">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('expenses.export.pdf', request()->query()) }}" class="btn btn-danger btn-sm fw-semibold">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            @if($expenses->isEmpty())
                <div class="alert alert-info border-0 m-4 text-center py-4">
                    <i class="bi bi-journal-x display-5 d-block mb-3"></i>
                    No expense records found matching the current criteria.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 3%;">#</th>
                                <th style="width: 25%;">Title</th>
                                <th style="width: 15%;">Category</th>
                                <th style="width: 10%;">Date</th>
                                <th style="width: 15%;">Amount (Tsh)</th>
                                <th style="width: 15%;">Created By</th>
                                <th style="width: 7%;">Status</th>
                                <th style="width: 10%;" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $index => $expense)
                                <tr>
                                    <td>{{ $expenses->firstItem() + $index }}</td>
                                    <td class="fw-semibold">{{ $expense->expense_title }}</td>
                                    <td><span class="badge bg-secondary-subtle text-dark">{{ $expense->category->name ?? 'N/A' }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') }}</td>
                                    <td class="text-danger fw-bold">{{ number_format($expense->total_amount, 2) }}</td>
                                    <td><i class="bi bi-person me-1"></i> {{ $expense->creator->name ?? 'System' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $expense->status === 'approved' ? 'success' : ($expense->status === 'pending' ? 'warning text-dark' : 'secondary') }} fw-semibold">
                                            {{ ucfirst($expense->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-outline-info" title="View"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-outline-warning" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                            <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete" 
                                                        onclick="return confirm('Are you sure you want to delete this expense record?');">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($expenses->hasPages())
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-center">
                            {{ $expenses->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection