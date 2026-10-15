@extends('layouts.workingside')

@section('title', 'Deleted Service Requests')
@section('page-title', 'Deleted Service Requests')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Service Requests
    </h3>

    <div>
        <a href="{{ route('serviceRequest') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Active Requests
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedServiceRequestsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>SR No.</th>
                        <th>Title</th>
                        <th>Requester</th>
                        <th>Committee</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $item->request_no }}</td>
                        <td>{{ $item->request_title }}</td>
                        <td>{{ $item->requester->name ?? 'N/A' }}</td>
                        <td>{{ $item->committee->committee_name ?? 'N/A' }}</td>
                        <td>{{ $item->deleted_at ? $item->deleted_at->format('d M, Y H:i A') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restoreServiceRequest', encrypt($item->id)) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Are you sure you want to restore this service request?')">
                                <i class="fas fa-undo me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Soft-Deleted Service Requests Found</td>
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
    new DataTable('#deletedServiceRequestsTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush