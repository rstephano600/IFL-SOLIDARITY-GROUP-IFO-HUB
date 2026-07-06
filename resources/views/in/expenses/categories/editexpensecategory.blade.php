@extends('layouts.workingside')

@section('title', 'Modify Expense Category')

@section('page-title', 'Modify Expense Category')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-edit"></i>
        </div>
        Edit Category Profile: {{ $data->name }}
    </h3>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="arbif-card">
            <div class="arbif-card-body">
                <form action="{{ route('updateexpensecategory', encrypt($data->id)) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label font-weight-bold">Category Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $data->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label font-weight-bold">Detailed Ledger Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $data->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('expensecategoryinformations') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-undo me-1"></i> Abort Changes
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save me-1"></i> Update Category Meta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- AUDIT TIMELINE TRACKER INSIDE EDIT SIDE PANEL --}}
    <div class="col-md-6">
        <div class="arbif-card">
            <div class="arbif-card-body">
                <h5 class="arbif-section-title mb-3">System History Summary</h5>
                <table class="table table-bordered table-striped custom-view-table">
                    <tbody>
                        <tr>
                            <th width="40%">Owner Entity Reference</th>
                            <td>{{ optional($data->user)->name ?? 'System Admin' }}</td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td>{{ optional($data->creator)->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Last Modification Author</th>
                            <td>{{ optional($data->updater)->name ?? 'Not Modified Yet' }}</td>
                        </tr>
                        <tr>
                            <th>Operational Lifecycle State</th>
                            <td>
                                <b class="{{ $data->Status == 'Active' ? 'text-success' : 'text-danger' }}">
                                    {{ $data->Status }}
                                </b>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection