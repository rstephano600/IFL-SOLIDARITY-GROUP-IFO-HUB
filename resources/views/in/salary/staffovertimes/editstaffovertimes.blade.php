@extends('layouts.workingside')
@section('title', 'Edit Staff Overtime')
@section('page-title', 'Edit Staff Overtime')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-edit"></i></div>Edit Staff Overtime</h3>
    <a href="{{ route('staffovertimes') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back to List</a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updatestaffovertimes', [encrypt($overtime->id)]) }}">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="Emp_id" data-searchable required>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('Emp_id', $overtime->Emp_id) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="PayAmnt">Pay Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="PayAmnt" name="PayAmnt" class="form-control" value="{{ old('PayAmnt', $overtime->PayAmnt) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Approval Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="AppStatus" required>
                        <option value="Pending" {{ old('AppStatus', $overtime->AppStatus) == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ old('AppStatus', $overtime->AppStatus) == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ old('AppStatus', $overtime->AppStatus) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="PayStatus" required>
                        <option value="Unpaid" {{ old('PayStatus', $overtime->PayStatus) == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="Paid" {{ old('PayStatus', $overtime->PayStatus) == 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Processing" {{ old('PayStatus', $overtime->PayStatus) == 'Processing' ? 'selected' : '' }}>Processing</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="PaidMonth">Paid Month <span class="text-danger">*</span></label>
                    <input type="month" id="PaidMonth" name="PaidMonth" class="form-control" value="{{ old('PaidMonth', $overtime->PaidMonth) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="AdvanceStatus">Advance Status</label>
                    <select class="form-select" id="AdvanceStatus" name="AdvanceStatus">
                        <option value="None" {{ old('AdvanceStatus', $overtime->AdvanceStatus) == 'None' ? 'selected' : '' }}>None</option>
                        <option value="Requested" {{ old('AdvanceStatus', $overtime->AdvanceStatus) == 'Requested' ? 'selected' : '' }}>Requested</option>
                        <option value="Issued" {{ old('AdvanceStatus', $overtime->AdvanceStatus) == 'Issued' ? 'selected' : '' }}>Issued</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="AdvanceCondition">Advance Condition / Remarks</label>
                    <textarea id="AdvanceCondition" name="AdvanceCondition" class="form-control" rows="3">{{ old('AdvanceCondition', $overtime->AdvanceCondition) }}</textarea>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('staffovertimes') }}" class="arbif-btn-cancel"><i class="bi bi-x"></i> Cancel</a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit"><i class="bi bi-check2"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection