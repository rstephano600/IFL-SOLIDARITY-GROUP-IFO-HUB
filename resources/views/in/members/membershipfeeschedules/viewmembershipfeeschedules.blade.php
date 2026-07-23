@extends('layouts.configside')
@section('title', 'View Membership Fee Schedule')
@section('page-title', 'Membership Fee Schedule Details')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-eye"></i></div>
        Fee Schedule: {{ $feeSchedule->ScheduleRefNo ?? 'N/A' }}
    </h3>
    <div>
        <a href="{{ route('editmembershipfeeschedules', [encrypt($feeSchedule->id)]) }}" class="arbif-btn-edit me-2">
            <i class="fas fa-pencil me-1"></i> Edit Schedule
        </a>
        <a href="{{ route('membershipfeeschedules') }}" class="arbif-btn-cancel">
            <i class="fas fa-arrow-left me-1"></i> Back to Index
        </a>
    </div>
</div>

<!-- Details Summary Cards -->
<div class="row g-3 mb-4">
    <!-- Schedule Info Card -->
    <div class="col-md-8">
        <div class="arbif-card h-100">
            <div class="arbif-card-header fw-bold text-navy">
                <i class="fas fa-file-invoice-dollar me-2"></i> Schedule Information
            </div>
            <div class="arbif-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Reference Number</small>
                        <span class="arbif-badge arbif-badge-navy fs-6">{{ $feeSchedule->ScheduleRefNo ?? '—' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Fee Amount</small>
                        <h4 class="text-navy fw-bold mb-0">{{ number_format($feeSchedule->FeeAmount, 2) }}</h4>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Effective Period</small>
                        <strong class="text-dark">
                            {{ $feeSchedule->EffectiveFrom ? $feeSchedule->EffectiveFrom->format('d M Y') : '—' }}
                        </strong>
                        <span class="text-muted"> to </span>
                        <strong class="text-dark">
                            {{ $feeSchedule->EffectiveTo ? $feeSchedule->EffectiveTo->format('d M Y') : 'Ongoing' }}
                        </strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Status</small>
                        <span class="arbif-badge {{ $feeSchedule->Status === 'Active' ? 'bg-success' : 'bg-danger' }} text-white">
                            {{ $feeSchedule->Status ?? 'Active' }}
                        </span>
                    </div>
                    <div class="col-md-12">
                        <small class="text-muted d-block">Description / Notes</small>
                        <p class="text-dark mb-0 bg-light p-2 rounded border">
                            {{ $feeSchedule->Description ?? 'No additional description provided.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Organizational & System Context Card -->
    <div class="col-md-4">
        <div class="arbif-card h-100">
            <div class="arbif-card-header fw-bold text-navy">
                <i class="fas fa-building me-2"></i> Organization Context
            </div>
            <div class="arbif-card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">
                        <small class="text-muted d-block">Company</small>
                        <strong class="text-dark">{{ $feeSchedule->company->company_name ?? '—' }}</strong>
                    </li>
                    <li class="list-group-item px-0">
                        <small class="text-muted d-block">Branch</small>
                        <strong class="text-dark">{{ $feeSchedule->branch->branch_name ?? '—' }}</strong>
                    </li>
                    <li class="list-group-item px-0">
                        <small class="text-muted d-block">Created By</small>
                        <span class="text-dark fw-bold">{{ $feeSchedule->user->name ?? 'System' }}</span>
                    </li>
                    <li class="list-group-item px-0 border-0">
                        <small class="text-muted d-block">Created Date</small>
                        <span class="text-dark">{{ $feeSchedule->created_at ? $feeSchedule->created_at->format('d M Y H:i A') : '—' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Related Payments Breakdown Table -->
<div class="arbif-card">
    <div class="arbif-card-header fw-bold text-navy d-flex justify-content-between align-items-center">
        <span><i class="fas fa-receipt me-2"></i> Associated Payments</span>
        <span class="badge bg-primary rounded-pill">{{ $feeSchedule->payments->count() }} Payments Recorded</span>
    </div>
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="paymentsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Payment Ref</th>
                        <th>Amount Paid</th>
                        <th>Payment Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feeSchedule->payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $payment->PaymentRefNo ?? $payment->id }}</span></td>
                        <td><strong class="text-navy">{{ number_format($payment->Amount ?? $payment->AmountPaid ?? 0, 2) }}</strong></td>
                        <td>{{ $payment->PaymentDate ? \Carbon\Carbon::parse($payment->PaymentDate)->format('d M Y') : '—' }}</td>
                        <td>
                            <span class="arbif-badge bg-success text-white">
                                {{ $payment->Status ?? 'Completed' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i> No payment transactions recorded against this schedule yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection