@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Edit Loan Request</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please correct the errors below.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('loans.update', $loan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="client_id" class="form-label">Client</label>
                        <select name="client_id" class="form-select">
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $loan->client_id == $client->id ? 'selected' : '' }}>
                                    {{ $client->first_name }} {{ $client->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="loan_category_id" class="form-label">Loan Category</label>
                        <select name="loan_category_id" class="form-select">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $loan->loan_category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Amount Requested</label>
                        <input type="number" step="0.01" name="amount_requested" class="form-control" value="{{ $loan->amount_requested }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Client Payable Frequency</label>
                        <input type="number" step="0.01" name="client_payable_frequency" class="form-control" value="{{ $loan->client_payable_frequency }}">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('loans.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
