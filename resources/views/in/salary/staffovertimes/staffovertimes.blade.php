@extends('layouts.workingside')
@section('title', 'Staff Overtimes Information')
@section('page-title', 'Staff Overtimes Information')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-business-time"></i></div>Staff Overtimes Information</h3>
    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addFormModal" onclick="openCreateForm()"><i class="fas fa-plus"></i> Record Overtime</button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="overtimeTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Emp Reg Number</th>
                        <th class="sortable">Employee Name</th>
                        <th class="sortable">Pay Amount</th>
                        <th class="sortable">Paid Month</th>
                        <th class="sortable">Approval Status</th>
                        <th class="sortable">Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($overtimes as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->employee->EmployeeID ?? '—' }}</td>
                        <td>{{ $item->employee->employee->name ?? '—' }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ number_format($item->PayAmnt, 2) }}</span></td>
                        <td>{{ $item->PaidMonth }}</td>
                        <td>
                            @if($item->AppStatus == 'Approved')
                                <span class="badge bg-success">{{ $item->AppStatus }}</span>
                            @elseif($item->AppStatus == 'Pending')
                                <span class="badge bg-warning text-dark">{{ $item->AppStatus }}</span>
                            @else
                                <span class="badge bg-danger">{{ $item->AppStatus }}</span>
                            @endif
                        </td>
                        <td>
                            @if($item->PayStatus == 'Paid')
                                <span class="badge bg-success">{{ $item->PayStatus }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $item->PayStatus }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('viewstaffovertimes', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-eye"></i> View </a>
                            <a href="{{ route('editstaffovertimes', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                            <a onclick="confirmDelete()" href="{{ route('deletestaffovertimes', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No staff overtime records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="overtimeTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="overtimeTable"></div>
        </div>
    </div>
</div>

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-business-time"></i></div>
                <h5 class="modal-title">Record Staff Overtime</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storestaffovertimes') }}" enctype="multipart/form-data">
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
                            <label class="form-label" for="PayAmnt">Pay Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="PayAmnt" name="PayAmnt" class="form-control" placeholder="e.g. 150000.00" value="{{ old('PayAmnt') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="PaidMonth">Paid Month <span class="text-danger">*</span></label>
                            <input type="month" id="PaidMonth" name="PaidMonth" class="form-control" value="{{ old('PaidMonth') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="AdvanceStatus">Advance Status</label>
                            <select class="form-select" id="AdvanceStatus" name="AdvanceStatus">
                                <option value="None" {{ old('AdvanceStatus') == 'None' ? 'selected' : '' }}>None</option>
                                <option value="Requested" {{ old('AdvanceStatus') == 'Requested' ? 'selected' : '' }}>Requested</option>
                                <option value="Issued" {{ old('AdvanceStatus') == 'Issued' ? 'selected' : '' }}>Issued</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="AdvanceCondition">Advance Condition / Remarks</label>
                            <textarea id="AdvanceCondition" name="AdvanceCondition" class="form-control" rows="3" placeholder="Specify conditions or remarks if applicable...">{{ old('AdvanceCondition') }}</textarea>
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