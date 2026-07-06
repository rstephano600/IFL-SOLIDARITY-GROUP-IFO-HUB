@extends('layouts.app')
@section('title', 'Edit Guarantor')
@section('page-title', 'Edit Guarantor')

@section('content')
<div class="container mt-4">
    <h4>Edit Guarantor</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('guarantors.update', $guarantor->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $guarantor->first_name) }}" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $guarantor->last_name) }}" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $guarantor->phone_number) }}" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $guarantor->email) }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">National ID</label>
                        <input type="text" name="national_id" value="{{ old('national_id', $guarantor->national_id) }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Occupation</label>
                        <input type="text" name="occupation" value="{{ old('occupation', $guarantor->occupation) }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Employer</label>
                        <input type="text" name="employer" value="{{ old('employer', $guarantor->employer) }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Monthly Income</label>
                        <input type="number" name="monthly_income" step="0.01" value="{{ old('monthly_income', $guarantor->monthly_income) }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Relationship to Client</label>
                        <input type="text" name="relationship_to_client" value="{{ old('relationship_to_client', $guarantor->relationship_to_client) }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ $guarantor->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $guarantor->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="declined" {{ $guarantor->status == 'declined' ? 'selected' : '' }}>Declined</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <button class="btn btn-success">Update</button>
                    <a href="{{ route('clients.show', $guarantor->client_id) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
