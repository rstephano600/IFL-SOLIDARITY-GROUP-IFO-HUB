@extends('layouts.configside')
@section('title', 'Department Profile Details')
@section('page-title', 'Department Profile Details')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-sitemap"></i></div>
        {{ $department->department_name }} <small class="text-muted">({{ $department->department_code }})</small>
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-submit">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h4 class="mb-3 text-navy font-weight-bold"><i class="fas fa-info-circle me-2"></i> Department Core Context</h4>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Department Code</label>
                <span class="arbif-badge arbif-badge-navy mt-1" style="font-size: 0.95rem;">{{ $department->department_code }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Parent Corporate Body</label>
                <span class="fw-bold text-dark d-block mt-1">{{ $department->company->company_name ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Assigned Branch Base</label>
                <span class="fw-bold text-dark d-block mt-1">{{ $department->branch->branch_name ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Functional Target</label>
                <span class="fw-bold text-dark d-block mt-1">{{ $department->function ?? 'General Scope' }}</span>
            </div>

            @if($department->descriptions)
            <div class="col-md-12">
                <hr class="my-2">
                <label class="form-label d-block text-muted mb-1">Functional Mandate & Description</label>
                <div class="p-3 bg-light rounded text-secondary" style="font-size: 0.9rem; line-height: 1.5;">
                    {{ $department->descriptions }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <ul class="nav nav-tabs mb-3" id="departmentDetailTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active text-dark fw-bold" id="cost-centres-tab" data-bs-toggle="tab" data-bs-target="#cost-centres-panel" type="button" role="tab">
                    Associated Cost Centres ({{ $department->costCentres->count() }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="departmentDetailTabsContent">
            <div class="tab-pane fade show active" id="cost-centres-panel" role="tabpanel">
                <div class="arbif-table-wrap">
                    <table class="arbif-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cost Centre Code</th>
                                <th>Cost Centre Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($department->costCentres as $idx => $cc)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><span class="arbif-badge arbif-badge-navy">{{ $cc->cost_centre_code ?? '—' }}</span></td>
                                <td>{{ $cc->cost_centre_name ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $cc->Status ?? 'Active' }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="arbif-table-empty">
                                    <i class="bi bi-inbox"></i> 
                                    No financial cost centres explicitly mapped under this operational department portfolio.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top d-flex justify-content-between text-muted" style="font-size: 0.85rem;">
            <div>
                <strong>System Record Owner:</strong> {{ $department->user->name ?? '—' }}
            </div>
            <div>
                <strong>Created By:</strong> {{ $department->createdBy->name ?? '—' }} 
                <span class="mx-2">|</span> 
                <strong>Last Updated By:</strong> {{ $department->updatedBy->name ?? '—' }}
            </div>
        </div>
    </div>
</div>
@endsection