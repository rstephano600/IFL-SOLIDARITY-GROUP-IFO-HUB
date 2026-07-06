@extends('layouts.app')

@section('title', 'Collections Summary')
@section('page-title', 'Collections Summary')

@section('content')
<div class="container mt-4">

    {{-- 🔹 Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary">Collections Summary</h3>
    </div>

    {{-- 🔍 Filter Bar --}}
    <form method="GET" action="{{ route('collections.summary.index') }}" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control"
                placeholder="Search by client, officer, group, or loan number..."
                value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <select name="center_id" class="form-select">
                <option value="">-- All Centers --</option>
                @foreach ($centers as $center)
                    <option value="{{ $center->id }}" {{ request('center_id') == $center->id ? 'selected' : '' }}>
                        {{ $center->center_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <select name="filter" class="form-select">
                <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Today</option>
                <option value="yesterday" {{ request('filter') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>This Month</option>
                <option value="year" {{ request('filter') == 'year' ? 'selected' : '' }}>This Year</option>
                <option value="total" {{ request('filter') == 'total' ? 'selected' : '' }}>All Time</option>
            </select>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> Search
            </button>
        </div>

        <div class="col-md-2">
            <a href="{{ route('collections.summary.index') }}" class="btn btn-secondary w-100">
                <i class="bi bi-arrow-repeat"></i> Reset
            </a>
        </div>
    </form>

    {{-- 📊 Summary Cards --}}
    <div class="row mb-4 text-center">
        @foreach ($summary as $key => $value)
            <div class="col-md-2 col-6 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body py-3">
                        <h6 class="text-uppercase text-muted small mb-1">{{ ucfirst($key) }}</h6>
                        <h4 class="fw-bold text-success mb-0">{{ number_format($value, 2) }}</h4>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- 🧰 Export Buttons --}}
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('collections.summary.export.excel', request()->query()) }}" class="btn btn-success me-2">
            <i class="bi bi-file-earmark-excel"></i> Excel
        </a>
        <a href="{{ route('collections.summary.export.pdf', request()->query()) }}" class="btn btn-danger me-2">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
        <a href="{{ route('collections.summary.export.pdfwithnodata', request()->query()) }}" class="btn btn-danger me-2">
            <i class="bi bi-file-earmark-pdf"></i> PDF No List
        </a>
        <button type="button" onclick="window.print()" class="btn btn-secondary">
            <i class="bi bi-printer"></i> Print
        </button>
    </div>

    {{-- 🧾 Collections Table --}}
    @if ($collections->isEmpty())
        <div class="alert alert-info text-center shadow-sm">
            No collections found for this selection.
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Loan Number</th>
                                <th>Client</th>
                                <th>Group</th>
                                <th>Center</th>
                                <th>Officer</th>
                                <th>Paid Date</th>
                                <th>Total Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($collections as $index => $c)
                                <tr>
                                    <td>{{ $collections->firstItem() + $index }}</td>
                                    <td>{{ $c->loan?->loan_number }}</td>
                                    <td>{{ $c->loan?->client?->first_name }} {{ $c->loan?->client?->last_name }}</td>
                                    <td>{{ $c->loan?->group?->group_name }}</td>
                                    <td>{{ $c->loan?->group?->groupCenter?->center_name }}</td>
                                    <td>{{ $c->loan?->collectionOfficer?->first_name }} {{ $c->loan?->collectionOfficer?->last_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($c->paid_date)->format('d M Y') }}</td>
                                    <td class="fw-semibold text-end text-success">
                                        {{ number_format($c->principal_paid + $c->penalty_paid , 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3 px-3">
                    {{ $collections->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
