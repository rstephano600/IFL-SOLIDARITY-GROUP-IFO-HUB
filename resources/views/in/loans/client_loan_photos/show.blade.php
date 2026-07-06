@extends('layouts.app')

@section('title', 'Photo Details')
@section('page-title', 'Client Loan Photo Details')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & ACTIONS --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-image me-2 text-info"></i> Photo Record Details
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('client-loan-photos.edit', $clientLoanPhoto->id) }}" class="btn btn-warning shadow-sm">
                <i class="bi bi-pencil-square me-1"></i> Edit Photo
            </a>
            <a href="{{ route('client-loan-photos.index') }}" class="btn btn-secondary shadow-sm">
                <i class="bi bi-arrow-left-circle me-1"></i> Back to Photos
            </a>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- DETAILS CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <div class="row align-items-stretch">
                
                {{-- 📸 Left Column: Photo Preview --}}
                <div class="col-md-6 col-lg-5 mb-4 mb-md-0 d-flex justify-content-center">
                    <div class="p-3 bg-light rounded-3 w-100 h-100">
                        <h6 class="text-muted fw-bold border-bottom pb-2 mb-3"><i class="bi bi-camera me-1"></i> Visual Record</h6>
                        @if ($clientLoanPhoto->photo && file_exists(storage_path('app/public/' . $clientLoanPhoto->photo)))
                            <a href="{{ asset('storage/' . $clientLoanPhoto->photo) }}" target="_blank" title="Click to view full size">
                                <img src="{{ asset('storage/' . $clientLoanPhoto->photo) }}" 
                                    alt="Client Loan Photo" 
                                    class="img-fluid rounded shadow-sm border w-100"
                                    style="max-height: 400px; object-fit: contain;">
                            </a>
                        @else
                            <div class="alert alert-danger text-center mt-3 py-5">
                                <i class="bi bi-exclamation-triangle-fill display-4 d-block mb-2"></i>
                                **No Image Found**
                                <p class="mb-0">The file path seems broken or the image was deleted.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 🧾 Right Column: Metadata --}}
                <div class="col-md-6 col-lg-7">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-4"><i class="bi bi-file-earmark-text me-1"></i> Photo Metadata</h5>
                    
                    <dl class="row mb-0 g-3">
                        {{-- Client --}}
                        <dt class="col-sm-4 fw-bold text-dark"><i class="bi bi-person me-1"></i> Client:</dt>
                        <dd class="col-sm-8 fs-6">
                            @if ($clientLoanPhoto->client)
                                <a href="{{ route('clients.show', $clientLoanPhoto->client_id) }}" class="text-primary fw-semibold">
                                    {{ $clientLoanPhoto->client->first_name }} {{ $clientLoanPhoto->client->last_name }}
                                </a>
                            @else
                                —
                            @endif
                        </dd>

                        {{-- Loan --}}
                        <dt class="col-sm-4 fw-bold text-dark"><i class="bi bi-currency-dollar me-1"></i> Loan Reference:</dt>
                        <dd class="col-sm-8 fs-6">
                            @if ($clientLoanPhoto->loan)
                                <span class="badge bg-info fw-semibold py-2">Loan #{{ $clientLoanPhoto->loan->loan_number }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </dd>

                        {{-- Date Captured --}}
                        <dt class="col-sm-4 fw-bold text-dark border-top pt-3 mt-3"><i class="bi bi-calendar-check me-1"></i> Date Captured:</dt>
                        <dd class="col-sm-8 fs-6 border-top pt-3 mt-3">
                            {{ $clientLoanPhoto->date_captured ? \Carbon\Carbon::parse($clientLoanPhoto->date_captured)->format('F d, Y') : '—' }}
                        </dd>

                        {{-- Uploaded By --}}
                        <dt class="col-sm-4 fw-bold text-dark"><i class="bi bi-cloud-arrow-up me-1"></i> Uploaded By:</dt>
                        <dd class="col-sm-8 fs-6">{{ $clientLoanPhoto->creator->name ?? 'System' }}</dd>
                    
                        {{-- Description --}}
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-dark mb-1"><i class="bi bi-card-text me-1"></i> Description / Notes:</h6>
                            <p class="text-muted border-start border-3 border-secondary ps-3 pb-1">
                                {{ $clientLoanPhoto->description ?? 'No specific notes were added for this photo.' }}
                            </p>
                        </div>
                    </dl>
                </div>
            </div>
            
            <div class="mt-5 border-top pt-3">
                <a href="{{ route('client-loan-photos.edit', $clientLoanPhoto) }}" class="btn btn-warning me-2">
                    <i class="bi bi-pencil"></i> Edit Photo Details
                </a>
                <a href="{{ route('client-loan-photos.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-list"></i> View All Photos
                </a>
            </div>
        </div>
    </div>
</div>
@endsection