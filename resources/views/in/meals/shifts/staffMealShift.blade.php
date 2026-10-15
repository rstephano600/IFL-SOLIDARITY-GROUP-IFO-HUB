@extends('layouts.configside')

@section('title', 'Staff Meal Shifts')
@section('page-title', 'Staff Meal Shifts')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-clock"></i>
        </div>
        Staff Meal Shifts Configuration
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedstaffMealShift') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Shifts
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addShiftModal">
            <i class="fas fa-plus me-1"></i> Add Meal Shift
        </button>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="staffMealShiftTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No.</th>
                        <th>Shift Code</th>
                        <th>Shift Name</th>
                        <th>Time Interval</th>
                        <th>Display Order</th>
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
                        <td><strong>{{ $item->shift_code }}</strong></td>
                        <td>{{ $item->shift_name }}</td>
                        <td>
                            @if($item->start_time && $item->end_time)
                                <span class="badge bg-info text-dark">
                                    <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Flexible / Any Time</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $item->display_order }}</span>
                        </td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewstaffMealShift', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <a href="{{ route('editstaffMealShift', encrypt($item->id)) }}" class="arbif-btn-edit">
                                    <i class="fas fa-pencil"></i> Edit
                                </a>

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroystaffMealShift', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to delete this meal shift?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No Staff Meal Shifts Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- REGISTER SHIFT MODAL --}}
<div class="modal fade arbif-modal" id="addShiftModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h5 class="modal-title">Register Staff Meal Shift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storestaffMealShift') }}">
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
                            <label class="form-label">Shift Code <span class="text-danger">*</span></label>
                            <input type="text" name="shift_code" class="form-control" value="{{ old('shift_code') }}" placeholder="e.g. MORNING, LUNCH, NIGHT" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Shift Name <span class="text-danger">*</span></label>
                            <input type="text" name="shift_name" class="form-control" value="{{ old('shift_name') }}" placeholder="e.g. MORNING BREAKFAST SHIFT" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="display_order" class="form-control" value="{{ old('display_order', 0) }}" min="0">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">End Time</label>
                            <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}">
                        </div>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save Shift</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new DataTable('#staffMealShiftTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush