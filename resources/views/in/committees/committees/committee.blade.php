@extends('layouts.workingside')

@section('title', 'Committees Management')
@section('page-title', 'Committees Management')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-users-cog"></i>
        </div>
        Committees Management
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedCommittee') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Committees
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addCommitteeModal">
            <i class="fas fa-plus me-1"></i> Add Committee
        </button>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('committee') }}" class="row g-2 align-items-center">
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
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by name, code or reference...">
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
                @if(request()->anyFilled(['branch_id', 'search']))
                    <a href="{{ route('committee') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="committeesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reference</th>
                        <th>Code</th>
                        <th>Committee Name</th>
                        <th>Chairman</th>
                        <th>Secretary</th>
                        <th>Active Members</th>
                        <th>Branch</th>
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
                        <td><strong>{{ $item->committee_code }}</strong></td>
                        <td>
                            <a href="{{ route('viewCommittee', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ $item->committee_name }}
                            </a>
                        </td>
                        <td>{{ $item->chairman->name ?? 'Unassigned' }}</td>
                        <td>{{ $item->secretary->name ?? 'Unassigned' }}</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                <i class="fas fa-users me-1"></i>{{ $item->active_members_count }} Member(s)
                            </span>
                        </td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewCommittee', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <a href="{{ route('editCommittee', encrypt($item->id)) }}" class="arbif-btn-edit">
                                    <i class="fas fa-pencil"></i> Edit
                                </a>

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroyCommittee', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to delete this committee record?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Active Committees Found</td>
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

{{-- REGISTER COMMITTEE MODAL --}}
<div class="modal fade arbif-modal" id="addCommitteeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h5 class="modal-title fw-bold">Register New Committee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storeCommittee') }}">
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
                            <label class="form-label">Committee Code</label>
                            <input type="text" name="committee_code" class="form-control" value="{{ old('committee_code') }}" placeholder="e.g. COM-TENDER (Optional)">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Committee Name <span class="text-danger">*</span></label>
                        <input type="text" name="committee_name" class="form-control" value="{{ old('committee_name') }}" placeholder="e.g. PROCUREMENT EVALUATION COMMITTEE" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Chairman</label>
                            <select name="chairman_user_id" class="form-select" data-searchable data-placeholder="Search chairman user...">
                                <option value="">-- Select Chairman --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('chairman_user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Secretary</label>
                            <select name="secretary_user_id" class="form-select" data-searchable data-placeholder="Search secretary user...">
                                <option value="">-- Select Secretary --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('secretary_user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', date('Y-m-d')) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Committee Description / Purpose</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Brief explanation of committee responsibilities and mandate...">{{ old('description') }}</textarea>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save Committee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection