@extends('layouts.workingside')
@section('title', 'Edit Membership Fee Payment')
@section('page-title', 'Edit Membership Fee Payment')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-edit"></i></div>
        Edit Payment: {{ $feePayment->PaymentRefNo ?? '' }}
    </h3>
    <a href="{{ route('membershipfeepayments') }}" class="arbif-btn-cancel">
        <i class="fas fa-arrow-left me-1"></i> Back to Index
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" id="dataFormFill" action="{{ route('updatemembershipfeepayments', [encrypt($feePayment->id)]) }}" enctype="multipart/form-data">
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
                <div class="col-md-3">
                    <label class="form-label" for="PaymentRefNo">Payment Ref No</label>
                    <input type="text" id="PaymentRefNo" class="form-control bg-light" value="{{ $feePayment->PaymentRefNo }}" disabled readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Member <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="member_id" data-searchable data-placeholder="Select Member..." required>
                        <option></option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id', $feePayment->member_id) == $member->id ? 'selected' : '' }}>
                                {{ $member->member_code ? '['.$member->member_code.'] ' : '' }}{{ $member->member_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label">Fee Schedule <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="fee_schedule_id" data-searchable data-placeholder="Select Fee Schedule..." required>
                        <option></option>
                        @foreach($feeSchedules as $schedule)
                            <option value="{{ $schedule->id }}" {{ old('fee_schedule_id', $feePayment->fee_schedule_id) == $schedule->id ? 'selected' : '' }}>
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
                    <input type="number" step="0.01" id="AmountPaid" name="AmountPaid" class="form-control" placeholder="0.00" value="{{ old('AmountPaid', $feePayment->AmountPaid) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="PaymentDate">Payment Date <span class="text-danger">*</span></label>
                    <input type="date" id="PaymentDate" name="PaymentDate" class="form-control" value="{{ old('PaymentDate', optional($feePayment->PaymentDate)->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="PaymentMethod">Payment Method <span class="text-danger">*</span></label>
                    <select id="PaymentMethod" name="PaymentMethod" class="form-select" required>
                        @php $currentMethod = old('PaymentMethod', $feePayment->PaymentMethod); @endphp
                        <option value="Bank Transfer" {{ $currentMethod == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Cash" {{ $currentMethod == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Mobile Money" {{ $currentMethod == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="Cheque" {{ $currentMethod == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="Direct Deposit" {{ $currentMethod == 'Direct Deposit' ? 'selected' : '' }}>Direct Deposit</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="PaymentReference">Payment Reference / Transaction ID</label>
                    <input type="text" id="PaymentReference" name="PaymentReference" class="form-control" placeholder="e.g. TXN12345678 or Cheque No." value="{{ old('PaymentReference', $feePayment->PaymentReference) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="Narration">Narration / Notes</label>
                    <input type="text" id="Narration" name="Narration" class="form-control" placeholder="Brief payment notes..." value="{{ old('Narration', $feePayment->Narration) }}">
                </div>
            </div>

            <!-- SECTION 3: ORGANIZATIONAL SCOPE & STATUS -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                <i class="fas fa-building me-2"></i> 3. Scope & Status
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Company Entity <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Company..." required>
                        <option></option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ old('company_id', $feePayment->company_id) == $comp->id ? 'selected' : '' }}>
                                {{ $comp->company_code ?? '' }} - {{ $comp->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Operational Branch <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Branch..." required>
                        <option></option>
                        @foreach($branches as $br)
                            <option value="{{ $br->id }}" {{ old('branch_id', $feePayment->branch_id) == $br->id ? 'selected' : '' }}>
                                {{ $br->branch_code ?? '' }} - {{ $br->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="{{ route('membershipfeepayments') }}" class="arbif-btn-cancel">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> <span id="submitBtnText">Update Payment</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection