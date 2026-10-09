@extends('layouts.workingside')
@section('title', 'Salary Allowances Information')
@section('page-title', 'Salary Allowances Information')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-hand-holding-usd"></i></div>Salary Allowances Information</h3>
    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addFormModal" onclick="openCreateForm()"><i class="fas fa-plus"></i> Add Allowance</button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="allowanceTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Emp Reg Number</th>
                        <th class="sortable">Employee Name</th>
                        <th class="sortable">Allowance Amount</th>
                        <th class="sortable">Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allowances as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->employee->EmployeeID ?? '—' }}</td>
                        <td>{{ $item->employee->employee->name ?? '—' }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ number_format($item->AllwAmount, 2) }}</span></td>
                        <td>
                            @if($item->PayStaus == 'Paid')
                                <span class="badge bg-success">{{ $item->PayStaus }}</span>
                            @elseif($item->PayStaus == 'Processing')
                                <span class="badge bg-warning text-dark">{{ $item->PayStaus }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $item->PayStaus ?? 'Unpaid' }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('viewslrallwncs', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-eye"></i> View </a>
                            <a href="{{ route('editslrallwncs', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                            <a onclick="confirmDelete()" href="{{ route('deleteslrallwncs', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No salary allowance records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="allowanceTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="allowanceTable"></div>
        </div>
    </div>
</div>

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-hand-holding-usd"></i></div>
                <h5 class="modal-title">Record Salary Allowance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storeslrallwncs') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Select Employee <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="Paid_id" data-searchable data-placeholder="Select Employee..." required>
                                <option></option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ old('Paid_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->employee->name }} ({{ $emp->EmployeeID }}) ({{ $emp->position ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="AllwAmount">Allowance Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="AllwAmount" name="AllwAmount" class="form-control" placeholder="e.g. 50000.00" value="{{ old('AllwAmount') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="PayStaus">Payment Status</label>
                            <select class="form-select" id="PayStaus" name="PayStaus">
                                <option value="Unpaid" {{ old('PayStaus') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="Processing" {{ old('PayStaus') == 'Processing' ? 'selected' : '' }}>Processing</option>
                                <option value="Paid" {{ old('PayStaus') == 'Paid' ? 'selected' : '' }}>Paid</option>
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