@extends('layouts.configside')
@section('title', 'Edit Branch Information')
@section('page-title', 'Edit Branch Information')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-pencil"></i></div>
        Edit Branch: {{ $branch->branch_name }}
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none d-inline-flex align-items-center">
        <i class="fas fa-arrow-left me-1"></i> Cancel & Return
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" id="dataFormFill" action="{{ route('updatebranchiesinformations', [encrypt($branch->id)]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Assign Company <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Parent Corporate Profile..." required>
                        <option></option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ old('company_id', $branch->company_id) == $comp->id ? 'selected' : '' }}>
                                {{ $comp->company_code }} - {{ $comp->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="branch_code">Branch Code <span class="text-danger">*</span></label>
                    <input type="text" id="branch_code" name="branch_code" class="form-control" placeholder="e.g. BR-01" value="{{ old('branch_code', $branch->branch_code) }}" maxlength="30" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="branch_name">Branch Name <span class="text-danger">*</span></label>
                    <input type="text" id="branch_name" name="branch_name" class="form-control" placeholder="e.g. Dar es Salaam Central" value="{{ old('branch_name', $branch->branch_name) }}" maxlength="200" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="established_date">Established Date</label>
                    <input type="date" id="established_date" name="established_date" class="form-control" value="{{ old('established_date', $branch->established_date ? \Carbon\Carbon::parse($branch->established_date)->format('Y-m-d') : '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="email">Branch Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="e.g. branchname@company.com" value="{{ old('email', $branch->email) }}" maxlength="150">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="phone">Branch Phone Contact</label>
                    <input type="text" id="phone" name="phone" class="form-control" placeholder="e.g. +255..." value="{{ old('phone', $branch->phone) }}" maxlength="30">
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="address">Physical Street Address</label>
                    <input type="text" id="address" name="address" class="form-control" placeholder="e.g. Plot 45, Sam Nujoma Road" value="{{ old('address', $branch->address) }}" maxlength="255">
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="region">Region</label>
                    <input type="text" id="region" name="region" class="form-control" placeholder="Region" value="{{ old('region', $branch->region) }}" maxlength="100">
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="district">District</label>
                    <input type="text" id="district" name="district" class="form-control" placeholder="District" value="{{ old('district', $branch->district) }}" maxlength="100">
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="ward">Ward</label>
                    <input type="text" id="ward" name="ward" class="form-control" placeholder="Ward" value="{{ old('ward', $branch->ward) }}" maxlength="100">
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="village">Village / Street</label>
                    <input type="text" id="village" name="village" class="form-control" placeholder="Village" value="{{ old('village', $branch->village) }}" maxlength="100">
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="description">Branch Operational Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Describe regional operations or branch purpose...">{{ old('description', $branch->description) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3" style="margin-top: 30px;">
                <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> <span id="submitBtnText">Save Branch updates</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection