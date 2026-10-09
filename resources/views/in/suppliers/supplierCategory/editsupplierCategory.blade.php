@extends('layouts.workingside')

@section('title', 'Edit Supplier Category')
@section('page-title', 'Edit Supplier Category')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-pencil-alt"></i>
        </div>
        Edit Supplier Category
    </h3>

    <div>
        <a href="{{ route('supplierCategory') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Categories
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('updatesupplierCategory', encrypt($data->id)) }}">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ $data->branch_id == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Category Code <span class="text-danger">*</span></label>
                    <input type="text" name="CategoryCode" class="form-control" value="{{ old('CategoryCode', $data->CategoryCode) }}" placeholder="e.g. SUP-LOCAL" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="CategoryName" class="form-control" value="{{ old('CategoryName', $data->CategoryName) }}" placeholder="e.g. RAW MATERIAL SUPPLIERS" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Supplier Group <span class="text-danger">*</span></label>
                    <input type="text" name="SupplierGroup" class="form-control" value="{{ old('SupplierGroup', $data->SupplierGroup) }}" placeholder="e.g. LOCAL / FOREIGN" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Description</label>
                <textarea name="Description" rows="4" class="form-control" placeholder="Provide category overview details here...">{{ old('Description', $data->Description) }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('supplierCategory') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Category</button>
            </div>
        </form>
    </div>
</div>

@endsection