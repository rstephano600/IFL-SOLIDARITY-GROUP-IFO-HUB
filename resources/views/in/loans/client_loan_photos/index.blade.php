@extends('layouts.app')

@section('title', 'Loan Photo Records')
@section('page-title', 'Client Loan Photos')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & NEW BUTTON --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-camera-fill me-2 text-primary"></i> Client Loan Photos
        </h2>
        <a href="{{ route('client-loan-photos.create') }}" class="btn btn-primary shadow-sm fw-semibold">
            <i class="bi bi-plus-circle-fill me-2"></i> Add New Photo
        </a>
    </div>

    {{-- ================================================================= --}}
    {{-- FILTERS CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <h6 class="card-title fw-bold mb-3 text-info"><i class="bi bi-funnel me-1"></i> Filter Photos</h6>
            <form method="GET" action="{{ route('client-loan-photos.index') }}" class="row g-3 align-items-end">

                {{-- Search --}}
                <div class="col-12 col-lg-3 col-md-6">
                    <label class="form-label small fw-semibold">Search Details</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Client, loan #, or description" value="{{ request('search') }}">
                </div>

                {{-- Client Filter --}}
                <div class="col-12 col-lg-2 col-md-6">
                    <label class="form-label small fw-semibold">Client</label>
                    <select name="client_id" class="form-select form-select-sm">
                        <option value="">-- All Clients --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->first_name }} {{ $client->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Date Range --}}
                <div class="col-6 col-lg-2 col-md-4">
                    <label class="form-label small fw-semibold">Start Date</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                </div>
                
                <div class="col-6 col-lg-2 col-md-4">
                    <label class="form-label small fw-semibold">End Date</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                </div>

                {{-- Created By --}}
                <div class="col-12 col-lg-2 col-md-4">
                    <label class="form-label small fw-semibold">Uploaded By</label>
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
                    <a href="{{ route('client-loan-photos.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                </div>
            </form>
        </div>
        <div class="card-footer bg-light text-end py-2">
            <span class="fw-semibold text-muted me-3">Total Photos: <span class="text-dark">{{ $photos->total() }}</span></span>
             <a href="{{ route('client-loan-photos.index', array_merge(request()->query(), ['export' => 'csv'])) }}" class="btn btn-success btn-sm fw-semibold">
                <i class="bi bi-file-earmark-excel"></i> Export CSV
            </a>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- PHOTO TABLE --}}
    {{-- ================================================================= --}}
    @if ($photos->count())
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 15%;">Client</th>
                            <th style="width: 10%;">Loan #</th>
                            <th style="width: 10%;">Photo</th>
                            <th style="width: 30%;">Description</th>
                            <th style="width: 15%;">Date Captured</th>
                            <th style="width: 10%;">Uploaded By</th>
                            <th style="width: 5%;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($photos as $photo)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><i class="bi bi-person me-1"></i> {{ $photo->client ? $photo->client->first_name . ' ' . $photo->client->last_name : '—' }}</td>
                            <td class="fw-semibold text-primary">{{ $photo->loan ? $photo->loan->loan_number : '—' }}</td>
                            <td>
                                @if($photo->photo)
                                    <img src="{{ asset('storage/' . $photo->photo) }}" 
                                         alt="Photo" 
                                         class="rounded shadow-sm cursor-pointer" 
                                         style="width: 50px; height: 50px; object-fit: cover;" 
                                         onclick="window.open('{{ asset('storage/' . $photo->photo) }}', '_blank')">
                                @else
                                    <span class="text-muted small">No Image</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ Str::limit($photo->description, 50) ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($photo->date_captured)->format('Y-m-d') }}</td>
                            <td>{{ $photo->creator->name ?? '—' }}</td>
                            <td class="text-end text-nowrap">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('client-loan-photos.show', $photo) }}" class="btn btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('client-loan-photos.edit', $photo) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('client-loan-photos.destroy', $photo) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete" 
                                            onclick="return confirm('Are you sure you want to delete this photo record?');">
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
        @if ($photos->hasPages())
            <div class="card-footer bg-light border-top">
                <div class="d-flex justify-content-center">
                    {{ $photos->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
    @else
    <div class="alert alert-info border-0 shadow-sm text-center py-4">
        <i class="bi bi-image-alt display-5 d-block mb-3"></i>
        No loan photo records found matching the current filters.
        <br>
        <a href="{{ route('client-loan-photos.create') }}" class="btn btn-primary mt-3 fw-semibold">
            <i class="bi bi-plus-circle-fill me-2"></i> Upload the first photo
        </a>
    </div>
    @endif
</div>
@endsection