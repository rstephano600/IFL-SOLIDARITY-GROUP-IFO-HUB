@extends('layouts.configside')

@section('title', 'View Committee Member')
@section('page-title', 'Member Profile & Responsibilities')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-user-check"></i>
        </div>
        Member Appointment Profile
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('editCommitteeMember', encrypt($data->id)) }}" class="arbif-btn-edit">
            <i class="fas fa-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('committeeMember') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Members
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        {{-- OVERVIEW CARD --}}
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-id-card me-1"></i> Appointment Information</h6>

                <table class="table table-borderless align-middle mb-0">
                    <tr>
                        <th style="width: 30%;" class="text-muted">Reference Code:</th>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $data->ReferenceNo }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Member Name:</th>
                        <td class="fw-bold text-primary fs-6">
                            {{ $data->member->full_name ?? $data->member->first_name . ' ' . $data->member->last_name }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Committee:</th>
                        <td class="fw-bold">{{ $data->committee->committee_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Position / Role:</th>
                        <td><span class="badge bg-light text-dark border fw-bold">{{ $data->position->position_name ?? 'N/A' }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Appointment Date:</th>
                        <td>{{ $data->appointment_date ? $data->appointment_date->format('d M, Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Term End Date:</th>
                        <td>{{ $data->end_date ? $data->end_date->format('d M, Y') : 'Ongoing / No fixed end' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Membership Status:</th>
                        <td><span class="badge bg-success">{{ $data->membership_status }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Appointment Notes:</th>
                        <td>{{ $data->appointment_notes ?? 'No notes recorded.' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ASSIGNED RESPONSIBILITIES --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-tasks me-1"></i> Assigned Responsibilities</h6>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Responsibility / Task</th>
                                <th>Assigned Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data->responsibilities as $idx => $resp)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><strong>{{ $resp->responsibility_name ?? $resp->title ?? 'N/A' }}</strong></td>
                                <td>{{ $resp->created_at ? $resp->created_at->format('d M, Y') : 'N/A' }}</td>
                                <td><span class="badge bg-info text-dark">{{ $resp->Status ?? 'Active' }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No specific responsibilities assigned to this member yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- SYSTEM INFORMATION CARD --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-building me-1"></i> System Information</h6>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Branch:</span>
                        <span>{{ $data->branch->branch_name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Company:</span>
                        <span>{{ $data->company->company_name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Record Status:</span>
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