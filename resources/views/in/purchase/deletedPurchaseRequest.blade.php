@extends('layouts.workingside')

@section('title', 'Deleted Purchase Requests')
@section('page-title', 'Deleted Purchase Requests')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Purchase Requests
    </h3>

    <div>
        <a href="{{ route('purchaseRequest') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Active Requests
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>PR No.</th>
                        <th>Requester</th>
                        <th>Procurement Type</th>
                        <th>Est. Total (TZS)</th>
                        <th>Deleted By</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $item->request_no }}</td>
                        <td>{{ $item->requester->name ?? 'N/A' }}</td>
                        <td>{{ $item->procurementType->procurement_name ?? 'N/A' }}</td>
                        <td class="fw-bold">{{ number_format($item->estimated_total, 2) }}</td>
                        <td>{{ $item->deleter->name ?? 'N/A' }}</td>
                        <td>{{ $item->deleted_at ? $item->deleted_at->format('d M, Y H:i A') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restorepurchaseRequest', encrypt($item->id)) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Restore this purchase request back to draft status?')">
                                <i class="fas fa-undo me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No Soft-Deleted Purchase Requests Found</td>
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
    new DataTable('#deletedTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush