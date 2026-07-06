@extends('layouts.app')
@section('title', 'Groups')
@section('page-title', 'Group Directory')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & ACTION BAR --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-people-fill text-primary me-2"></i> Group Directory
        </h2>
        <a href="{{ route('groups.create') }}" class="btn btn-primary shadow-sm d-flex align-items-center fw-semibold">
            <i class="bi bi-person-plus-fill me-2"></i> Add New Group
        </a>
    </div>

    {{-- ================================================================= --}}
    {{-- SEARCH, FILTER & EXPORT CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-sm mb-4 no-print border-0">
        <div class="card-body p-3">
            <form id="groupSearchForm" action="{{ route('groups.index') }}" method="GET" class="row g-3 align-items-center">
                
                {{-- Search Input --}}
                <div class="col-12 col-lg-8">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" id="search_input" class="form-control border-start-0" 
                               placeholder="Search group name, code, or officer..."
                               value="{{ $search }}">
                    </div>
                </div>
                
                {{-- Submit and Reset Buttons --}}
                <div class="col-6 col-md-2 col-lg-2">
                    <button class="btn btn-primary btn-sm w-100 fw-semibold" type="submit">
                        <i class="bi bi-funnel me-1 d-md-none"></i> <span class="d-none d-md-inline">Apply Filter</span>
                    </button>
                </div>
                <div class="col-6 col-md-2 col-lg-2">
                    <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-arrow-clockwise me-1 d-md-none"></i> <span class="d-none d-md-inline">Reset</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- GROUPS TABLE --}}
    {{-- ================================================================= --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light"> {{-- Changed to dark for better contrast --}}
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 15%;">Group Code</th>
                            <th style="width: 25%;">Group Name</th>
                            <th style="width: 10%;">Type</th>
                            <th style="width: 15%;">Officer</th>
                            <th style="width: 10%;">Registered</th>
                            <th class="text-center" style="width: 10%;">Status</th>
                            <th class="text-center" style="width: 10%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $key => $group)
                        <tr>
                            <td class="text-center">{{ $groups->firstItem() + $key }}</td>
                            <td class="fw-bold text-primary">{{ $group->group_code }}</td>
                            <td>{{ $group->group_name }}</td>
                            <td>
                                {{-- Use badge-primary for key data --}}
                                <span class="badge bg-primary-subtle text-primary fw-semibold">{{ $group->group_type ?? '—' }}</span>
                            </td>
                            <td>{{ $group->creditOfficer->first_name ?? '—' }} {{ $group->creditOfficer->last_name ?? '—' }}</td>
                            <td>{{ $group->registration_date ? \Carbon\Carbon::parse($group->registration_date)->format('Y-m-d') : '—' }}</td>
                            <td class="text-center">
                                @if($group->is_active)
                                    <span class="badge bg-success-subtle text-success fw-semibold"><i class="bi bi-check-circle me-1"></i> Active</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger fw-semibold"><i class="bi bi-x-circle me-1"></i> Inactive</span>
                                @endif
                            </td>
                            <td class="text-center text-nowrap">
                                {{-- ENHANCED: Use icons only and smaller buttons for a clean look --}}
                                <div class="btn-group btn-group-sm" role="group" aria-label="Group Actions">
                                    
                                    {{-- View Button --}}
                                    <a href="{{ route('groups.show', $group->id) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="View Group Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    {{-- Edit Button --}}
                                    <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-outline-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Group">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    {{-- Delete Form/Button --}}
                                    <form action="{{ route('groups.destroy', $group->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this group? This action cannot be undone.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Group">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-5 text-muted h5">
                            <i class="bi bi-info-circle me-2"></i> No groups found matching your criteria.
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination in Card Footer --}}
            @if($groups->hasPages())
                <div class="card-footer bg-light border-top">
                    <div class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                        {{ $groups->links('pagination::bootstrap-5') }}
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