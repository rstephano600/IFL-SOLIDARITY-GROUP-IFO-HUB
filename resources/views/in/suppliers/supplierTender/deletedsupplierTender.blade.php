@extends('layouts.workingside')

@section('title', 'Deleted Supplier Tenders')
@section('page-title', 'Deleted Supplier Tenders')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon text-danger">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Supplier Tenders Archive
    </h3>

    <div>
        <a href="{{ route('supplierTender') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="fas fa-arrow-left"></i> Active Tenders
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedSupplierTenderTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tender No</th>
                        <th>Title</th>
                        <th>Closing Date</th>
                        <th>Branch</th>
                        <th>Removed By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->tender_no }}
                            </span>
                        </td>
                        <td>{{ $item->tender_title }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->closing_date)->format('d M Y') }}</td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td>{{ $item->deleter->name ?? 'N/A' }}</td>
                        <td>
                            <form action="{{ route('restoresupplierTender', encrypt($item->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Restore this supplier tender back to active status?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-undo me-1"></i> Restore
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Soft-Deleted Tenders Found</td>
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
    new DataTable('#deletedSupplierTenderTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush