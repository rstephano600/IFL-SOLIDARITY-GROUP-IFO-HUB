@extends('layouts.workingside')
@section('title', 'View Staff Overtime Details')
@section('page-title', 'View Staff Overtime Details')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-business-time"></i></div>Overtime Details</h3>
    <a href="{{ route('staffovertimes') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back to List</a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <strong>Employee Name:</strong>
                <p>{{ $overtime->employee->first_name ?? '' }} {{ $overtime->employee->last_name ?? '—' }}</p>
            </div>
            <div class="col-md-3">
                <strong>Approval Status:</strong>
                <p><span class="arbif-badge arbif-badge-navy">{{ $overtime->AppStatus }}</span></p>
            </div>
            <div class="col-md-3">
                <strong>Payment Status:</strong>
                <p><span class="arbif-badge arbif-badge-navy">{{ $overtime->PayStatus }}</span></p>
            </div>
            <div class="col-md-4">
                <strong>Pay Amount:</strong>
                <p>{{ number_format($overtime->PayAmnt, 2) }}</p>
            </div>
            <div class="col-md-4">
                <strong>Paid Month:</strong>
                <p>{{ $overtime->PaidMonth }}</p>
            </div>
            <div class="col-md-4">
                <strong>Advance Status:</strong>
                <p>{{ $overtime->AdvanceStatus ?? 'N/A' }}</p>
            </div>
            <div class="col-md-12">
                <strong>Advance Condition / Remarks:</strong>
                <p>{{ $overtime->AdvanceCondition ?? 'No additional conditions provided.' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection