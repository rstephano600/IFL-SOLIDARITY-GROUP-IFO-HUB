@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1">Loan Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">Loans</a></li>
                    <li class="breadcrumb-item active">{{ $loan->loan_number }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <!-- <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit Loan
            </a> -->
            <a href="{{ route('client-loan-photos.create', ['loan_id' => $loan->id]) }}" class="btn btn-primary">
            + Add Photo
            </a>
            <a href="{{ route('loans.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
    

@php
    switch ($loan->status) {
        case 'active':
            $alertClass = 'success';
            $statusLabel = 'Active';
            break;
        case 'pending':
            $alertClass = 'warning';
            $statusLabel = 'Pending Approval';
            break;
        case 'approved':
            $alertClass = 'info';
            $statusLabel = 'Approved';
            break;
        case 'closed':
            $alertClass = 'secondary';
            $statusLabel = 'Closed';
            break;
        case 'refunded':
            $alertClass = 'primary';
            $statusLabel = 'Refunded';
            break;
        case 'rejected':
            $alertClass = 'danger';
            $statusLabel = 'Rejected';
            break;
        default:
            $alertClass = 'light';
            $statusLabel = ucfirst($loan->status);
            break;
    }
@endphp

    <div class="alert alert-{{ $alertClass }} d-flex justify-content-between align-items-center">
    <div>
        <strong>Loan Status:</strong>
        <span class="text-uppercase fw-bold">{{ $statusLabel }}</span>
        @if($loan->closed_at)
            <span class="ms-2 text-muted small">• Closed on {{ $loan->closed_at->format('M d, Y') }}</span>
        @endif
    </div>

    <span class="badge rounded-pill bg-{{ $loan->is_active ? 'success' : 'danger' }}">
        {{ $loan->is_active ? 'ACTIVE' : 'INACTIVE' }}
    </span>
</div>

    <div class="row">
        <!-- Left Column: Basic Information -->
        <div class="col-md-6">
            <!-- Loan Basic Information Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle"></i> Basic Information
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Loan Number</dt>
                        <dd class="col-sm-7"><strong>{{ $loan->loan_number }}</strong></dd>

                        <dt class="col-sm-5">Loan Category</dt>
                        <dd class="col-sm-7">{{ $loan->loanCategory?->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-5">Currency</dt>
                        <dd class="col-sm-7">{{ $loan->currency ?? 'N/A' }}</dd>

                        <dt class="col-sm-5">Is New Client</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-{{ $loan->is_new_client ? 'info' : 'secondary' }}">
                                {{ $loan->is_new_client ? 'Yes' : 'No' }}
                            </span>
                        </dd>

                        <dt class="col-sm-5">Created Date</dt>
                        <dd class="col-sm-7">{{ $loan->created_at->format('M d, Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Client & Group Information Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people"></i> Client & Group Information
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Client Name</dt>
                        <dd class="col-sm-7">{{ $loan->client?->first_name }} {{ $loan->client?->last_name }}</dd>

                        <dt class="col-sm-5">Group</dt>
                        <dd class="col-sm-7">{{ $loan->group?->group_name ?? 'N/A' }}</dd>

                        <dt class="col-sm-5">Group Center</dt>
                        <dd class="col-sm-7">{{ $loan->groupCenter?->center_name ?? 'N/A' }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Fees Breakdown Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-receipt"></i> Fees Breakdown
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-6">Membership Fee</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->membership_fee ?? 0, 2) }}</dd>

                        <dt class="col-sm-6">Insurance Fee</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->insurance_fee ?? 0, 2) }}</dd>

                        <dt class="col-sm-6">Officer Visit Fee</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->officer_visit_fee ?? 0, 2) }}</dd>

                        <dt class="col-sm-6">Other Fee</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->other_fee ?? 0, 2) }}</dd>

                        <dt class="col-sm-6">Penalty Fee</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->penalty_fee ?? 0, 2) }}</dd>

                        <dt class="col-sm-6">Preclosure Fee</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->preclosure_fee ?? 0, 2) }}</dd>

                        <hr class="my-2">
                        <dt class="col-sm-6 fw-bold">Total Fees</dt>
                        <dd class="col-sm-6 text-end fw-bold border-top pt-1">
                            {{ number_format($loan->total_fee, 2) }}
                        </dd>
                    </dl>
                </div>
            </div>

    @php
        // Get the authenticated user
        $user = Auth::user();
        
        // Define simple permission variables using the new methods
        $canManageAll = $user->isAdmin();
        $canManageHisData = $user->isLoanOfficer();
        $canManageLoans = $user->isAdmin() || $user->isManagement();
        $canViewClients = $user->isAdmin() || $user->isManagement() || $user->hasRole('marketing_officer');
        $canManageGroups = $user->isAdmin() || $user->isManagement() || $user->isLoanOfficer() || $user->hasRole('marketing_officer');
        $canManageFinance = $user->isAdmin() || $user->isManagement() || $user->isFinance();
        $canManageHR = $user->isAdmin() || $user->isManagement() || $user->isHR();
        $isClient = $user->isClient();

    @endphp
@if($canManageLoans)
            <!-- Pre closure fee Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-receipt"></i> Pre closure informations
                    </h5>
                </div>
                <div class="card-body">

                {{-- Set Preclosure Fee --}}
                 <form action="{{ route('loans.preclosure.set', $loan->id) }}" method="POST" class="d-inline">
                     @csrf
                     <div class="input-group mb-2" style="max-width: 250px;">
                         <input type="number" name="preclosure_fee" step="0.01" min="0" class="form-control" placeholder="Enter fee amount" required>
                         <button type="submit" class="btn btn-outline-primary btn-sm" onclick="return confirm('Please confirm — do you want to set preclosure Fee for This Loan?')">Set Fee</button>
                     </div>
                 </form>

                 {{-- Mark Preclosure as Paid --}}
                 @if($loan->preclosure_fee > 0 && $loan->preclosure_fee_paid < $loan->preclosure_fee)
                 <form action="{{ route('loans.preclosure.pay', $loan->id) }}" method="POST" class="d-inline">
                     @csrf

                     <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Please confirm — do you want to Mark preclosure as Paid??')">Mark as Pay</button>

                 </form>
                 @endif

                </div>
            </div>

<!-- Refunding Information Card -->
<div class="card shadow border-0 mb-4">
  <div class="card-header bg-warning text-dark d-flex align-items-center">
    <i class="bi bi-receipt me-2"></i>
    <h5 class="card-title mb-0">Refunding Information</h5>
  </div>

  <div class="card-body">
    {{-- Set Preclosure Fee --}}
    <form action="{{ route('loans.refund.set', $loan->id) }}" method="POST" class="mb-3">
      @csrf
      <div class="mb-3">
        <label for="refunding_reason" class="form-label fw-semibold">Reason for Refund</label>
        <textarea 
          id="refunding_reason" 
          name="refunding_reason" 
          class="form-control" 
          rows="3" 
          placeholder="Enter reason for refund..."
          required
        ></textarea>
      </div>
      <button 
        type="submit" 
        class="btn btn-outline-primary btn-sm px-4" 
        onclick="return confirm('Please confirm — do you want to set this loan as refunded?')"
      >
        <i class="bi bi-check-circle"></i> Set Refund
      </button>
    </form>

    <!-- {{-- Mark Preclosure as Paid --}}
    @if($loan->preclosure_fee > 0 && $loan->preclosure_fee_paid < $loan->preclosure_fee)
      <form action="{{ route('loans.preclosure.pay', $loan->id) }}" method="POST" class="d-inline">
        @csrf
        <button 
          type="submit" 
          class="btn btn-sm btn-success px-4" 
          onclick="return confirm('Please confirm — do you want to mark as paid?')"
        >
          <i class="bi bi-cash-stack"></i> Mark as Paid
        </button>
      </form>
    @endif -->
  </div>
</div>
@endif
        </div>

        <!-- Right Column: Financial & Dates -->
        <div class="col-md-6">
            <!-- Financial Information Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-cash-coin"></i> Financial Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center bg-light">
                                <h6 class="text-muted mb-1">Amount Requested</h6>
                                <h4 class="text-primary">{{ number_format($loan->amount_requested ?? 0, 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 text-center bg-light">
                                <h6 class="text-muted mb-1">Amount Disbursed</h6>
                                <h4 class="text-success">{{ number_format($loan->amount_disbursed ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>

                    <dl class="row mb-0">
@if($canManageLoans)
                        <dt class="col-sm-6">Interest Rate</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->interest_rate ?? 0, 2) }}%</dd>

                        <dt class="col-sm-6">Interest Amount</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->interest_amount ?? 0, 2) }}</dd>
@endif
                        <dt class="col-sm-6">Client Payable Frequency</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->client_payable_frequency ?? 0, 2) }}</dd>

                        <dt class="col-sm-6">Repayment Frequency</dt>
                        <dd class="col-sm-6 text-end">{{ $loan->repayment_frequency ?? 'N/A' }}</dd>

                        <dt class="col-sm-6">Repayment Fees</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->total_fee, 2) }}</dd>

                        <dt class="col-sm-6">Total Repayable Amount</dt>
                        <dd class="col-sm-6 text-end fw-bold">
                            {{ number_format($loan->repayable_amount, 2) }}
                        </dd>
                    </dl>
                </div>
            </div>

            <!-- Dates & Terms Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar-event"></i> Dates & Terms
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Disbursement Date</dt>
                        <dd class="col-sm-7">{{ $loan->disbursement_date ? $loan->disbursement_date->format('M d, Y') : 'N/A' }}</dd>

                        <dt class="col-sm-5">Start Date</dt>
                        <dd class="col-sm-7">{{ $loan->start_date ? $loan->start_date->format('M d, Y') : 'N/A' }}</dd>

                        <dt class="col-sm-5">End Date</dt>
                        <dd class="col-sm-7">{{ $loan->end_date ? $loan->end_date->format('M d, Y') : 'N/A' }}</dd>

                        <dt class="col-sm-5">Days Left</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-{{ $loan->days_left > 30 ? 'success' : ($loan->days_left > 0 ? 'warning' : 'danger') }}">
                                {{ $loan->days_left ?? 0 }} days
                            </span>
                        </dd>

                        <dt class="col-sm-5">Max Term (Days)</dt>
                        <dd class="col-sm-7">{{ $loan->max_term_days ?? 'N/A' }}</dd>

                        <dt class="col-sm-5">Max Term (Months)</dt>
                        <dd class="col-sm-7">{{ $loan->max_term_months ?? 'N/A' }}</dd>

                        <dt class="col-sm-5">Total Days Due</dt>
                        <dd class="col-sm-7">{{ $loan->total_days_due ?? 'N/A' }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Repayment & Balance Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up"></i> Repayment & Balance
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-6">Principal Due</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->principal_due ?? 0, 2) }}</dd>
@if($canManageLoans)
                        <dt class="col-sm-6">Interest Due</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->interest_due ?? 0, 2) }}</dd>

                        <dt class="col-sm-6">Total Due</dt>
                        <dd class="col-sm-6 text-end fw-bold">{{ number_format($loan->total_due, 2) }}</dd>
@endif
                        <hr class="my-2">

                        <dt class="col-sm-6">Amount Paid</dt>
                        <dd class="col-sm-6 text-end text-success">{{ number_format($loan->amount_paid ?? 0, 2) }}</dd>

                        <dt class="col-sm-6">Membership Fee Paid</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->membership_fee_paid ?? 0, 2) }}</dd>
                       
                        <dt class="col-sm-6">Loan Officer Fee Paid</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->officer_visit_fee_paid ?? 0, 2) }}</dd>
                        
                        <dt class="col-sm-6">Insurance Fee Paid</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->insurance_fee_paid ?? 0, 2) }}</dd>

                        <dt class="col-sm-6">Preclosure Fee Paid</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->preclosure_fee_paid ?? 0, 2) }}</dd>

                        <dt class="col-sm-6">Amount with Preclosure</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->amount_with_preclosure ?? 0, 2) }}</dd>
                    
                        <dt class="col-sm-6">Amount Refunded</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->amount_with_refund ?? 0, 2) }}</dd>

                        <dt class="col-sm-6">Other Fee Paid</dt>
                        <dd class="col-sm-6 text-end">{{ number_format($loan->other_fee_paid ?? 0, 2) }}</dd>

                        <dt class="col-sm-6">Total Amount Paid</dt>
                        <dd class="col-sm-6 text-end fw-bold text-success border-top pt-1">
                            {{ number_format($loan->total_amount_paid, 2) }}
                        </dd>

                        <hr class="my-2">

                        <dt class="col-sm-6">Outstanding Balance</dt>
                        <dd class="col-sm-6 text-end fw-bold fs-5 text-danger">
                            {{ number_format($loan->outstanding_balance ?? 0, 2) }}
                        </dd>
@if($canManageLoans)
                        <dt class="col-sm-6">Profit/Loss</dt>
                        <dd class="col-sm-6 text-end fw-bold text-{{ $loan->profit_loss_amount >= 0 ? 'success' : 'danger' }}">
                            {{ number_format($loan->profit_loss_amount, 2) }}
                        </dd>
@endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
@if($canManageLoans)
    <!-- System Information & Closure Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-gear"></i> System Information & Closure
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2">User Actions</h6>
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Created By</dt>
                                <dd class="col-sm-8">{{ $loan->creator?->name ?? 'N/A' }}</dd>

                                <dt class="col-sm-4">Approved By</dt>
                                <dd class="col-sm-8">{{ $loan->approver?->name ?? 'N/A' }}</dd>

                                <dt class="col-sm-4">Updated By</dt>
                                <dd class="col-sm-8">{{ $loan->updater?->name ?? 'N/A' }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2">Closure Information</h6>
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Closed At</dt>
                                <dd class="col-sm-8">{{ $loan->closed_at ? $loan->closed_at->format('M d, Y H:i') : 'Not Closed' }}</dd>

                                <dt class="col-sm-4">Closure Reason</dt>
                                <dd class="col-sm-8">{{ $loan->closure_reason ?? 'N/A' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif

    <div class="alert alert-info bg-info text-white mt-3"> <h3>Repayment Schedule for Loan {{ $loan->loan_number }}</h3></div>

    @if ($schedules->isEmpty())
        <div class="alert alert-info mt-3">No pending Pending Schedule.</div>
    @else
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <!-- <th>#</th> -->
                        <th>Due Day</th>
                        <th>Principal Due</th>
                        <th>Penalty Due</th>
                        <th>Toatal Due</th>
                        <th>Status</th>
                        <th>Days Left</th>
                        <th>Mark Paid</th>
                        <!-- <th>Add Penalty</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <!-- <td>{{ $schedule->installment_number }}</td> -->
                            <td>Day {{ $schedule->due_day_number }}</td>
                            <td>{{ number_format($schedule->principal_due, 2) }}</td>
                            <td>{{ number_format($schedule->penalty_due, 2) }}</td>
                            <td>{{ number_format($schedule->total_proncipal_penalty_due, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $schedule->status === 'paid' ? 'success' : ($schedule->status === 'overdue' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                            <td>{{ $schedule->days_left }}</td>
                            <td>
                                @if($schedule->status !== 'paid')
                                    <form action="{{ route('repayments.pay', $schedule->id) }}" method="POST">
                                        @csrf
                            <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Please confirm — do you want to make this payment now?')">Pay</button>

                                </form>
                                    </form>
                                @else
                                    <span class="text-success fw-bold">✔ Paid</span>
                                @endif
                            </td>
                            <!-- <td>
                                @if($schedule->status !== 'paid')
                                <form action="{{ route('schedules.addPenalty', $schedule->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm" t onclick="return confirm('Do you want to apply 2000 penalty fee for this schedule ?')">Apply Penalty</button>
                                </form>
                                @endif
                            </td> -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
     @endif

    @if ($schedulesPaids->isEmpty())
        <div class="alert alert-info mt-3">No  Schedule Paid.</div>
    @else
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>installment Number</th>
                        <!-- <th>Due Day</th> -->
                        <th>Principal Paid</th>
                        <!-- <th>Penalty Paid</th> -->
                        <th>Toatal Paid</th>
                        <th>Status</th>
                        <th>Days Left</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedulesPaids as $schedule)
                        <tr>
                            <td>{{ $schedule->installment_number }}</td>
                            <!-- <td>Day {{ $schedule->due_day_number }}</td> -->
                            <td>{{ number_format($schedule->principal_paid, 2) }}</td>
                            <!-- <td>{{ number_format($schedule->penalty_paid, 2) }}</td> -->
                            <td>{{ number_format($schedule->total_proncipal_penalty_paid, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $schedule->status === 'paid' ? 'success' : ($schedule->status === 'overdue' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                            <td>{{ $schedule->days_left }}</td>
                            <td>
                                @if($schedule->status !== 'paid')
                                    <form action="{{ route('repayments.pay', $schedule->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-primary">Pay</button>
                                    </form>
                                @else
                                    <span class="text-success fw-bold">✔ Paid</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
     @endif
 

</div>

<style>
.card-header h5 {
    font-size: 1rem;
    font-weight: 600;
}
.dl-horizontal dt {
    font-weight: 500;
    color: #6c757d;
}
.border-bottom {
    border-bottom: 1px solid #dee2e6 !important;
}
</style>
@endsection