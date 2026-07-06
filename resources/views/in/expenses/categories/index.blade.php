@extends('layouts.app')

@section('title', 'Manage Expense Categories')
@section('page-title', 'Expense Categories')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & NEW BUTTON --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-tag-fill me-2 text-danger"></i> Expense Categories
        </h2>
        <a href="{{ route('expense-categories.create') }}" class="btn btn-danger shadow-sm fw-semibold">
            <i class="bi bi-plus-circle-fill me-2"></i> Add New Category
        </a>
    </div>

    {{-- ================================================================= --}}
    {{-- SEARCH CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-8 col-lg-5">
                    <label for="search" class="form-label fw-semibold small visually-hidden">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" 
                            placeholder="Search by name or description">
                    </div>
                </div>
                
                <div class="col-6 col-md-2 col-lg-auto">
                    <button class="btn btn-primary btn-sm fw-semibold w-100" type="submit">Filter</button>
                </div>
                
                <div class="col-6 col-md-2 col-lg-auto">
                    <a href="{{ route('expense-categories.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- CATEGORY TABLE --}}
    {{-- ================================================================= --}}
    @if($categories->count() > 0)
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 25%;">Name</th>
                                <th style="width: 45%;">Description</th>
                                <th style="width: 10%;" class="text-center">Status</th>
                                <th style="width: 15%;" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $i => $category)
                            <tr>
                                <td>{{ $categories->firstItem() + $i }}</td>
                                <td class="fw-bold">{{ $category->name }}</td>
                                <td class="small text-muted">{{ $category->description ?? '—' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $category->status === 'active' ? 'success' : 'secondary' }} fw-semibold">
                                        {{ ucfirst($category->status) }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <div class="btn-group btn-group-sm" role="group">
                                        {{-- View --}}
                                        <a href="{{ route('expense-categories.show', $category->id) }}" class="btn btn-outline-info" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        {{-- Edit --}}
                                        <a href="{{ route('expense-categories.edit', $category->id) }}" class="btn btn-outline-warning" title="Edit Category">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        {{-- Delete Form --}}
                                        <form action="{{ route('expense-categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete Category"
                                                onclick="return confirm('Are you sure you want to delete the category \'{{ $category->name }}\'? This action cannot be undone and may affect existing expenses.');">
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

                {{-- Pagination in Card Footer --}}
                @if($categories->hasPages())
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-center">
                            {{ $categories->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-info border-0 shadow-sm text-center py-4">
            <i class="bi bi-info-circle-fill display-5 d-block mb-3"></i>
            No expense categories found.
            <br>
            <a href="{{ route('expense-categories.create') }}" class="btn btn-danger mt-3 fw-semibold">
                <i class="bi bi-plus-circle-fill me-2"></i> Create the first category
            </a>
        </div>
    @endif
</div>
@endsection