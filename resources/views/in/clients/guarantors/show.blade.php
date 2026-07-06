@extends('layouts.app')
@section('title', 'Guarantor Details')
@section('page-title', 'Guarantor Details')

@section('content')
<div class="container mt-4">
    <h4>Guarantor Details</h4>

    <div class="card mt-3 shadow-sm">
        <div class="card-body">
            <h5>{{ $guarantor->first_name }} {{ $guarantor->last_name }}</h5>
            <p><strong>Phone:</strong> {{ $guarantor->phone_number }}</p>
            <p><strong>Email:</strong> {{ $guarantor->email ?? 'N/A' }}</p>
            <p><strong>National ID:</strong> {{ $guarantor->national_id ?? 'N/A' }}</p>
            <p><strong>Client:</strong> {{ $guarantor->client->first_name ?? 'N/A' }} {{ $guarantor->client->last_name ?? '' }}</p>
            <p><strong>Address:</strong> {{ $guarantor->address_line1 ?? '' }}, {{ $guarantor->city ?? '' }}, {{ $guarantor->country ?? '' }}</p>
            <p><strong>Occupation:</strong> {{ $guarantor->occupation ?? 'N/A' }}</p>
            <p><strong>Employer:</strong> {{ $guarantor->employer ?? 'N/A' }}</p>
            <p><strong>Monthly Income:</strong> {{ number_format($guarantor->monthly_income ?? 0, 2) }} TZS</p>
            <p><strong>Status:</strong> <span class="badge bg-primary">{{ ucfirst($guarantor->status) }}</span></p>
            <p><strong>Verified:</strong> {{ $guarantor->verified ? 'Yes' : 'No' }}</p>
            <p><strong>Relationship to Client:</strong> {{ $guarantor->relationship_to_client ?? 'N/A' }}</p>

            <div class="mt-3">
                <a href="{{ route('guarantors.edit', $guarantor->id) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('guarantors.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
