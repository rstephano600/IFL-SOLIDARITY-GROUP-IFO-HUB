@extends('layouts.app')
@section('title', 'Client Guarantors')
@section('page-title', 'Client Guarantors List')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Client Guarantors</h4>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search and Filters --}}
    <form method="GET" action="{{ route('guarantors.index') }}" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                placeholder="Search guarantor name, ID or phone">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Filter by Status</option>
                <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                <option value="declined" {{ request('status')=='declined'?'selected':'' }}>Declined</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="client_id" class="form-select">
                <option value="">Filter by Client</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ request('client_id')==$client->id?'selected':'' }}>
                        {{ $client->first_name ?? $client->business_name }} {{ $client->last_name ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Guarantor Name</th>
                        <th>Phone</th>
                        <th>Client</th>
                        <th>Status</th>
                        <th>Verified</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guarantors as $index => $g)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $g->first_name }} {{ $g->last_name }}</td>
                            <td>{{ $g->phone_number }}</td>
                            <td>{{ $g->client->first_name ?? 'N/A' }} {{ $g->client->last_name ?? '' }}</td>
                            <td>
                                <span class="badge 
                                    {{ $g->status == 'active' ? 'bg-success' : ($g->status == 'inactive' ? 'bg-secondary' : 'bg-danger') }}">
                                    {{ ucfirst($g->status) }}
                                </span>
                            </td>
                            <td>
                                {!! $g->verified 
                                    ? '<span class="badge bg-success">Yes</span>' 
                                    : '<span class="badge bg-warning text-dark">No</span>' !!}
                            </td>
                            <td>
                                <a href="{{ route('guarantors.show', $g->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('guarantors.edit', $g->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('guarantors.destroy', $g->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this guarantor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">No guarantors found</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $guarantors->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
