@extends('layouts.configside')
@section('title', 'Share Types')
@section('page-title', 'Share Types Management')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-chart-pie"></i></div>Share Types Portfolio</h3>
    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addShareTypeModal">
        <i class="fas fa-plus-circle me-1"></i> Add New Share Type
    </button>
</div>

<!-- Main Datatable Index Panel -->
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="shareTypesTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Ref No</th>
                        <th class="sortable">Type Code</th>
                        <th class="sortable">Type Name</th>
                        <th class="sortable">Nominal Value</th>
                        <th class="sortable">Dividend Eligible</th>
                        <th class="sortable">Company & Branch</th>
                        <th class="sortable">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shareTypes as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->TypeRefNo ?? '—' }}</span></td>
                        <td><strong>{{ $item->TypeCode }}</strong></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->TypeName }}</div>
                            <small class="text-muted">{{ Str::limit($item->Description, 40) }}</small>
                        </td>
                        <td><strong class="text-navy">{{ number_format($item->NominalValue, 2) }}</strong></td>
                        <td>
                            @if($item->DividendEligible)
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Yes</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i> No</span>
                            @endif
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
                            <a href="{{ route('viewsharetypes', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('editsharetypes', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> Edit
                            </a>
                            <a onclick="confirmDelete()" href="{{ route('destroysharetypes', [encrypt($item->id)]) }}" class="arbif-btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No active share types exist in the record.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="shareTypesTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="shareTypesTable"></div>
        </div>
    </div>
</div>

<!-- Share Type Creation Modal -->
<div class="modal fade arbif-modal" id="addShareTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-chart-pie"></i></div>
                <h5 class="modal-title">Create New Share Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storesharetypes') }}" enctype="multipart/form-data">
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

                    <!-- SECTION 1: IDENTITY & INFORMATION -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-tag me-2"></i> 1. Share Type Details
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label" for="TypeCode">Type Code <span class="text-danger">*</span></label>
                            <input type="text" id="TypeCode" name="TypeCode" class="form-control" placeholder="e.g. ST-001" value="{{ old('TypeCode') }}" required>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label" for="TypeName">Type Name <span class="text-danger">*</span></label>
                            <input type="text" id="TypeName" name="TypeName" class="form-control" placeholder="e.g. Ordinary Shares" value="{{ old('TypeName') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="NominalValue">Nominal Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="NominalValue" name="NominalValue" class="form-control" placeholder="0.00" value="{{ old('NominalValue', '0.00') }}" required>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4 pt-2">
                                <input class="form-check-input" type="checkbox" id="DividendEligible" name="DividendEligible" value="1" {{ old('DividendEligible', 1) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="DividendEligible">Eligible for Dividends</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="Description">Description</label>
                            <textarea id="Description" name="Description" class="form-control" rows="2" placeholder="Brief details about this share type...">{{ old('Description') }}</textarea>
                        </div>
                    </div>

                    <!-- SECTION 2: ORGANIZATIONAL ASSIGNMENT -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-building me-2"></i> 2. Organizational Scope
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
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Commit Share Type</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection