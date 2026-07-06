@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <h5 class="mb-0">{{ $loanCategory->name ?? 'Loan Category Details' }}</h5>
            <a href="{{ route('loan_categories.index') }}" class="btn btn-light btn-sm">Back</a>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Amount Disbursed:</strong> {{ number_format($loanCategory->amount_disbursed,2) }}</div>
                <div class="col-md-4"><strong>Interest Rate:</strong> {{ $loanCategory->interest_rate }}%</div>
                <div class="col-md-4"><strong>Frequency:</strong> {{ ucfirst(str_replace('_',' ', $loanCategory->repayment_frequency)) }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Insurance Fee:</strong> {{ number_format($loanCategory->insurance_fee,2) }}</div>
                <div class="col-md-4"><strong>Officer Visit Fee:</strong> {{ number_format($loanCategory->officer_visit_fee,2) }}</div>
                <div class="col-md-4"><strong>Interest Amount:</strong> {{ number_format($loanCategory->interest_amount,2) }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Principal Due:</strong> {{ $loanCategory->principal_due }}</div>
                <div class="col-md-4"><strong>Interest Due:</strong> {{ number_format($loanCategory->interest_due,2) }}</div>
                <div class="col-md-4"><strong>Total Due:</strong> {{ number_format($loanCategory->total_due,2) }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Total Days Due:</strong> {{ number_format($loanCategory->total_days_due) }}</div>
                <div class="col-md-4"><strong>Total Repayable Amount:</strong> {{ number_format($loanCategory->repayable_amount,2) }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Currency:</strong> {{ $loanCategory->currency }}</div>
                <div class="col-md-4"><strong>Status:</strong>
                    <span class="badge bg-{{ $loanCategory->is_active ? 'success' : 'secondary' }}">
                        {{ $loanCategory->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="col-md-4"><strong>New Client:</strong>
                    <span class="badge bg-{{ $loanCategory->is_new_client ? 'info' : 'dark' }}">
                        {{ $loanCategory->is_new_client ? 'Yes' : 'No' }}
                    </span>
                </div>
            </div>
            <div class="mb-3"><strong>Conditions:</strong><br>{{ $loanCategory->conditions }}</div>
            <div class="mb-3"><strong>Descriptions:</strong><br>{{ $loanCategory->descriptions }}</div>
            <div><strong>Created By:</strong> {{ $loanCategory->creator->first_name ?? ''}} {{ $loanCategory->creator->last_name ?? 'N/A'}} | <strong>Updated By:</strong> {{ $loanCategory->updater->first_name ?? '' }} {{ $loanCategory->updater->last_name ?? 'N/A' }} </div>
        </div>
    </div>
</div>
@endsection
