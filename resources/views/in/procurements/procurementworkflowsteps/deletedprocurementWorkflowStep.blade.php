@extends('layouts.configside')

@section('title', 'Deleted Workflow Steps')
@section('page-title', 'Deleted Workflow Steps')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Workflow Steps
    </h3>

    <div>
        <a href="{{ route('procurementWorkflowStep') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Active Steps
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedWorkflowStepTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Step Name</th>
                        <th>Procurement Type</th>
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
                                {{ $item->step_code }}
                            </span>
                        </td>
                        <td>{{ $item->step_name }}</td>
                        <td>{{ $item->procurementType->procurement_name ?? 'N/A' }}</td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>{{ $item->deleter->name ?? 'N/A' }}</td>
                        <td>{{ $item->deleted_at ? $item->deleted_at->format('d M, Y H:i A') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restoreprocurementWorkflowStep', encrypt($item->id)) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Are you sure you want to restore this workflow step?')">
                                <i class="fas fa-undo me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No Soft-Deleted Workflow Steps Found</td>
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
    new DataTable('#deletedWorkflowStepTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush