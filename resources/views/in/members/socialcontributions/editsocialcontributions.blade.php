@extends('layouts.workingside')
@section('title', 'Edit Social Contribution')
@section('page-title', 'Edit Contribution')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-edit"></i></div>
        Edit Contribution: <span class="text-primary">{{ $contribution->ContributionRefNo }}</span>
    </h3>
    <a href="{{ route('socialcontributions') }}" class="arbif-btn-cancel">
        <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" id="dataFormFill" action="{{ route('updatesocialcontributions', [encrypt($contribution->id)]) }}">
            @csrf

            <!-- SECTION 1: MEMBER & SCHEDULE SELECTION -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                <i class="fas fa-user-tag me-2"></i> 1. Assignment Details
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label" for="member_id">Member <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="member_id" id="member_id" data-searchable data-placeholder="Select Member..." required>
                        <option></option>
                        @foreach($members as $m)
                            <option value="{{ $m->id }}" {{ $contribution->member_id == $m->id ? 'selected' : '' }}>
                                {{ $m->member_code ? '['.$m->member_code.'] ' : '' }}{{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="social_contribution_schedule_id">Contribution Schedule <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="social_contribution_schedule_id" id="social_contribution_schedule_id" data-searchable data-placeholder="Select Schedule..." required>
                        <option></option>
                        @foreach($schedules as $s)
                            <option value="{{ $s->id }}" {{ $contribution->social_contribution_schedule_id == $s->id ? 'selected' : '' }}>
                                {{ $s->ScheduleRefNo }} (Amt: {{ number_format($s->FeeAmount, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- SECTION 2: PAYMENT & FINANCIAL DATA -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                <i class="fas fa-credit-card me-2"></i> 2. Payment & Financial Data
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label" for="ContributionMonth">Contribution Month <span class="text-danger">*</span></label>
                    <input type="month" id="ContributionMonth" name="ContributionMonth" class="form-control" 
                           value="{{ $contribution->ContributionMonth ? \Carbon\Carbon::parse($contribution->ContributionMonth)->format('Y-m') : date('Y-m') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="ExpectedAmount">Expected Amount</label>
                    <input type="number" step="0.01" id="ExpectedAmount" name="ExpectedAmount" class="form-control" 
                           value="{{ old('ExpectedAmount', $contribution->ExpectedAmount) }}" placeholder="0.00">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="AmountPaid">Amount Paid <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="AmountPaid" name="AmountPaid" class="form-control" 
                           value="{{ old('AmountPaid', $contribution->AmountPaid) }}" placeholder="0.00" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="PaymentDate">Payment Date <span class="text-danger">*</span></label>
                    <input type="date" id="PaymentDate" name="PaymentDate" class="form-control" 
                           value="{{ $contribution->PaymentDate ? \Carbon\Carbon::parse($contribution->PaymentDate)->format('Y-m-d') : date('Y-m-d') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="PaymentMethod">Payment Method <span class="text-danger">*</span></label>
                    <select id="PaymentMethod" name="PaymentMethod" class="form-select" required>
                        @foreach(['Bank Transfer', 'Cash', 'Mobile Money', 'Cheque'] as $method)
                            <option value="{{ $method }}" {{ $contribution->PaymentMethod == $method ? 'selected' : '' }}>
                                {{ $method }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="PaymentStatus">Payment Status <span class="text-danger">*</span></label>
                    <select id="PaymentStatus" name="PaymentStatus" class="form-select" required>
                        @foreach(['Paid', 'Partial', 'Pending'] as $status)
                            <option value="{{ $status }}" {{ $contribution->PaymentStatus == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="PaymentReference">Payment Reference / Transaction ID</label>
                    <input type="text" id="PaymentReference" name="PaymentReference" class="form-control" 
                           value="{{ old('PaymentReference', $contribution->PaymentReference) }}" placeholder="e.g. TXN98765432">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="Narration">Narration / Notes</label>
                    <input type="text" id="Narration" name="Narration" class="form-control" 
                           value="{{ old('Narration', $contribution->Narration) }}" placeholder="Short description...">
                </div>
            </div>

            <!-- SECTION 3: ORGANIZATIONAL SCOPE -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                <i class="fas fa-sitemap me-2"></i> 3. Entity Scope
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label" for="company_id">Company Entity</label>
                    <select style="width: 100%" name="company_id" id="company_id" data-searchable data-placeholder="Select Company...">
                        <option></option>
                        @foreach($companies as $c)
                            <option value="{{ $c->id }}" {{ $contribution->company_id == $c->id ? 'selected' : '' }}>
                                {{ $c->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="branch_id">Operational Branch</label>
                    <select style="width: 100%" name="branch_id" id="branch_id" data-searchable data-placeholder="Select Branch...">
                        <option></option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ $contribution->branch_id == $b->id ? 'selected' : '' }}>
                                {{ $b->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="{{ route('socialcontributions') }}" class="arbif-btn-cancel">Cancel</a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="fas fa-save me-1"></i> <span id="submitBtnText">Update Contribution</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection