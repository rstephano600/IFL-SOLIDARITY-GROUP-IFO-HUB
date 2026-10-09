@extends('layouts.workingside')
@section('title', 'View Salary Allowance Details')
@section('page-title', 'View Salary Allowance Details')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-hand-holding-usd"></i></div>Salary Allowance Details</h3>
    <a href="{{ route('slrallwncs') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back to List</a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <strong>Employee Name:</strong>
                <p>{{ $allowance->employee->first_name ?? '' }} {{ $allowance->employee->last_name ?? '—' }}</p>
            </div>
            <div class="col-md-6">
                <strong>Allowance Amount:</strong>
                <p><span class="arbif-badge arbif-badge-navy">{{ number_format($allowance->AllwAmount, 2) }}</span></p>
            </div>
            <div class="col-md-6">
                <strong>Payment Status:</strong>
                <p>
                    @if($allowance->PayStaus == 'Paid')
                        <span class="badge bg-success">{{ $allowance->PayStaus }}</span>
                    @elseif($allowance->PayStaus == 'Processing')
                        <span class="badge bg-warning text-dark">{{ $allowance->PayStaus }}</span>
                    @else
                        <span class="badge bg-secondary">{{ $allowance->PayStaus ?? 'Unpaid' }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection