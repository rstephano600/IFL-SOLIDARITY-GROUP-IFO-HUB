@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">🔍 Loan Details: {{ $loan->loan_number }}</h1>
        <div>
            <a href="{{ route('loans.edit', $loan) }}" class="btn btn-warning me-2"><i class="fas fa-edit"></i> Edit Loan</a>
            <a href="{{ route('loans.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to List</a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            General Information
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Client:</strong> {{ $loan->client->first_name ?? 'N/A' }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Category:</strong> {{ $loan->category->name ?? 'N/A' }}
                </div>
            </div>
             <div class="row">
                {{-- DISPLAY GROUP CENTER INFO --}}
                <div class="col-md-6 mb-3">
                    <strong>Assigned Center:</strong> 
                    @if($loan->groupCenter)
                        {{ $loan->groupCenter->center_name }} ({{ $loan->groupCenter->center_code }})
                    @else
                        Individual Loan (N/A)
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <strong>Status:</strong> 
                    <span class="badge bg-{{ ['pending' => 'warning', 'approved' => 'info', 'active' => 'success', 'completed' => 'primary', 'defaulted' => 'danger', 'closed' => 'secondary'][$loan->status] ?? 'dark' }}">{{ ucwords($loan->status) }}</span>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Disbursement Date:</strong> {{ $loan->disbursement_date ? $loan->disbursement_date->format('Y-m-d') : 'N/A' }}
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Loan Number:</strong> {{ $loan->loan_number }}
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-info text-white">
            Financial Terms & Totals
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3"><strong>Principal:</strong> {{ number_format($loan->principal_amount ?? 0, 2) }}</div>
                <div class="col-md-3 mb-3"><strong>Interest Rate:</strong> {{ $loan->interest_rate ?? 'N/A' }}%</div>
                <div class="col-md-3 mb-3"><strong>Term (Months):</strong> {{ $loan->term_months ?? 'N/A' }}</div>
                <div class="col-md-3 mb-3"><strong>Frequency:</strong> {{ ucwords(str_replace('_', ' ', $loan->repayment_frequency ?? 'N/A')) }}</div>
            </div>
            <div class="row border-top pt-3 mt-3">
                <div class="col-md-4 mb-3"><strong>Total Payable:</strong> {{ number_format($loan->total_payable, 2) }}</div>
                <div class="col-md-4 mb-3"><strong>Total Interest:</strong> {{ number_format($loan->total_interest, 2) }}</div>
                <div class="col-md-4 mb-3"><strong>Delinquency:</strong> <span class="badge bg-{{ $loan->delinquency_status !== 'current' ? 'danger' : 'success' }}">{{ ucwords(str_replace('_', ' ', $loan->delinquency_status)) }}</span></div>
            </div>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header bg-warning text-dark">
            Outstanding & Paid
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <strong>Total Outstanding:</strong> <h4 class="text-danger">{{ number_format($loan->total_outstanding, 2) }}</h4>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Principal Outstanding:</strong> {{ number_format($loan->outstanding_principal, 2) }}
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Interest Outstanding:</strong> {{ number_format($loan->outstanding_interest, 2) }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <strong>Total Paid:</strong> <h4 class="text-success">{{ number_format($loan->total_paid, 2) }}</h4>
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Interest Paid:</strong> {{ number_format($loan->interest_paid, 2) }}
                </div>
                <div class="col-md-4 mb-3">
                    <strong>Fees Paid:</strong> {{ number_format($loan->fees_paid, 2) }}
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="card shadow mb-4">
        <div class="card-header bg-light text-dark">
            Audit & Closure
        </div>
        <div class="card-body small">
            <div class="row">
                <div class="col-md-6">
                    <strong>Created By:</strong> {{ $loan->creator->name ?? 'System' }} at {{ $loan->created_at->format('Y-m-d H:i') }}
                </div>
                <div class="col-md-6">
                    <strong>Last Updated By:</strong> {{ $loan->updater->name ?? 'N/A' }} at {{ $loan->updated_at->format('Y-m-d H:i') }}
                </div>
            </div>
            @if ($loan->closed_at)
            <div class="mt-3">
                <strong>Closed At:</strong> {{ $loan->closed_at->format('Y-m-d H:i') }} |
                <strong>Reason:</strong> {{ $loan->closure_reason ?? 'N/A' }}
            </div>
            @endif
        </div>
    </div>
    
</div>
@endsection