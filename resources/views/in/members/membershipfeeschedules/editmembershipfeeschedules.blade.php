@extends('layouts.configside')
@section('title', 'Edit Membership Fee Schedule')
@section('page-title', 'Edit Membership Fee Schedule')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-edit"></i></div>
        Edit Fee Schedule: {{ $feeSchedule->ScheduleRefNo ?? '' }}
    </h3>
    <a href="{{ route('membershipfeeschedules') }}" class="arbif-btn-cancel">
        <i class="fas fa-arrow-left me-1"></i> Back to Index
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" id="dataFormFill" action="{{ route('updatemembershipfeeschedules', [encrypt($feeSchedule->id)]) }}" enctype="multipart/form-data">
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

            <!-- SECTION 1: FEE DETAILS & SCHEDULE -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                <i class="fas fa-coins me-2"></i> 1. Schedule & Amount Configuration
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label" for="ScheduleRefNo">Schedule Ref No</label>
                    <input type="text" id="ScheduleRefNo" class="form-control bg-light" value="{{ $feeSchedule->ScheduleRefNo }}" disabled readonly>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="FeeAmount">Fee Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="FeeAmount" name="FeeAmount" class="form-control" placeholder="0.00" value="{{ old('FeeAmount', $feeSchedule->FeeAmount) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="EffectiveFrom">Effective From <span class="text-danger">*</span></label>
                    <input type="date" id="EffectiveFrom" name="EffectiveFrom" class="form-control" value="{{ old('EffectiveFrom', optional($feeSchedule->EffectiveFrom)->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="EffectiveTo">Effective To</label>
                    <input type="date" id="EffectiveTo" name="EffectiveTo" class="form-control" value="{{ old('EffectiveTo', optional($feeSchedule->EffectiveTo)->format('Y-m-d')) }}">
                    <small class="text-muted fs-11">Leave blank if ongoing indefinitely.</small>
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="Description">Description / Notes</label>
                    <textarea id="Description" name="Description" class="form-control" rows="3" placeholder="Brief details about this fee schedule...">{{ old('Description', $feeSchedule->Description) }}</textarea>
                </div>
            </div>

            <!-- SECTION 2: ORGANIZATIONAL SCOPE & STATUS -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                <i class="fas fa-building me-2"></i> 2. Scope & Record Status
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Company Entity <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Company..." required>
                        <option></option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ old('company_id', $feeSchedule->company_id) == $comp->id ? 'selected' : '' }}>
                                {{ $comp->company_code ?? '' }} - {{ $comp->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Operational Branch <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Branch..." required>
                        <option></option>
                        @foreach($branches as $br)
                            <option value="{{ $br->id }}" {{ old('branch_id', $feeSchedule->branch_id) == $br->id ? 'selected' : '' }}>
                                {{ $br->branch_code ?? '' }} - {{ $br->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="Status">Status <span class="text-danger">*</span></label>
                    <select name="Status" id="Status" class="form-select" required>
                        <option value="Active" {{ old('Status', $feeSchedule->Status) === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('Status', $feeSchedule->Status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="{{ route('membershipfeeschedules') }}" class="arbif-btn-cancel">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> <span id="submitBtnText">Update Fee Schedule</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection