@extends('layouts.workingside')
@section('title', 'Staff Absent Deductions')
@section('page-title', 'Staff Absent Deductions')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-calculator"></i></div>Staff Absent Deductions</h3>
    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addFormModal" onclick="openCreateForm()"><i class="fas fa-plus"></i> Calculate & Record Deduction</button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="staffAbsentTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Emp Reg Number</th>
                        <th class="sortable">Employee Name</th>
                        <th class="sortable">Deduction Amount</th>
                        <th class="sortable">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absents as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->employee->EmployeeID ?? '—' }}</td>
                        <td>{{ $item->employee->employee->name ?? '—' }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">TZS {{ number_format($item->DedactAmount, 2) }}</span></td>
                        <td>
                            @if($item->AbsentStatus == 'Submited' || $item->AbsentStatus == 'Approved')
                                <span class="badge bg-success">{{ $item->AbsentStatus }}</span>
                            @elseif($item->AbsentStatus == 'Pending')
                                <span class="badge bg-warning text-dark">{{ $item->AbsentStatus }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $item->AbsentStatus ?? 'Pending' }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('viewstaffabsent', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-eye"></i> View </a>
                            <a href="{{ route('editstaffabsent', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                            <a onclick="confirmDelete()" href="{{ route('deletestaffabsent', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No staff absent deduction records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="staffAbsentTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="staffAbsentTable"></div>
        </div>
    </div>
</div>

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-calculator"></i></div>
                <h5 class="modal-title">Process Staff Absent Deduction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storestaffabsent') }}" enctype="multipart/form-data">
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
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle"></i> Deductions will be automatically calculated based on active recorded absent dates for the current payroll cycle (16th to 15th).
                            </small>
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="margin-top: 20px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-calculator"></i> <span id="submitBtnText">Process Deduction</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection