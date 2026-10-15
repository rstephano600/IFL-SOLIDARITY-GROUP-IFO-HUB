@extends('layouts.workingside')

@section('title', 'View Committee Position')
@section('page-title', 'Position Details')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-user-tag"></i>
        </div>
        Position Profile: {{ $data->position_name }}
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('editCommitteePosition', encrypt($data->id)) }}" class="arbif-btn-edit">
            <i class="fas fa-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('committeePosition') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Positions
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        {{-- OVERVIEW CARD --}}
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> Position Overview</h6>

                <table class="table table-borderless align-middle mb-0">
                    <tr>
                        <th style="width: 30%;" class="text-muted">Reference Code:</th>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $data->ReferenceNo }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Position Code:</th>
                        <td class="fw-bold">{{ $data->position_code }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Position Name:</th>
                        <td class="fw-bold text-primary fs-6">{{ $data->position_name }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Branch:</th>
                        <td>{{ $data->branch->branch_name ?? 'Global / Central' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Company:</th>
                        <td>{{ $data->company->company_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Description:</th>
                        <td>{{ $data->description ?? 'No description provided.' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- MEMBERS ASSIGNED TO THIS POSITION --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-users me-1"></i> Members Holding This Position</h6>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Committee</th>
                                <th>Member Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data->committeeMembers as $idx => $member)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><strong>{{ $member->committee->committee_name ?? 'N/A' }}</strong></td>
                                <td>{{ $member->user->name ?? $member->member_name ?? 'N/A' }}</td>
                                <td><span class="badge bg-success">{{ $member->membership_status ?? 'Active' }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No members currently assigned to this position.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- METADATA CARD --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-building me-1"></i> System Information</h6>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Status:</span>
                        <span class="badge bg-success">{{ $data->Status }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Auditing Status:</span>
                        <span class="badge bg-warning text-dark">{{ $data->AuditingStatus }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Created By:</span>
                        <span>{{ $data->user->name ?? 'System' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Created At:</span>
                        <span>{{ $data->created_at ? $data->created_at->format('d M, Y H:i') : 'N/A' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection