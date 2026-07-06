@extends('layouts.app')
@section('title', 'Edit Loan Details: ' . $loan->loan_number)
@section('page-title', 'Edit Loan Details')

@section('content')
<div class="container mt-4">
    <h3>Edit Loan Request</h3>
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('loan_request_new_client.update', $loan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Client</label>
                        <select name="client_id" class="form-select" required>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ $loan->client_id == $client->id ? 'selected' : '' }}>
                                    {{ $client->first_name }} {{ $client->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Loan Category</label>
                        <select name="loan_category_id" class="form-select" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $loan->loan_category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('loan_request_new_client.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
