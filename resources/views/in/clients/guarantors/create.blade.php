@extends('layouts.app')

@section('title', 'Add Guarantor')
@section('page-title', 'Add Guarantor')

@section('content')
<div class="container mt-4">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There were some issues with your submission:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add Guarantor Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('guarantors.store') }}" method="POST">
                @csrf

                <input type="hidden" name="client_id" value="{{ $client_id ?? '' }}">

                {{-- Personal Information --}}
                <h6 class="fw-bold text-secondary mb-3">Personal Information</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">National ID</label>
                        <input type="text" name="national_id" class="form-control" value="{{ old('national_id') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                </div>

                {{-- Address --}}
                <h6 class="fw-bold text-secondary mt-4 mb-3">Address</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Address Line 1</label>
                        <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country') }}">
                    </div>
                </div>

                {{-- Financial Info --}}
                <h6 class="fw-bold text-secondary mt-4 mb-3">Financial Information</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Occupation</label>
                        <input type="text" name="occupation" class="form-control" value="{{ old('occupation') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Employer</label>
                        <input type="text" name="employer" class="form-control" value="{{ old('employer') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Monthly Income (TZS)</label>
                        <input type="number" name="monthly_income" class="form-control" value="{{ old('monthly_income') }}" step="0.01">
                    </div>
                </div>

                {{-- Relationship --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Relationship to Client</label>
                        <input type="text" name="relationship_to_client" class="form-control" value="{{ old('relationship_to_client') }}">
                    </div>
                </div>

                {{-- Status --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="declined" {{ old('status') == 'declined' ? 'selected' : '' }}>Declined</option>
                        </select>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('clients.show', $client_id) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success px-4">Save Guarantor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
