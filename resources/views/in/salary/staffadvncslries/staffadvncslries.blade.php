@extends('layouts.workingside')
@section('title', 'Staff Advance Salaries')
@section('page-title', 'Staff Advance Salaries')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-hand-holding-usd"></i></div>Staff Advance Salaries</h3>
    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addFormModal" onclick="openCreateForm()"><i class="fas fa-plus"></i> Request Advance</button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="advanceSalaryTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Emp Reg Number</th>
                        <th class="sortable">Employee Name</th>
                        <th class="sortable">Advance Amount</th>
                        <th class="sortable">Paid Amount</th>
                        <th class="sortable">Installment Amount</th>
                        <th class="sortable">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($advances as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->employee->EmployeeID ?? '—' }}</td>
                        <td>{{ $item->employee->employee->name ?? '—' }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ number_format($item->AdvanceAmount, 2) }}</span></td>
                        <td>{{ number_format($item->PaidAmount, 2) }}</td>
                        <td>{{ number_format($item->InstallAmount, 2) }}</td>
                        <td>
                            @if($item->AdvStatus == 'Active' || $item->AdvStatus == 'Approved')
                                <span class="badge bg-success">{{ $item->AdvStatus }}</span>
                            @elseif($item->AdvStatus == 'Pending')
                                <span class="badge bg-warning text-dark">{{ $item->AdvStatus }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $item->AdvStatus }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('viewstaffadvncslries', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-eye"></i> View </a>
                            <a href="{{ route('editstaffadvncslries', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                            <a onclick="confirmDelete()" href="{{ route('deletestaffadvncslries', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No staff advance salary records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="advanceSalaryTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="advanceSalaryTable"></div>
        </div>
    </div>
</div>

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-hand-holding-usd"></i></div>
                <h5 class="modal-title">Record Advance Salary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storestaffadvncslries') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Select Employee <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="Emp_id" data-searchable data-placeholder="Select Employee..." required>
                                <option></option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ old('Emp_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->employee->name }} ({{ $emp->EmployeeID }}) ({{ $emp->position ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="AdvanceAmount">Advance Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="AdvanceAmount" name="AdvanceAmount" class="form-control" placeholder="e.g. 500000.00" value="{{ old('AdvanceAmount') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="InstallAmount">Installment Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="InstallAmount" name="InstallAmount" class="form-control" placeholder="e.g. 50000.00" value="{{ old('InstallAmount') }}" required>
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="margin-top: 20px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Save Record</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection