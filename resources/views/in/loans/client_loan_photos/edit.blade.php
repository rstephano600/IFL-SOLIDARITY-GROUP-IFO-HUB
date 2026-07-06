@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Edit Client Loan Photo</h4>

    <form action="{{ route('client-loan-photos.update', $clientLoanPhoto) }}" method="POST" enctype="multipart/form-data" class="mt-3">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="client_id" class="form-label">Client</label>
                <select name="client_id" class="form-select" required>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $clientLoanPhoto->client_id == $client->id ? 'selected' : '' }}>
                            {{ $client->first_name }} {{ $client->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="client_loan_id" class="form-label">Client Loan</label>
                <select name="client_loan_id" class="form-select">
                    <option value="">-- None --</option>
                    @foreach($loans as $loan)
                        <option value="{{ $loan->id }}" {{ $clientLoanPhoto->client_loan_id == $loan->id ? 'selected' : '' }}>
                            Loan #{{ $loan->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="photo" class="form-label">Replace Photo</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
                @if($clientLoanPhoto->photo)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $clientLoanPhoto->photo) }}" alt="Current" width="100" class="img-thumbnail">
                    </div>
                @endif
            </div>

            <div class="col-md-6 mb-3">
                <label for="date_captured" class="form-label">Date Captured</label>
                <input type="date" name="date_captured" class="form-control" value="{{ $clientLoanPhoto->date_captured }}">
            </div>

            <div class="col-md-12 mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control">{{ $clientLoanPhoto->description }}</textarea>
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('client-loan-photos.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
