@extends('layouts.workingside')
@section('title', 'Membership Fee Payments')
@section('page-title', 'Membership Fee Payments Management')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-receipt"></i></div>Membership Fee Payments</h3>
    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addFeePaymentModal">
        <i class="fas fa-plus-circle me-1"></i> Record Payment
    </button>
</div>

<!-- Main Datatable Index Panel -->
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="membershipFeePaymentsTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Ref No</th>
                        <th class="sortable">Member</th>
                        <th class="sortable">Schedule / Fee</th>
                        <th class="sortable">Amount Paid</th>
                        <th class="sortable">Payment Date</th>
                        <th class="sortable">Method & Ref</th>
                        <th class="sortable">Company & Branch</th>
                        <th class="sortable">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feePayments as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->PaymentRefNo ?? '—' }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->member->member_name ?? '—' }}</div>
                            <small class="text-muted">{{ $item->member->member_code ?? '' }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->feeSchedule->ScheduleRefNo ?? '—' }}</div>
                            <small class="text-muted">
                                Schedule Fee: {{ isset($item->feeSchedule->FeeAmount) ? number_format($item->feeSchedule->FeeAmount, 2) : '—' }}
                            </small>
                        </td>
                        <td><strong class="text-navy fs-6">{{ number_format($item->AmountPaid, 2) }}</strong></td>
                        <td>
                            <span class="text-dark fw-bold">
                                {{ $item->PaymentDate ? $item->PaymentDate->format('d M Y') : '—' }}
                            </span>
                        </td>
                        <td>
                            <div class="badge bg-secondary text-white">{{ $item->PaymentMethod ?? '—' }}</div>
                            <small class="d-block text-muted">{{ $item->PaymentReference ?? '—' }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->company->company_name ?? '—' }}</div>
                            <small class="text-muted">{{ $item->branch->branch_name ?? '—' }}</small>
                        </td>
                        <td>
                            <span class="arbif-badge {{ $item->Status === 'Active' ? 'bg-success' : 'bg-danger' }} text-white">
                                {{ $item->Status ?? 'Active' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('viewmembershipfeepayments', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('editmembershipfeepayments', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> Edit
                            </a>
                            <a onclick="confirmDelete()" href="{{ route('destroymembershipfeepayments', [encrypt($item->id)]) }}" class="arbif-btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No membership fee payments recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="membershipFeePaymentsTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="membershipFeePaymentsTable"></div>
        </div>
    </div>
</div>

<!-- Record Payment Modal -->
<div class="modal fade arbif-modal" id="addFeePaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-receipt"></i></div>
                <h5 class="modal-title">Record Membership Fee Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storemembershipfeepayments') }}" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- SECTION 1: MEMBER & SCHEDULE SELECTION -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-user-tag me-2"></i> 1. Member & Fee Schedule
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Member <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="member_id" data-searchable data-placeholder="Select Member..." required>
                                <option></option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->member_code ? '['.$member->member_code.'] ' : '' }}{{ $member->member_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Fee Schedule <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="fee_schedule_id" data-searchable data-placeholder="Select Fee Schedule..." required>
                                <option></option>
                                @foreach($feeSchedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('fee_schedule_id') == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->ScheduleRefNo ?? 'Ref#'.$schedule->id }} - Amount: {{ number_format($schedule->FeeAmount, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- SECTION 2: PAYMENT DETAILS -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-credit-card me-2"></i> 2. Payment Details
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label" for="AmountPaid">Amount Paid <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="AmountPaid" name="AmountPaid" class="form-control" placeholder="0.00" value="{{ old('AmountPaid') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="PaymentDate">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" id="PaymentDate" name="PaymentDate" class="form-control" value="{{ old('PaymentDate', date('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="PaymentMethod">Payment Method <span class="text-danger">*</span></label>
                            <select id="PaymentMethod" name="PaymentMethod" class="form-select" required>
                                <option value="" disabled {{ old('PaymentMethod') ? '' : 'selected' }}>Select Method...</option>
                                <option value="Bank Transfer" {{ old('PaymentMethod') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="Cash" {{ old('PaymentMethod') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Mobile Money" {{ old('PaymentMethod') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                                <option value="Cheque" {{ old('PaymentMethod') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="Direct Deposit" {{ old('PaymentMethod') == 'Direct Deposit' ? 'selected' : '' }}>Direct Deposit</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="PaymentReference">Payment Reference / Transaction ID</label>
                            <input type="text" id="PaymentReference" name="PaymentReference" class="form-control" placeholder="e.g. TXN12345678 or Cheque No." value="{{ old('PaymentReference') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="Narration">Narration / Notes</label>
                            <input type="text" id="Narration" name="Narration" class="form-control" placeholder="Brief payment notes..." value="{{ old('Narration') }}">
                        </div>
                    </div>

                    <!-- SECTION 3: ORGANIZATIONAL SCOPE -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-building me-2"></i> 3. Organizational Scope
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Company Entity <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Company..." required>
                                <option></option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ old('company_id') == $comp->id ? 'selected' : '' }}>
                                        {{ $comp->company_code ?? '' }} - {{ $comp->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Operational Branch <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Branch..." required>
                                <option></option>
                                @foreach($branches as $br)
                                    <option value="{{ $br->id }}" {{ old('branch_id') == $br->id ? 'selected' : '' }}>
                                        {{ $br->branch_code ?? '' }} - {{ $br->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer shadow-sm bg-light rounded-bottom p-3" style="margin-top: 30px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Commit Payment</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection