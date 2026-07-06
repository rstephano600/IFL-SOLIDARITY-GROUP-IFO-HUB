@extends('layouts.app')

@section('title', 'Donation Details: ' . $donation->donation_title)
@section('page-title', 'Donation Details')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & ACTIONS --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-gift me-2 text-danger"></i> Donation: <span class="text-primary">{{ $donation->donation_title }}</span>
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('donations.edit', $donation->id) }}" class="btn btn-warning shadow-sm">
                <i class="bi bi-pencil-square me-1"></i> Edit Donation
            </a>
            <a href="{{ route('donations.index') }}" class="btn btn-secondary shadow-sm">
                <i class="bi bi-arrow-left-circle me-1"></i> Back to List
            </a>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- MAIN DETAILS CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Donation Overview</h5>
            <span class="fs-6 fw-bold">
                Status: 
                <span class="badge bg-{{ 
                    $donation->status === 'completed' ? 'success' : 
                    ($donation->status === 'pending' ? 'warning text-dark' : 'secondary') 
                }} fw-bold">
                    {{ ucfirst($donation->status) }}
                </span>
            </span>
        </div>
        <div class="card-body">
            
            <div class="row">
                {{-- Left Column: Details --}}
                <div class="col-lg-7">
                    <dl class="row mb-0">
                        {{-- Date --}}
                        <dt class="col-sm-4 fw-bold text-dark"><i class="bi bi-calendar me-1"></i> Donation Date:</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($donation->donation_date)->format('F d, Y') }}</dd>

                        {{-- Type of Support --}}
                        <dt class="col-sm-4 fw-bold text-dark"><i class="bi bi-bookmark me-1"></i> Support Type:</dt>
                        <dd class="col-sm-8">{{ $donation->support_type ?? '—' }}</dd>
                        
                        {{-- Created By --}}
                        <dt class="col-sm-4 fw-bold text-dark"><i class="bi bi-person me-1"></i> Recorded By:</dt>
                        <dd class="col-sm-8">{{ $donation->createdBy->name ?? '—' }}</dd>
                        
                        {{-- Attachment --}}
                        @if($donation->attachment)
                        <dt class="col-sm-4 fw-bold text-dark"><i class="bi bi-paperclip me-1"></i> Attachment:</dt>
                        <dd class="col-sm-8">
                            <a href="{{ asset('storage/'.$donation->attachment) }}" target="_blank" class="text-info fw-semibold">
                                <i class="bi bi-box-arrow-up-right"></i> View File
                            </a>
                        </dd>
                        @endif
                    </dl>
                </div>
                
                {{-- Right Column: Total Amount Highlight --}}
                <div class="col-lg-5 d-flex align-items-center justify-content-end">
                    <div class="text-end bg-light p-4 rounded-3 shadow-sm border border-success border-3">
                        <h6 class="text-muted mb-1">TOTAL DONATION VALUE</h6>
                        <h1 class="display-4 fw-bold text-success mb-0">
                            {{ number_format($donation->amount, 2) }} 
                            <span class="fs-5 text-muted">{{ $donation->currency ?? 'TZS' }}</span>
                        </h1>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            
            {{-- Description --}}
            <h6 class="fw-bold text-dark mb-1"><i class="bi bi-card-text me-1"></i> Description / Notes</h6>
            <p class="text-muted border-start border-3 border-primary ps-3 pb-1">{{ $donation->description ?? 'No detailed description provided.' }}</p>

        </div>
    </div>


    {{-- ================================================================= --}}
    {{-- RECIPIENT DETAILS & ITEMS CARD (Nested Sections) --}}
    {{-- ================================================================= --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            {{-- Recipient Details --}}
            <div class="mb-4 p-3 rounded-3 border bg-light">
                <h5 class="text-danger fw-bold mb-3"><i class="bi bi-people me-2"></i> Recipient Information</h5>
                <dl class="row mb-0">
                    <dt class="col-sm-3 fw-semibold">Name:</dt>
                    <dd class="col-sm-9">{{ $donation->recipient_name }}</dd>

                    <dt class="col-sm-3 fw-semibold">Type:</dt>
                    <dd class="col-sm-9">{{ $donation->recipient_type ?? '—' }}</dd>

                    <dt class="col-sm-3 fw-semibold">Contact:</dt>
                    <dd class="col-sm-9">{{ $donation->recipient_contact ?? '—' }}</dd>
                </dl>
            </div>

            {{-- Donation Items --}}
            @if($donation->items->count())
            <h5 class="text-danger fw-bold mb-3"><i class="bi bi-list-check me-2"></i> Donation Items (Goods/Materials)</h5>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 30%;">Item Name</th>
                            <th style="width: 15%;">Quantity</th>
                            <th style="width: 15%;">Unit Value</th>
                            <th style="width: 20%;">Total Value</th>
                            <th style="width: 15%;">Currency</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($donation->items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $item->item_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-nowrap">{{ number_format($item->unit_value, 2) }}</td>
                            <td class="fw-bold text-danger text-nowrap">{{ number_format($item->total_value, 2) }}</td>
                            <td>{{ $item->currency ?? $donation->currency }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                 <div class="alert alert-light border text-muted">No detailed line items were recorded for this donation.</div>
            @endif

        </div>
    </div>
</div>
@endsection