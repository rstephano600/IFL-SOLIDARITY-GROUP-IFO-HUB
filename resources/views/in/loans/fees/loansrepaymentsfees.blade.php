@extends('layouts.workingside')

@section('title', 'Fees Loan Repayments')
@section('page-title', 'Fees Loan Repayments')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        Fees Loan Repayments
    </h3>


</div>
<div class="d-flex gap-2">

    <a href="{{ route('downloadloanrepaymenttemplatefee') }}"
       class="arbif-btn-edit">

        <i class="fas fa-download"></i>
        Download Template

    </a>

    <button class="arbif-btn-view"
            data-bs-toggle="modal"
            data-bs-target="#importModal">

        <i class="fas fa-file-import"></i>
        Import Excel

    </button>

    <button class="arbif-btn-submit"
            data-bs-toggle="modal"
            data-bs-target="#addFormModal">

        <i class="fas fa-plus"></i>
        Register Fee Payment

    </button>

</div>

<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table" id="repaymentTable">

                <thead>

                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Loan Reference Number</th>
                        <th class="sortable">Client Full Name</th>
                        <th class="sortable">Payment Date</th>
                        <th class="sortable">Member Fee Paid</th>
                        <th class="sortable">Officer Fee Paid</th>
                        <th class="sortable">Insurance Fee Paid</th>
                        <th class="sortable">Preclosure Fee Paid</th>
                        <th class="sortable">Penalty Fee Paid</th>
                        <th class="sortable">Other Fee Paid</th>
                        <th class="sortable">Method</th>
                        <th class="sortable">Reference</th>
                        <th class="sortable">Received By</th>
                        <th>More Actions</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>
                            {{ optional($item->loan)->loan_number }}
                        </td>

                        <td>
                            {{ optional($item->client)->client->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->payment_date)->format('d M Y') }}
                        </td>

                        <td>
                            {{ number_format($item->membership_fee_paid ?? 0, 2) }}
                        </td>
                        <td>
                            {{ number_format($item->insurance_fee_paid ?? 0, 2) }}
                        </td>
                        <td>
                            {{ number_format($item->insurance_fee_paid ?? 0, 2) }}
                        </td>
                        <td>
                            {{ number_format($item->preclosure_fee_paid ?? 0, 2) }}
                        </td>
                        <td>
                            {{ number_format($item->penalty_fee_paid ?? 0, 2) }}
                        </td>
                        <td>
                            {{ number_format($item->other_fee_paid ?? 0, 2) }}
                        </td>

                        <td>
                            {{ $item->payment_method }}
                        </td>

                        <td>
                            {{ $item->reference_number }}
                        </td>

                        <td>
                            {{ optional($item->receiver)->name }}
                        </td>

                        <td>

                            <a href="{{ route('viewloanrepaymentfee', encrypt($item->id)) }}"
                               class="arbif-btn-view">
                                View
                            </a>

                            <a href="{{ route('editloanrepaymentfee', encrypt($item->id)) }}"
                               class="arbif-btn-edit">
                                Edit
                            </a>

                            <a href="{{ route('destroyloanrepaymentfee', encrypt($item->id)) }}"
                               class="arbif-btn-delete"
                               onclick="return confirm('Are you sure?')">
                                Delete
                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="14" class="text-center">
                            No Repayments Found
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>



<div class="modal fade arbif-modal"
     id="addFormModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <div class="modal-icon">
                    <i class="bi bi-cash"></i>
                </div>

                <h5 class="modal-title">
                    Register Loan Fees Repayment
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form method="POST"
                      action="{{ route('storeloanrepaymentfee') }}">

                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Loan
                            </label>

                            <select name="loan_id" data-searchable data-placeholder="Search ..."
                                    required>

                                <option value="">
                                    Select Loan
                                </option>

                                @foreach($loans as $loan)

                                    <option value="{{ $loan->id }}">

                                        {{ $loan->loan_number }}
                                        -
                                        {{ optional($loan->client)->client->name ?? ' ' }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Payment Date
                            </label>
                            <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="form-control" required>

                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Membership Fee</label>
                            <input type="number" step="0.01" name="membership_fee_paid" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Officer Visit Fee</label>
                            <input type="number" step="0.01" name="officer_visit_fee_paid" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Insurance Fee</label>
                            <input type="number" step="0.01" name="insurance_fee_paid" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pre - closure Fee</label>
                            <input type="number" step="0.01" name="preclosure_fee_paid" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">penalty Fee</label>
                            <input type="number" step="0.01" name="penalty_fee_paid" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Other Fee</label>
                            <input type="number" step="0.01" name="other_fee_paid" class="form-control">
                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Payment Method
                            </label>

                            <select name="payment_method" data-searchable data-placeholder="Search ..." required>
                                <option value="">
                                    Select Method
                                </option>
                                <option value="Cash">Cash</option>
                                <option value="Bank">Bank</option>
                                <option value="Mobile Money">Mobile Money</option>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Reference Number
                            </label>

                            <input type="text"
                                   name="reference_number"
                                   class="form-control">

                        </div>

                        <div class="col-md-12">

                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea name="remarks"
                                      rows="4"
                                      class="form-control"></textarea>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="submit"
                                class="arbif-btn-submit">

                            Save Payment

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<div class="modal fade arbif-modal"
     id="importModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <div class="modal-icon">

                    <i class="fas fa-file-excel"></i>

                </div>

                <h5 class="modal-title">
                    Import Loan Repayments
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form method="POST"
                      action="{{ route('importloanrepaymentsfee') }}"
                      enctype="multipart/form-data">

                    @csrf

                    <div class="mb-3">

                        <label class="form-label">

                            Excel File

                        </label>

                        <input type="file"
                               name="file"
                               class="form-control"
                               accept=".xlsx,.xls,.csv"
                               required>

                    </div>

                    <div class="alert alert-info">

                        <strong>Required Columns:</strong>

                        <ul class="mb-0 mt-2">

                            <li>Loan Number</li>
                            <li>Payment Date</li>
                            <li>Amount Paid</li>
                            <li>Payment Method</li>
                            <li>Reference Number</li>
                            <li>Remarks</li>

                        </ul>

                    </div>

                    <div class="modal-footer">

                        <button type="button"
                                class="arbif-btn-cancel"
                                data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button type="submit"
                                class="arbif-btn-submit">

                            <i class="fas fa-upload"></i>

                            Import Repayments

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

$(document).ready(function(){

    new DataTable('#repaymentTable');

    $('.select2_demo_3').select2({
        theme:'bootstrap4',
        width:'100%'
    });

});

</script>

@endpush