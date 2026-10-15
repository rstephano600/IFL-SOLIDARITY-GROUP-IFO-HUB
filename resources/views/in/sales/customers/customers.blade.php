@extends('layouts.workingside')

@section('title', 'Customers Directory')
@section('page-title', 'Customers Directory')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-users"></i>
        </div>
        Sales Customers
    </h3>
</div>

<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('salesCustomers') }}" class="row g-2">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search customer name, code, phone...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Search</button>
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Customer Name</th>
                        <th>Type</th>
                        <th>Phone</th>
                        <th>Total Sales (TZS)</th>
                        <th>Paid (TZS)</th>
                        <th>Outstanding Balance (TZS)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $customer)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $customer->customer_code }}</span></td>
                        <td>
                            <a href="{{ route('viewsaleCustomer', encrypt($customer->id)) }}" class="fw-bold text-decoration-none">
                                {{ $customer->customer_name }}
                            </a>
                        </td>
                        <td><span class="badge bg-secondary">{{ $customer->customer_type }}</span></td>
                        <td>{{ $customer->phone ?? 'N/A' }}</td>
                        <td class="fw-bold">{{ number_format($customer->sales_sum_total_amount ?? 0, 2) }}</td>
                        <td class="text-success">{{ number_format($customer->sales_sum_paid_amount ?? 0, 2) }}</td>
                        <td class="fw-bold text-danger fs-6">{{ number_format($customer->sales_sum_balance_amount ?? 0, 2) }}</td>
                        <td>
                            <a href="{{ route('viewsaleCustomer', encrypt($customer->id)) }}" class="arbif-btn-view">
                                <i class="fas fa-eye me-1"></i> Profile & Sales
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No customers found.</td>
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