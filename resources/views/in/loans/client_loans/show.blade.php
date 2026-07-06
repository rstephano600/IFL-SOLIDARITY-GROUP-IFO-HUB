@extends('layouts.app')

@section('title', 'Loan Details: ' . $clientLoan->loan_number)
@section('page-title', 'Loan Details')

@section('content')
<div class="container py-4">

    {{-- Header and Action Buttons --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-primary">Loan Details: {{ $clientLoan->loan_number }}</h2>
        <div class="btn-group">
            {{-- Approve/Disburse Button (Conditional) --}}
            @if(in_array(Auth::user()->role, ['manager', 'Admin']) && $clientLoan->status === 'pending')
                <a href="{{ route('loans.approve.edit', $clientLoan->id) }}" class="btn btn-success d-flex align-items-center">
                    <i class="bi bi-check-circle me-2"></i> Approve/Disburse Loan
                </a>
            @endif
            {{-- Fill Collection Button --}}
            <a href="{{ route('daily_collections.create', ['loan_id' => $clientLoan->id]) }}" 
               class="btn btn-primary d-flex align-items-center">
                <i class="bi bi-cash-coin me-2"></i> Fill Collection
            </a>
            <a href="{{ route('client_loans.edit', $clientLoan) }}" class="btn btn-warning d-flex align-items-center">
                <i class="bi bi-pencil me-2"></i> Edit Loan
            </a>
            <a href="{{ route('client_loans.index') }}" class="btn btn-secondary d-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i> Back to List
            </a>
        </div>
    </div>

    {{-- Dynamic Status Badge Logic (consistent with index) --}}
    @php
        $statusClass = [
            'pending' => 'warning',
            'approved' => 'info',
            'disbursed' => 'success',
            'completed' => 'primary',
            'defaulted' => 'danger',
        ][$clientLoan->status] ?? 'secondary';
    @endphp

    <div class="row">
        {{-- LOAN & CLIENT INFORMATION --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> General Loan Info</h5>
                    <span class="badge bg-{{ $statusClass }} fs-6">{{ ucfirst($clientLoan->status) }}</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Loan Number:</strong>
                            <span class="fw-bold text-primary">{{ $clientLoan->loan_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Client:</strong>
                            <a href="{{ route('clients.show', $clientLoan->client_id) }}" class="text-decoration-none">{{ $clientLoan->client->first_name }} {{ $clientLoan->client->last_name }}</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Group Center:</strong>
                            <span>{{ $clientLoan->groupCenter->center_name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Start Date:</strong>
                            <span>{{ $clientLoan->start_date ? \Carbon\Carbon::parse($clientLoan->start_date)->format('M d, Y') : '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>End Date:</strong>
                            <span>{{ $clientLoan->end_date ? \Carbon\Carbon::parse($clientLoan->end_date)->format('M d, Y') : '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Days Remaining:</strong>
                            <span class="fw-bold text-{{ ($clientLoan->days_left < 10 && $clientLoan->status !== 'completed') ? 'danger' : 'success' }}">
                                {{ $clientLoan->days_left ?? '-' }}
                            </span>
                        </li>
                        <li class="list-group-item">
                            <strong>Remarks:</strong>
                            <p class="text-muted mb-0 small">{{ $clientLoan->remarks ?? 'No remarks provided.' }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- FINANCIAL BREAKDOWN --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-calculator me-2"></i> Financial Overview</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        {{-- Requested / Disbursed --}}
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            <strong>Amount Requested:</strong>
                            <span class="fw-bold">{{ number_format($clientLoan->amount_requested, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Amount Disbursed:</strong>
                            <span class="fw-bold text-success">{{ number_format($clientLoan->amount_disbursed, 2) }}</span>
                        </li>

                        {{-- Fees and Interest --}}
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Interest Rate:</strong>
                            <span>{{ $clientLoan->interest_rate }}%</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Estimated Interest:</strong>
                            <span>{{ number_format($clientLoan->interest_amount ?? 0, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Loan Fee / Other Fees:</strong>
                            <span>{{ number_format($clientLoan->loan_fee ?? 0, 2) }} / ${{ number_format($clientLoan->other_fee ?? 0, 2) }}</span>
                        </li>
                        
                        {{-- Frequency --}}
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Payable Frequency:</strong>
                            <span>{{ ucfirst($clientLoan->payable_frequency ?? '-') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Repayment Frequency:</strong>
                            <span>{{ ucfirst($clientLoan->repayment_frequency ?? '-') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- PAYMENT SUMMARY --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-cash-stack me-2"></i> Payment and Balance</h5>
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-4 g-4 text-center">
                {{-- Amount Paid --}}
                <div class="col">
                    <div class="p-3 bg-light border rounded">
                        <small class="text-muted">Amount Paid</small>
                        <h4 class="fw-bold text-success mb-0">{{ number_format($clientLoan->amount_paid ?? 0, 2) }}</h4>
                    </div>
                </div>
                {{-- Outstanding Balance --}}
                <div class="col">
                    <div class="p-3 bg-light border rounded">
                        <small class="text-muted">Outstanding Balance</small>
                        <h4 class="fw-bold text-danger mb-0">{{ number_format($clientLoan->outstanding_balance ?? 0, 2) }}</h4>
                    </div>
                </div>
                {{-- Penalty Fee --}}
                <div class="col">
                    <div class="p-3 bg-light border rounded">
                        <small class="text-muted">Penalty Fee</small>
                        <h4 class="fw-bold text-warning mb-0">{{ number_format($clientLoan->penalty_fee ?? 0, 2) }}</h4>
                    </div>
                </div>
                {{-- Profit/Loss --}}
                <div class="col">
                    <div class="p-3 bg-light border rounded">
                        <small class="text-muted">Profit/Loss (Total)</small>
                        @php
                            $profitLoss = $clientLoan->profit_loss_amount ?? 0;
                            $profitLossClass = $profitLoss >= 0 ? 'success' : 'danger';
                        @endphp
                        <h4 class="fw-bold text-{{ $profitLossClass }} mb-0">{{ number_format(abs($profitLoss), 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CLOSURE DETAILS (Conditional) --}}
    @if($clientLoan->closed_at)
    <div class="card shadow-sm mb-4 border-secondary">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-x-octagon me-2"></i> Loan Closure Details</h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Closed On:</strong>
                    <span>{{ \Carbon\Carbon::parse($clientLoan->closed_at)->format('M d, Y H:i A') }}</span>
                </li>
                <li class="list-group-item">
                    <strong>Closure Reason:</strong>
                    <p class="mb-0">{{ $clientLoan->closure_reason ?? 'N/A' }}</p>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Total Preclosure Amount:</strong>
                    <span>{{ number_format($clientLoan->total_preclosure ?? 0, 2) }}</span>
                </li>
            </ul>
        </div>
    </div>
    @endif

</div>
@endsection