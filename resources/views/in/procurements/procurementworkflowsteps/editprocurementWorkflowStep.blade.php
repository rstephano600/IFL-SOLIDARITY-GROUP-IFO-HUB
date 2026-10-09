@extends('layouts.configside')

@section('title', 'Edit Workflow Step')
@section('page-title', 'Edit Workflow Step')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-pencil-alt"></i>
        </div>
        Edit Workflow Step
    </h3>

    <div>
        <a href="{{ route('procurementWorkflowStep') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Steps
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('updateprocurementWorkflowStep', encrypt($data->id)) }}">
            @csrf

            {{-- BRANCH & PROCUREMENT TYPE --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $data->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Procurement Type <span class="text-danger">*</span></label>
                    <select name="procurement_type_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                        <option value="">-- Select Procurement Type --</option>
                        @foreach($procurementTypes as $type)
                            <option value="{{ $type->id }}" {{ old('procurement_type_id', $data->procurement_type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->procurement_name }} ({{ $type->procurement_code }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- STEP CODE, STEP NAME & ORDER --}}
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Step Code <span class="text-danger">*</span></label>
                    <input type="text" name="step_code" class="form-control" value="{{ old('step_code', $data->step_code) }}" placeholder="e.g. STEP-HOD-APP" required>
                </div>

                <div class="col-md-5">
                    <label class="form-label">Step Name <span class="text-danger">*</span></label>
                    <input type="text" name="step_name" class="form-control" value="{{ old('step_name', $data->step_name) }}" placeholder="e.g. HOD APPROVAL" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sequence Order <span class="text-danger">*</span></label>
                    <input type="number" name="step_order" class="form-control" value="{{ old('step_order', $data->step_order) }}" min="1" required>
                </div>
            </div>

            {{-- PERMISSIONS ROW --}}
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">View Permission</label>
                    <select name="view_permission" class="form-select" data-searchable data-placeholder="Search ...">
                        <option value="">Select View Permission</option>
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->slug }}" {{ old('view_permission', $data->view_permission) == $permission->slug ? 'selected' : '' }}>
                                {{ $permission->slug }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Approval Permission</label>
                    <select name="approval_permission" class="form-select" data-searchable data-placeholder="Search ...">
                        <option value="">Select Approval Permission</option>
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->slug }}" {{ old('approval_permission', $data->approval_permission) == $permission->slug ? 'selected' : '' }}>
                                {{ $permission->slug }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Rejection Permission</label>
                    <select name="rejection_permission" class="form-select" data-searchable data-placeholder="Search ...">
                        <option value="">Select Rejection Permission</option>
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->slug }}" {{ old('rejection_permission', $data->rejection_permission) == $permission->slug ? 'selected' : '' }}>
                                {{ $permission->slug }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ASSIGNED ROLE & DESCRIPTION --}}
            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <label class="form-label">Assigned Role</label>
                    <input type="text" name="assigned_role" class="form-control" value="{{ old('assigned_role', $data->assigned_role) }}" placeholder="e.g. Manager, Loanofficer, Cashier...">
                </div>

                <div class="col-md-7">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="2" class="form-control" placeholder="Explain what occurs during this approval/processing step...">{{ old('description', $data->description) }}</textarea>
                </div>
            </div>

            {{-- CONFIGURATION SWITCHES --}}
            <h6 class="fw-bold border-bottom pb-2 mb-3 text-primary"><i class="fas fa-sliders me-2"></i>Step Rules & Behaviors</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_required" id="is_req" {{ old('is_required', $data->is_required) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_req">Mandatory Step</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_act" {{ old('is_active', $data->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_act">Active Status</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="can_skip" id="can_skp" {{ old('can_skip', $data->can_skip) ? 'checked' : '' }}>
                        <label class="form-check-label" for="can_skp">Allow Skipped Processing</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_final_step" id="is_fnl" {{ old('is_final_step', $data->is_final_step) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_fnl">Mark as Final Step in Workflow</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_approval" id="req_appr" {{ old('requires_approval', $data->requires_approval) ? 'checked' : '' }}>
                        <label class="form-check-label" for="req_appr">Requires Explicit Approval Action</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_attachment" id="req_att" {{ old('requires_attachment', $data->requires_attachment) ? 'checked' : '' }}>
                        <label class="form-check-label" for="req_att">Requires Document Attachment</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_comment" id="req_cmm" {{ old('requires_comment', $data->requires_comment) ? 'checked' : '' }}>
                        <label class="form-check-label" for="req_cmm">Requires User Remark/Comment</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('procurementWorkflowStep') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Step</button>
            </div>
        </form>
    </div>
</div>

@endsection