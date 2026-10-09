@extends('layouts.workingside')

@section('title', 'Purchase Requests')
@section('page-title', 'Purchase Requests')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        Purchase Requests
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedpurchaseRequest') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Requests
        </a>
        <a href="{{ route('createpurchaseRequest') }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-plus me-1"></i> Create Purchase Request
        </a>
    </div>
</div>

{{-- FILTERS --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('purchaseRequest') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Statuses --</option>
                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Pending Approval" {{ request('status') == 'Pending Approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="procurement_type_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Procurement Type --</option>
                    @foreach($procurementTypes as $type)
                        <option value="{{ $type->id }}" {{ request('procurement_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->procurement_name }}
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
                @if(request()->anyFilled(['status', 'procurement_type_id', 'from_date', 'to_date']))
                    <a href="{{ route('purchaseRequest') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="purchaseRequestTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>PR No.</th>
                        <th>Requester</th>
                        <th>Procurement Type</th>
                        <th>Date</th>
                        <th>Priority</th>
                        <th>Est. Total (TZS)</th>
                        <th>Current Step</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('viewpurchaseRequest', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ $item->request_no }}
                            </a>
                        </td>
                        <td>{{ $item->requester->name ?? 'N/A' }}</td>
                        <td>{{ $item->procurementType->procurement_name ?? 'N/A' }}</td>
                        <td>{{ $item->request_date ? $item->request_date->format('d M, Y') : 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $item->priority == 'High' || $item->priority == 'Urgent' ? 'bg-danger' : ($item->priority == 'Medium' ? 'bg-warning text-dark' : 'bg-info text-dark') }}">
                                {{ $item->priority }}
                            </span>
                        </td>
                        <td class="fw-bold">{{ number_format($item->estimated_total, 2) }}</td>
                        <td>
                            @if($item->currentStep)
                                <span class="badge bg-secondary"><i class="fas fa-layer-group me-1"></i>{{ $item->currentStep->step_name }}</span>
                            @else
                                <span class="text-muted small">--</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClasses = [
                                    'Draft' => 'bg-secondary',
                                    'Pending Approval' => 'bg-warning text-dark',
                                    'Approved' => 'bg-success',
                                    'Rejected' => 'bg-danger',
                                    'Cancelled' => 'bg-dark',
                                ];
                            @endphp
                            <span class="badge {{ $statusClasses[$item->Status] ?? 'bg-secondary' }}">
                                {{ $item->Status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewpurchaseRequest', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                @if(in_array($item->Status, ['Draft', 'Rejected']))
                                    <a href="{{ route('editpurchaseRequest', encrypt($item->id)) }}" class="arbif-btn-edit">
                                        <i class="fas fa-pencil"></i> Edit
                                    </a>
                                @endif

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroypurchaseRequest', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to delete this purchase request?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No Purchase Requests Found</td>
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