@extends('layouts.configside')

@section('title', 'Workflow Step Details')
@section('page-title', 'Workflow Step Profile')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-list-check"></i>
        </div>
        Workflow Step Profile
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('procurementWorkflowStep') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
        <a href="{{ route('editprocurementWorkflowStep', encrypt($data->id)) }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-pencil me-1"></i> Edit Step
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                    <div>
                        <h4 class="fw-bold text-primary mb-1">{{ $data->step_name }}</h4>
                        <span class="arbif-badge arbif-badge-navy">{{ $data->step_code }}</span>
                        @if($data->is_final_step)
                            <span class="badge bg-danger ms-2"><i class="fas fa-flag-checkered me-1"></i> Final Workflow Step</span>
                        @endif
                    </div>
                    <div>
                        <span class="badge bg-light text-dark border">Sequence Order: {{ $data->step_order }}</span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Associated Procurement Type:</strong>
                        <p class="text-muted mb-2">{{ $data->procurementType->procurement_name ?? 'N/A' }} ({{ $data->procurementType->procurement_code ?? '' }})</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Branch:</strong>
                        <p class="text-muted mb-2">{{ $data->branch->branch_name ?? 'Global / Central' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Assigned Role:</strong>
                        <p class="text-muted mb-2">{{ $data->assigned_role ?? 'Unassigned / Open' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Step Status:</strong>
                        <p class="mb-2">
                            <span class="badge {{ $data->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $data->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>

                    <div class="col-12">
                        <strong>Description:</strong>
                        <p class="text-muted mb-0">{{ $data->description ?? 'No description details provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fas fa-sliders me-2 text-primary"></i>Rules & Enforcements</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-2 border rounded d-flex justify-content-between align-items-center">
                            <span>Mandatory Processing</span>
                            @if($data->is_required) <span class="badge bg-primary">Required</span> @else <span class="badge bg-secondary">Optional</span> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-2 border rounded d-flex justify-content-between align-items-center">
                            <span>Explicit Approval Action</span>
                            @if($data->requires_approval) <span class="badge bg-warning text-dark">Required</span> @else <span class="badge bg-secondary">Not Required</span> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-2 border rounded d-flex justify-content-between align-items-center">
                            <span>Attachment Requirement</span>
                            @if($data->requires_attachment) <span class="badge bg-dark">Attachment Required</span> @else <span class="badge bg-secondary">Optional</span> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-2 border rounded d-flex justify-content-between align-items-center">
                            <span>Remark / Comment</span>
                            @if($data->requires_comment) <span class="badge bg-info text-dark">Comment Required</span> @else <span class="badge bg-secondary">Optional</span> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-2 border rounded d-flex justify-content-between align-items-center">
                            <span>Skippable Step</span>
                            @if($data->can_skip) <span class="badge bg-success">Can Skip</span> @else <span class="badge bg-danger">Cannot Skip</span> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Audit & System Log</h6>

                <div class="mb-3">
                    <small class="text-muted d-block">Record Status</small>
                    <span class="badge bg-success">{{ $data->Status }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Auditing Status</small>
                    <span class="badge bg-warning text-dark">{{ $data->AuditingStatus }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Report Status</small>
                    <span class="badge bg-info text-dark">{{ $data->ReportStatus }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Created By</small>
                    <span class="fw-bold text-dark">{{ $data->creator->name ?? ($data->user->name ?? 'N/A') }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Created At</small>
                    <span class="text-muted">{{ $data->created_at ? $data->created_at->format('d M, Y H:i A') : 'N/A' }}</span>
                </div>

                <div>
                    <small class="text-muted d-block">Last Updated By</small>
                    <span class="fw-bold text-dark">{{ $data->updater->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection