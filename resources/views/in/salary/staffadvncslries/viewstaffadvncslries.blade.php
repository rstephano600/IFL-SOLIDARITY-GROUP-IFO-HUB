@extends('layouts.workingside')
@section('title', 'View Staff Advance Salary')
@section('page-title', 'View Staff Advance Salary')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-hand-holding-usd"></i></div>Advance Details</h3>
    <a href="{{ route('staffadvncslries') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back to List</a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <strong>Employee Name:</strong>
                <p>{{ $advance->employee->employee->name ?? '' }}</p>
            </div>
            <div class="col-md-6">
                <strong>Status:</strong>
                <p><span class="arbif-badge arbif-badge-navy">{{ $advance->AdvStatus }}</span></p>
            </div>
            <div class="col-md-4">
                <strong>Advance Amount:</strong>
                <p>{{ number_format($advance->AdvanceAmount, 2) }}</p>
            </div>
            <div class="col-md-4">
                <strong>Paid Amount:</strong>
                <p>{{ number_format($advance->PaidAmount, 2) }}</p>
            </div>
            <div class="col-md-4">
                <strong>Installment Amount:</strong>
                <p>{{ number_format($advance->InstallAmount, 2) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection