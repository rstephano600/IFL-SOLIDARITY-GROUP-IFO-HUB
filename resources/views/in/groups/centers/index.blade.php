@extends('layouts.app')
@section('title', 'Group Centers')
@section('page-title', 'Group Collection Centers')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & ACTION BAR --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-geo-alt-fill text-primary me-2"></i> Group Collection Centers
        </h2>
        <a href="{{ route('group_centers.create') }}" class="btn btn-primary shadow-sm d-flex align-items-center fw-semibold">
            <i class="bi bi-plus-circle me-2"></i> Add New Center
        </a>
    </div>

    {{-- ================================================================= --}}
    {{-- SEARCH, FILTER & EXPORT CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-sm mb-4 no-print border-0">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('group_centers.index') }}" class="row g-3 align-items-center">
                
                {{-- Search Input --}}
                <div class="col-12 col-lg-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, code, or location..." value="{{ request('search') }}">
                    </div>
                </div>
                
                {{-- Status Filter --}}
                <div class="col-6 col-lg-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status')=='1'?'selected':'' }}>Active</option>
                        {{-- NOTE: Changed '1' to '0' for Inactive value --}}
                        <option value="0" {{ request('status')=='0'?'selected':'' }}>Inactive</option> 
                    </select>
                </div>

                {{-- Date Filter (Assuming this is meant to be a filter date, though `group_id` name seems wrong) --}}
                <div class="col-6 col-lg-2">
                    <input type="date" name="date_filter" class="form-control form-control-sm" value="{{ request('date_filter') }}" placeholder="Filter by Date">
                </div>
                
                {{-- Submit Button --}}
                <div class="col-6 col-md-3 col-lg-1 d-grid">
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>
                 {{-- Reset Button --}}
                 <div class="col-6 col-md-3 col-lg-1 d-grid">
                    <a href="{{ route('group_centers.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- CENTERS TABLE --}}
    {{-- ================================================================= --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 15%;">Center Code</th>
                            <th style="width: 25%;">Center Name</th>
                            <th style="width: 20%;">Location</th>
                            <th style="width: 20%;">Collection Officer</th>
                            <th class="text-center" style="width: 10%;">Status</th>
                            <th class="text-center" style="width: 5%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($centers as $key => $center)
                            <tr>
                                <td class="text-center">{{ $centers->firstItem() + $key }}</td>
                                <td class="fw-bold text-primary">{{ $center->center_code }}</td>
                                <td>{{ $center->center_name }}</td>
                                <td class="text-muted">{{ $center->location }}</td>
                                <td>
                                    <i class="bi bi-person me-1"></i>
                                    {{ $center->collection_officer->first_name ?? 'N/A' }} {{ $center->collection_officer->last_name ?? '' }}
                                </td>
                                <td class="text-center">
                                    @if($center->is_active == 1)
                                        <span class="badge bg-success-subtle text-success fw-semibold"><i class="bi bi-check-circle me-1"></i> Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger fw-semibold"><i class="bi bi-x-circle me-1"></i> Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    {{-- ENHANCED: Icon-only buttons with tooltips --}}
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Center Actions">
                                        
                                        {{-- View Button --}}
                                        <a href="{{ route('group_centers.show', $center->id) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        {{-- Edit Button --}}
                                        <a href="{{ route('group_centers.edit', $center->id) }}" class="btn btn-outline-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Center">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        {{-- Delete Form/Button --}}
                                        <form action="{{ route('group_centers.destroy', $center->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this center? This action cannot be undone.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Center">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-5 text-muted h5">
                                <i class="bi bi-info-circle me-2"></i> No collection centers found matching your criteria.
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination in Card Footer --}}
            @if($centers->hasPages())
                <div class="card-footer bg-light border-top">
                    <div class="d-flex justify-content-between align-items-center flex-column flex-sm-row">

                        {{ $centers->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Initialize tooltips for the new action icons
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection