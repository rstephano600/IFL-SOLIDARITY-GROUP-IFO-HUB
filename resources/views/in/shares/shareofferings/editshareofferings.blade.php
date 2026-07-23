@extends('layouts.configside')
@section('title', 'Edit Share Offering')
@section('page-title', 'Edit Share Offering Profile')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-pencil"></i></div>
        Edit Share Offering: <span class="text-navy ms-1">{{ $shareOffering->OfferingName }}</span>
    </h3>
    <a href="{{ route('shareofferings') }}" class="arbif-btn-cancel text-decoration-none">
        <i class="fas fa-arrow-left me-1"></i> Back to Portfolio
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" id="dataFormFill" action="{{ route('updateshareofferings', [encrypt($shareOffering->id)]) }}" enctype="multipart/form-data">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- SECTION 1: OFFERING DETAILS -->
            <h5 class="text-navy mb-3 pb-2 border-bottom fw-bold">
                <i class="fas fa-file-signature me-2"></i> 1. Offering Details
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <label class="form-label" for="OfferingName">Offering Name <span class="text-danger">*</span></label>
                    <input type="text" id="OfferingName" name="OfferingName" class="form-control" placeholder="e.g. Q3 2026 Public Share Issue" value="{{ old('OfferingName', $shareOffering->OfferingName) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Share Type <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="share_type_id" data-searchable data-placeholder="Select Share Type..." required>
                        <option></option>
                        @foreach($shareTypes as $st)
                            <option value="{{ $st->id }}" {{ old('share_type_id', $shareOffering->share_type_id) == $st->id ? 'selected' : '' }}>
                                {{ $st->TypeCode }} - {{ $st->TypeName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="TotalShares">Total Shares Allocated <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="TotalShares" name="TotalShares" class="form-control" placeholder="0.00" value="{{ old('TotalShares', $shareOffering->TotalShares) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="PricePerShare">Price Per Share <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="PricePerShare" name="PricePerShare" class="form-control" placeholder="0.00" value="{{ old('PricePerShare', $shareOffering->PricePerShare) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="MaxPercentPerMember">Max % Per Member <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" max="100" id="MaxPercentPerMember" name="MaxPercentPerMember" class="form-control" placeholder="e.g. 10.00" value="{{ old('MaxPercentPerMember', $shareOffering->MaxPercentPerMember) }}" required>
                </div>
            </div>

            <!-- SECTION 2: SCHEDULE & OFFERING STATUS -->
            <h5 class="text-navy mb-3 pb-2 border-bottom fw-bold">
                <i class="fas fa-calendar-alt me-2"></i> 2. Offering Timeline & Status
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label" for="OfferingStartDate">Start Date <span class="text-danger">*</span></label>
                    <input type="date" id="OfferingStartDate" name="OfferingStartDate" class="form-control" value="{{ old('OfferingStartDate', $shareOffering->OfferingStartDate ? $shareOffering->OfferingStartDate->format('Y-m-d') : '') }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="OfferingEndDate">End Date <span class="text-danger">*</span></label>
                    <input type="date" id="OfferingEndDate" name="OfferingEndDate" class="form-control" value="{{ old('OfferingEndDate', $shareOffering->OfferingEndDate ? $shareOffering->OfferingEndDate->format('Y-m-d') : '') }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="OfferingStatus">Offering Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="OfferingStatus" name="OfferingStatus" required>
                        <option value="Open" {{ old('OfferingStatus', $shareOffering->OfferingStatus) == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Pending" {{ old('OfferingStatus', $shareOffering->OfferingStatus) == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Closed" {{ old('OfferingStatus', $shareOffering->OfferingStatus) == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="Status">System Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="Status" id="Status" required>
                        <option value="Active" {{ old('Status', $shareOffering->Status) === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('Status', $shareOffering->Status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <!-- SECTION 3: ORGANIZATIONAL SCOPE -->
            <h5 class="text-navy mb-3 pb-2 border-bottom fw-bold">
                <i class="fas fa-building me-2"></i> 3. Organizational Scope
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Company Entity <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Company..." required>
                        <option></option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ old('company_id', $shareOffering->company_id) == $comp->id ? 'selected' : '' }}>
                                {{ $comp->company_code ?? '' }} - {{ $comp->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Operational Branch <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Branch..." required>
                        <option></option>
                        @foreach($branches as $br)
                            <option value="{{ $br->id }}" {{ old('branch_id', $shareOffering->branch_id) == $br->id ? 'selected' : '' }}>
                                {{ $br->branch_code ?? '' }} - {{ $br->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- FOOTER ACTIONS -->
            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="{{ route('shareofferings') }}" class="arbif-btn-cancel text-decoration-none">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> <span id="submitBtnText">Update Share Offering</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection