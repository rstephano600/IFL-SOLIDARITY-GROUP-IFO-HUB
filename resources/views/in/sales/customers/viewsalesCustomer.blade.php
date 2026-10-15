@extends('layouts.workingside')

@section('title', 'View Sales Customer')
@section('page-title', 'Customer Profile')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-user-check"></i>
        </div>
        Customer Profile: {{ $data->customer_name }}
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('salesCustomer') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
        <a href="{{ route('editsalesCustomer', encrypt($data->id)) }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-pencil me-1"></i> Edit Profile
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-id-card me-1"></i> Contact & General Details</h6>

                <table class="table table-borderless align-middle">
                    <tr>
                        <th style="width: 30%;" class="text-muted">Reference No:</th>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $data->ReferenceNo }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Customer Code:</th>
                        <td class="fw-bold">{{ $data->customer_code }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Customer Type:</th>
                        <td><span class="badge bg-primary">{{ $data->customer_type }}</span></td>
                    </tr>
                    @if($data->customer_type === 'COMPANY')
                    <tr>
                        <th class="text-muted">Billing Company:</th>
                        <td class="fw-bold text-dark">{{ $data->billingCompany->company_name ?? 'N/A' }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th class="text-muted">Contact Person:</th>
                        <td>{{ $data->contact_person ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Phone Number:</th>
                        <td>{{ $data->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Email Address:</th>
                        <td>{{ $data->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Credit Limit:</th>
                        <td class="fw-bold text-success fs-6">TZS {{ number_format($data->credit_limit, 2) }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Payment Terms:</th>
                        <td>{{ $data->payment_terms_days }} Days</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Address / Notes:</th>
                        <td>{{ $data->address ?? 'No address provided.' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- SALES TRANSACTION HISTORY --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-history me-1"></i> Recent Sales History</h6>

                <div class="table-responsive">
                    <table class="table table-sm table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Sale No.</th>
                                <th>Date</th>
                                <th>Sales Type</th>
                                <th>Total (TZS)</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data->sales->take(5) as $sale)
                            <tr>
                                <td><span class="fw-bold">{{ $sale->sale_no }}</span></td>
                                <td>{{ $sale->sale_date ? $sale->sale_date->format('d M, Y') : '' }}</td>
                                <td>{{ $sale->salesType->type_name ?? 'N/A' }}</td>
                                <td>{{ number_format($sale->total_amount, 2) }}</td>
                                <td><span class="badge bg-info text-dark">{{ $sale->payment_status }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No sales history found for this customer.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-building me-1"></i> System Metadata</h6>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Status:</span>
                        <span class="badge bg-success">{{ $data->Status }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Branch:</span>
                        <span class="fw-bold">{{ $data->branch->branch_name ?? 'Global / Central' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Registered By:</span>
                        <span>{{ $data->creator->name ?? 'System' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Registered At:</span>
                        <span>{{ $data->created_at ? $data->created_at->format('d M, Y H:i') : 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Last Updated:</span>
                        <span>{{ $data->updated_at ? $data->updated_at->format('d M, Y H:i') : 'N/A' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection