@extends('layouts.workingside')
@section('title', 'Social Contributions')
@section('page-title', 'Social Contributions Management')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-hand-holding-usd"></i></div>Social Contributions</h3>
    <div class="d-flex gap-2">
        <button class="arbif-btn-cancel text-navy bg-white border" data-bs-toggle="modal" data-bs-target="#downloadTemplateModal">
            <i class="fas fa-file-excel me-1 text-success"></i> Download Template
        </button>
        <button class="arbif-btn-cancel text-navy bg-white border" data-bs-toggle="modal" data-bs-target="#importExcelModal">
            <i class="fas fa-file-import me-1 text-primary"></i> Import Excel
        </button>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addContributionModal">
            <i class="fas fa-plus-circle me-1"></i> Add Contribution
        </button>
    </div>
</div>

<!-- Main Datatable Index Panel -->
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="socialContributionsTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Ref No</th>
                        <th class="sortable">Member</th>
                        <th class="sortable">Schedule & Month</th>
                        <th class="sortable">Amount Paid / Expected</th>
                        <th class="sortable">Payment Details</th>
                        <th class="sortable">Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contributions as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->ContributionRefNo ?? '—' }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->member->name ?? '—' }}</div>
                            <small class="text-muted">{{ $item->member->member_code ?? '—' }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->schedule->ScheduleRefNo ?? '—' }}</div>
                            <small class="text-muted">Month: {{ $item->ContributionMonth ? $item->ContributionMonth->format('M Y') : '—' }}</small>
                        </td>
                        <td>
                            <strong class="text-navy fs-6">{{ number_format($item->AmountPaid, 2) }}</strong>
                            <small class="d-block text-muted">Exp: {{ number_format($item->ExpectedAmount, 2) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary text-white">{{ $item->PaymentMethod ?? '—' }}</span>
                            <small class="d-block text-muted">{{ $item->PaymentDate ? $item->PaymentDate->format('d M Y') : '—' }}</small>
                        </td>
                        <td>
                            @php
                                $statusClass = match($item->PaymentStatus) {
                                    'Paid' => 'bg-success',
                                    'Partial' => 'bg-warning text-dark',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="arbif-badge {{ $statusClass }} text-white">
                                {{ $item->PaymentStatus ?? 'Pending' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('viewsocialcontributions', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('editsocialcontributions', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> Edit
                            </a>
                            <a onclick="confirmDelete()" href="{{ route('destroysocialcontributions', [encrypt($item->id)]) }}" class="arbif-btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No active social contributions recorded in the system.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="socialContributionsTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="socialContributionsTable"></div>
        </div>
    </div>
</div>

<!-- Modal 1: Download Excel Template -->
<div class="modal fade arbif-modal" id="downloadTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-file-excel text-success"></i></div>
                <h5 class="modal-title">Download Contribution Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form method="GET" action="{{ route('downloadsocialcontributiontemplate') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="ScheduleRefNo">Select Schedule <span class="text-danger">*</span></label>
                        <select style="width: 100%" name="ScheduleRefNo" id="ScheduleRefNo" data-searchable data-placeholder="Choose Schedule..." required>
                            <option></option>
                            @foreach($schedules as $sched)
                                <option value="{{ $sched->ScheduleRefNo }}">
                                    {{ $sched->ScheduleRefNo }} (Amount: {{ number_format($sched->FeeAmount, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="ContributionMonth">Contribution Month</label>
                        <input type="month" id="ContributionMonth" name="ContributionMonth" class="form-control" value="{{ date('Y-m') }}">
                        <small class="text-muted">Defaults to current month if left blank.</small>
                    </div>
                </div>

                <div class="modal-footer shadow-sm bg-light rounded-bottom p-3">
                    <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="arbif-btn-submit">
                        <i class="fas fa-download me-1"></i> Download Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Import Excel Contributions -->
<div class="modal fade arbif-modal" id="importExcelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-file-import text-primary"></i></div>
                <h5 class="modal-title">Import Contributions Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form method="POST" action="{{ route('importsocialcontributions') }}" enctype="multipart/form-data">
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

<!-- Modal 3: Add Contribution Modal (Placeholder / Standard Single Addition) -->
<div class="modal fade arbif-modal" id="addContributionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-hand-holding-usd"></i></div>
                <h5 class="modal-title">Record Social Contribution</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storesocialcontributions') ?? '#' }}" enctype="multipart/form-data">
                    @csrf
                    <!-- SECTION 1: MEMBER & SCHEDULE -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-user-tag me-2"></i> 1. Member & Schedule Selection
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
                            <label class="form-label">Contribution Schedule <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="social_contribution_schedule_id" data-searchable data-placeholder="Select Schedule..." required>
                                <option></option>
                                @foreach($schedules as $s)
                                    <option value="{{ $s->id }}">{{ $s->ScheduleRefNo }} (Amt: {{ number_format($s->FeeAmount, 2) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- SECTION 2: PAYMENT INFO -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-credit-card me-2"></i> 2. Payment Details
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label" for="ContributionMonth">Contribution Month <span class="text-danger">*</span></label>
                            <input type="date" id="ContributionMonth" name="ContributionMonth" class="form-control" value="{{ date('Y-m-01') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="AmountPaid">Amount Paid <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="AmountPaid" name="AmountPaid" class="form-control" placeholder="0.00" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="PaymentDate">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" id="PaymentDate" name="PaymentDate" class="form-control" value="{{ date('Y-m-d') }}" required>
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
                            <input type="text" id="PaymentReference" name="PaymentReference" class="form-control" placeholder="e.g. TXN98765432">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="Narration">Narration / Notes</label>
                            <input type="text" id="Narration" name="Narration" class="form-control" placeholder="Monthly contribution details...">
                        </div>
                    </div>

                    <div class="modal-footer shadow-sm bg-light rounded-bottom p-3" style="margin-top: 30px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Save Contribution</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection