@extends('layouts.workingside')

@section('title', 'Committee Positions')
@section('page-title', 'Committee Positions')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-user-tag"></i>
        </div>
        Committee Positions
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedCommitteePosition') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Positions
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addPositionModal">
            <i class="fas fa-plus me-1"></i> Add Position
        </button>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('committeePosition') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <select name="branch_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Branches --</option>
                    @foreach($branchies as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->branch_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-5">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by position name, code or reference...">
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
                @if(request()->anyFilled(['branch_id', 'search']))
                    <a href="{{ route('committeePosition') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="committeePositionsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reference</th>
                        <th>Code</th>
                        <th>Position Name</th>
                        <th>Branch</th>
                        <th>Assigned Members</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->ReferenceNo }}
                            </span>
                        </td>
                        <td><strong>{{ $item->position_code }}</strong></td>
                        <td>
                            <a href="{{ route('viewCommitteePosition', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ $item->position_name }}
                            </a>
                        </td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                <i class="fas fa-users me-1"></i>{{ $item->committee_members_count }} Member(s)
                            </span>
                        </td>
                        <td>{{ Str::limit($item->description, 40) ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewCommitteePosition', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <a href="{{ route('editCommitteePosition', encrypt($item->id)) }}" class="arbif-btn-edit">
                                    <i class="fas fa-pencil"></i> Edit
                                </a>

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroyCommitteePosition', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to delete this committee position?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No Committee Positions Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $data->links() }}
        </div>
    </div>
</div>

{{-- REGISTER POSITION MODAL --}}
<div class="modal fade arbif-modal" id="addPositionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-user-tag"></i>
                </div>
                <h5 class="modal-title fw-bold">Register Committee Position</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storeCommitteePosition') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select" data-searchable data-placeholder="Search branch..." required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branchies as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Position Code</label>
                            <input type="text" name="position_code" class="form-control" value="{{ old('position_code') }}" placeholder="e.g. POS-CHR (Optional)">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Position Name <span class="text-danger">*</span></label>
                        <input type="text" name="position_name" class="form-control" value="{{ old('position_name') }}" placeholder="e.g. COMMITTEE CHAIRMAN / MEMBER / SECRETARY" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description / Scope of Position</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Brief overview of responsibilities attached to this position...">{{ old('description') }}</textarea>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save Position</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection