@extends('layouts.configside')
@section('title', 'Branch Profile Details')
@section('page-title', 'Branch Profile Details')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-network-wired"></i></div>
        {{ $branch->branch_name }} <small class="text-muted">({{ $branch->branch_code }})</small>
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-submit">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>


<div class="arbif-card mb-4">
    <div class="arbif-card-body">

        {{-- ===================================================== --}}
        {{-- CORE BRANCH INFORMATION --}}
        {{-- ===================================================== --}}

        <h4 class="mb-3 text-navy font-weight-bold">
            <i class="fas fa-info-circle me-2"></i> Core Branch Information
        </h4>

        <div class="row g-3">

            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Branch Code</label>
                <span class="arbif-badge arbif-badge-navy mt-1" style="font-size: 0.95rem;">
                    {{ $branch->branch_code }}
                </span>
            </div>

            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Branch Name</label>
                <span class="fw-bold text-dark d-block mt-1">
                    {{ $branch->branch_name }}
                </span>
            </div>

            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Assigned Company</label>
                <span class="fw-bold text-dark d-block mt-1">
                    {{ $branch->company->company_name ?? '—' }}
                </span>
            </div>

            @if($branch->established_date)
                <div class="col-md-3">
                    <label class="form-label d-block text-muted mb-0">Established Date</label>
                    <span class="fw-bold text-dark d-block mt-1">
                        {{ \Carbon\Carbon::parse($branch->established_date)->format('M d, Y') }}
                    </span>
                </div>
            @endif

            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Auditing Status</label>
                <span class="fw-bold text-dark d-block mt-1">
                    <span class="badge bg-secondary">
                        {{ $branch->AuditingStatus ?? 'Pending' }}
                    </span>
                </span>
            </div>

            @if($branch->Status)
                <div class="col-md-3">
                    <label class="form-label d-block text-muted mb-0">Branch Status</label>
                    <span class="fw-bold text-dark d-block mt-1">
                        <span class="badge bg-success">{{ $branch->Status }}</span>
                    </span>
                </div>
            @endif

            @if($branch->phone)
                <div class="col-md-3">
                    <label class="form-label d-block text-muted mb-0">
                        <i class="fas fa-phone me-1"></i> Phone Line
                    </label>
                    <span class="text-dark d-block mt-1">{{ $branch->phone }}</span>
                </div>
            @endif

            @if($branch->email)
                <div class="col-md-3">
                    <label class="form-label d-block text-muted mb-0">
                        <i class="fas fa-envelope me-1"></i> Email Address
                    </label>
                    <span class="text-dark d-block mt-1">{{ $branch->email }}</span>
                </div>
            @endif

            @if($branch->address)
                <div class="col-md-6">
                    <label class="form-label d-block text-muted mb-0">
                        <i class="fas fa-map-marker-alt me-1"></i> Physical Address
                    </label>
                    <span class="text-dark d-block mt-1">{{ $branch->address }}</span>
                </div>
            @endif

            @if($branch->region)
                <div class="col-md-3">
                    <label class="form-label d-block text-muted mb-0">Region</label>
                    <span class="text-dark d-block mt-1">{{ $branch->region }}</span>
                </div>
            @endif

            @if($branch->district)
                <div class="col-md-3">
                    <label class="form-label d-block text-muted mb-0">District</label>
                    <span class="text-dark d-block mt-1">{{ $branch->district }}</span>
                </div>
            @endif

            @if($branch->ward)
                <div class="col-md-3">
                    <label class="form-label d-block text-muted mb-0">Ward</label>
                    <span class="text-dark d-block mt-1">{{ $branch->ward }}</span>
                </div>
            @endif

            @if($branch->village)
                <div class="col-md-3">
                    <label class="form-label d-block text-muted mb-0">Village / Street</label>
                    <span class="text-dark d-block mt-1">{{ $branch->village }}</span>
                </div>
            @endif

            @if($branch->description)
                <div class="col-md-12">
                    <label class="form-label d-block text-muted mb-1">Operational Description</label>
                    <div class="p-3 bg-light rounded text-secondary" style="font-size: 0.9rem; line-height: 1.5;">
                        {{ $branch->description }}
                    </div>
                </div>
            @endif

        </div>

        {{-- ===================================================== --}}
        {{-- BANK ACCOUNT INFORMATION --}}
        {{-- ===================================================== --}}

        @if(
            $branch->bank_name ||
            $branch->bank_branch ||
            $branch->bank_account_name ||
            $branch->bank_account_number ||
            $branch->bank_swift_code
        )
            <hr class="my-4">

            <h4 class="mb-3 text-navy font-weight-bold">
                <i class="fas fa-university me-2"></i> Bank Account Information
            </h4>

            <div class="row g-3">

                @if($branch->bank_name)
                    <div class="col-md-4">
                        <label class="form-label d-block text-muted mb-0">Bank Name</label>
                        <span class="text-dark d-block mt-1">{{ $branch->bank_name }}</span>
                    </div>
                @endif

                @if($branch->bank_branch)
                    <div class="col-md-4">
                        <label class="form-label d-block text-muted mb-0">Bank Branch</label>
                        <span class="text-dark d-block mt-1">{{ $branch->bank_branch }}</span>
                    </div>
                @endif

                @if($branch->bank_account_name)
                    <div class="col-md-4">
                        <label class="form-label d-block text-muted mb-0">Account Name</label>
                        <span class="text-dark d-block mt-1">{{ $branch->bank_account_name }}</span>
                    </div>
                @endif

                @if($branch->bank_account_number)
                    <div class="col-md-6">
                        <label class="form-label d-block text-muted mb-0">Bank Account Number</label>
                        <span class="text-dark d-block mt-1">{{ $branch->bank_account_number }}</span>
                    </div>
                @endif

                @if($branch->bank_swift_code)
                    <div class="col-md-6">
                        <label class="form-label d-block text-muted mb-0">SWIFT / BIC Code</label>
                        <span class="text-dark d-block mt-1">{{ $branch->bank_swift_code }}</span>
                    </div>
                @endif

            </div>
        @endif

        {{-- ===================================================== --}}
        {{-- MOBILE MONEY INFORMATION --}}
        {{-- ===================================================== --}}

        @if(
            $branch->mobile_money_provider ||
            $branch->mobile_money_number ||
            $branch->mobile_money_account_name
        )
            <hr class="my-4">

            <h4 class="mb-3 text-navy font-weight-bold">
                <i class="fas fa-mobile-alt me-2"></i> Mobile Money Information
            </h4>

            <div class="row g-3">

                @if($branch->mobile_money_provider)
                    <div class="col-md-4">
                        <label class="form-label d-block text-muted mb-0">Mobile Money Provider</label>
                        <span class="text-dark d-block mt-1">{{ $branch->mobile_money_provider }}</span>
                    </div>
                @endif

                @if($branch->mobile_money_number)
                    <div class="col-md-4">
                        <label class="form-label d-block text-muted mb-0">Mobile Money Number</label>
                        <span class="text-dark d-block mt-1">{{ $branch->mobile_money_number }}</span>
                    </div>
                @endif

                @if($branch->mobile_money_account_name)
                    <div class="col-md-4">
                        <label class="form-label d-block text-muted mb-0">Account Name</label>
                        <span class="text-dark d-block mt-1">{{ $branch->mobile_money_account_name }}</span>
                    </div>
                @endif

            </div>
        @endif

        {{-- ===================================================== --}}
        {{-- PAYMENT SETTINGS --}}
        {{-- ===================================================== --}}

        @if(
            $branch->default_payment_method ||
            $branch->default_currency ||
            $branch->payment_reference_prefix
        )
            <hr class="my-4">

            <h4 class="mb-3 text-navy font-weight-bold">
                <i class="fas fa-money-bill-wave me-2"></i> Payment Settings
            </h4>

            <div class="row g-3">

                @if($branch->default_payment_method)
                    <div class="col-md-4">
                        <label class="form-label d-block text-muted mb-0">Default Payment Method</label>
                        <span class="text-dark d-block mt-1">
                            {{ ucwords(strtolower(str_replace('_', ' ', $branch->default_payment_method))) }}
                        </span>
                    </div>
                @endif

                @if($branch->default_currency)
                    <div class="col-md-4">
                        <label class="form-label d-block text-muted mb-0">Default Currency</label>
                        <span class="text-dark d-block mt-1">{{ $branch->default_currency }}</span>
                    </div>
                @endif

                @if($branch->payment_reference_prefix)
                    <div class="col-md-4">
                        <label class="form-label d-block text-muted mb-0">Payment Reference Prefix</label>
                        <span class="text-dark d-block mt-1">{{ $branch->payment_reference_prefix }}</span>
                    </div>
                @endif

            </div>
        @endif

        {{-- ===================================================== --}}
        {{-- FINANCE CONTACT INFORMATION --}}
        {{-- ===================================================== --}}

        @if(
            $branch->finance_contact_person ||
            $branch->finance_phone ||
            $branch->finance_email
        )
            <hr class="my-4">

            <h4 class="mb-3 text-navy font-weight-bold">
                <i class="fas fa-address-card me-2"></i> Finance Contact Information
            </h4>

            <div class="row g-3">

                @if($branch->finance_contact_person)
                    <div class="col-md-4">
                        <label class="form-label d-block text-muted mb-0">Finance Contact Person</label>
                        <span class="text-dark d-block mt-1">{{ $branch->finance_contact_person }}</span>
                    </div>
                @endif

                @if($branch->finance_phone)
                    <div class="col-md-4">
                        <label class="form-label d-block text-muted mb-0">Finance Phone</label>
                        <span class="text-dark d-block mt-1">{{ $branch->finance_phone }}</span>
                    </div>
                @endif

                @if($branch->finance_email)
                    <div class="col-md-4">
                        <label class="form-label d-block text-muted mb-0">Finance Email</label>
                        <span class="text-dark d-block mt-1">{{ $branch->finance_email }}</span>
                    </div>
                @endif

            </div>
        @endif

        {{-- ===================================================== --}}
        {{-- TAX INFORMATION --}}
        {{-- ===================================================== --}}

        @if($branch->tin_number || $branch->vrn_number)
            <hr class="my-4">

            <h4 class="mb-3 text-navy font-weight-bold">
                <i class="fas fa-file-invoice me-2"></i> Tax Information
            </h4>

            <div class="row g-3">

                @if($branch->tin_number)
                    <div class="col-md-6">
                        <label class="form-label d-block text-muted mb-0">TIN Number</label>
                        <span class="text-dark d-block mt-1">{{ $branch->tin_number }}</span>
                    </div>
                @endif

                @if($branch->vrn_number)
                    <div class="col-md-6">
                        <label class="form-label d-block text-muted mb-0">VRN Number</label>
                        <span class="text-dark d-block mt-1">{{ $branch->vrn_number }}</span>
                    </div>
                @endif

            </div>
        @endif

    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <ul class="nav nav-tabs mb-3" id="branchDetailTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active text-dark fw-bold" id="departments-tab" data-bs-toggle="tab" data-bs-target="#departments-panel" type="button" role="tab">
                    Departments ({{ $branch->departments->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-bold" id="cost-centres-tab" data-bs-toggle="tab" data-bs-target="#cost-centres-panel" type="button" role="tab">
                    Cost Centres ({{ $branch->costCentres->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-bold" id="business-codes-tab" data-bs-toggle="tab" data-bs-target="#business-codes-panel" type="button" role="tab">
                    Business Codes ({{ $branch->businessCodes->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-bold" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories-panel" type="button" role="tab">
                    Member Categories ({{ $branch->memberCategories->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark fw-bold" id="members-tab" data-bs-toggle="tab" data-bs-target="#members-panel" type="button" role="tab">
                    Members ({{ $branch->members->count() }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="branchDetailTabsContent">
            <div class="tab-pane fade show active" id="departments-panel" role="tabpanel">
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
                            @forelse($branch->departments as $idx => $dept)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><span class="arbif-badge arbif-badge-navy">{{ $dept->department_code ?? '—' }}</span></td>
                                <td>{{ $dept->department_name ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="arbif-table-empty"><i class="bi bi-inbox"></i> No associated departments registered for this branch.</td>
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
                            @forelse($branch->costCentres as $idx => $cc)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><span class="arbif-badge arbif-badge-navy">{{ $cc->cost_centre_code ?? '—' }}</span></td>
                                <td>{{ $cc->cost_centre_name ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="arbif-table-empty"><i class="bi bi-inbox"></i> No cost centres map to this branch location.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="business-codes-panel" role="tabpanel">
                <div class="arbif-table-wrap">
                    <table class="arbif-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Business Code</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($branch->businessCodes as $idx => $bc)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><span class="arbif-badge arbif-badge-navy">{{ $bc->code ?? '—' }}</span></td>
                                <td>{{ $bc->description ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="arbif-table-empty"><i class="bi bi-inbox"></i> No specific business codes tied to this operational branch.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="categories-panel" role="tabpanel">
                <div class="arbif-table-wrap">
                    <table class="arbif-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category Name</th>
                                <th>Code Baseline</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($branch->memberCategories as $idx => $cat)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $cat->category_name ?? '—' }}</td>
                                <td><span class="arbif-badge arbif-badge-navy">{{ $cat->category_code ?? '—' }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="arbif-table-empty"><i class="bi bi-inbox"></i> No localized member categories configurations found.</td>
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
                                <th>Member Fullname</th>
                                <th>Contact Access</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($branch->members as $idx => $member)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $member->name ?? '—' }}</td>
                                <td>{{ $member->email ?? ($member->phone ?? '—') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="arbif-table-empty"><i class="bi bi-inbox"></i> No branch membership registers active contextually.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top d-flex justify-content-between text-muted" style="font-size: 0.85rem;">
            <div>
                <strong>System Record Owner:</strong> {{ $branch->user->name ?? '—' }}
            </div>
            <div>
                <strong>Created By:</strong> {{ $branch->createdBy->name ?? '—' }} 
                <span class="mx-2">|</span> 
                <strong>Last Updated By:</strong> {{ $branch->updatedBy->name ?? '—' }}
            </div>
        </div>
    </div>
</div>
@endsection