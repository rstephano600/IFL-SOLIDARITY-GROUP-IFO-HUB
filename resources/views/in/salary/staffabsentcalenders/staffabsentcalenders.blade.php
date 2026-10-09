@extends('layouts.workingside')
@section('title', 'Staff Absent Calendar Information')
@section('page-title', 'Staff Absent Calendar Information')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-calendar-times"></i></div>Staff Absent Calendar Information</h3>
    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addFormModal" onclick="openCreateForm()"><i class="fas fa-plus"></i> Record Absent Date</button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="absentCalenderTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Emp Reg Number</th>
                        <th class="sortable">Employee Name</th>
                        <th class="sortable">Absent Date</th>
                        <th class="sortable">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absentCalenders as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->employee->EmployeeID ?? '—' }}</td>
                        <td>{{ $item->employee->employee->name ?? '—' }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ \Carbon\Carbon::parse($item->AbsentDate)->format('d M, Y') }}</span></td>
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
                            <a href="{{ route('viewstaffabsentcalenders', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-eye"></i> View </a>
                            <a href="{{ route('editstaffabsentcalenders', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                            <a onclick="confirmDelete()" href="{{ route('deletestaffabsentcalenders', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No staff absent calendar records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="absentCalenderTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="absentCalenderTable"></div>
        </div>
    </div>
</div>

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-calendar-times"></i></div>
                <h5 class="modal-title">Record Staff Absent Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storestaffabsentcalenders') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
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
                            <label class="form-label" for="AbsentDate">Absent Date <span class="text-danger">*</span></label>
                            <input type="date" id="AbsentDate" name="AbsentDate" class="form-control" value="{{ old('AbsentDate') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="AbsentStatus">Absent Status</label>
                            <select class="form-select" id="AbsentStatus" name="AbsentStatus">
                                <option value="Pending" {{ old('AbsentStatus') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Submited" {{ old('AbsentStatus') == 'Submited' ? 'selected' : '' }}>Submited</option>
                                <option value="Approved" {{ old('AbsentStatus') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Rejected" {{ old('AbsentStatus') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
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