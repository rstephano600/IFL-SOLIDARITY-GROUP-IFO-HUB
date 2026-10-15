@extends('layouts.workingside')

@section('title', 'Deleted Sales Customers')
@section('page-title', 'Deleted Customers')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon text-danger">
            <i class="fas fa-user-slash"></i>
        </div>
        Deleted Sales Customers
    </h3>

    <div>
        <a href="{{ route('salesCustomer') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Sales Customers
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedSalesCustomerTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No.</th>
                        <th>Customer Code</th>
                        <th>Customer Name</th>
                        <th>Type</th>
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
                        <td><strong>{{ $item->customer_code }}</strong></td>
                        <td>{{ $item->customer_name }}</td>
                        <td><span class="badge bg-secondary">{{ $item->customer_type }}</span></td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>{{ $item->deleter->name ?? 'System' }}</td>
                        <td>{{ $item->deleted_at ? $item->deleted_at->format('d M, Y H:i') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restoresalesCustomer', encrypt($item->id)) }}" class="btn btn-sm btn-success text-white" onclick="return confirm('Are you sure you want to restore this customer?')">
                                <i class="fas fa-undo me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Soft-Deleted Customers Found</td>
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
    new DataTable('#deletedSalesCustomerTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush