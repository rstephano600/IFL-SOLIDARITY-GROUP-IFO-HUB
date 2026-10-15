@extends('layouts.workingside')

@section('title', 'Deleted Committee Positions')
@section('page-title', 'Deleted Committee Positions')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Committee Positions
    </h3>

    <div>
        <a href="{{ route('committeePosition') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Active Positions
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedPositionsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reference</th>
                        <th>Code</th>
                        <th>Position Name</th>
                        <th>Branch</th>
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
                        <td><strong>{{ $item->position_code }}</strong></td>
                        <td>{{ $item->position_name }}</td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td>{{ $item->deleted_at ? $item->deleted_at->format('d M, Y H:i A') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restoreCommitteePosition', encrypt($item->id)) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Are you sure you want to restore this position record?')">
                                <i class="fas fa-undo me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Soft-Deleted Positions Found</td>
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
    new DataTable('#deletedPositionsTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush