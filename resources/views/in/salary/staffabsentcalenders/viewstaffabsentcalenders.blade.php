@extends('layouts.workingside')
@section('title', 'View Staff Absent Calendar Details')
@section('page-title', 'View Staff Absent Calendar Details')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-calendar-times"></i></div>Staff Absent Calendar Details</h3>
    <a href="{{ route('staffabsentcalenders') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back to List</a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <strong>Employee Name:</strong>
                <p>{{ $absentCalender->employee->first_name ?? '' }} {{ $absentCalender->employee->last_name ?? '—' }}</p>
            </div>
            <div class="col-md-3">
                <strong>Absent Date:</strong>
                <p><span class="arbif-badge arbif-badge-navy">{{ \Carbon\Carbon::parse($absentCalender->AbsentDate)->format('d M, Y') }}</span></p>
            </div>
            <div class="col-md-3">
                <strong>Status:</strong>
                <p>
                    @if($absentCalender->AbsentStatus == 'Submited' || $absentCalender->AbsentStatus == 'Approved')
                        <span class="badge bg-success">{{ $absentCalender->AbsentStatus }}</span>
                    @elseif($absentCalender->AbsentStatus == 'Pending')
                        <span class="badge bg-warning text-dark">{{ $absentCalender->AbsentStatus }}</span>
                    @else
                        <span class="badge bg-secondary">{{ $absentCalender->AbsentStatus ?? 'Pending' }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection