@extends('layouts.workingside')

@section('title', 'Supplier Tenders')
@section('page-title', 'Supplier Tenders')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-contract"></i>
        </div>
        Supplier Tenders
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedsupplierTender') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Tenders
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addTenderModal">
            <i class="fas fa-plus"></i> Create Tender
        </button>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="supplierTenderTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tender No</th>
                        <th>Title</th>
                        <th>Dates</th>
                        <th>Budget</th>
                        <th>Status</th>
                        <th>Branch</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->tender_no }}
                            </span>
                        </td>
                        <td>{{ $item->tender_title }}</td>
                        <td>
                            <small class="d-block"><strong>Open:</strong> {{ \Carbon\Carbon::parse($item->tender_date)->format('d M Y') }}</small>
                            <small class="d-block text-danger"><strong>Close:</strong> {{ \Carbon\Carbon::parse($item->closing_date)->format('d M Y') }}</small>
                        </td>
                        <td>
                            {{ $item->currency_code }} {{ number_format($item->estimated_budget, 2) }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $item->tender_status == 'Published' ? 'success' : ($item->tender_status == 'Draft' ? 'secondary' : 'info') }}">
                                {{ $item->tender_status }}
                            </span>
                        </td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('viewsupplierTender', encrypt($item->id)) }}" class="arbif-btn-view">
                                <i class="fas fa-eye"></i> view
                            </a>

                            <a href="{{ route('editsupplierTender', encrypt($item->id)) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> edit
                            </a>

                            @can('view-generally-delete-of-any-item')
                            <form action="{{ route('destroysupplierTender', encrypt($item->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this tender?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="arbif-btn-delete border-0 bg-transparent">
                                    <i class="fas fa-trash"></i> del
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No Supplier Tenders Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- REGISTER SUPPLIER TENDER MODAL --}}
<div class="modal fade arbif-modal" id="addTenderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <h5 class="modal-title">Register Supplier Tender</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storesupplierTender') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" data-searchable required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branchies as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tender No <span class="text-danger">*</span></label>
                            <input type="text" name="tender_no" class="form-control @error('tender_no') is-invalid @enderror" value="{{ old('tender_no') }}" placeholder="e.g. TND-2026-001" required>
                            @error('tender_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Purchase Request (PR)</label>
                            <select name="purchase_request_id" class="form-select @error('purchase_request_id') is-invalid @enderror" data-searchable>
                                <option value="">-- Select Purchase Request (Optional) --</option>
                                @foreach($purchaseRequests as $pr)
                                    <option value="{{ $pr->id }}" {{ old('purchase_request_id') == $pr->id ? 'selected' : '' }}>
                                        {{ $pr->pr_no ?? 'PR #'.$pr->id }}
                                    </option>
                                @endforeach
                            </select>
                            @error('purchase_request_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Tender Title <span class="text-danger">*</span></label>
                            <input type="text" name="tender_title" class="form-control @error('tender_title') is-invalid @enderror" value="{{ old('tender_title') }}" placeholder="e.g. SUPPLY OF OFFICE STATIONERY & COMPUTERS" required>
                            @error('tender_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tender Status <span class="text-danger">*</span></label>
                            <select name="tender_status" class="form-select @error('tender_status') is-invalid @enderror" required>
                                <option value="Draft" {{ old('tender_status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Published" {{ old('tender_status') == 'Published' ? 'selected' : '' }}>Published</option>
                                <option value="Under Evaluation" {{ old('tender_status') == 'Under Evaluation' ? 'selected' : '' }}>Under Evaluation</option>
                                <option value="Awarded" {{ old('tender_status') == 'Awarded' ? 'selected' : '' }}>Awarded</option>
                                <option value="Cancelled" {{ old('tender_status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('tender_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Tender Date <span class="text-danger">*</span></label>
                            <input type="date" name="tender_date" class="form-control @error('tender_date') is-invalid @enderror" value="{{ old('tender_date', date('Y-m-d')) }}" required>
                            @error('tender_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Closing Date <span class="text-danger">*</span></label>
                            <input type="date" name="closing_date" class="form-control @error('closing_date') is-invalid @enderror" value="{{ old('closing_date') }}" required>
                            @error('closing_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Currency</label>
                            <input type="text" name="currency_code" class="form-control @error('currency_code') is-invalid @enderror" value="{{ old('currency_code', 'TZS') }}" placeholder="TZS, USD...">
                            @error('currency_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Estimated Budget</label>
                            <input type="number" step="0.01" name="estimated_budget" class="form-control @error('estimated_budget') is-invalid @enderror" value="{{ old('estimated_budget') }}" placeholder="0.00">
                            @error('estimated_budget') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Evaluation Method</label>
                            <input type="text" name="evaluation_method" class="form-control @error('evaluation_method') is-invalid @enderror" value="{{ old('evaluation_method') }}" placeholder="e.g. Least Cost / Quality and Cost Based">
                            @error('evaluation_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Awarded Supplier (Optional)</label>
                            <select name="awarded_supplier_id" class="form-select @error('awarded_supplier_id') is-invalid @enderror" data-searchable>
                                <option value="">-- None / Pending --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('awarded_supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->supplier_name ?? $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('awarded_supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tender Description</label>
                        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Scope of work, technical requirements, or terms...">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save Tender</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new DataTable('#supplierTenderTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });

    @if ($errors->any())
        var addModal = new bootstrap.Modal(document.getElementById('addTenderModal'));
        addModal.show();
    @endif
</script>
@endpush