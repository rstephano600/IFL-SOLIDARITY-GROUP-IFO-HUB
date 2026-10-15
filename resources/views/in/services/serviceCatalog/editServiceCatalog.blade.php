@extends('layouts.workingside')

@section('title', 'Edit Service Catalog Item')
@section('page-title', 'Edit Service Item')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-edit"></i>
        </div>
        Edit Service: {{ $data->service_name }}
    </h3>

    <a href="{{ route('serviceCatalog') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
        <i class="fas fa-arrow-left me-2"></i> Back to Catalog
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updateserviceCategory', encrypt($data->id)) }}">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ (old('branch_id', $data->branch_id) == $branch->id) ? 'selected' : '' }}>
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
                            <option value="{{ $category->id }}" {{ (old('service_category_id', $data->service_category_id) == $category->id) ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Service Name <span class="text-danger">*</span></label>
                    <input type="text" name="service_name" class="form-control" value="{{ old('service_name', $data->service_name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Service Code <span class="text-danger">*</span></label>
                    <input type="text" name="service_code" class="form-control" value="{{ old('service_code', $data->service_code) }}" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Default Unit</label>
                    <input type="text" name="default_unit" class="form-control" value="{{ old('default_unit', $data->default_unit) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Default Unit Price</label>
                    <input type="number" step="0.01" name="default_unit_price" class="form-control" value="{{ old('default_unit_price', $data->default_unit_price) }}">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="requires_product" value="1" id="requires_product" {{ old('requires_product', $data->requires_product) ? 'checked' : '' }}>
                        <label class="form-check-label" for="requires_product">
                            Requires Product Link
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="requires_attachment" value="1" id="requires_attachment" {{ old('requires_attachment', $data->requires_attachment) ? 'checked' : '' }}>
                        <label class="form-check-label" for="requires_attachment">
                            Requires Supporting Attachment
                        </label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description / Scope</label>
                <textarea name="description" rows="4" class="form-control">{{ old('description', $data->description) }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('serviceCatalog') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Service</button>
            </div>
        </form>
    </div>
</div>

@endsection