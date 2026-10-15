@extends('layouts.workingside')

@section('title', 'Edit Committee')
@section('page-title', 'Edit Committee')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-edit"></i>
        </div>
        Edit Committee: {{ $data->committee_name }}
    </h3>

    <div>
        <a href="{{ route('committee') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('updateCommittee', encrypt($data->id)) }}">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" data-searchable data-placeholder="Search branch..." required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $data->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Committee Code <span class="text-danger">*</span></label>
                    <input type="text" name="committee_code" class="form-control" value="{{ old('committee_code', $data->committee_code) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Committee Name <span class="text-danger">*</span></label>
                <input type="text" name="committee_name" class="form-control" value="{{ old('committee_name', $data->committee_name) }}" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Chairman</label>
                    <select name="chairman_user_id" class="form-select" data-searchable data-placeholder="Search chairman user...">
                        <option value="">-- Select Chairman --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('chairman_user_id', $data->chairman_user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Secretary</label>
                    <select name="secretary_user_id" class="form-select" data-searchable data-placeholder="Search secretary user...">
                        <option value="">-- Select Secretary --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('secretary_user_id', $data->secretary_user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $data->start_date ? $data->start_date->format('Y-m-d') : '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $data->end_date ? $data->end_date->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Committee Description / Purpose</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description', $data->description) }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('committee') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Committee</button>
            </div>
        </form>
    </div>
</div>

@endsection