@extends('layouts.workingside')

@section('title', 'Service Money Requests')
@section('page-title', 'Service Money Requests')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-hand-holding-dollar"></i>
        </div>
        Service Money Requests
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedServiceMoneyRequest') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Requests
        </a>
        <a href="{{ route('createServiceMoneyRequest') }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-plus me-1"></i> Prepare Money Request
        </a>
    </div>
</div>

{{-- FILTERS --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('serviceMoneyRequest') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="money_status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Money Statuses --</option>
                    <option value="Prepared" {{ request('money_status') == 'Prepared' ? 'selected' : '' }}>Prepared</option>
                    <option value="Approved by Finance Chairman" {{ request('money_status') == 'Approved by Finance Chairman' ? 'selected' : '' }}>Approved by Finance Chairman</option>
                    <option value="Approved for Payment" {{ request('money_status') == 'Approved for Payment' ? 'selected' : '' }}>Approved for Payment</option>
                    <option value="Paid" {{ request('money_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                    <option value="Rejected" {{ request('money_status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="finance_chairman_approval_status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Finance Approval --</option>
                    <option value="Pending" {{ request('finance_chairman_approval_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ request('finance_chairman_approval_status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('finance_chairman_approval_status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-4">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search Ref, Payee, Purpose...">
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                @if(request()->anyFilled(['money_status', 'finance_chairman_approval_status', 'search']))
                    <a href="{{ route('serviceMoneyRequest') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="serviceMoneyRequestTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Request No</th>
                        <th>Service Req Ref</th>
                        <th>Payee Name</th>
                        <th>Date</th>
                        <th>Amount Requested</th>
                        <th>Amount Approved</th>
                        <th>Finance Chair</th>
                        <th>Group Chair</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('viewServiceMoneyRequest', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ $item->money_request_no }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $item->serviceRequest->request_no ?? $item->ReferenceNo }}
                            </span>
                        </td>
                        <td><strong>{{ $item->payee_name }}</strong></td>
                        <td>{{ $item->money_request_date ? $item->money_request_date->format('d M, Y') : 'N/A' }}</td>
                        <td class="fw-bold text-primary">{{ number_format($item->amount_requested, 2) }} {{ $item->currency_code }}</td>
                        <td class="fw-bold text-success">{{ number_format($item->amount_approved, 2) }} {{ $item->currency_code }}</td>
                        <td>
                            <span class="badge {{ $item->finance_chairman_approval_status == 'Approved' ? 'bg-success' : ($item->finance_chairman_approval_status == 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ $item->finance_chairman_approval_status }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $item->group_chairman_approval_status == 'Approved' ? 'bg-success' : ($item->group_chairman_approval_status == 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ $item->group_chairman_approval_status }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $item->money_status == 'Paid' ? 'bg-success' : ($item->money_status == 'Rejected' ? 'bg-danger' : 'bg-info text-dark') }}">
                                {{ $item->money_status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewServiceMoneyRequest', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                @if($item->finance_chairman_approval_status !== 'Approved' && $item->money_status !== 'Paid')
                                    <a href="{{ route('editServiceMoneyRequest', encrypt($item->id)) }}" class="arbif-btn-edit">
                                        <i class="fas fa-pencil"></i> Edit
                                    </a>
                                @endif

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroyServiceMoneyRequest', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to soft delete this money request?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">No Service Money Requests Found</td>
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