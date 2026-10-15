@extends('layouts.configside')

@section('title', 'Deleted Staff Meal Shifts')
@section('page-title', 'Deleted Shifts')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon text-danger">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Staff Meal Shifts
    </h3>

    <div>
        <a href="{{ route('staffMealShift') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Staff Meal Shifts
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedStaffMealShiftTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No.</th>
                        <th>Shift Code</th>
                        <th>Shift Name</th>
                        <th>Branch</th>
                        <th>Deleted By</th>
                        <th>Deleted At</th>
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
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>{{ $item->deleter->name ?? 'System' }}</td>
                        <td>{{ $item->deleted_at ? $item->deleted_at->format('d M, Y H:i') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restorestaffMealShift', encrypt($item->id)) }}" class="btn btn-sm btn-success text-white" onclick="return confirm('Are you sure you want to restore this meal shift?')">
                                <i class="fas fa-undo me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No Soft-Deleted Meal Shifts Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new DataTable('#deletedStaffMealShiftTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush