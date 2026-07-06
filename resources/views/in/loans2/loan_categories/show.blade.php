@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">🔍 Loan Category Details: {{ $loanCategory->name }}</h1>
        <div>
            <a href="{{ route('loan_categories.edit', $loanCategory) }}" class="btn btn-warning me-2"><i class="fas fa-edit"></i> Edit</a>
            <a href="{{ route('loan_categories.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to List</a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            General Information
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Name:</strong> {{ $loanCategory->name }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Status:</strong> 
                    @if ($loanCategory->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Interest Rate:</strong> {{ $loanCategory->interest_rate }}%
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Repayment Frequency:</strong> {{ ucwords(str_replace('_', ' ', $loanCategory->repayment_frequency)) }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Max Term (Months):</strong> {{ $loanCategory->max_term_months }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Max Term (Days):</strong> {{ $loanCategory->max_term_days ?? 'N/A' }}
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-info text-white">
            Financial Details (All in {{ $loanCategory->currency }})
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <strong>Min Amount:</strong> {{ number_format($loanCategory->min_amount, 2) }}
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Max Amount:</strong> {{ number_format($loanCategory->max_amount, 2) }}
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Default Principal:</strong> {{ number_format($loanCategory->principal_amount, 2) }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Default Installment:</strong> {{ number_format($loanCategory->installment_amount, 2) }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Default Total Repayable:</strong> {{ number_format($loanCategory->total_repayable_amount, 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-secondary text-white">
            Conditions and Audit
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>Conditions:</strong>
                <p class="mt-2">{{ $loanCategory->conditions }}</p>
            </div>
            <hr>
            <div class="row small text-muted">
                <div class="col-md-4">
                    <strong>Created By:</strong> {{ $loanCategory->creator->name ?? 'N/A' }}
                </div>
                <div class="col-md-4">
                    <strong>Created At:</strong> {{ $loanCategory->created_at->format('Y-m-d H:i') }}
                </div>
                <div class="col-md-4">
                    <strong>Last Updated:</strong> {{ $loanCategory->updated_at->format('Y-m-d H:i') }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection