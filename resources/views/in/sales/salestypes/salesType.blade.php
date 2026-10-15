@extends('layouts.configside')

@section('title', 'Sales Types')
@section('page-title', 'Sales Types')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-tags"></i>
        </div>
        Sales Types Configuration
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedsalesType') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Sales Types
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addSalesTypeModal">
            <i class="fas fa-plus me-1"></i> Add Sales Type
        </button>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="salesTypeTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No.</th>
                        <th>Type Code</th>
                        <th>Type Name</th>
                        <th>Payment Terms</th>
                        <th>Customer Req.</th>
                        <th>Employee Req.</th>
                        <th>Discount</th>
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
                                {{ $item->ReferenceNo }}
                            </span>
                        </td>
                        <td><strong>{{ $item->type_code }}</strong></td>
                        <td>{{ $item->type_name }}</td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $item->default_payment_terms }}</span>
                        </td>
                        <td>
                            @if($item->requires_customer)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            @if($item->requires_employee)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            @if($item->allows_discount)
                                <span class="badge bg-primary">Allowed</span>
                            @else
                                <span class="badge bg-danger">Disabled</span>
                            @endif
                        </td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewsalesType', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <a href="{{ route('editsalesType', encrypt($item->id)) }}" class="arbif-btn-edit">
                                    <i class="fas fa-pencil"></i> Edit
                                </a>

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroysalesType', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to remove this sales type?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No Sales Types Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- REGISTER SALES TYPE MODAL --}}
<div class="modal fade arbif-modal" id="addSalesTypeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <h5 class="modal-title">Register Sales Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storesalesType') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branchies as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Type Code <span class="text-danger">*</span></label>
                            <input type="text" name="type_code" class="form-control" value="{{ old('type_code') }}" placeholder="e.g. POS, DIRECT, CREDIT" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Type Name <span class="text-danger">*</span></label>
                            <input type="text" name="type_name" class="form-control" value="{{ old('type_name') }}" placeholder="e.g. DIRECT OVER-THE-COUNTER SALE" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Default Payment Terms <span class="text-danger">*</span></label>
                            <select name="default_payment_terms" class="form-select" required>
                                <option value="IMMEDIATE" {{ old('default_payment_terms') == 'IMMEDIATE' ? 'selected' : '' }}>IMMEDIATE</option>
                                <option value="CREDIT" {{ old('default_payment_terms') == 'CREDIT' ? 'selected' : '' }}>CREDIT</option>
                                <option value="COMPANY_ACCOUNT" {{ old('default_payment_terms') == 'COMPANY_ACCOUNT' ? 'selected' : '' }}>COMPANY ACCOUNT</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="display_order" class="form-control" value="{{ old('display_order', 0) }}" min="0">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="requires_customer" value="1" id="req_cust" {{ old('requires_customer') ? 'checked' : '' }}>
                                <label class="form-check-label" for="req_cust">Requires Customer</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="requires_employee" value="1" id="req_emp" {{ old('requires_employee') ? 'checked' : '' }}>
                                <label class="form-check-label" for="req_emp">Requires Employee</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="allows_discount" value="1" id="allow_disc" {{ old('allows_discount', 1) ? 'checked' : '' }}>
                                <label class="form-check-label" for="allow_disc">Allows Discount</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Provide overview details here...">{{ old('description') }}</textarea>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save Sales Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new DataTable('#salesTypeTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush