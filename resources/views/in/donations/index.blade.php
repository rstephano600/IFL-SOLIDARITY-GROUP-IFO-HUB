@extends('layouts.app')

@section('title', 'Manage Donations')
@section('page-title', 'Donations Overview')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & TOTAL SUMMARY --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-heart-fill me-2 text-danger"></i> Donation Records
        </h2>
        <a href="{{ route('donations.create') }}" class="btn btn-danger shadow-sm fw-semibold">
            <i class="bi bi-plus-circle-fill me-2"></i> Record New Donation
        </a>
    </div>

    {{-- 💰 Total Amount Summary --}}
    <div class="card bg-success-subtle shadow-sm border-0 mb-4">
        <div class="card-body py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-success fw-bold">
                <i class="bi bi-cash me-2"></i> Total Donated Amount
            </h5>
            <span class="display-6 fw-bold text-success">
                {{ number_format($totalAmount ?? 0, 2) }} <span class="fs-5 text-muted">TZS</span> 
                {{-- Assuming a default currency, adjust if $donation->currency is available globally --}}
            </span>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- FILTERS CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <h6 class="card-title fw-bold mb-3 text-primary"><i class="bi bi-funnel me-1"></i> Filter Donations</h6>
            <form method="GET" action="{{ route('donations.index') }}" class="row g-3 align-items-end">
                
                <div class="col-12 col-md-4 col-lg-3">
                    <label class="form-label small fw-semibold">Search Title/Recipient</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Title, recipient, or type" value="{{ request('search') }}">
                </div>
                
                <div class="col-6 col-md-2 col-lg-2">
                    <label class="form-label small fw-semibold">Start Date</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                </div>
                
                <div class="col-6 col-md-2 col-lg-2">
                    <label class="form-label small fw-semibold">End Date</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                </div>
                
                <div class="col-12 col-md-4 col-lg-3">
                    <label class="form-label small fw-semibold">Recorded By</label>
                    <select name="created_by" class="form-select form-select-sm">
                        <option value="">-- All Creators --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('created_by') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Action Buttons --}}
                <div class="col-6 col-lg-1 d-grid">
                    <button class="btn btn-primary btn-sm fw-semibold"><i class="bi bi-search"></i> Filter</button>
                </div>
                <div class="col-6 col-lg-1 d-grid">
                    <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-circle"></i> Reset</a>
                </div>
            </form>
        </div>
        <div class="card-footer bg-light text-end">
             <a href="{{ route('donations.index', array_merge(request()->query(), ['export' => 'csv'])) }}" class="btn btn-success btn-sm fw-semibold">
                <i class="bi bi-file-earmark-excel"></i> Export CSV
            </a>
        </div>
    </div>


    {{-- ================================================================= --}}
    {{-- DONATIONS TABLE --}}
    {{-- ================================================================= --}}
    @if ($donations->count())
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 20%;">Title</th>
                            <th style="width: 15%;">Recipient</th>
                            <th style="width: 15%;">Amount</th>
                            <th style="width: 10%;">Support Type</th>
                            <th style="width: 10%;">Date</th>
                            <th style="width: 15%;">Created By</th>
                            <th style="width: 10%;" class="text-center">Status</th>
                            <th style="width: 10%;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($donations as $donation)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $donation->donation_title }}</td>
                            <td>{{ $donation->recipient_name }}</td>
                            <td class="text-success fw-bold text-nowrap">
                                {{ number_format($donation->amount, 2) }} 
                                <span class="small text-muted">{{ $donation->currency ?? 'TZS' }}</span>
                            </td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $donation->support_type ?? '-' }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($donation->donation_date)->format('Y-m-d') }}</td>
                            <td><i class="bi bi-person me-1"></i> {{ $donation->createdBy->name ?? '—' }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $donation->status == 'completed' ? 'success' : ($donation->status == 'pending' ? 'warning text-dark' : 'secondary') }} fw-semibold">
                                    {{ ucfirst($donation->status) }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('donations.show', $donation) }}" class="btn btn-outline-info" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('donations.edit', $donation) }}" class="btn btn-outline-warning" title="Edit Donation">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('donations.destroy', $donation) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete Donation" 
                                            onclick="return confirm('Are you sure you want to delete the donation for {{ $donation->recipient_name }}?');">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($donations->hasPages())
            <div class="card-footer bg-light border-top">
                <div class="d-flex justify-content-center">
                    {{ $donations->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
    @else
    <div class="alert alert-info border-0 shadow-sm text-center py-4">
        <i class="bi bi-gift-fill display-5 d-block mb-3"></i>
        No donation records found matching the current filters.
    </div>
    @endif
</div>
@endsection