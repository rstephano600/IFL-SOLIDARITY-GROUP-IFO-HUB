@extends('layouts.configside')
@section('title', 'Departments Information')
@section('page-title', 'Departments Information')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-sitemap"></i></div>Departments Information</h3>
    <button class="arbif-btn-submit" onclick="openCreateForm()"><i class="fas fa-plus"></i> Add Department</button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="departmentTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Dept Code</th>
                        <th class="sortable">Department Name</th>
                        <th class="sortable">Company</th>
                        <th class="sortable">Branch</th>
                        <th class="sortable">Core Function</th>
                        <th class="sortable">Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->department_code }}</span></td>
                        <td>{{ $item->department_name }}</td>
                        <td>{{ $item->company->company_name ?? '—' }}</td>
                        <td>{{ $item->branch->branch_name ?? '—' }}</td>
                        <td>{{ $item->function ?? '—' }}</td>
                        <td>{{ $item->createdBy->name ?? ($item->user->name ?? '—') }}</td>
                        <td>
                            <a href="{{ route('viewdepartmentinformations', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-eye"></i> View </a>
                            <a href="{{ route('editdepartmentinformations', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                            <a onclick="confirmDelete()" href="{{ route('deletedepartmentinformations', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No department records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="departmentTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="departmentTable"></div>
        </div>
    </div>
</div>

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-sitemap"></i></div>
                <h5 class="modal-title">Register New Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storedepartmentinformations') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Assign Company <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Corporate Profile..." required>
                                <option></option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ old('company_id') == $comp->id ? 'selected' : '' }}>
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
                                    <option value="{{ $br->id }}" {{ old('branch_id') == $br->id ? 'selected' : '' }}>
                                        {{ $br->branch_code ?? $br->company_code }} - {{ $br->branch_name ?? $br->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="department_code">Department Code <span class="text-danger">*</span></label>
                            <input type="text" id="department_code" name="department_code" class="form-control" placeholder="e.g. DEPT-HR" value="{{ old('department_code') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="department_name">Department Name <span class="text-danger">*</span></label>
                            <input type="text" id="department_name" name="department_name" class="form-control" placeholder="e.g. Human Resources & Admin" value="{{ old('department_name') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="function">Core Functional Focus</label>
                            <input type="text" id="function" name="function" class="form-control" placeholder="e.g. Talent Management & Payroll" value="{{ old('function') }}">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="description">Departmental Description / Mandate</label>
                            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Describe structural objectives or responsibilities...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="margin-top: 20px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Save Department</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection