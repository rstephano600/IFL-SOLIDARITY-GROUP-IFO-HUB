@extends('layouts.workingside')

@section('title', 'Supplier Invoices')
@section('page-title', 'Supplier Invoices')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        Supplier Invoices
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedsupplierInvoice') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Invoices
        </a>
        <a href="{{ route('unInvoicedGoodsReceipts') }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-receipt me-1"></i> Create Invoice from GRN
        </a>
    </div>
</div>

{{-- FILTERS --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('supplierInvoice') }}" class="row g-2 align-items-center">
            <div class="col-md-2">
                <select name="invoice_status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Payment Status --</option>
                    <option value="Unpaid" {{ request('invoice_status') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="Partially Paid" {{ request('invoice_status') == 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="Paid" {{ request('invoice_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="verification_status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Verification --</option>
                    <option value="Pending" {{ request('verification_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Verified" {{ request('verification_status') == 'Verified' ? 'selected' : '' }}>Verified</option>
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

            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                @if(request()->anyFilled(['invoice_status', 'verification_status', 'supplier_id', 'from_date']))
                    <a href="{{ route('supplierInvoice') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="supplierInvoiceTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>System Inv #</th>
                        <th>Supplier Inv #</th>
                        <th>Supplier</th>
                        <th>Invoice Date</th>
                        <th>Total Amount</th>
                        <th>Outstanding</th>
                        <th>Verification</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('viewsupplierInvoice', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ $item->invoice_no }}
                            </a>
                        </td>
                        <td><strong>{{ $item->supplier_invoice_no }}</strong></td>
                        <td>{{ $item->supplier->SupplierName ?? 'N/A' }}</td>
                        <td>{{ $item->invoice_date ? $item->invoice_date->format('d M, Y') : 'N/A' }}</td>
                        <td class="fw-bold">{{ number_format($item->total_amount, 2) }} {{ $item->currency_code }}</td>
                        <td class="fw-bold text-danger">{{ number_format($item->outstanding_amount, 2) }} {{ $item->currency_code }}</td>
                        <td>
                            <span class="badge {{ $item->verification_status == 'Verified' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $item->verification_status }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusClasses = [
                                    'Unpaid' => 'bg-danger',
                                    'Partially Paid' => 'bg-warning text-dark',
                                    'Paid' => 'bg-success',
                                ];
                            @endphp
                            <span class="badge {{ $statusClasses[$item->invoice_status] ?? 'bg-secondary' }}">
                                {{ $item->invoice_status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewsupplierInvoice', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                @if($item->verification_status !== 'Verified' && $item->paid_amount == 0)
                                    <a href="{{ route('editsupplierInvoice', encrypt($item->id)) }}" class="arbif-btn-edit">
                                        <i class="fas fa-pencil"></i> Edit
                                    </a>
                                @endif

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroysupplierInvoice', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to soft delete this invoice?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No Supplier Invoices Found</td>
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