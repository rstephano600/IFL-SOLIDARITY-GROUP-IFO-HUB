@extends('layouts.configside')
@section('title', 'Share Offerings')
@section('page-title', 'Share Offerings Management')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-hand-holding-usd"></i></div>Share Offerings Portfolio</h3>
    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addShareOfferingModal">
        <i class="fas fa-plus-circle me-1"></i> Add New Offering
    </button>
</div>

<!-- Main Datatable Index Panel -->
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="shareOfferingsTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Ref No</th>
                        <th class="sortable">Offering Name</th>
                        <th class="sortable">Share Type</th>
                        <th class="sortable">Total Shares</th>
                        <th class="sortable">Price / Share</th>
                        <th class="sortable">Total Capital Share</th>
                        <th class="sortable">Max % / Member</th>
                        <th class="sortable">Offering Period</th>
                        <th class="sortable">Offering Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shareOfferings as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->OfferingRefNo ?? '—' }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->OfferingName }}</div>
                            <small class="text-muted">{{ $item->company->company_name ?? '—' }} ({{ $item->branch->branch_name ?? '—' }})</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border fw-bold">
                                {{ $item->shareType->TypeName ?? '—' }}
                            </span>
                        </td>
                        <td><strong class="text-dark">{{ number_format($item->TotalShares, 2) }}</strong></td>
                        <td><strong class="text-navy">{{ number_format($item->PricePerShare, 2) }}</strong></td>
                        <td><strong class="text-navy">{{ number_format($item->TotalCapitalAmount, 2) }}</strong></td>
                        <td><span class="badge bg-info text-dark">{{ number_format($item->MaxPercentPerMember, 2) }}%</span></td>
                        <td>
                            <small class="d-block text-dark fw-bold">
                                {{ $item->OfferingStartDate ? $item->OfferingStartDate->format('d M Y') : '—' }}
                            </small>
                            <small class="text-muted">
                                to {{ $item->OfferingEndDate ? $item->OfferingEndDate->format('d M Y') : '—' }}
                            </small>
                        </td>
                        <td>
                            @php
                                $statusClass = match($item->OfferingStatus) {
                                    'Open' => 'bg-success',
                                    'Closed' => 'bg-danger',
                                    'Pending' => 'bg-warning text-dark',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="arbif-badge {{ $statusClass }} text-white">
                                {{ $item->OfferingStatus ?? 'Open' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('viewshareofferings', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('editshareofferings', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> Edit
                            </a>
                            <a onclick="confirmDelete()" href="{{ route('destroyshareofferings', [encrypt($item->id)]) }}" class="arbif-btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No active share offerings exist in the record.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="shareOfferingsTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="shareOfferingsTable"></div>
        </div>
    </div>
</div>

<!-- Share Offering Creation Modal -->
<div class="modal fade arbif-modal" id="addShareOfferingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-hand-holding-usd"></i></div>
                <h5 class="modal-title">Create New Share Offering</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storeshareofferings') }}" enctype="multipart/form-data">
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

                    <!-- SECTION 1: OFFERING DETAILS -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-file-signature me-2"></i> 1. Offering Details
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label" for="OfferingName">Offering Name <span class="text-danger">*</span></label>
                            <input type="text" id="OfferingName" name="OfferingName" class="form-control" placeholder="e.g. Q3 2026 Public Share Issue" value="{{ old('OfferingName') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Share Type <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="share_type_id" data-searchable data-placeholder="Select Share Type..." required>
                                <option></option>
                                @foreach($shareTypes as $st)
                                    <option value="{{ $st->id }}" {{ old('share_type_id') == $st->id ? 'selected' : '' }}>
                                        {{ $st->TypeCode }} - {{ $st->TypeName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="TotalShares">Total Shares Allocated <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="TotalShares" name="TotalShares" class="form-control" placeholder="0.00" value="{{ old('TotalShares') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="PricePerShare">Price Per Share <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="PricePerShare" name="PricePerShare" class="form-control" placeholder="0.00" value="{{ old('PricePerShare') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="MaxPercentPerMember">Max % Per Member <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" max="100" id="MaxPercentPerMember" name="MaxPercentPerMember" class="form-control" placeholder="e.g. 10.00" value="{{ old('MaxPercentPerMember', '10.00') }}" required>
                        </div>
                    </div>

                    <!-- SECTION 2: SCHEDULE & OFFERING STATUS -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i> 2. Offering Timeline & Status
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label" for="OfferingStartDate">Start Date <span class="text-danger">*</span></label>
                            <input type="date" id="OfferingStartDate" name="OfferingStartDate" class="form-control" value="{{ old('OfferingStartDate') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="OfferingEndDate">End Date <span class="text-danger">*</span></label>
                            <input type="date" id="OfferingEndDate" name="OfferingEndDate" class="form-control" value="{{ old('OfferingEndDate') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="OfferingStatus">Offering Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="OfferingStatus" name="OfferingStatus" required>
                                <option value="Open" {{ old('OfferingStatus') == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Pending" {{ old('OfferingStatus') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Closed" {{ old('OfferingStatus') == 'Closed' ? 'selected' : '' }}>Closed</option>
                            </select>
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
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Commit Share Offering</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection