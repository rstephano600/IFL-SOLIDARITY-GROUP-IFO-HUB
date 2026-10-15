@extends('layouts.workingside')

@section('title', 'Edit Service Category')
@section('page-title', 'Edit Service Category')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-edit"></i>
        </div>
        Edit Service Category: {{ $data->category_name }}
    </h3>

    <a href="{{ route('serviceCategory') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
        <i class="fas fa-arrow-left me-2"></i> Back to List
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
                    <label class="form-label">Category Code <span class="text-danger">*</span></label>
                    <input type="text" name="category_code" class="form-control" value="{{ old('category_code', $data->category_code) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                <input type="text" name="category_name" class="form-control" value="{{ old('category_name', $data->category_name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description / Scope</label>
                <textarea name="description" rows="4" class="form-control">{{ old('description', $data->description) }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('serviceCategory') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Category</button>
            </div>
        </form>
    </div>
</div>

@endsection