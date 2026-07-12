@extends('layouts.configside')
@section('title', 'Edit Member Category')
@section('page-title', 'Edit Member Category')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-pencil"></i></div>
        Edit Member Category: {{ $membercategory->member_category_name }}
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none d-inline-flex align-items-center">
        <i class="fas fa-arrow-left me-1"></i> Cancel & Return
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" id="dataFormFill" action="{{ route('updatemembercategoryinformations', [encrypt($membercategory->id)]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Assign Company <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Corporate Scope..." required>
                        <option></option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ old('company_id', $membercategory->company_id) == $comp->id ? 'selected' : '' }}>
                                {{ $comp->company_code }} - {{ $comp->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Assign Branch <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Branch Scope..." required>
                        <option></option>
                        @foreach($branches as $br)
                            <option value="{{ $br->id }}" {{ old('branch_id', $membercategory->branch_id) == $br->id ? 'selected' : '' }}>
                                {{ $br->branch_code }} - {{ $br->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="member_category_code">Category Code <span class="text-danger">*</span></label>
                    <input type="text" id="member_category_code" name="member_category_code" class="form-control" placeholder="e.g. CAT-PREM" value="{{ old('member_category_code', $membercategory->member_category_code) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="member_category_name">Category Name <span class="text-danger">*</span></label>
                    <input type="text" id="member_category_name" name="member_category_name" class="form-control" placeholder="e.g. Premium Shareholder" value="{{ old('member_category_name', $membercategory->member_category_name) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="voting_right">Has Voting Rights? <span class="text-danger">*</span></label>
                    <select class="form-select" id="voting_right" name="voting_right" required>
                        <option></option>
                        <option value="Voting" >Voting</option>
                        <option value="Voting subject to constitution">Voting subject to constitution</option>
                        <option value="Voting through representative">Voting through representative</option>
                        <option value="Non voting">Non voting</option>
                        <option value="Suspended">Suspended</option>
                        <option value="None">None</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="loan_eligibility">Eligible for Loans? <span class="text-danger">*</span></label>
                    <select class="form-select" id="loan_eligibility" name="loan_eligibility" required>
                        <option value="Eligible">Eligible</option>
                        <option value="Not eligible unless approved">Not eligible unless approved</option>
                        <option value="Not eligible">Not eligible</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="description">Category Scope Privileges / Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Describe the constraints, requirements, and policy definitions unique to this category profile...">{{ old('description', $membercategory->description) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3" style="margin-top: 30px;">
                <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> <span id="submitBtnText">Save Category Updates</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection