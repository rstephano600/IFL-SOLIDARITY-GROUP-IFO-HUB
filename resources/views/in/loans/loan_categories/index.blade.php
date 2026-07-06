@extends('layouts.app')

@section('title', 'Manage Loan Categories')
@section('page-title', 'Manage Loan Categories')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & NEW BUTTON --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-tags-fill me-2 text-info"></i> Loan Categories Management
        </h2>
        <a href="{{ route('loan_categories.create') }}" class="btn btn-primary shadow-sm fw-semibold">
            <i class="bi bi-plus-circle-fill me-2"></i> New Category
        </a>
    </div>

    {{-- ================================================================= --}}
    {{-- SEARCH AND FILTERS CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <h6 class="card-title fw-bold mb-3 text-primary">
                <i class="bi bi-filter-square me-1"></i> Category Filters
            </h6>
            <form method="GET" class="row g-2 align-items-end">
                
                <div class="col-12 col-md-4 col-lg-3">
                    <label for="search" class="form-label fw-semibold small">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search name or condition"
                            value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-6 col-md-2 col-lg-2">
                    <label for="status" class="form-label fw-semibold small">Status</label>
                    <select name="status" id="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <div class="col-6 col-md-2 col-lg-2">
                    <label for="currency" class="form-label fw-semibold small">Currency</label>
                    <select name="currency" id="currency" class="form-select form-select-sm">
                        <option value="">All Currencies</option>
                        <option value="TZS" {{ request('currency')=='TZS' ? 'selected' : '' }}>TZS</option>
                        <option value="USD" {{ request('currency')=='USD' ? 'selected' : '' }}>USD</option>
                        {{-- Add more currencies as needed --}}
                    </select>
                </div>
                
                <div class="col-6 col-md-2 col-lg-2">
                    <label for="frequency" class="form-label fw-semibold small">Repayment Freq.</label>
                    <select name="frequency" id="frequency" class="form-select form-select-sm">
                        <option value="">All Frequencies</option>
                        @foreach(['daily','weekly','bi_weekly','monthly','quarterly'] as $f)
                            <option value="{{ $f }}" {{ request('frequency')==$f ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ', $f)) }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-6 col-md-2 col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('loan_categories.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- CATEGORY TABLE --}}
    {{-- ================================================================= --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 3%;">#</th>
                            <th style="width: 20%;">Name</th>
                            <th style="width: 15%;">Max Amount</th>
                            <th style="width: 10%;">Interest</th>
                            <th style="width: 15%;">Repayment</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 12%;">Created </th>
                            <th style="width: 15%;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loanCategories as $cat)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-bold text-primary">{{ $cat->name ?? '-' }}</td>
                                <td class="text-nowrap">
                                    {{ number_format($cat->amount_disbursed,2) }} 
                                    <span class="text-muted small">{{ $cat->currency ?? config('app.currency','TZS') }}</span>
                                </td>
                                <td><span class="badge bg-info fw-semibold">{{ $cat->interest_rate }}%</span></td>
                                <td>
                                    {{ ucfirst(str_replace('_',' ', $cat->repayment_frequency)) }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $cat->is_active ? 'success' : 'secondary' }} fw-semibold">
                                        {{ $cat->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($cat->created_at)->format('Y-m-d') }}</td>
                                <td class="text-end text-nowrap">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('loan_categories.show',$cat) }}" class="btn btn-outline-info" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('loan_categories.edit',$cat) }}" class="btn btn-outline-warning" title="Edit Category">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('loan_categories.destroy',$cat) }}" method="POST" class="d-inline">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete Category"
                                                    onclick="return confirm('WARNING: Are you sure you want to delete the category \'{{ $cat->name }}\'? This action cannot be undone and may affect existing loans.')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-folder-open display-5 d-block mb-3"></i>
                                No loan categories found.
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination --}}
        @if ($loanCategories->hasPages())
            <div class="card-footer bg-light border-top">
                <div class="d-flex justify-content-center">
                    {{ $loanCategories->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection