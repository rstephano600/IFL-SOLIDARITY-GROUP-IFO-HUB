@extends('layouts.workingside')

@section('title', 'Expense Ledger Management')

@section('page-title', 'Expense Ledger Management')

@section('content')

<div class="arbif-page-header d-flex justify-content-between align-items-center mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-wallet"></i>
        </div>
        Expense Management Ledger
    </h3>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#registerExpenseModal">
            <i class="fas fa-plus-circle me-1"></i> File New Expense
        </button>
    </div>
</div>

{{-- MASTER LEDGER TABLE GRID --}}
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="expensesMasterTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Voucher Code</th>
                        <th class="sortable">Expense Title</th>
                        <th class="sortable">Category</th>
                        <th class="sortable">Execution Date</th>
                        <th class="sortable">Total Allocation</th>
                        <th class="sortable">Approval</th>
                        <th class="sortable">Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong class="text-primary">{{ $item->expense_number ?? 'N/A' }}</strong>
                        </td>
                        <td>
                            <div><strong>{{ $item->expense_title }}</strong></div>
                            @if($item->items->count() > 0)
                                <small class="text-muted"><i class="fas fa-boxes me-1"></i> Contains {{ $item->items->count() }} item breakdown lines</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ optional($item->category)->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td>
                            {{ $item->expense_date ? \Carbon\Carbon::parse($item->expense_date)->format('d M Y') : 'N/A' }}
                        </td>
                        <td>
                            <strong class="text-dark">{{ $item->currency }} {{ number_format($item->total_amount, 2) }}</strong>
                        </td>
                        <td>
                            @if(($item->ApprovalStatus ?? $item->AppStatus) == 'Pending')
                                <span class="arbif-badge arbif-badge-warning">Pending</span>
                            @elseif(($item->ApprovalStatus ?? $item->AppStatus) == 'Approved')
                                <span class="arbif-badge arbif-badge-success">Approved</span>
                            @else
                                <span class="arbif-badge arbif-badge-danger">{{ $item->ApprovalStatus ?? $item->AppStatus }}</span>
                            @endif
                        </td>
                        <td>
                            @if($item->PaymentStatus == 'Pending')
                                <span class="badge bg-secondary text-white p-1 px-2">Unpaid</span>
                            @elseif($item->PaymentStatus == 'Paid')
                                <span class="badge bg-success text-white p-1 px-2">Settled</span>
                            @else
                                <span class="badge bg-dark text-white p-1 px-2">{{ $item->PaymentStatus }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewexpense', encrypt($item->id)) }}" class="arbif-btn-view py-1 px-2" style="font-size: 0.8rem;" title="View Voucher Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(($item->ApprovalStatus ?? $item->AppStatus) == 'Pending')
                                    <a href="{{ route('deleteexpense', encrypt($item->id)) }}" class="arbif-btn-delete py-1 px-2" style="font-size: 0.8rem;" title="Drop Entry" onclick="return confirm('Permanently remove this financial record transaction?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            No recorded expense vouchers located within the database ledger scope.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MASTER MULTI-ITEM EXPENSE REGISTRATION MODAL --}}
<div class="modal fade" id="registerExpenseModal" tabindex="-1" aria-labelledby="registerExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form action="{{ route('registerexpense') }}" method="POST" id="expenseSubmissionForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold" id="registerExpenseModalLabel">
                        <i class="fas fa-file-invoice-dollar text-primary me-2"></i>File New Corporate Expense Voucher
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    
                    {{-- HEADER METADATA CARDS --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Voucher Title / Purpose <span class="text-danger">*</span></label>
                            <input type="text" name="expense_title" class="form-control" placeholder="e.g. Q2 Office Contingency Restock" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label font-weight-bold">Ledger Classification Category <span class="text-danger">*</span></label>
                            <select name="expense_category_id" class="form-select" required>
                                <option value="" disabled selected>-- Select Classification --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label font-weight-bold">Transaction Date <span class="text-danger">*</span></label>
                            <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label font-weight-bold">Base Currency <span class="text-danger">*</span></label>
                            <select name="currency" class="form-select" required>
                                <option value="TZS" selected>TZS</option>
                                <option value="USD">USD</option>
                                <option value="KES">KES</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label font-weight-bold">Operational Context Notes / Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Provide background summary details for audit context..."></textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- INTERACTIVE ITEM LINE BREAKDOWN --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-secondary font-weight-bold"><i class="fas fa-list me-1"></i> Itemized Line Breakdowns</h5>
                        <button type="button" class="btn btn-outline-success btn-sm" id="addItemRowButton">
                            <i class="fas fa-plus me-1"></i> Add Item Line
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle" id="itemsDynamicTable">
                            <thead class="table-dark">
                                <tr>
                                    <th width="25%">Item Name / Description <span class="text-danger">*</span></th>
                                    <th width="20%">Line Context / Specs</th>
                                    <th width="10%">Qty <span class="text-danger">*</span></th>
                                    <th width="10%">Unit Name <span class="text-danger">*</span></th>
                                    <th width="15%">Unit Cost <span class="text-danger">*</span></th>
                                    <th width="15%">Gross Line Cost</th>
                                    <th width="5%" class="text-center"><i class="fas fa-trash-alt"></i></th>
                                </tr>
                            </thead>
                            <tbody id="dynamicItemRowContainer">
                                {{-- Default Row 1 --}}
                                <tr>
                                    <td>
                                        <input type="text" name="item_name[]" class="form-control form-control-sm" placeholder="Item Name" required>
                                    </td>
                                    <td>
                                        <input type="text" name="description_item[]" class="form-control form-control-sm" placeholder="Details/Supplier">
                                    </td>
                                    <td>
                                        <input type="number" name="quantity[]" class="form-control form-control-sm qty-calc" min="1" value="1" required>
                                    </td>
                                    <td>
                                        <input type="text" name="unit[]" class="form-control form-control-sm" placeholder="e.g. Pcs, Box" value="Pcs" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="unit_cost[]" class="form-control form-control-sm cost-calc" min="0" placeholder="0.00" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="total_cost[]" class="form-control form-control-sm total-line-cost bg-light" readonly value="0.00">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm border-0 remove-row-btn" disabled><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- MASTER ACCOUNTING FOOTER BALANCE TRACKER --}}
                    <div class="row justify-content-end mt-3">
                        <div class="col-md-4">
                            <div class="card bg-light border p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold fs-6 text-uppercase">Aggregated Total Amount:</span>
                                    <div class="text-end">
                                        {{-- Hidden input matching transaction column specification --}}
                                        <input type="hidden" name="total_amount" id="masterGrandTotalInput" value="0.00">
                                        <h4 class="mb-0 font-weight-bold text-primary" id="masterGrandTotalLabel">0.00</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> Authorize and Store Voucher</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- DYNAMIC LEDGER CALCULATION ENGINE SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rowContainer = document.getElementById('dynamicItemRowContainer');
    const addRowBtn = document.getElementById('addItemRowButton');
    const grandTotalLabel = document.getElementById('masterGrandTotalLabel');
    const grandTotalInput = document.getElementById('masterGrandTotalInput');

    function calculateFinancialBalances() {
        let masterAggregateSum = 0;
        const rows = rowContainer.querySelectorAll('tr');

        rows.forEach(row => {
            const qtyInput = row.querySelector('.qty-calc');
            const costInput = row.querySelector('.cost-calc');
            const rowTotalInput = row.querySelector('.total-line-cost');

            const qty = parseFloat(qtyInput.value) || 0;
            const cost = parseFloat(costInput.value) || 0;
            const lineGross = qty * cost;

            rowTotalInput.value = lineGross.toFixed(2);
            masterAggregateSum += lineGross;
        });

        grandTotalLabel.textContent = masterAggregateSum.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        grandTotalInput.value = masterAggregateSum.toFixed(2);
    }

    addRowBtn.addEventListener('click', function () {
        const structuralTemplateRow = `
            <tr>
                <td>
                    <input type="text" name="item_name[]" class="form-control form-control-sm" placeholder="Item Name" required>
                </td>
                <td>
                    <input type="text" name="description_item[]" class="form-control form-control-sm" placeholder="Details/Supplier">
                </td>
                <td>
                    <input type="number" name="quantity[]" class="form-control form-control-sm qty-calc" min="1" value="1" required>
                </td>
                <td>
                    <input type="text" name="unit[]" class="form-control form-control-sm" placeholder="e.g. Pcs, Box" value="Pcs" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="unit_cost[]" class="form-control form-control-sm cost-calc" min="0" placeholder="0.00" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="total_cost[]" class="form-control form-control-sm total-line-cost bg-light" readonly value="0.00">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm border-0 remove-row-btn"><i class="fas fa-times"></i></button>
                </td>
            </tr>`;
        
        rowContainer.insertAdjacentHTML('beforeend', structuralTemplateRow);
        calculateFinancialBalances();
    });

    rowContainer.addEventListener('click', function (e) {
        if (e.target.closest('.remove-row-btn')) {
            const targetRow = e.target.closest('tr');
            targetRow.remove();
            calculateFinancialBalances();
        }
    });

    rowContainer.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty-calc') || e.target.classList.contains('cost-calc')) {
            calculateFinancialBalances();
        }
    });
});
</script>

@endsection