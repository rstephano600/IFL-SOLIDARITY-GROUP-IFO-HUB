@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Create Loan Request</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were problems with your input.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('loans.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="client_id" class="form-label">Client</label>
                        <select name="client_id" class="form-select" required>
                            <option value="">-- Select Client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->first_name }} {{ $client->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="loan_category_id" class="form-label">Loan Category</label>
                        <select name="loan_category_id" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->currency }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="group_center_id" class="form-label">Group Center</label>
                        <select name="group_center_id" class="form-select">
                            <option value="">-- Select Group Center --</option>
                            @foreach($groupCenters as $center)
                                <option value="{{ $center->id }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="group_id" class="form-label">Group</label>
                        <select name="group_id" class="form-select">
                            <option value="">-- Select Group --</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Amount Requested</label>
                        <input type="number" step="0.01" name="amount_requested" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Client Payable Frequency</label>
                        <input type="number" step="0.01" name="client_payable_frequency" class="form-control" required>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">Submit Request</button>
                    <a href="{{ route('loans.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
