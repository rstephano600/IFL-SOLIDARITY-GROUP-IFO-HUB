@extends('layouts.workingside')
@section('title', 'View Staff Absent Deduction Details')
@section('page-title', 'View Staff Absent Deduction Details')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-calculator"></i></div>Staff Absent Deduction Details</h3>
    <a href="{{ route('staffabsent') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back to List</a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <strong>Employee Name:</strong>
                <p>{{ $absent->employee->first_name ?? '' }} {{ $absent->employee->last_name ?? '—' }}</p>
            </div>
            <div class="col-md-3">
                <strong>Deduction Amount:</strong>
                <p><span class="arbif-badge arbif-badge-navy">TZS {{ number_format($absent->DedactAmount, 2) }}</span></p>
            </div>
            <div class="col-md-3">
                <strong>Status:</strong>
                <p>
                    @if($absent->AbsentStatus == 'Submited' || $absent->AbsentStatus == 'Approved')
                        <span class="badge bg-success">{{ $absent->AbsentStatus }}</span>
                    @elseif($absent->AbsentStatus == 'Pending')
                        <span class="badge bg-warning text-dark">{{ $absent->AbsentStatus }}</span>
                    @else
                        <span class="badge bg-secondary">{{ $absent->AbsentStatus ?? 'Pending' }}</span>
                    @endif
                </p>
            </div>
            <div class="col-md-6">
                <strong>Created At:</strong>
                <p>{{ \Carbon\Carbon::parse($absent->created_at)->format('d M, Y H:i A') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection