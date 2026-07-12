@extends('layouts.configside')
@section('title', 'Cost Centres Information')
@section('page-title', 'Cost Centres Information')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-calculator"></i></div>Cost Centres Information</h3>
    <button class="arbif-btn-submit" onclick="openCreateForm()"><i class="fas fa-plus"></i> Add Cost Centre</button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="costCentreTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">CC Code</th>
                        <th class="sortable">Cost Centre Name</th>
                        <th class="sortable">Company</th>
                        <th class="sortable">Branch</th>
                        <th class="sortable">Department</th>
                        <th class="sortable">Reporting Segment</th>
                        <th class="sortable">Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($costcentres as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->cost_centre_code }}</span></td>
                        <td>{{ $item->cost_centre_name }}</td>
                        <td>{{ $item->company->company_name ?? '—' }}</td>
                        <td>{{ $item->branch->branch_name ?? '—' }}</td>
                        <td>{{ $item->department->department_name ?? '—' }}</td>
                        <td>{{ $item->reporting_segment ?? '—' }}</td>
                        <td>{{ $item->createdBy->name ?? ($item->user->name ?? '—') }}</td>
                        <td>
                            <a href="{{ route('viewcostcentreinformations', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-eye"></i> View </a>
                            <a href="{{ route('editcostcentreinformations', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                            <a onclick="confirmDelete()" href="{{ route('deletecostcentreinformations', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No cost centre records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="costCentreTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="costCentreTable"></div>
        </div>
    </div>
</div>

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-calculator"></i></div>
                <h5 class="modal-title">Register New Cost Centre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storecostcentreinformations') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-4">
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

                        <div class="col-md-4">
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
                            <label class="form-label">Assign Department <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="department_id" data-searchable data-placeholder="Select Department..." required>
                                <option></option>
                                @foreach($departments as $dept)
                                    <option value="{{ $br->id }}" {{ old('branch_id') == $br->id ? 'selected' : '' }}>
                                        {{ $dept->department_code }} - {{ $dept->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="cost_centre_code">Cost Centre Code <span class="text-danger">*</span></label>
                            <input type="text" id="cost_centre_code" name="cost_centre_code" class="form-control" placeholder="e.g. CC-FIN-01" value="{{ old('cost_centre_code') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="cost_centre_name">Cost Centre Name <span class="text-danger">*</span></label>
                            <input type="text" id="cost_centre_name" name="cost_centre_name" class="form-control" placeholder="e.g. Accounts Payable Unit" value="{{ old('cost_centre_name') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="reporting_segment">Reporting Segment</label>
                            <input type="text" id="reporting_segment" name="reporting_segment" class="form-control" placeholder="e.g. Finance Operations" value="{{ old('reporting_segment') }}">
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="margin-top: 20px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Save Cost Centre</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection