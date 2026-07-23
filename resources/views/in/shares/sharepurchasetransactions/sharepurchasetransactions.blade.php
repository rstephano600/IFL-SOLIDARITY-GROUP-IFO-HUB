@extends('layouts.workingside')
@section('title', 'Share Purchase Transactions')
@section('page-title', 'Share Purchase Transactions')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-chart-line"></i></div>Share Purchase Transactions</h3>
    <div class="d-flex gap-2">
        <button class="arbif-btn-cancel text-navy bg-white border" data-bs-toggle="modal" data-bs-target="#downloadTemplateModal">
            <i class="fas fa-file-excel me-1 text-success"></i> Download Template
        </button>
        <button class="arbif-btn-cancel text-navy bg-white border" data-bs-toggle="modal" data-bs-target="#importExcelModal">
            <i class="fas fa-file-import me-1 text-primary"></i> Import Excel
        </button>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
            <i class="fas fa-plus-circle me-1"></i> New Purchase
        </button>
    </div>
</div>

<!-- Datatable Container -->
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="sharePurchaseTransactionsTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Ref No</th>
                        <th class="sortable">Member Code</th>
                        <th class="sortable">Member Name</th>
                        <th class="sortable">Offering / Type</th>
                        <th class="sortable">Shares</th>
                        <th class="sortable">Unit Price</th>
                        <th class="sortable">Total Value</th>
                        <th class="sortable">Purchase Mode</th>
                        <th class="sortable">Transaction Date</th>
                        <th class="sortable">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->TransactionRefNo ?? '—' }}</span></td>
                        <td>
                            <small class="text-muted">{{ $item->member->member_code ?? '—' }}</small>
                        </td>
                        <td>
                            <!-- <div class="fw-bold text-dark">{{ $item->member->member_name ?? '—' }}</div> -->
                            <small class="text-muted">{{ $item->member->member_name ?? '—' }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->shareOffering->OfferingRefNo ?? '—' }}</div>
                            <!-- <small class="badge bg-light text-navy border">{{ $item->shareType->TypeName ?? $item->shareType->TypeCode ?? '—' }}</small> -->
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ number_format($item->SharesQuantity, 2) }} Qty</div>
                        </td>
                        <td>
                            <small class="text-muted">@ {{ number_format($item->PricePerShare, 2) }}</small>
                        </td>
                        <td>
                            <strong class="text-success fs-6">
                                {{ number_format($item->SharesQuantity * $item->PricePerShare, 2) }}
                            </strong>
                        </td>
                        <td>
                            <small class="d-block text-muted">{{ $item->PaymentMethod ?? '—' }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->TransactionDate ? $item->TransactionDate->format('d M Y') : '—' }}</div>
                            <!-- <small class="text-muted">{{ $item->created_at ? $item->created_at->diffForHumans() : '' }}</small> -->
                        </td>
                        <td>
                            @php
                                $statusClass = match($item->Status) {
                                    'Active' => 'bg-success',
                                    'Pending' => 'bg-warning text-dark',
                                    'Cancelled' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="arbif-badge {{ $statusClass }} text-white">
                                {{ $item->Status ?? 'Active' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('viewsharepurchasetransactions', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('editsharepurchasetransactions', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> Edit
                            </a>
                            <a onclick="confirmDelete()" href="{{ route('destroysharepurchasetransactions', [encrypt($item->id)]) }}" class="arbif-btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No share purchase transactions recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="sharePurchaseTransactionsTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="sharePurchaseTransactionsTable"></div>
        </div>
    </div>
</div>

<!-- Modal 1: Download Template -->
<div class="modal fade arbif-modal" id="downloadTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-file-excel text-success"></i></div>
                <h5 class="modal-title">Download Purchase Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form method="GET" action="{{ route('downloadsharepurchasetemplate') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="OfferingRefNo">Select Share Offering <span class="text-danger">*</span></label>
                        <select style="width: 100%" name="OfferingRefNo" id="OfferingRefNo" data-searchable data-placeholder="Choose Share Offering..." required>
                            <option></option>
                            @foreach($offerings as $offering)
                                <option value="{{ $offering->OfferingRefNo }}">
                                    {{ $offering->OfferingRefNo }} - {{ $offering->shareType->TypeName ?? 'Offering' }} (Price: {{ number_format($offering->PricePerShare ?? $offering->Price ?? 0, 2) }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">This will generate an Excel list pre-populated with active members eligible for this offering.</small>
                    </div>
                </div>

                <div class="modal-footer shadow-sm bg-light rounded-bottom p-3">
                    <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="arbif-btn-submit">
                        <i class="fas fa-download me-1"></i> Download Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Import Batch Purchases -->
<div class="modal fade arbif-modal" id="importExcelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-file-import text-primary"></i></div>
                <h5 class="modal-title">Import Share Purchases</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form method="POST" action="{{ route('importsharepurchasetransactions') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="excel_file">Excel File (.xlsx, .xls, .csv) <span class="text-danger">*</span></label>
                        <input type="file" id="excel_file" name="excel_file" class="form-control" accept=".xlsx, .xls, .csv" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Company Entity</label>
                        <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Company...">
                            <option></option>
                            @foreach($companies as $comp)
                                <option value="{{ $comp->id }}">{{ $comp->company_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Operational Branch</label>
                        <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Branch...">
                            <option></option>
                            @foreach($branches as $br)
                                <option value="{{ $br->id }}">{{ $br->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer shadow-sm bg-light rounded-bottom p-3">
                    <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="arbif-btn-submit">
                        <i class="fas fa-upload me-1"></i> Upload & Process
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 3: Manual Add Share Purchase -->
<div class="modal fade arbif-modal" id="addTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-chart-line"></i></div>
                <h5 class="modal-title">Record New Share Purchase</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storesharepurchasetransactions') ?? '#' }}">
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
                                    <option value="{{ $m->id }}">{{ $m->member_code ? '['.$m->member_code.'] ' : '' }}{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Share Offering <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="share_offering_id" data-searchable data-placeholder="Select Offering..." required>
                                <option></option>
                                @foreach($offerings as $off)
                                    <option value="{{ $off->id }}">{{ $off->OfferingRefNo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Share Type <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="share_type_id" data-searchable data-placeholder="Select Share Type..." required>
                                <option></option>
                                @foreach($shareTypes as $st)
                                    <option value="{{ $st->id }}">{{ $st->TypeName ?? $st->TypeCode }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="TransactionType">Transaction Type <span class="text-danger">*</span></label>
                            <select id="TransactionType" name="TransactionType" class="form-select" required>
                                <option value="Purchase" selected>Purchase</option>
                                <option value="Transfer">Transfer</option>
                                <option value="Bonus">Bonus</option>
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
                            <input type="number" step="0.01" id="SharesQuantity" name="SharesQuantity" class="form-control" placeholder="0.00" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="PricePerShare">Price Per Share <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="PricePerShare" name="PricePerShare" class="form-control" placeholder="0.00" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="TransactionDate">Transaction Date <span class="text-danger">*</span></label>
                            <input type="date" id="TransactionDate" name="TransactionDate" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="PaymentMethod">Payment Method <span class="text-danger">*</span></label>
                            <select id="PaymentMethod" name="PaymentMethod" class="form-select" required>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cash">Cash</option>
                                <option value="Mobile Money">Mobile Money</option>
                                <option value="Cheque">Cheque</option>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label" for="PaymentReference">Payment Reference / Transaction ID</label>
                            <input type="text" id="PaymentReference" name="PaymentReference" class="form-control" placeholder="e.g. TXN-SHARE-102938">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="Narration">Narration / Notes</label>
                            <input type="text" id="Narration" name="Narration" class="form-control" placeholder="Add transaction remarks...">
                        </div>
                    </div>

                    <div class="modal-footer shadow-sm bg-light rounded-bottom p-3" style="margin-top: 30px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Save Transaction</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection