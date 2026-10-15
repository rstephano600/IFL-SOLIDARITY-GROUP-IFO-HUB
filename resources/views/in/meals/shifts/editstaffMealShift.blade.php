@extends('layouts.configside')

@section('title', 'Edit Staff Meal Shift')
@section('page-title', 'Edit Meal Shift')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-pencil-alt"></i>
        </div>
        Edit Meal Shift: {{ $data->shift_name }}
    </h3>

    <div>
        <a href="{{ route('staffMealShift') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('updatestaffMealShift', encrypt($data->id)) }}">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $data->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Shift Code <span class="text-danger">*</span></label>
                    <input type="text" name="shift_code" class="form-control" value="{{ old('shift_code', $data->shift_code) }}" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label">Shift Name <span class="text-danger">*</span></label>
                    <input type="text" name="shift_name" class="form-control" value="{{ old('shift_name', $data->shift_name) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $data->display_order) }}" min="0">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Start Time</label>
                    <input type="time" name="start_time" class="form-control" value="{{ old('start_time', $data->start_time ? \Carbon\Carbon::parse($data->start_time)->format('H:i') : '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">End Time</label>
                    <input type="time" name="end_time" class="form-control" value="{{ old('end_time', $data->end_time ? \Carbon\Carbon::parse($data->end_time)->format('H:i') : '') }}">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('staffMealShift') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Shift</button>
            </div>
        </form>
    </div>
</div>

@endsection