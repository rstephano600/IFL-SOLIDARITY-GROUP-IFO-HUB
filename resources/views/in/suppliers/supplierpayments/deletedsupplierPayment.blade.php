@extends('layouts.workingside')

@section('title', 'Deleted Supplier Payments')
@section('page-title', 'Deleted Supplier Payments')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Supplier Payments
    </h3>

    <div>
        <a href="{{ route('supplierPayment') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Active Payments
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedPaymentTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Payment Ref</th>
                        <th>Supplier</th>
                        <th>Amount</th>
                        <th>Deleted By</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $item->payment_no }}</td>
                        <td>{{ $item->supplier->SupplierName ?? 'N/A' }}</td>
                        <td class="fw-bold text-success">{{ number_format($item->payment_amount, 2) }} {{ $item->currency_code }}</td>
                        <td>{{ $item->deleter->name ?? 'N/A' }}</td>
                        <td>{{ $item->deleted_at ? $item->deleted_at->format('d M, Y H:i A') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restoresupplierPayment', encrypt($item->id)) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Restore this Payment record?')">
                                <i class="fas fa-undo me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Soft-Deleted Supplier Payments Found</td>
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
    new DataTable('#deletedPaymentTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush