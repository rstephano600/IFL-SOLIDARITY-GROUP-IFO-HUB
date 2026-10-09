@extends('layouts.workingside')
@section('title', 'Edit Staff Advance Salary')
@section('page-title', 'Edit Staff Advance Salary')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-edit"></i></div>Edit Staff Advance Salary</h3>
    <a href="{{ route('staffadvncslries') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back to List</a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updatestaffadvncslries', [encrypt($advance->id)]) }}">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="Emp_id" data-searchable required>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('Emp_id', $advance->Emp_id) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Advance Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="AdvStatus" required>
                        <option value="Pending" {{ old('AdvStatus', $advance->AdvStatus) == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Active" {{ old('AdvStatus', $advance->AdvStatus) == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Completed" {{ old('AdvStatus', $advance->AdvStatus) == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="AdvanceAmount">Advance Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="AdvanceAmount" name="AdvanceAmount" class="form-control" value="{{ old('AdvanceAmount', $advance->AdvanceAmount) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="PaidAmount">Paid Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="PaidAmount" name="PaidAmount" class="form-control" value="{{ old('PaidAmount', $advance->PaidAmount) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="InstallAmount">Installment Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="InstallAmount" name="InstallAmount" class="form-control" value="{{ old('InstallAmount', $advance->InstallAmount) }}" required>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('staffadvncslries') }}" class="arbif-btn-cancel"><i class="bi bi-x"></i> Cancel</a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit"><i class="bi bi-check2"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection