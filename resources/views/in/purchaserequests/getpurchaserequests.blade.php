@extends('layouts.workingside')

@section('content')

<div class="container-fluid py-4">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-cart-check me-2"></i>
                Purchase Requests
            </h4>

            <p class="text-muted mb-0">
                Manage purchase requests and procurement workflows.
            </p>
        </div>

        <div>
            <a href="{{ route('createpurchaserequest') }}"
               class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                New Purchase Request
            </a>
        </div>

    </div>


    {{-- FILTER CARD --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">
            <strong>
                <i class="bi bi-funnel me-1"></i>
                Search & Filter
            </strong>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('getpurchaserequests') }}">

                <div class="row g-3">

                    {{-- SEARCH --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Request No, purpose..."
                               value="{{ request('search') }}">

                    </div>


                    {{-- PROCUREMENT TYPE --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Procurement Type
                        </label>

                        <select name="procurement_type_id"
                                class="form-select">

                            <option value="">
                                All Procurement Types
                            </option>

                            @foreach($procurementTypes as $type)

                                <option value="{{ $type->id }}"
                                    {{ request('procurement_type_id') == $type->id ? 'selected' : '' }}>

                                    {{ $type->procurement_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- STATUS --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="Draft"
                                {{ request('status') == 'Draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="Submitted"
                                {{ request('status') == 'Submitted' ? 'selected' : '' }}>
                                Submitted
                            </option>

                            <option value="In Progress"
                                {{ request('status') == 'In Progress' ? 'selected' : '' }}>
                                In Progress
                            </option>

                            <option value="Completed"
                                {{ request('status') == 'Completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="Rejected"
                                {{ request('status') == 'Rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>

                        </select>

                    </div>


                    {{-- FROM DATE --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            From Date
                        </label>

                        <input type="date"
                               name="date_from"
                               class="form-control"
                               value="{{ request('date_from') }}">

                    </div>


                    {{-- TO DATE --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            To Date
                        </label>

                        <input type="date"
                               name="date_to"
                               class="form-control"
                               value="{{ request('date_to') }}">

                    </div>


                    {{-- BUTTONS --}}
                    <div class="col-12">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bi bi-search me-1"></i>
                            Search

                        </button>

                        <a href="{{ route('getpurchaserequests') }}"
                           class="btn btn-outline-secondary">

                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- REQUEST TABLE --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <strong>
                Purchase Request List
            </strong>

            <span class="badge bg-primary">
                {{ $purchaseRequests->total() }} Requests
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="50">
                                #
                            </th>

                            <th>
                                Request No.
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Requester
                            </th>

                            <th>
                                Procurement Type
                            </th>

                            <th>
                                Items
                            </th>

                            <th class="text-end">
                                Estimated Total
                            </th>

                            <th>
                                Current Step
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="100">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($purchaseRequests as $key => $purchaseRequest)

                            <tr>

                                <td>
                                    {{ $purchaseRequests->firstItem() + $key }}
                                </td>


                                <td>

                                    <a href="{{ route('viewpurchaserequest', encrypt($purchaseRequest->id)) }}"
                                       class="fw-bold text-decoration-none">

                                        {{ $purchaseRequest->request_no }}

                                    </a>

                                    @if($purchaseRequest->priority === 'Urgent')

                                        <span class="badge bg-danger ms-1">
                                            Urgent
                                        </span>

                                    @elseif($purchaseRequest->priority === 'High')

                                        <span class="badge bg-warning text-dark ms-1">
                                            High
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    {{ optional($purchaseRequest->request_date)->format('d/m/Y') }}

                                </td>


                                <td>

                                    {{ $purchaseRequest->requester->name
                                        ?? $purchaseRequest->requester->username
                                        ?? 'N/A' }}

                                </td>


                                <td>

                                    @php

                                        $typeClass = match($purchaseRequest->procurementType->procurement_code ?? '') {

                                            'DAILY_OPERATIONAL' => 'success',

                                            'CONSUMABLE' => 'info',

                                            'STOCK_RESALE' => 'primary',

                                            'CAPITAL_EQUIPMENT' => 'warning',

                                            'CAPITAL_ASSET' => 'danger',

                                            'SERVICE' => 'secondary',

                                            default => 'dark',

                                        };

                                    @endphp

                                    <span class="badge bg-{{ $typeClass }}">

                                        {{ $purchaseRequest->procurementType->procurement_name ?? 'N/A' }}

                                    </span>

                                </td>


                                <td>

                                    <span class="badge bg-light text-dark border">

                                        {{ $purchaseRequest->items->count() }}

                                    </span>

                                </td>


                                <td class="text-end">

                                    {{ number_format($purchaseRequest->estimated_total ?? 0, 2) }}

                                    <small class="text-muted">
                                        {{ $purchaseRequest->currency_code }}
                                    </small>

                                </td>


                                <td>

                                    @if($purchaseRequest->currentStep)

                                        <span class="badge bg-light text-dark border">

                                            {{ $purchaseRequest->currentStep->step_order }}.
                                            {{ $purchaseRequest->currentStep->step_name }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @php

                                        $statusClass = match($purchaseRequest->Status) {

                                            'Draft' => 'secondary',

                                            'Submitted' => 'primary',

                                            'In Progress' => 'warning',

                                            'Completed' => 'success',

                                            'Rejected' => 'danger',

                                            default => 'dark',

                                        };

                                    @endphp

                                    <span class="badge bg-{{ $statusClass }}">

                                        {{ $purchaseRequest->Status }}

                                    </span>

                                </td>


                                <td>

                                    <div class="dropdown">

                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown">

                                            Action

                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end">

                                            <li>

                                                <a class="dropdown-item"
                                                   href="{{ route('viewpurchaserequest', encrypt($purchaseRequest->id)) }}">

                                                    <i class="bi bi-eye me-2"></i>
                                                    View

                                                </a>

                                            </li>


                                            @if($purchaseRequest->Status === 'Draft')

                                                <li>

                                                    <a class="dropdown-item"
                                                       href="{{ route('editpurchaserequest', encrypt($purchaseRequest->id)) }}">

                                                        <i class="bi bi-pencil me-2"></i>
                                                        Edit

                                                    </a>

                                                </li>


                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>


                                                <li>

                                                    <form method="POST"
                                                          action="{{ route('submitpurchaserequest', encrypt($purchaseRequest->id)) }}">

                                                        @csrf

                                                        <button type="submit"
                                                                class="dropdown-item text-primary"
                                                                onclick="return confirm('Submit this purchase request for processing?')">

                                                            <i class="bi bi-send me-2"></i>
                                                            Submit Request

                                                        </button>

                                                    </form>

                                                </li>

                                            @endif


                                            @if(
                                                in_array($purchaseRequest->Status, ['Submitted', 'In Progress'])
                                                &&
                                                $purchaseRequest->currentStep
                                            )

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li>

                                                    <a href="{{ route('viewpurchaserequest', encrypt($purchaseRequest->id)) }}"
                                                       class="dropdown-item text-success">

                                                        <i class="bi bi-check-circle me-2"></i>
                                                        Process Workflow

                                                    </a>

                                                </li>

                                            @endif

                                        </ul>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10"
                                    class="text-center py-5">

                                    <i class="bi bi-inbox fs-1 text-muted"></i>

                                    <h6 class="mt-3">
                                        No Purchase Requests Found
                                    </h6>

                                    <p class="text-muted">
                                        There are currently no purchase requests matching your search.
                                    </p>

                                    <a href="{{ route('createpurchaserequest') }}"
                                       class="btn btn-primary">

                                        <i class="bi bi-plus-circle me-1"></i>
                                        Create Purchase Request

                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($purchaseRequests->hasPages())

            <div class="card-footer bg-white">

                {{ $purchaseRequests->links() }}

            </div>

        @endif

    </div>

</div>

@endsection