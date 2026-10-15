@extends('layouts.configside')

@section('title', 'Committee Members')
@section('page-title', 'Committee Members')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-users-cog"></i>
        </div>
        Committee Members
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedCommitteeMember') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Members
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addMemberModal">
            <i class="fas fa-plus me-1"></i> Appoint Member
        </button>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('committeeMember') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="committee_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Filter by Committee --</option>
                    @foreach($committees as $committee)
                        <option value="{{ $committee->id }}" {{ request('committee_id') == $committee->id ? 'selected' : '' }}>
                            {{ $committee->committee_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="committee_position_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Filter by Position --</option>
                    @foreach($positions as $pos)
                        <option value="{{ $pos->id }}" {{ request('committee_position_id') == $pos->id ? 'selected' : '' }}>
                            {{ $pos->position_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="membership_status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Status --</option>
                    <option value="Active" {{ request('membership_status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ request('membership_status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="Suspended" {{ request('membership_status') == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="Resigned" {{ request('membership_status') == 'Resigned' ? 'selected' : '' }}>Resigned</option>
                </select>
            </div>

            <div class="col-md-2">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search ref, member...">
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
                @if(request()->anyFilled(['committee_id', 'committee_position_id', 'membership_status', 'search']))
                    <a href="{{ route('committeeMember') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="committeeMembersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reference</th>
                        <th>Member Name</th>
                        <th>Committee</th>
                        <th>Position</th>
                        <th>Appointed Date</th>
                        <th>Responsibilities</th>
                        <th>Status</th>
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
                        <td>
                            <a href="{{ route('viewCommitteeMember', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ $item->member->full_name ?? $item->member->first_name . ' ' . $item->member->last_name }}
                            </a>
                            <br><small class="text-muted"><i class="fas fa-id-card me-1"></i>{{ $item->member->member_code ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $item->committee->committee_name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $item->position->position_name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $item->appointment_date ? $item->appointment_date->format('d M, Y') : 'N/A' }}</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                <i class="fas fa-tasks me-1"></i>{{ $item->active_responsibilities_count }} Task(s)
                            </span>
                        </td>
                        <td>
                            @php
                                $statusClasses = [
                                    'Active' => 'bg-success',
                                    'Inactive' => 'bg-secondary',
                                    'Suspended' => 'bg-warning text-dark',
                                    'Resigned' => 'bg-danger',
                                ];
                            @endphp
                            <span class="badge {{ $statusClasses[$item->membership_status] ?? 'bg-secondary' }}">
                                {{ $item->membership_status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewCommitteeMember', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <a href="{{ route('editCommitteeMember', encrypt($item->id)) }}" class="arbif-btn-edit">
                                    <i class="fas fa-pencil"></i> Edit
                                </a>

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroyCommitteeMember', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to remove this member from the committee?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Committee Members Found</td>
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

{{-- APPOINT COMMITTEE MEMBER MODAL --}}
<div class="modal fade arbif-modal" id="addMemberModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h5 class="modal-title fw-bold">Appoint Committee Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storeCommitteeMember') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Committee <span class="text-danger">*</span></label>
                            <select name="committee_id" class="form-select" data-searchable data-placeholder="Select committee..." required>
                                <option value="">-- Select Committee --</option>
                                @foreach($committees as $committee)
                                    <option value="{{ $committee->id }}" {{ old('committee_id') == $committee->id ? 'selected' : '' }}>
                                        {{ $committee->committee_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Member <span class="text-danger">*</span></label>
                            <select name="member_id" class="form-select" data-searchable data-placeholder="Select member..." required>
                                <option value="">-- Select Member --</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->first_name }} {{ $member->last_name }} ({{ $member->member_code ?? 'ID: '.$member->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Position / Role <span class="text-danger">*</span></label>
                            <select name="committee_position_id" class="form-select" required>
                                <option value="">-- Select Position --</option>
                                @foreach($positions as $pos)
                                    <option value="{{ $pos->id }}" {{ old('committee_position_id') == $pos->id ? 'selected' : '' }}>
                                        {{ $pos->position_name }} ({{ $pos->position_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Membership Status <span class="text-danger">*</span></label>
                            <select name="membership_status" class="form-select" required>
                                <option value="Active" {{ old('membership_status') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('membership_status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="Suspended" {{ old('membership_status') == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="Resigned" {{ old('membership_status') == 'Resigned' ? 'selected' : '' }}>Resigned</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Appointment Date <span class="text-danger">*</span></label>
                            <input type="date" name="appointment_date" class="form-control" value="{{ old('appointment_date', date('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">End Date (Term Expiry)</label>
                            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Appointment Notes / Remarks</label>
                        <textarea name="appointment_notes" rows="3" class="form-control" placeholder="Provide appointment details, terms, or reference notes...">{{ old('appointment_notes') }}</textarea>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Appoint Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection