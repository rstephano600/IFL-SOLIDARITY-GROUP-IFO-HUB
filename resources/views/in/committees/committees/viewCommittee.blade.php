@extends('layouts.workingside')

@section('title', 'View Committee Details')
@section('page-title', 'Committee Details')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-users-cog"></i>
        </div>
        Committee: {{ $data->committee_name }}
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('editCommittee', encrypt($data->id)) }}" class="arbif-btn-edit">
            <i class="fas fa-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('committee') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        {{-- GENERAL OVERVIEW CARD --}}
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> Committee Overview</h6>

                <table class="table table-borderless align-middle mb-0">
                    <tr>
                        <th style="width: 30%;" class="text-muted">Reference Code:</th>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $data->ReferenceNo }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Committee Code:</th>
                        <td class="fw-bold">{{ $data->committee_code }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Chairman:</th>
                        <td><strong>{{ $data->chairman->name ?? 'Unassigned' }}</strong></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Secretary:</th>
                        <td><strong>{{ $data->secretary->name ?? 'Unassigned' }}</strong></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tenure Period:</th>
                        <td>
                            {{ $data->start_date ? $data->start_date->format('d M, Y') : 'N/A' }} 
                            to 
                            {{ $data->end_date ? $data->end_date->format('d M, Y') : 'Ongoing / Indefinite' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Description:</th>
                        <td>{{ $data->description ?? 'No description provided.' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- COMMITTEE MEMBERS LIST --}}
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-users me-1"></i> Committee Active Members</h6>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Member Name</th>
                                <th>Role / Designation</th>
                                <th>Membership Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data->activeMembers as $idx => $member)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><strong>{{ $member->user->name ?? $member->member_name ?? 'N/A' }}</strong></td>
                                <td>{{ $member->role ?? 'Member' }}</td>
                                <td><span class="badge bg-success">{{ $member->membership_status }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No active members registered in this committee.</td>
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
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-building me-1"></i> System Information</h6>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Branch:</span>
                        <span class="fw-bold">{{ $data->branch->branch_name ?? 'Global' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Company:</span>
                        <span>{{ $data->company->company_name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Status:</span>
                        <span class="badge bg-success">{{ $data->Status }}</span>
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