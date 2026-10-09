@extends('layouts.workingside')

@section('title', 'Supplier Awards')
@section('page-title', 'Supplier Awards')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-award"></i>
        </div>
        Supplier Awards
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedsupplierAward') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Awards
        </a>
        <a href="{{ route('createsupplierAward') }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-plus me-1"></i> Register Supplier Award
        </a>
    </div>
</div>

{{-- FILTERS --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('supplierAward') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="award_status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Award Status --</option>
                    <option value="Pending" {{ request('award_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ request('award_status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('award_status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
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
                @if(request()->anyFilled(['award_status', 'supplier_id', 'from_date', 'to_date']))
                    <a href="{{ route('supplierAward') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="supplierAwardTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Award No.</th>
                        <th>Supplier</th>
                        <th>Award Date</th>
                        <th>Amount</th>
                        <th>Purchase Request</th>
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
                            <a href="{{ route('viewsupplierAward', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ $item->award_no }}
                            </a>
                        </td>
                        <td>
                            <strong>{{ $item->supplier->SupplierName ?? 'N/A' }}</strong>
                        </td>
                        <td>{{ $item->award_date ? $item->award_date->format('d M, Y') : 'N/A' }}</td>
                        <td class="fw-bold text-success">
                            {{ number_format($item->award_amount, 2) }} {{ $item->currency_code }}
                        </td>
                        <td>
                            @if($item->purchaseRequest)
                                <span class="badge bg-light text-dark border">{{ $item->purchaseRequest->request_no }}</span>
                            @else
                                <span class="text-muted small">--</span>
                            @endif
                        </td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td>
                            @php
                                $statusClasses = [
                                    'Pending'  => 'bg-warning text-dark',
                                    'Approved' => 'bg-success',
                                    'Rejected' => 'bg-danger',
                                ];
                            @endphp
                            <span class="badge {{ $statusClasses[$item->award_status] ?? 'bg-secondary' }}">
                                {{ $item->award_status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewsupplierAward', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                @if($item->award_status !== 'Approved')
                                    <a href="{{ route('editsupplierAward', encrypt($item->id)) }}" class="arbif-btn-edit">
                                        <i class="fas fa-pencil"></i> Edit
                                    </a>
                                @endif

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroysupplierAward', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to remove this supplier award?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Supplier Awards Found</td>
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