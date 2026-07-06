@extends('layouts.app')
@section('title', 'Client Management')
@section('page-title', 'Client Directory')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-primary">Client Directory <i class="bi bi-people-fill"></i></h2>
        <a href="{{ route('clients.create') }}" class="btn btn-primary shadow-sm d-flex align-items-center">
            <i class="bi bi-plus-circle me-2"></i> Add New Client
        </a>
    </div>

    {{-- Search and Filter Card (Improved Spacing and Clarity) --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('clients.index') }}" class="row g-2 align-items-center">
                
                {{-- Search Input --}}
                <div class="col-12 col-lg-4 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="form-control" placeholder="Search by name, email, or phone...">
                    </div>
                </div>
                
                {{-- Status Filter --}}
                <div class="col-12 col-lg-3 col-md-4">
                    <select name="status" class="form-select">
                        <option value="">— Filter by Status —</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>⚪ Inactive</option>
                        <option value="blacklisted" {{ request('status') == 'blacklisted' ? 'selected' : '' }}>⚫ Blacklisted</option>
                    </select>
                </div>
                
                {{-- Action Buttons --}}
                <div class="col-6 col-lg-2 col-md-1">
                    <button class="btn btn-dark w-100 d-flex align-items-center justify-content-center" type="submit">
                        <i class="bi bi-funnel me-1 d-md-none"></i>Filter
                    </button>
                </div>
                <div class="col-6 col-lg-2 col-md-2">
                    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-arrow-clockwise me-1 d-md-none"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-body p-0"> {{-- p-0 to make table full-width in card --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 10%;">Type</th>
                            <th style="width: 25%;">Full Name / Business</th>
                            <th style="width: 20%;">Contact Info</th>
                            <th class="text-center" style="width: 15%;">Loan Officer</th>
                            <th class="text-center" style="width: 10%;">Status</th>
                            <th class="text-center" style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary fw-normal">
                                        {{ ucfirst($client->client_type) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="fw-bold">{{ $client->first_name . ' ' . $client->last_name }}</div>
                                        <small class="text-muted">{{ $client->business_name ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small><i class="bi bi-envelope me-1"></i>{{ $client->email }}</small>
                                        <small><i class="bi bi-phone me-1"></i>{{ $client->phone }}</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-semibold">{{ $client->assignedLoanOfficer->first_name ?? 'N/A' }} {{ $client->assignedLoanOfficer->last_name ?? ' ' }}</span>
                                    
                                </td>
                                <td class="text-center">
                                    {{-- Dynamic Status Badge with better colors --}}
                                    @php
                                        $statusClass = [
                                            'active' => 'success',
                                            'inactive' => 'secondary',
                                            'blacklisted' => 'danger',
                                            'pending' => 'warning', // Assuming you might have 'pending'
                                        ][$client->status] ?? 'info';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('clients.show', $client->id) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-outline-warning" title="Edit Client">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('WARNING: Are you sure you want to delete client {{ $client->first_name }}? This action is irreversible.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Client">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">No clients matching your criteria were found. 🙁</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($clients->hasPages())
                <div class="card-footer bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ $clients->firstItem() }} to {{ $clients->lastItem() }} of {{ $clients->total() }} results
                        </div>
                        <div class="mt-2 mt-sm-0">
                            {{ $clients->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection