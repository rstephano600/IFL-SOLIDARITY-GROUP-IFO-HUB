@extends('layouts.workingside')

@section('title', 'Service Catalog')
@section('page-title', 'Service Catalog')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-concierge-bell"></i>
        </div>
        Service Catalog
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedserviceCatalog') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Services
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addServiceModal">
            <i class="fas fa-plus me-1"></i> Add Service
        </button>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('serviceCatalog') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="branch_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Branches --</option>
                    @foreach($branchies as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->branch_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="service_category_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Categories --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('service_category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by service name, code or reference...">
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
                @if(request()->anyFilled(['branch_id', 'service_category_id', 'search']))
                    <a href="{{ route('serviceCatalog') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="serviceCatalogTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reference</th>
                        <th>Code</th>
                        <th>Service Name</th>
                        <th>Category</th>
                        <th>Branch</th>
                        <th>Unit Price</th>
                        <th>Requests</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->ReferenceNo }}
                            </span>
                        </td>
                        <td><strong>{{ $item->service_code }}</strong></td>
                        <td>
                            <a href="{{ route('viewserviceCategory', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ $item->service_name }}
                            </a>
                        </td>
                        <td>{{ $item->category->category_name ?? 'N/A' }}</td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>{{ number_format($item->default_unit_price, 2) }}</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                <i class="fas fa-file-invoice me-1"></i>{{ $item->request_items_count }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewserviceCategory', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <a href="{{ route('editserviceCategory', encrypt($item->id)) }}" class="arbif-btn-edit">
                                    <i class="fas fa-pencil"></i> Edit
                                </a>

                                @can('view-generally-delete-of-any-item')
                                    <form action="{{ route('destroyserviceCategory', encrypt($item->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this service item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="arbif-btn-delete border-0 bg-transparent">
                                            <i class="fas fa-trash"></i> Del
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Service Catalog Items Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- REGISTER SERVICE MODAL --}}
<div class="modal fade arbif-modal" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <h5 class="modal-title fw-bold">Register Service Catalog Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storeserviceCategory') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select" required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branchies as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Service Category <span class="text-danger">*</span></label>
                            <select name="service_category_id" class="form-select" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('service_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Service Name <span class="text-danger">*</span></label>
                            <input type="text" name="service_name" class="form-control" value="{{ old('service_name') }}" placeholder="e.g. TAX CONSULTANCY" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Service Code</label>
                            <input type="text" name="service_code" class="form-control" value="{{ old('service_code') }}" placeholder="e.g. SRV-TAX (Optional)">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Default Unit</label>
                            <input type="text" name="default_unit" class="form-control" value="{{ old('default_unit') }}" placeholder="e.g. Hours, Session, Task">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Default Unit Price</label>
                            <input type="number" step="0.01" name="default_unit_price" class="form-control" value="{{ old('default_unit_price') }}" placeholder="0.00">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="requires_product" value="1" id="requires_product" {{ old('requires_product') ? 'checked' : '' }}>
                                <label class="form-check-label" for="requires_product">
                                    Requires Product Link
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="requires_attachment" value="1" id="requires_attachment" {{ old('requires_attachment') ? 'checked' : '' }}>
                                <label class="form-check-label" for="requires_attachment">
                                    Requires Supporting Attachment
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description / Scope</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Brief overview of this service...">{{ old('description') }}</textarea>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection