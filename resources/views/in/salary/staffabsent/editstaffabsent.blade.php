@extends('layouts.workingside')
@section('title', 'Edit Staff Absent Deduction')
@section('page-title', 'Edit Staff Absent Deduction')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-edit"></i></div>Edit Staff Absent Deduction</h3>
    <a href="{{ route('staffabsent') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back to List</a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updatestaffabsent', [encrypt($absent->id)]) }}">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Employee</label>
                    <input type="text" class="form-control" value="{{ $absent->employee->first_name ?? '' }} {{ $absent->employee->last_name ?? '—' }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="DedactAmount">Deduction Amount (TZS) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="DedactAmount" name="DedactAmount" class="form-control" value="{{ old('DedactAmount', $absent->DedactAmount) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="AbsentStatus">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="AbsentStatus" name="AbsentStatus" required>
                        <option value="Pending" {{ old('AbsentStatus', $absent->AbsentStatus) == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Submited" {{ old('AbsentStatus', $absent->AbsentStatus) == 'Submited' ? 'selected' : '' }}>Submited</option>
                        <option value="Approved" {{ old('AbsentStatus', $absent->AbsentStatus) == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ old('AbsentStatus', $absent->AbsentStatus) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('staffabsent') }}" class="arbif-btn-cancel"><i class="bi bi-x"></i> Cancel</a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit"><i class="bi bi-check2"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection