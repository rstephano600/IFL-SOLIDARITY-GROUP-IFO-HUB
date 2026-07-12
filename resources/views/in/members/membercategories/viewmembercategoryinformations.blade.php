@extends('layouts.configside')
@section('title', 'Member Category Profile')
@section('page-title', 'Member Category Profile')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-tags"></i></div>
        {{ $membercategory->member_category_name }} <small class="text-muted">({{ $membercategory->member_category_code }})</small>
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-submit">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h4 class="mb-3 text-navy font-weight-bold"><i class="fas fa-info-circle me-2"></i> Category Profile Specifications</h4>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Category Code</label>
                <span class="arbif-badge arbif-badge-navy mt-1" style="font-size: 0.95rem;">{{ $membercategory->member_category_code }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Voting Privileges</label>
                <span class="fw-bold d-block mt-1">
                    <span class="badge {{ $membercategory->voting_right == 'Yes' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $membercategory->voting_right ?? 'No' }}
                    </span>
                </span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Loan Financing Eligibility</label>
                <span class="fw-bold d-block mt-1">
                    <span class="badge {{ $membercategory->loan_eligibility == 'Yes' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $membercategory->loan_eligibility ?? 'No' }}
                    </span>
                </span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Status</label>
                <span class="fw-bold d-block mt-1">
                    <span class="badge bg-success">{{ $membercategory->Status ?? 'Active' }}</span>
                </span>
            </div>

            <div class="col-md-12"><hr class="my-1"></div>

            <div class="col-md-6">
                <label class="form-label d-block text-muted mb-0"><i class="fas fa-building me-1"></i> Associated Company Context</label>
                <span class="text-dark d-block mt-1 fw-bold">{{ $membercategory->company->company_name ?? '—' }}</span>
            </div>
            <div class="col-md-6">
                <label class="form-label d-block text-muted mb-0"><i class="fas fa-network-wired me-1"></i> Branch Anchor Point</label>
                <span class="text-dark d-block mt-1 fw-bold">{{ $membercategory->branch->branch_name ?? '—' }}</span>
            </div>

            @if($membercategory->description)
            <div class="col-md-12">
                <hr class="my-1">
                <label class="form-label d-block text-muted mb-1">Scope Privileges & Strategic Definition</label>
                <div class="p-3 bg-light rounded text-secondary" style="font-size: 0.9rem; line-height: 1.5;">
                    {{ $membercategory->description }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <ul class="nav nav-tabs mb-3" id="categoryDetailsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active text-dark fw-bold" id="members-tab" data-bs-toggle="tab" data-bs-target="#members-panel" type="button" role="tab">
                    Registered Members under Category ({{ $membercategory->members->count() }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="categoryDetailsTabContent">
            <div class="tab-pane fade show active" id="members-panel" role="tabpanel">
                <div class="arbif-table-wrap">
                    <table class="arbif-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Member Identity / Code</th>
                                <th>Full Registered Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($membercategory->members as $idx => $mb)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><span class="arbif-badge arbif-badge-navy">{{ $mb->member_code ?? '—' }}</span></td>
                                <td>{{ $mb->member_name ?? $mb->name ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $mb->Status ?? 'Active' }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="arbif-table-empty">
                                    <i class="bi bi-inbox"></i> 
                                    No members are explicitly assigned to this membership grouping standard profile yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top d-flex justify-content-between text-muted" style="font-size: 0.85rem;">
            <div><strong>System Owner Check:</strong> {{ $membercategory->user->name ?? '—' }}</div>
            <div>
                <strong>Created By:</strong> {{ $membercategory->createdBy->name ?? '—' }} 
                <span class="mx-2">|</span> 
                <strong>Last Modified By:</strong> {{ $membercategory->updatedBy->name ?? '—' }}
            </div>
        </div>
    </div>
</div>
@endsection