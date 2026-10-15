@extends('layouts.workingside')

@section('title', 'Edit Service Request')
@section('page-title', 'Edit Service Request Header')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-edit"></i>
        </div>
        Edit Service Request: {{ $data->request_no }}
    </h3>

    <div>
        <a href="{{ route('viewServiceRequest', encrypt($data->id)) }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Cancel & View Request
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('updateServiceRequest', encrypt($data->id)) }}">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $data->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Committee <span class="text-danger">*</span></label>
                    <select name="committee_id" class="form-select" required>
                        <option value="">-- Select Committee --</option>
                        @foreach($committees as $committee)
                            <option value="{{ $committee->id }}" {{ old('committee_id', $data->committee_id) == $committee->id ? 'selected' : '' }}>
                                {{ $committee->committee_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Committee Chairman</label>
                    <select name="committee_chairman_id" class="form-select">
                        <option value="">-- Select Chairman --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('committee_chairman_id', $data->committee_chairman_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Request Title <span class="text-danger">*</span></label>
                    <input type="text" name="request_title" class="form-control" value="{{ old('request_title', $data->request_title) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Request Date <span class="text-danger">*</span></label>
                    <input type="date" name="request_date" class="form-control" value="{{ old('request_date', $data->request_date ? $data->request_date->format('Y-m-d') : '') }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Required Date</label>
                    <input type="date" name="required_date" class="form-control" value="{{ old('required_date', $data->required_date ? $data->required_date->format('Y-m-d') : '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Priority <span class="text-danger">*</span></label>
                    <select name="priority" class="form-select" required>
                        <option value="Low" {{ old('priority', $data->priority) == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority', $data->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority', $data->priority) == 'High' ? 'selected' : '' }}>High</option>
                        <option value="Urgent" {{ old('priority', $data->priority) == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Currency <span class="text-danger">*</span></label>
                    <input type="text" name="requested_currency" class="form-control" value="{{ old('requested_currency', $data->requested_currency) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Beneficiary Name</label>
                    <input type="text" name="beneficiary_name" class="form-control" value="{{ old('beneficiary_name', $data->beneficiary_name) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Purpose / Overview</label>
                    <textarea name="purpose" class="form-control" rows="3">{{ old('purpose', $data->purpose) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('viewServiceRequest', encrypt($data->id)) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Request Header</button>
            </div>
        </form>
    </div>
</div>

@endsection