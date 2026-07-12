@extends('layouts.configside')
@section('title', 'Edit Business Code')
@section('page-title', 'Edit Business Code')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-pencil"></i></div>
        Edit Business Code: {{ $businesscode->business_name }}
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none d-inline-flex align-items-center">
        <i class="fas fa-arrow-left me-1"></i> Cancel & Return
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" id="dataFormFill" action="{{ route('updatecompanybusinesscodeinformations', [encrypt($businesscode->id)]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Assign Company <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Corporate Context..." required>
                        <option></option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ old('company_id', $businesscode->company_id) == $comp->id ? 'selected' : '' }}>
                                {{ $comp->company_code }} - {{ $comp->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Assign Branch <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Operational Branch Location..." required>
                        <option></option>
                        @foreach($branches as $br)
                            <option value="{{ $br->id }}" {{ old('branch_id', $businesscode->branch_id) == $br->id ? 'selected' : '' }}>
                                {{ $br->branch_code }} - {{ $br->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="business_code">Business Code <span class="text-danger">*</span></label>
                    <input type="text" id="business_code" name="business_code" class="form-control" placeholder="e.g. ISIC-4610" value="{{ old('business_code', $businesscode->business_code) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="business_name">Business Name <span class="text-danger">*</span></label>
                    <input type="text" id="business_name" name="business_name" class="form-control" placeholder="e.g. Wholesale Trade Agents" value="{{ old('business_name', $businesscode->business_name) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="business_activity">Business Activity Focus</label>
                    <input type="text" id="business_activity" name="business_activity" class="form-control" placeholder="e.g. Distribution & Logistics" value="{{ old('business_activity', $businesscode->business_activity) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="segment">Reporting Segment</label>
                    <input type="text" id="segment" name="segment" class="form-control" placeholder="e.g. Commercial Division" value="{{ old('segment', $businesscode->segment) }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="description">Code Scope / Operational Guidelines Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Describe the corporate activities tracked under this code profile...">{{ old('description', $businesscode->description) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3" style="margin-top: 30px;">
                <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> <span id="submitBtnText">Save Business Code Updates</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection