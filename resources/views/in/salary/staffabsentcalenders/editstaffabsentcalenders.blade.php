@extends('layouts.workingside')
@section('title', 'Edit Staff Absent Calendar')
@section('page-title', 'Edit Staff Absent Calendar')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-edit"></i></div>Edit Staff Absent Calendar</h3>
    <a href="{{ route('staffabsentcalenders') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back to List</a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updatestaffabsentcalenders', [encrypt($absentCalender->id)]) }}">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="Emp_id" data-searchable required>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('Emp_id', $absentCalender->Emp_id) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="AbsentDate">Absent Date <span class="text-danger">*</span></label>
                    <input type="date" id="AbsentDate" name="AbsentDate" class="form-control" value="{{ old('AbsentDate', \Carbon\Carbon::parse($absentCalender->AbsentDate)->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="AbsentStatus">Absent Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="AbsentStatus" name="AbsentStatus" required>
                        <option value="Pending" {{ old('AbsentStatus', $absentCalender->AbsentStatus) == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Submited" {{ old('AbsentStatus', $absentCalender->AbsentStatus) == 'Submited' ? 'selected' : '' }}>Submited</option>
                        <option value="Approved" {{ old('AbsentStatus', $absentCalender->AbsentStatus) == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ old('AbsentStatus', $absentCalender->AbsentStatus) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('staffabsentcalenders') }}" class="arbif-btn-cancel"><i class="bi bi-x"></i> Cancel</a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit"><i class="bi bi-check2"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection