@extends('layouts.configside')

@section('title', 'Procurement Workflow Steps')
@section('page-title', 'Procurement Workflow Steps')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-list-check"></i>
        </div>
        Procurement Workflow Steps
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedprocurementWorkflowStep') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Steps
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addWorkflowStepModal">
            <i class="fas fa-plus me-1"></i> Add Workflow Step
        </button>
    </div>
</div>

{{-- FILTER BY PROCUREMENT TYPE --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('procurementWorkflowStep') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <select name="procurement_type_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Procurement Types --</option>
                    @foreach($procurementTypes as $type)
                        <option value="{{ $type->id }}" {{ request('procurement_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->procurement_name }} ({{ $type->procurement_code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                @if(request()->filled('procurement_type_id'))
                    <a href="{{ route('procurementWorkflowStep') }}" class="btn btn-sm btn-secondary text-white w-100">Reset Filter</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="workflowStepTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Step Name</th>
                        <th>Order</th>
                        <th>Procurement Type</th>
                        <th>View Permissions</th>
                        <th>Approval Permissions</th>
                        <th>Rejection Permissions</th>
                        <th>Status</th>
                        <th>Rules / Flags</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->step_code }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ $item->step_name }}</strong>
                            @if($item->is_final_step)
                                <span class="badge bg-danger ms-1">Final Step</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border fw-bold">{{ $item->step_order }}</span>
                        </td>
                        <td>{{ $item->procurementType->procurement_name ?? 'N/A' }}</td>
                        <td>
                            @if($item->view_permission)
                                <span class="badge bg-info text-dark"><i class="fas fa-user-shield me-1"></i>{{ $item->view_permission }}</span>
                            @else
                                <span class="text-muted small">Unassigned</span>
                            @endif
                        </td>
                        <td>
                            @if($item->approval_permission)
                                <span class="badge bg-info text-dark"><i class="fas fa-user-shield me-1"></i>{{ $item->approval_permission }}</span>
                            @else
                                <span class="text-muted small">Unassigned</span>
                            @endif
                        </td>
                        <td>
                            @if($item->rejection_permission)
                                <span class="badge bg-info text-dark"><i class="fas fa-user-shield me-1"></i>{{ $item->rejection_permission }}</span>
                            @else
                                <span class="text-muted small">Unassigned</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('toggleprocurementWorkflowStep', encrypt($item->id)) }}" class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }} text-decoration-none">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </a>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @if($item->is_required) <span class="badge bg-primary">Required</span> @endif
                                @if($item->requires_approval) <span class="badge bg-warning text-dark">Approval</span> @endif
                                @if($item->requires_attachment) <span class="badge bg-dark">Attachment</span> @endif
                                @if($item->requires_comment) <span class="badge bg-secondary">Comment</span> @endif
                                @if($item->can_skip) <span class="badge bg-light text-dark border">Skippable</span> @endif
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('viewprocurementWorkflowStep', encrypt($item->id)) }}" class="arbif-btn-view">
                                <i class="fas fa-eye"></i> view
                            </a>

                            <a href="{{ route('editprocurementWorkflowStep', encrypt($item->id)) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> edit
                            </a>

                            @can('view-generally-delete-of-any-item')
                            <a href="{{ route('destroyprocurementWorkflowStep', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to remove this workflow step?')">
                                <i class="fas fa-trash"></i> del
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Workflow Steps Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- REGISTER WORKFLOW STEP MODAL --}}
<div class="modal fade arbif-modal" id="addWorkflowStepModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-list-check"></i>
                </div>
                <h5 class="modal-title fw-bold">Register Workflow Step</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storeprocurementWorkflowStep') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branchies as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Procurement Type <span class="text-danger">*</span></label>
                            <select name="procurement_type_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">-- Select Procurement Type --</option>
                                @foreach($procurementTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('procurement_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->procurement_name }} ({{ $type->procurement_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Step Code <span class="text-danger">*</span></label>
                            <input type="text" name="step_code" class="form-control" placeholder="e.g. STEP-HOD-APP" required>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">Step Name <span class="text-danger">*</span></label>
                            <input type="text" name="step_name" class="form-control" placeholder="e.g. HOD APPROVAL" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Sequence Order <span class="text-danger">*</span></label>
                            <input type="number" name="step_order" class="form-control" value="1" min="1" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">View Permission</label>
                            <select name="view_permission" class="form-select"data-searchable data-placeholder="Search ..." >
                                <option value="">Select View Permission</option>
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->slug }}">
                                        {{ $permission->slug }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Approval Permission <span class="text-danger"></span></label>
                            <select name="approval_permission" class="form-select"data-searchable data-placeholder="Search ..." >
                                <option value="">Select View Permission</option>
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->slug }}">
                                        {{ $permission->slug }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Rejection Permission <span class="text-danger"></span></label>
                            <select name="rejection_permission" class="form-select"data-searchable data-placeholder="Search ..." >
                                <option value="">Select View Permission</option>
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->slug }}">
                                        {{ $permission->slug }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-5">
                            <label class="form-label">Assigned Role</label>
                            <input type="text" name="assigned_role" class="form-control" placeholder="e.g. Manager, Loanofficer, Cashier...">
                        </div>

                        <div class="col-md-7">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="2" class="form-control" placeholder="Explain what occurs during this approval/processing step..."></textarea>
                        </div>
                    </div>

                    {{-- CONFIGURATION SWITCHES --}}
                    <h6 class="fw-bold border-bottom pb-2 mb-3 text-primary"><i class="fas fa-sliders me-2"></i>Step Rules & Behaviors</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_required" id="is_req" checked>
                                <label class="form-check-label" for="is_req">Mandatory Step</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_act" checked>
                                <label class="form-check-label" for="is_act">Active Status</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="can_skip" id="can_skp">
                                <label class="form-check-label" for="can_skp">Allow Skipped Processing</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_final_step" id="is_fnl">
                                <label class="form-check-label" for="is_fnl">Mark as Final Step in Workflow</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="requires_approval" id="req_appr" checked>
                                <label class="form-check-label" for="req_appr">Requires Explicit Approval Action</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="requires_attachment" id="req_att">
                                <label class="form-check-label" for="req_att">Requires Document Attachment</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="requires_comment" id="req_cmm">
                                <label class="form-check-label" for="req_cmm">Requires User Remark/Comment</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save Step</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new DataTable('#workflowStepTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush