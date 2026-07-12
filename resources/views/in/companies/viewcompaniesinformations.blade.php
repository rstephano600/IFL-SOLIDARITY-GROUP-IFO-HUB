@extends('layouts.configside')
@section('title', 'Company Profile Details')
@section('page-title', 'Company Profile Details')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-building"></i></div>
        {{ $company->company_name }} <small class="text-muted">({{ $company->company_code }})</small>
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-submit">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h4 class="mb-3 text-navy font-weight-bold"><i class="fas fa-info-circle me-2"></i> Core Information</h4>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Company Code</label>
                <span class="arbif-badge arbif-badge-navy mt-1" style="font-size: 0.95rem;">{{ $company->company_code }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Company Type</label>
                <span class="fw-bold text-dark d-block mt-1">{{ $company->company_type }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Parent Organization</label>
                <span class="fw-bold text-dark d-block mt-1">
                    {{ $company->parentCompany->company_name ?? 'None (Independent Headquarter)' }}
                </span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Established Date</label>
                <span class="fw-bold text-dark d-block mt-1">
                    {{ $company->established_date ? \Carbon\Carbon::parse($company->established_date)->format('M d, Y') : '—' }}
                </span>
            </div>

            <div class="col-md-12">
                <hr class="my-2">
            </div>

            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0"><i class="fas fa-phone me-1"></i> Phone Line</label>
                <span class="text-dark d-block mt-1">{{ $company->phone ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0"><i class="fas fa-envelope me-1"></i> Email Address</label>
                <span class="text-dark d-block mt-1">{{ $company->email ?? '—' }}</span>
            </div>
            <div class="col-md-6">
                <label class="form-label d-block text-muted mb-0"><i class="fas fa-map-marker-alt me-1"></i> Street Address</label>
                <span class="text-dark d-block mt-1">{{ $company->address ?? '—' }}</span>
            </div>

            <div class="col-md-12">
                <hr class="my-2">
            </div>

            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Region</label>
                <span class="text-dark d-block mt-1">{{ $company->region ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">District</label>
                <span class="text-dark d-block mt-1">{{ $company->district ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Ward</label>
                <span class="text-dark d-block mt-1">{{ $company->ward ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Village / Street Context</label>
                <span class="text-dark d-block mt-1">{{ $company->village ?? '—' }}</span>
            </div>

            @if($company->description)
            <div class="col-md-12">
                <label class="form-label d-block text-muted mb-1">Corporate Description</label>
                <div class="p-3 bg-light rounded text-secondary" style="font-size: 0.9rem; line-height: 1.5;">
                    {{ $company->description }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <ul class="nav nav-tabs mb-3" id="companyDetailTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active text-dark fw-bold" id="branches-tab" data-bs-toggle="tab" data-bs-target="#branches-panel" type="button" role="tab">
                    Branches ({{ $company->branches->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-bold" id="departments-tab" data-bs-toggle="tab" data-bs-target="#departments-panel" type="button" role="tab">
                    Departments ({{ $company->departments->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-bold" id="cost-centres-tab" data-bs-toggle="tab" data-bs-target="#cost-centres-panel" type="button" role="tab">
                    Cost Centres ({{ $company->costCentres->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-bold" id="child-companies-tab" data-bs-toggle="tab" data-bs-target="#child-companies-panel" type="button" role="tab">
                    Subsidiaries ({{ $company->childCompanies->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-bold" id="members-tab" data-bs-toggle="tab" data-bs-target="#members-panel" type="button" role="tab">
                    Members ({{ $company->members->count() }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="companyDetailTabsContent">
            <div class="tab-pane fade show active" id="branches-panel" role="tabpanel">
                <div class="arbif-table-wrap">
                    <table class="arbif-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Branch Code</th>
                                <th>Branch Name</th>
                                <th>Location Context</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($company->branches as $idx => $branch)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><span class="arbif-badge arbif-badge-navy">{{ $branch->branch_code ?? '—' }}</span></td>
                                <td>{{ $branch->branch_name ?? '—' }}</td>
                                <td>{{ $branch->location ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="arbif-table-empty"><i class="bi bi-inbox"></i> No associated branches registered under this organization profile.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="departments-panel" role="tabpanel">
                <div class="arbif-table-wrap">
                    <table class="arbif-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Department Code</th>
                                <th>Department Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($company->departments as $idx => $dept)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><span class="arbif-badge arbif-badge-navy">{{ $dept->department_code ?? '—' }}</span></td>
                                <td>{{ $dept->department_name ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="arbif-table-empty"><i class="bi bi-inbox"></i> No departments loaded.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="cost-centres-panel" role="tabpanel">
                <div class="arbif-table-wrap">
                    <table class="arbif-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cost Centre Code</th>
                                <th>Cost Centre Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($company->costCentres as $idx => $cc)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><span class="arbif-badge arbif-badge-navy">{{ $cc->cost_centre_code ?? '—' }}</span></td>
                                <td>{{ $cc->cost_centre_name ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="arbif-table-empty"><i class="bi bi-inbox"></i> No financial cost centres associated with this profile.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="child-companies-panel" role="tabpanel">
                <div class="arbif-table-wrap">
                    <table class="arbif-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Company Code</th>
                                <th>Corporate Structure Name</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($company->childCompanies as $idx => $child)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><span class="arbif-badge arbif-badge-navy">{{ $child->company_code }}</span></td>
                                <td>{{ $child->company_name }}</td>
                                <td>{{ $child->company_type }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="arbif-table-empty"><i class="bi bi-inbox"></i> This company has no structural downstream subsidiary companies assigned.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="members-panel" role="tabpanel">
                <div class="arbif-table-wrap">
                    <table class="arbif-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Member Name</th>
                                <th>Contact Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($company->members as $idx => $member)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $member->name ?? '—' }}</td>
                                <td>{{ $member->email ?? ($member->phone ?? '—') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="arbif-table-empty"><i class="bi bi-inbox"></i> No member portfolios found mapping directly to this organization.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top d-flex justify-content-between text-muted" style="font-size: 0.85rem;">
            <div>
                <strong>System Record Owner:</strong> {{ $company->user->name ?? '—' }}
            </div>
            <div>
                <strong>Created By:</strong> {{ $company->createdBy->name ?? '—' }} 
                <span class="mx-2">|</span> 
                <strong>Last Updated By:</strong> {{ $company->updatedBy->name ?? '—' }}
            </div>
        </div>
    </div>
</div>
@endsection