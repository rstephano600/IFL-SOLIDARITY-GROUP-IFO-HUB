@extends('layouts.app')
@section('title', 'Edit Group')
@section('page-title', 'Group Editing')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-warning text-dark py-3">
            <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Edit Group - {{ $group->group_name }}</h4>
        </div>
        <div class="card-body">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Whoops!</strong> There were some problems with your input.
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('groups.update', $group->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    
 <div class="col-md-6">
    <label for="group_center_id" class="form-label">Select Group Center</label>
    <select class="form-select" name="group_center_id" id="group_center_id" required>
        <option value="">Select Group Center</option>
        @foreach($groupCenters as $groupCenter)
            <option value="{{ $groupCenter->id }}"
                {{ old('group_center_id', $group->group_center_id) == $groupCenter->id ? 'selected' : '' }}>
                {{ $groupCenter->center_name }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-6">
    <label for="credit_officer_id" class="form-label">Select Group Officer</label>
    <select class="form-select" name="credit_officer_id" id="credit_officer_id" required>
        <option value="">Select Credit Officer</option>
        @foreach($creditOfficers as $creditOfficer)
            <option value="{{ $creditOfficer->id }}"
                {{ old('credit_officer_id', $group->credit_officer_id) == $creditOfficer->id ? 'selected' : '' }}>
                {{ $creditOfficer->first_name }} {{ $creditOfficer->last_name }}
            </option>
        @endforeach
    </select>
</div>

                    {{-- Group Name --}}
                    <div class="col-md-6">
                        <label for="group_name" class="form-label fw-bold">Group Name <span class="text-danger">*</span></label>
                        <input type="text" name="group_name" id="group_name" class="form-control @error('group_name') is-invalid @enderror" 
                               value="{{ old('group_name', $group->group_name) }}" required placeholder="e.g., Kijitonyama Youth Group">
                        @error('group_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    {{-- Group Type --}}
                    <div class="col-md-6">
                        <label for="group_type" class="form-label fw-bold">Group Type</label>
                        <input type="text" name="group_type" id="group_type" class="form-control @error('group_type') is-invalid @enderror" 
                               value="{{ old('group_type', $group->group_type) }}" placeholder="e.g., Savings Group">
                        @error('group_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    {{-- Location --}}
                    <div class="col-md-6">
                        <label for="location" class="form-label fw-bold">Location</label>
                        <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" 
                               value="{{ old('location', $group->location) }}" placeholder="e.g., Dar es Salaam, Kinondoni">
                        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    

                    {{-- Registration Date --}}
                    <div class="col-md-6">
                        <label for="registration_date" class="form-label fw-bold">Registration Date</label>
                        <input type="date" name="registration_date" id="registration_date" class="form-control @error('registration_date') is-invalid @enderror" 
                               value="{{ old('registration_date', $group->registration_date) }}">
                        @error('registration_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    {{-- Status (is_active) --}}
                    <div class="col-md-6">
                        <label for="is_active" class="form-label fw-bold">Status</label>
                        <select name="is_active" id="is_active" class="form-select @error('is_active') is-invalid @enderror">
                            <option value="1" {{ old('is_active', $group->is_active) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $group->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Provide a brief description of the group's purpose or activities.">{{ old('description', $group->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                </div>

                {{-- Form Actions --}}
                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('groups.index') }}" class="btn btn-secondary shadow-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                    <button type="submit" class="btn btn-warning shadow-sm">
                        <i class="bi bi-save me-1"></i> Update Group
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
