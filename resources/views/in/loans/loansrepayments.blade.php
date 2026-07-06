@extends('layouts.workingside')

@section('title', 'Loan Repayments')
@section('page-title', 'Loan Repayments')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        Loan Repayments
    </h3>
</div>

<div class="d-flex gap-2">

    <a href="{{ route('downloadloanrepaymenttemplate') }}"
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
        Register Repayment
    </button>

</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">

            <table class="arbif-table" id="repaymentTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Loan Number</th>
                        <th class="sortable">Client</th>
                        <th class="sortable">Payment Date</th>
                        <th class="sortable">Amount Paid</th>
                        <th class="sortable">Method</th>
                        <th class="sortable">Reference</th>
                        <th class="sortable">Received By</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>{{ optional($item->loan)->loan_number }}</td>

                        <td>{{ optional($item->client)->client->name ?? 'N/A' }}</td>

                        <td>{{ \Carbon\Carbon::parse($item->payment_date)->format('d M Y') }}</td>

                        <td>{{ number_format($item->amount_paid, 2) }}</td>

                        <td>{{ $item->payment_method }}</td>

                        <td>{{ $item->reference_number }}</td>

                        <td>{{ optional($item->receiver)->name }}</td>

                        <td>
                            <a href="{{ route('viewloanrepayment', encrypt($item->id)) }}"
                               class="arbif-btn-view">View</a>

                            <a href="{{ route('editloanrepayment', encrypt($item->id)) }}"
                               class="arbif-btn-edit">Edit</a>

                            {{-- ✅ DELETE: POST form instead of bare GET anchor --}}
                            <form method="POST"
                                  action="{{ route('destroyloanrepayment', encrypt($item->id)) }}"
                                  style="display:inline"
                                  onsubmit="return confirm('Are you sure you want to delete this repayment?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="arbif-btn-delete">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Repayments Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════
     ADD REPAYMENT MODAL
══════════════════════════════════════════════ --}}
<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <div class="modal-icon">
                    <i class="bi bi-cash"></i>
                </div>
                <h5 class="modal-title">Register Loan Repayment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('storeloanrepayment') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label">Loan</label>

                            {{-- ✅ Added class="form-control" + id for Select2 targeting --}}
                            <select name="loan_id"
                                    id="loanSelect"
                                    class="form-control"
                                    required>

                                <option value="">Select Loan</option>

                                @foreach($loans as $item)
                                    @php
                                        $totalRepayable    = $item->total_repayable;
                                        $amountPaid        = $item->total_amount_paid;
                                        $remainingBalance  = max(0, $totalRepayable - $amountPaid);
                                    @endphp

                                    {{-- ✅ optional() guard on client chain --}}
                                    <option value="{{ $item->id }}"
                                            data-repayable="{{ number_format($totalRepayable, 2, '.', '') }}"
                                            data-paid="{{ number_format($amountPaid, 2, '.', '') }}"
                                            data-outstanding="{{ number_format($remainingBalance, 2, '.', '') }}"
                                            data-client="{{ $item->client_id }}"
                                            data-group="{{ $item->group_id }}"
                                            data-center="{{ $item->group_center_id }}">
                                        {{ optional($item->client)->client->name ?? 'Unknown Client' }}
                                        ({{ $item->loan_number }}) —
                                        TRepay: {{ number_format($totalRepayable, 2) }} -
                                        TPayed: {{ number_format($amountPaid, 2) }} -
                                        Owed: {{ number_format($remainingBalance, 2) }}
                                        ({{ optional($item->loanCategory)->name }})
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Payment Date</label>
                            <input type="date"
                                   name="payment_date"
                                   value="{{ date('Y-m-d') }}"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Amount Paid</label>
                            <input type="number"
                                   step="0.01"
                                   name="amount_paid"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-control">
                                <option value="Cash">Cash</option>
                                <option value="Bank">Bank</option>
                                <option value="Mobile Money">Mobile Money</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Reference Number</label>
                            <input type="text"
                                   name="reference_number"
                                   class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks"
                                      rows="4"
                                      class="form-control"></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="arbif-btn-submit">
                            Save Repayment
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════
     IMPORT MODAL
══════════════════════════════════════════════ --}}
<div class="modal fade arbif-modal" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-file-excel"></i>
                </div>
                <h5 class="modal-title">Import Loan Repayments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="POST"
                      action="{{ route('importloanrepayments') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Excel File</label>
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
                        <button type="submit" class="arbif-btn-submit">
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
$(document).ready(function () {

    new DataTable('#repaymentTable');

    // ✅ Target by correct ID, not the non-existent .select2_demo_3 class
    $('#loanSelect').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Search ...',
        allowClear: true
    });

});
</script>
@endpush