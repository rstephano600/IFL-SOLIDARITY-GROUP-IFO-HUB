@extends('layouts.configside')

@section('title', 'Edit Unit of Measure')
@section('page-title', 'Edit Unit of Measure')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-edit"></i>
        </div>
        Edit Unit of Measure
    </h3>
    <a href="{{ route('unitOfMeasure') }}" class="btn btn-secondary btn-sm d-flex align-items-center px-3" style="height: 38px; border-radius: 4px;">
        <i class="fas fa-arrow-left me-2"></i> Back to List
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updateunitOfMeasure', encrypt($data->id)) }}">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Assigned Company <span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-light" value="{{ $data->company->company_name }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select"  data-searchable data-placeholder="Search ..." required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ $data->branch_id == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label text-muted">Unit Reference No (System Protected)</label>
                    <input type="text" class="form-control bg-light" value="{{ $data->UnitRefNo }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Unit Code <span class="text-danger">*</span></label>
                    <input type="text" name="UnitCode" class="form-control" value="{{ $data->UnitCode }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Unit Name <span class="text-danger">*</span></label>
                <input type="text" name="UnitName" class="form-control" value="{{ $data->UnitName }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="Description" rows="4" class="form-control">{{ $data->Description }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('unitOfMeasure') }}" class="btn btn-secondary px-4">Cancel</a>
                <button type="submit" class="arbif-btn-submit px-4">Update Unit</button>
            </div>
        </form>
    </div>
</div>

@endsection