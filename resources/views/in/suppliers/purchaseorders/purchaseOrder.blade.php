@extends('layouts.workingside')

@section('title', 'Purchase Orders')
@section('page-title', 'Purchase Orders')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-invoice"></i>
        </div>
        Purchase Orders
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedpurchaseOrder') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Orders
        </a>
        <a href="{{ route('createpurchaseOrder') }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-plus me-1"></i> Create Purchase Order
        </a>
    </div>
</div>

{{-- FILTERS --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('purchaseOrder') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="order_status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Order Status --</option>
                    <option value="Draft" {{ request('order_status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Approved" {{ request('order_status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Issued" {{ request('order_status') == 'Issued' ? 'selected' : '' }}>Issued</option>
                    <option value="Completed" {{ request('order_status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ request('order_status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="supplier_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Suppliers --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->SupplierName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From Date">
            </div>

            <div class="col-md-2">
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To Date">
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                @if(request()->anyFilled(['order_status', 'supplier_id', 'from_date', 'to_date']))
                    <a href="{{ route('purchaseOrder') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="purchaseOrderTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>PO No.</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Expected Delivery</th>
                        <th>Total Amount</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('viewpurchaseOrder', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ $item->purchase_order_no }}
                            </a>
                        </td>
                        <td><strong>{{ $item->supplier->SupplierName ?? 'N/A' }}</strong></td>
                        <td>{{ $item->order_date ? $item->order_date->format('d M, Y') : 'N/A' }}</td>
                        <td>{{ $item->expected_delivery_date ? $item->expected_delivery_date->format('d M, Y') : 'N/A' }}</td>
                        <td class="fw-bold text-success">{{ number_format($item->total_amount, 2) }} {{ $item->currency_code }}</td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td>
                            @php
                                $statusClasses = [
                                    'Draft'     => 'bg-secondary',
                                    'Approved'  => 'bg-success',
                                    'Issued'    => 'bg-info text-dark',
                                    'Completed' => 'bg-primary',
                                    'Cancelled' => 'bg-danger',
                                ];
                            @endphp
                            <span class="badge {{ $statusClasses[$item->order_status] ?? 'bg-secondary' }}">
                                {{ $item->order_status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewpurchaseOrder', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                @if(!in_array($item->order_status, ['Approved', 'Issued', 'Completed']))
                                    <a href="{{ route('editpurchaseOrder', encrypt($item->id)) }}" class="arbif-btn-edit">
                                        <i class="fas fa-pencil"></i> Edit
                                    </a>
                                @endif

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroypurchaseOrder', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to remove this purchase order?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Purchase Orders Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $data->links() }}
        </div>
    </div>
</div>

@endsection