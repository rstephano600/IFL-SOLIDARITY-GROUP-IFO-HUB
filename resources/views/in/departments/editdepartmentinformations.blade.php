@extends('layouts.configside')
@section('title', 'Edit Department Information')
@section('page-title', 'Edit Department Information')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-pencil"></i></div>
        Edit Department: {{ $department->department_name }}
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none d-inline-flex align-items-center">
        <i class="fas fa-arrow-left me-1"></i> Cancel & Return
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" id="dataFormFill" action="{{ route('updatedepartmentinformations', [encrypt($department->id)]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Assign Company <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Corporate Profile..." required>
                        <option></option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ old('company_id', $department->company_id) == $comp->id ? 'selected' : '' }}>
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
                            <option value="{{ $br->id }}" {{ old('branch_id', $department->branch_id) == $br->id ? 'selected' : '' }}>
                                {{ $br->branch_code }} - {{ $br->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="department_code">Department Code <span class="text-danger">*</span></label>
                    <input type="text" id="department_code" name="department_code" class="form-control" placeholder="e.g. DEPT-HR" value="{{ old('department_code', $department->department_code) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="department_name">Department Name <span class="text-danger">*</span></label>
                    <input type="text" id="department_name" name="department_name" class="form-control" placeholder="e.g. Human Resources & Admin" value="{{ old('department_name', $department->department_name) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="function">Core Functional Focus</label>
                    <input type="text" id="function" name="function" class="form-control" placeholder="e.g. Talent Management & Payroll" value="{{ old('function', $department->function) }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="description">Departmental Description / Mandate</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Describe structural objectives or responsibilities...">{{ old('description', $department->description ?? $department->descriptions) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3" style="margin-top: 30px;">
                <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> <span id="submitBtnText">Save Department Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection