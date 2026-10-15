@extends('layouts.workingside')

@section('title', 'Deleted Committees')
@section('page-title', 'Deleted Committees')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Committees
    </h3>

    <div>
        <a href="{{ route('committee') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Active List
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedCommitteesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reference</th>
                        <th>Code</th>
                        <th>Committee Name</th>
                        <th>Chairman</th>
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
                        <td><strong>{{ $item->committee_code }}</strong></td>
                        <td>{{ $item->committee_name }}</td>
                        <td>{{ $item->chairman->name ?? 'N/A' }}</td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td>{{ $item->deleted_at ? $item->deleted_at->format('d M, Y H:i A') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restoreCommittee', encrypt($item->id)) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Are you sure you want to restore this committee record?')">
                                <i class="fas fa-undo me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No Soft-Deleted Committees Found</td>
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
    new DataTable('#deletedCommitteesTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush