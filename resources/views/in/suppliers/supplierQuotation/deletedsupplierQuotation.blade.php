@extends('layouts.workingside')

@section('title', 'Deleted Supplier Quotations')
@section('page-title', 'Deleted Supplier Quotations')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon text-danger">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Supplier Quotations
    </h3>

    <div>
        <a href="{{ route('supplierQuotation') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="fas fa-arrow-left"></i> Active Quotations
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedSupplierQuotationTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Quotation No</th>
                        <th>Supplier</th>
                        <th>Branch</th>
                        <th>Removed By</th>
                        <th>Deleted Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong class="text-danger">{{ $item->quotation_no }}</strong>
                        </td>
                        <td>{{ $item->supplier->supplier_name ?? $item->supplier->name ?? 'N/A' }}</td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td>{{ $item->deleter->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->deleted_at)->format('d M Y, H:i') }}</td>
                        <td>
                            <form action="{{ route('restoresupplierQuotation', encrypt($item->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Restore this quotation back to active status?')">
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
                        <td colspan="7" class="text-center">No Soft-Deleted Quotations Found</td>
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
    new DataTable('#deletedSupplierQuotationTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush