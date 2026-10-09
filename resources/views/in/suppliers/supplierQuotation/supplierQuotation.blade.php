@extends('layouts.workingside')

@section('title', 'Supplier Quotations')
@section('page-title', 'Supplier Quotations')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        Supplier Quotations
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('createsupplierQuotation') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="fas fa-plus"></i> Add Quotation
        </a>
        <a href="{{ route('deletedsupplierQuotation') }}" class="btn btn-outline-danger d-flex align-items-center gap-2">
            <i class="fas fa-trash"></i> Deleted Quotations
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="supplierQuotationTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Quotation No</th>
                        <th>Supplier</th>
                        <th>Date</th>
                        <th>Valid Until</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Branch</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong class="text-primary">{{ $item->quotation_no }}</strong>
                            @if($item->supplier_quotation_no)
                                <br><small class="text-muted">Ref: {{ $item->supplier_quotation_no }}</small>
                            @endif
                        </td>
                        <td>{{ $item->supplier->SupplierName ?? $item->supplier->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->quotation_date)->format('d M Y') }}</td>
                        <td>{{ $item->valid_until ? \Carbon\Carbon::parse($item->valid_until)->format('d M Y') : 'N/A' }}</td>
                        <td><strong>{{ $item->currency_code }} {{ number_format($item->total_amount, 2) }}</strong></td>
                        <td>
                            <span class="arbif-badge arbif-badge-{{ strtolower($item->quotation_status) == 'approved' ? 'success' : (strtolower($item->quotation_status) == 'rejected' ? 'danger' : 'warning') }}">
                                {{ $item->quotation_status }}
                            </span>
                        </td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewsupplierQuotation', encrypt($item->id)) }}" class="btn btn-sm btn-outline-info" title="View / Print">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('editsupplierQuotation', encrypt($item->id)) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('destroysupplierQuotation', encrypt($item->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this quotation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Supplier Quotations Found</td>
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
    new DataTable('#supplierQuotationTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush