@extends('layouts.workingside')

@section('title', 'Edit Committee Position')
@section('page-title', 'Edit Committee Position')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-edit"></i>
        </div>
        Edit Position: {{ $data->position_name }}
    </h3>

    <div>
        <a href="{{ route('committeePosition') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Positions
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('updateCommitteePosition', encrypt($data->id)) }}">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" data-searchable data-placeholder="Search branch..." required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $data->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Position Code <span class="text-danger">*</span></label>
                    <input type="text" name="position_code" class="form-control" value="{{ old('position_code', $data->position_code) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Position Name <span class="text-danger">*</span></label>
                <input type="text" name="position_name" class="form-control" value="{{ old('position_name', $data->position_name) }}" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Description / Scope of Position</label>
                <textarea name="description" rows="4" class="form-control">{{ old('description', $data->description) }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('committeePosition') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Position</button>
            </div>
        </form>
    </div>
</div>

@endsection