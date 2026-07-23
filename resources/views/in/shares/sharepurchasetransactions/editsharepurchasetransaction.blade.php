@extends('layouts.workingside')
@section('title', 'Edit Share Purchase Transaction')
@section('page-title', 'Edit Share Purchase')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-pencil"></i></div>
        Edit Transaction: {{ $transaction->TransactionRefNo ?? '' }}
    </h3>
    <a href="{{ route('sharepurchasetransactions') }}" class="arbif-btn-cancel">
        <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" id="dataFormFill" action="{{ route('updatesharepurchasetransactions', [encrypt($transaction->id)]) }}">
            @csrf

            <!-- SECTION 1: PARTICIPANTS & OFFERING -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                <i class="fas fa-id-card me-2"></i> 1. Member & Share Type Selection
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Member <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="member_id" data-searchable data-placeholder="Select Member..." required>
                        <option></option>
                        @foreach($members as $m)
                            <option value="{{ $m->id }}" {{ $transaction->member_id == $m->id ? 'selected' : '' }}>
                                {{ $m->member_code ? '['.$m->member_code.'] ' : '' }}{{ $m->member_name ?? $m->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Share Offering <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="share_offering_id" data-searchable data-placeholder="Select Offering..." required>
                        <option></option>
                        @foreach($offerings as $off)
                            <option value="{{ $off->id }}" {{ $transaction->share_offering_id == $off->id ? 'selected' : '' }}>
                                {{ $off->OfferingRefNo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Share Type <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="share_type_id" data-searchable data-placeholder="Select Share Type..." required>
                        <option></option>
                        @foreach($shareTypes as $st)
                            <option value="{{ $st->id }}" {{ $transaction->share_type_id == $st->id ? 'selected' : '' }}>
                                {{ $st->TypeName ?? $st->TypeCode }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="TransactionType">Transaction Type <span class="text-danger">*</span></label>
                    <select id="TransactionType" name="TransactionType" class="form-select" required>
                        <option value="Purchase" {{ $transaction->TransactionType == 'Purchase' ? 'selected' : '' }}>Purchase</option>
                        <option value="Transfer" {{ $transaction->TransactionType == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="Bonus" {{ $transaction->TransactionType == 'Bonus' ? 'selected' : '' }}>Bonus</option>
                    </select>
                </div>
            </div>

            <!-- SECTION 2: QUANTITY & FINANCIALS -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                <i class="fas fa-calculator me-2"></i> 2. Financials & Payment
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label" for="SharesQuantity">Shares Quantity <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="SharesQuantity" name="SharesQuantity" class="form-control" value="{{ old('SharesQuantity', $transaction->SharesQuantity) }}" placeholder="0.00" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="PricePerShare">Price Per Share <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="PricePerShare" name="PricePerShare" class="form-control" value="{{ old('PricePerShare', $transaction->PricePerShare) }}" placeholder="0.00" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="TransactionDate">Transaction Date <span class="text-danger">*</span></label>
                    <input type="date" id="TransactionDate" name="TransactionDate" class="form-control" value="{{ old('TransactionDate', $transaction->TransactionDate ? $transaction->TransactionDate->format('Y-m-d') : '') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="PaymentMethod">Payment Method <span class="text-danger">*</span></label>
                    <select id="PaymentMethod" name="PaymentMethod" class="form-select" required>
                        <option value="Bank Transfer" {{ $transaction->PaymentMethod == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Cash" {{ $transaction->PaymentMethod == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Mobile Money" {{ $transaction->PaymentMethod == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="Cheque" {{ $transaction->PaymentMethod == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="PaymentReference">Payment Reference / Transaction ID</label>
                    <input type="text" id="PaymentReference" name="PaymentReference" class="form-control" value="{{ old('PaymentReference', $transaction->PaymentReference) }}" placeholder="e.g. TXN-SHARE-102938">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="Status">Status <span class="text-danger">*</span></label>
                    <select id="Status" name="Status" class="form-select" required>
                        <option value="Active" {{ $transaction->Status == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Pending" {{ $transaction->Status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Cancelled" {{ $transaction->Status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="Narration">Narration / Notes</label>
                    <input type="text" id="Narration" name="Narration" class="form-control" value="{{ old('Narration', $transaction->Narration) }}" placeholder="Add transaction remarks...">
                </div>
            </div>

            <!-- SECTION 3: ENTITY MAPPING -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                <i class="fas fa-building me-2"></i> 3. Entity Assignment
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Company Entity</label>
                    <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Company...">
                        <option></option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ $transaction->company_id == $comp->id ? 'selected' : '' }}>
                                {{ $comp->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Operational Branch</label>
                    <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Branch...">
                        <option></option>
                        @foreach($branches as $br)
                            <option value="{{ $br->id }}" {{ $transaction->branch_id == $br->id ? 'selected' : '' }}>
                                {{ $br->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('sharepurchasetransactions') }}" class="arbif-btn-cancel">Cancel</a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2 me-1"></i> <span id="submitBtnText">Update Transaction</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection