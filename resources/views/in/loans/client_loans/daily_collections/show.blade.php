@extends('layouts.app')

@section('title', 'Collection Details')
@section('page-title', 'Collection Record')

@section('content')
<div class="container py-4">

    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-primary">Collection Details <i class="bi bi-receipt"></i></h2>
    </div>

    {{-- Details Card --}}
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Payment Record #{{ $collection->id }}</h5>
            {{-- Display payment method as a badge in the header --}}
            <span class="badge bg-light text-dark fs-6">{{ ucfirst(str_replace('_', ' ', $collection->payment_method)) }}</span>
        </div>

        <div class="card-body">
            <div class="row g-4">
                {{-- Column 1: Client and Loan Info --}}
                <div class="col-md-6 border-end">
                    <h6 class="text-secondary mb-3"><i class="bi bi-person-lines-fill me-2"></i> Client & Loan Information</h6>
                    
                    <p class="mb-2">
                        <strong class="text-dark">Loan Number:</strong> 
                        <span class="ms-2 fw-bold text-info">{{ $collection->loan->loan_number ?? 'N/A' }}</span>
                    </p>
                    
                    <p class="mb-2">
                        <strong class="text-dark">Client Name:</strong> 
                        <span class="ms-2">{{ $collection->loan->client->first_name ?? 'N/A' }} {{ $collection->loan->client->last_name ?? 'N/A' }}</span>
                    </p>

                    <p class="mb-0">
                        <strong class="text-dark">Date of Payment:</strong> 
                        <span class="ms-2">{{ \Carbon\Carbon::parse($collection->date_of_payment)->format('F j, Y') }}</span>
                    </p>
                </div>

                {{-- Column 2: Financial Details --}}
                <div class="col-md-6">
                    <h6 class="text-secondary mb-3"><i class="bi bi-currency-dollar me-2"></i> Financial Summary</h6>

                    <p class="mb-2">
                        <strong class="text-dark">Amount Paid:</strong> 
                        <span class="ms-2 fs-5 text-success fw-bold">${{ number_format($collection->amount_paid, 2) }}</span>
                    </p>
                    
                    <p class="mb-2">
                        <strong class="text-dark">Penalty Fee:</strong> 
                        <span class="ms-2 text-danger">${{ number_format($collection->penalty_fee, 2) }}</span>
                    </p>

                    <p class="mb-0">
                        <strong class="text-dark">Total Preclosure Amount:</strong> 
                        <span class="ms-2">${{ number_format($collection->total_preclosure, 2) }}</span>
                    </p>
                </div>
            </div>

            {{-- Row 3: Flags/Status --}}
            <h6 class="text-secondary border-top pt-3 mt-4 mb-3"><i class="bi bi-flag me-2"></i> Payment Status Flags</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <strong class="text-dark">First Payment:</strong>
                    @if($collection->first_date_pay)
                        <span class="badge bg-success ms-2">YES</span>
                    @else
                        <span class="badge bg-secondary ms-2">NO</span>
                    @endif
                </div>
                <div class="col-md-3">
                    <strong class="text-dark">Last Payment:</strong>
                    @if($collection->last_date_pay)
                        <span class="badge bg-warning text-dark ms-2">YES</span>
                    @else
                        <span class="badge bg-secondary ms-2">NO</span>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- Card Footer for Actions --}}
        <div class="card-footer d-flex justify-content-end bg-light">
            <a href="{{ route('daily_collections.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left"></i> Back to Collections
            </a>
            {{-- Assuming you might want an edit/print option later --}}
            {{-- <a href="{{ route('daily_collections.edit', $collection->id) }}" class="btn btn-warning text-dark">
                <i class="bi bi-pencil"></i> Edit
            </a> --}}
        </div>
    </div>
</div>
@endsection