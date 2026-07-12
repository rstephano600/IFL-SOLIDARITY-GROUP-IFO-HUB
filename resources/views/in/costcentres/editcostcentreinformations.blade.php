@extends('layouts.configside')
@section('title', 'Edit Cost Centre')
@section('page-title', 'Edit Cost Centre')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-pencil"></i></div>
        Edit Cost Centre: {{ $costcentre->cost_centre_name }}
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none d-inline-flex align-items-center">
        <i class="fas fa-arrow-left me-1"></i> Cancel & Return
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" id="dataFormFill" action="{{ route('updatecostcentreinformations', [encrypt($costcentre->id)]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Assign Company <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Corporate Profile..." required>
                        <option></option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ old('company_id', $costcentre->company_id) == $comp->id ? 'selected' : '' }}>
                                {{ $comp->company_code }} - {{ $comp->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Assign Branch <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Branch..." required>
                        <option></option>
                        @foreach($branches as $br)
                            <option value="{{ $br->id }}" {{ old('branch_id', $costcentre->branch_id) == $br->id ? 'selected' : '' }}>
                                {{ $br->branch_code }} - {{ $br->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Assign Department <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="department_id" data-searchable data-placeholder="Select Department..." required>
                        <option></option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $costcentre->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->department_code }} - {{ $dept->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="cost_centre_code">Cost Centre Code <span class="text-danger">*</span></label>
                    <input type="text" id="cost_centre_code" name="cost_centre_code" class="form-control" placeholder="e.g. CC-FIN-01" value="{{ old('cost_centre_code', $costcentre->cost_centre_code) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="cost_centre_name">Cost Centre Name <span class="text-danger">*</span></label>
                    <input type="text" id="cost_centre_name" name="cost_centre_name" class="form-control" placeholder="e.g. Accounts Payable Unit" value="{{ old('cost_centre_name', $costcentre->cost_centre_name) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="reporting_segment">Reporting Segment</label>
                    <input type="text" id="reporting_segment" name="reporting_segment" class="form-control" placeholder="e.g. Finance Operations" value="{{ old('reporting_segment', $costcentre->reporting_segment) }}">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3" style="margin-top: 30px;">
                <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> <span id="submitBtnText">Save Cost Centre Updates</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection