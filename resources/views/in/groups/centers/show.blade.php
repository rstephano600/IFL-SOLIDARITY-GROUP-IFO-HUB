@extends('layouts.app')
@section('title', 'Groups Centers')
@section('page-title', 'Groups Center Details')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-geo-alt-fill text-primary me-2"></i> Center Details: <span class="text-primary">{{ $groupCenter->center_name }}</span>
        </h2>
        <div class="btn-group shadow-sm" role="group">
            <a href="{{ route('group_centers.index') }}" class="btn btn-secondary d-flex align-items-center">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
            <a href="{{ route('group_centers.edit', $groupCenter->id) }}" class="btn btn-warning d-flex align-items-center">
                <i class="bi bi-pencil-square me-1"></i> Edit Center
            </a>
            {{-- Optional: Add an Export button here if needed for single center data --}}
            {{-- <button onclick="exportCenterData({{ $groupCenter->id }})" class="btn btn-success d-flex align-items-center">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Data
            </button> --}}
        </div>
    </div>

    <div class="card shadow-lg border-start ">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0 text-primary">General Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                
                <div class="col-md-6 col-lg-4">
                    <p class="mb-0"><strong>Center Code:</strong></p>
                    <p class="h6 text-dark">{{ $groupCenter->center_code }}</p>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <p class="mb-0"><strong>Collection Officer:</strong></p>
                    <p class="h6 text-dark">{{ $groupCenter->collection_officer->first_name ?? '—' }} {{ $groupCenter->collection_officer->last_name ?? '' }}</p>
                </div>

                <div class="col-md-6 col-lg-4">
                    <p class="mb-0"><strong>Location:</strong></p>
                    <p class="h6 text-dark">{{ $groupCenter->location }}</p>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <p class="mb-0"><strong>Status:</strong></p>
                    <p class="h6 mb-0">
                        <span class="badge bg-{{ $groupCenter->is_active == 1 ? 'success' : 'danger' }} fs-6">
                            {{ ucfirst($groupCenter->status) }}
                        </span>
                    </p>
                </div>
                
                <div class="col-12 mt-4">
                    <p class="mb-2"><strong>Description:</strong></p>
                    <div class="border p-3 rounded bg-light">
                        <p class="mb-0 text-muted">{{ $groupCenter->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    {{-- ✅ Groups under this Center --}}
    <div class="card shadow-lg mt-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-light py-3">
            <h5 class="mb-0 text-primary">Groups in this Center</h5>

            <a href="{{ route('groups.create', ['center_id' => $groupCenter->id, 'credit_officer_id' => $groupCenter->collection_officer_id]) }}" class="btn btn-primary btn-sm d-flex align-items-center">
             <i class="bi bi-plus-lg me-1"></i> Add Group to this Center
            </a>
        </div>

        <div class="card-body">
            @if($groupCenter->groups->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Group Name</th>
                                <th>Group Code</th>
                                <th>Loan Officer</th>
                                <th>Location</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupCenter->groups as $i => $group)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $group->group_name }}</td>
                                    <td>{{ $group->group_code }}</td>
                                    <td>{{ $group->loanOfficer->first_name ?? '—' }} {{ $group->loanOfficer->last_name ?? '—' }}</td>
                                    <td>{{ $group->location }}</td>
                                    <td>{{ $group->registration_date ? $group->registration_date->format('Y-m-d') : '—' }}</td>
                                    <td>
                                        <a href="{{ route('groups.show', $group->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">No groups have been created under this center yet.</p>
            @endif
        </div>
    </div>
</div>





{{-- ✅ Inline Add Group Form --}}
        <div id="addGroupForm" class="collapse border-top">
            <div class="card-body bg-light">
                <h5 class="text-primary mb-3">Create New Group</h5>
                <form action="{{ route('groups.store') }}" method="POST">
                    @csrf

                    {{-- Hidden center ID --}}
                    <input type="hidden" name="group_center_id" value="{{ $groupCenter->id }}">

                    <div class="row g-3">
                        <div class="col-md-6 col-lg-4">
                            <label for="group_name" class="form-label">Group Name <span class="text-danger">*</span></label>
                            <input type="text" name="group_name" id="group_name" class="form-control" required>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <label for="group_type" class="form-label">Group Type</label>
                            <input type="text" name="group_type" id="group_type" class="form-control">
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" name="location" id="location" class="form-control" value="{{ $groupCenter->location }}">
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <label for="credit_officer_id" class="form-label">Loan Officer</label>
                            <select name="credit_officer_id" id="credit_officer_id" class="form-select">
                                <option value="">-- Select Officer --</option>
                                @foreach ($creditOfficers as $officer)
                                    <option value="{{ $officer->id }}">{{ $officer->first_name }} {{ $officer->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <label for="registration_date" class="form-label">Registration Date</label>
                            <input type="date" name="registration_date" id="registration_date" class="form-control">
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Save Group
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#addGroupForm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    
    {{-- Placeholder for Group Center Members or Meetings Schedule --}}
    <!-- <div class="mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i> Related Data (e.g., Meeting Schedule)</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">Data structure for members or meeting schedule can be added here.</p>
            </div>
        </div>
    </div> -->

@endsection
