@extends('layouts.app')
@section('title', 'Groups Centers')
@section('page-title', 'Groups Center Creation')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i> Add New Group Center</h4>
        </div>
        <div class="card-body">

            <form action="{{ route('group_centers.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    
                    {{-- Center Name --}}
                    <div class="col-md-6">
                        <label for="center_name" class="form-label fw-bold">Center Name <span class="text-danger">*</span></label>
                        <input type="text" name="center_name" id="center_name" class="form-control @error('center_name') is-invalid @enderror" 
                               value="{{ old('center_name') }}" required placeholder="e.g., Central Plaza Meeting Point">
                        @error('center_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Location --}}
                    <div class="col-md-6">
                        <label for="location" class="form-label fw-bold">Location</label>
                        <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" 
                               value="{{ old('location') }}" placeholder="e.g., Street A, Building B">
                        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Collection Officer (Dynamic Search) --}}
                    <div class="col-md-6 position-relative">
                        <label for="collection_officer_search" class="form-label fw-bold">Collection Officer</label>
                        <input type="text" id="collection_officer_search" class="form-control" placeholder="Search officer by name, email, or phone..." autocomplete="off">

                        {{-- Hidden input to store selected officer --}}
                        <input type="hidden" name="collection_officer_id" id="collection_officer_id" value="{{ old('collection_officer_id') }}">

                        {{-- Results dropdown --}}
                        <ul id="officer-results" class="list-group position-absolute w-100 shadow-sm mt-1" style="z-index: 1000; display: none;"></ul>

                        @error('collection_officer_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    
                    {{-- Status --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="Details about meeting schedules or center operations.">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('group_centers.index') }}" class="btn btn-secondary shadow-sm">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="bi bi-save me-1"></i> Save Center
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript for AJAX Search --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('collection_officer_search');
    const resultsBox = document.getElementById('officer-results');
    const hiddenInput = document.getElementById('collection_officer_id');

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 2) {
            resultsBox.style.display = 'none';
            return;
        }

        fetch(`/search-officers?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                resultsBox.innerHTML = '';
                if (data.length === 0) {
                    resultsBox.innerHTML = '<li class="list-group-item text-muted">No matches found</li>';
                } else {
                    data.forEach(officer => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item list-group-item-action';
                        li.textContent = `${officer.first_name} ${officer.last_name} (${officer.email})`;
                        li.style.cursor = 'pointer';
                        li.addEventListener('click', function() {
                            searchInput.value = `${officer.first_name} ${officer.last_name}`;
                            hiddenInput.value = officer.id;
                            resultsBox.style.display = 'none';
                        });
                        resultsBox.appendChild(li);
                    });
                }
                resultsBox.style.display = 'block';
            });
    });

    document.addEventListener('click', function(e) {
        if (!resultsBox.contains(e.target) && e.target !== searchInput) {
            resultsBox.style.display = 'none';
        }
    });
});
</script>
@endsection
