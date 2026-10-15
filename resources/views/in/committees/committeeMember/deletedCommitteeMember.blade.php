@extends('layouts.configside')

@section('title', 'Deleted Committee Members')
@section('page-title', 'Deleted Committee Members')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Committee Members
    </h3>

    <div>
        <a href="{{ route('committeeMember') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Active Members
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedMembersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reference</th>
                        <th>Member Name</th>
                        <th>Committee</th>
                        <th>Position</th>
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
                        <td>{{ $item->member->full_name ?? $item->member->first_name . ' ' . $item->member->last_name }}</td>
                        <td>{{ $item->committee->committee_name ?? 'N/A' }}</td>
                        <td>{{ $item->position->position_name ?? 'N/A' }}</td>
                        <td>{{ $item->deleted_at ? $item->deleted_at->format('d M, Y H:i A') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restoreCommitteeMember', encrypt($item->id)) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Are you sure you want to restore this committee member?')">
                                <i class="fas fa-undo me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Soft-Deleted Committee Members Found</td>
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
    new DataTable('#deletedMembersTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush