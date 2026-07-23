@extends('layouts.configside')
@section('title', 'View Social Contribution Schedule')
@section('page-title', 'Social Contribution Schedule Details')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-eye"></i></div>
        Schedule Ref: {{ $schedule->ScheduleRefNo ?? 'N/A' }}
    </h3>
    <div>
        <a href="{{ route('editsocialcontributionschedules', [encrypt($schedule->id)]) }}" class="arbif-btn-edit me-2">
            <i class="fas fa-pencil me-1"></i> Edit Schedule
        </a>
        <a href="{{ route('socialcontributionschedules') }}" class="arbif-btn-cancel">
            <i class="fas fa-arrow-left me-1"></i> Back to Index
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Schedule Main Details -->
    <div class="col-md-8">
        <div class="arbif-card h-100">
            <div class="arbif-card-header fw-bold text-navy">
                <i class="fas fa-hand-holding-heart me-2"></i> Schedule Overview
            </div>
            <div class="arbif-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Schedule Ref Number</small>
                        <span class="arbif-badge arbif-badge-navy fs-6">{{ $schedule->ScheduleRefNo ?? '—' }}</span>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">Contribution Amount</small>
                        <h4 class="text-navy fw-bold mb-0">{{ number_format($schedule->FeeAmount, 2) }}</h4>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">Effective From</small>
                        <strong class="text-dark">
                            {{ $schedule->EffectiveFrom ? $schedule->EffectiveFrom->format('d M Y') : '—' }}
                        </strong>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block">Effective To</small>
                        <strong class="text-dark">
                            {{ $schedule->EffectiveTo ? $schedule->EffectiveTo->format('d M Y') : 'Ongoing' }}
                        </strong>
                    </div>

                    <div class="col-md-12">
                        <small class="text-muted d-block">Description / Notes</small>
                        <p class="text-dark mb-0 bg-light p-2 rounded border">
                            {{ $schedule->Description ?? 'No description provided.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scope & System Metadata -->
    <div class="col-md-4">
        <div class="arbif-card h-100">
            <div class="arbif-card-header fw-bold text-navy">
                <i class="fas fa-building me-2"></i> Scope & System Context
            </div>
            <div class="arbif-card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">
                        <small class="text-muted d-block">Company</small>
                        <strong class="text-dark">{{ $schedule->company->company_name ?? '—' }}</strong>
                    </li>
                    <li class="list-group-item px-0">
                        <small class="text-muted d-block">Branch</small>
                        <strong class="text-dark">{{ $schedule->branch->branch_name ?? '—' }}</strong>
                    </li>
                    <li class="list-group-item px-0">
                        <small class="text-muted d-block">Created By</small>
                        <span class="text-dark fw-bold">{{ $schedule->user->name ?? 'System' }}</span>
                    </li>
                    <li class="list-group-item px-0">
                        <small class="text-muted d-block">Record Status</small>
                        <span class="arbif-badge {{ $schedule->Status === 'Active' ? 'bg-success' : 'bg-danger' }} text-white">
                            {{ $schedule->Status ?? 'Active' }}
                        </span>
                    </li>
                    <li class="list-group-item px-0 border-0">
                        <small class="text-muted d-block">Created Date</small>
                        <span class="text-dark">{{ $schedule->created_at ? $schedule->created_at->format('d M Y H:i A') : '—' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Associated Contributions Table -->
<div class="arbif-card">
    <div class="arbif-card-header fw-bold text-navy">
        <i class="fas fa-list-alt me-2"></i> Associated Contributions
    </div>
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Member</th>
                        <th>Amount Paid</th>
                        <th>Payment Date</th>
                        <th>Payment Method</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedule->contributions as $index => $contribution)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong class="text-dark">{{ $contribution->member->name ?? '—' }}</strong>
                            <small class="d-block text-muted">{{ $contribution->member->member_no ? 'No: '.$contribution->member->member_no : '' }}</small>
                        </td>
                        <td><strong class="text-navy">{{ number_format($contribution->AmountPaid ?? 0, 2) }}</strong></td>
                        <td>{{ $contribution->PaymentDate ? \Carbon\Carbon::parse($contribution->PaymentDate)->format('d M Y') : '—' }}</td>
                        <td><span class="badge bg-secondary text-white">{{ $contribution->PaymentMethod ?? '—' }}</span></td>
                        <td><span class="font-monospace text-dark">{{ $contribution->PaymentReference ?? '—' }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No contributions recorded against this schedule yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection