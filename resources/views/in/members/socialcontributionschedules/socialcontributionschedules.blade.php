@extends('layouts.configside')
@section('title', 'Social Contribution Schedules')
@section('page-title', 'Social Contribution Schedules Management')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-hand-holding-heart"></i></div>Social Contribution Schedules</h3>
    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addSocialScheduleModal">
        <i class="fas fa-plus-circle me-1"></i> Add Schedule
    </button>
</div>

<!-- Main Datatable Index Panel -->
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="socialContributionSchedulesTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Ref No</th>
                        <th class="sortable">Contribution Amount</th>
                        <th class="sortable">Effective Period</th>
                        <th class="sortable">Description</th>
                        <th class="sortable">Company & Branch</th>
                        <th class="sortable">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->ScheduleRefNo ?? '—' }}</span></td>
                        <td><strong class="text-navy fs-6">{{ number_format($item->FeeAmount, 2) }}</strong></td>
                        <td>
                            <small class="d-block text-dark fw-bold">
                                {{ $item->EffectiveFrom ? $item->EffectiveFrom->format('d M Y') : '—' }}
                            </small>
                            <small class="text-muted">
                                to {{ $item->EffectiveTo ? $item->EffectiveTo->format('d M Y') : 'Ongoing' }}
                            </small>
                        </td>
                        <td>
                            <span class="text-dark">{{ Str::limit($item->Description ?? '—', 40) }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->company->company_name ?? '—' }}</div>
                            <small class="text-muted">{{ $item->branch->branch_name ?? '—' }}</small>
                        </td>
                        <td>
                            <span class="arbif-badge {{ $item->Status === 'Active' ? 'bg-success' : 'bg-danger' }} text-white">
                                {{ $item->Status ?? 'Active' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('viewsocialcontributionschedules', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('editsocialcontributionschedules', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> Edit
                            </a>
                            <a onclick="confirmDelete()" href="{{ route('destroysocialcontributionschedules', [encrypt($item->id)]) }}" class="arbif-btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No active social contribution schedules exist in the record.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="socialContributionSchedulesTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="socialContributionSchedulesTable"></div>
        </div>
    </div>
</div>

<!-- Social Schedule Creation Modal -->
<div class="modal fade arbif-modal" id="addSocialScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <h5 class="modal-title">Create Social Contribution Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storesocialcontributionschedules') }}" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- SECTION 1: SCHEDULE & AMOUNT CONFIGURATION -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-coins me-2"></i> 1. Schedule & Amount Configuration
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label" for="FeeAmount">Contribution Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="FeeAmount" name="FeeAmount" class="form-control" placeholder="0.00" value="{{ old('FeeAmount') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="EffectiveFrom">Effective From <span class="text-danger">*</span></label>
                            <input type="date" id="EffectiveFrom" name="EffectiveFrom" class="form-control" value="{{ old('EffectiveFrom', date('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="EffectiveTo">Effective To</label>
                            <input type="date" id="EffectiveTo" name="EffectiveTo" class="form-control" value="{{ old('EffectiveTo') }}">
                            <small class="text-muted fs-11">Leave blank if ongoing indefinitely.</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="Description">Description / Notes</label>
                            <textarea id="Description" name="Description" class="form-control" rows="2" placeholder="Brief details about this social contribution schedule...">{{ old('Description') }}</textarea>
                        </div>
                    </div>

                    <!-- SECTION 2: ORGANIZATIONAL SCOPE -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-building me-2"></i> 2. Organizational Scope
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Company Entity <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Company..." required>
                                <option></option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ old('company_id') == $comp->id ? 'selected' : '' }}>
                                        {{ $comp->company_code ?? '' }} - {{ $comp->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Operational Branch <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Branch..." required>
                                <option></option>
                                @foreach($branches as $br)
                                    <option value="{{ $br->id }}" {{ old('branch_id') == $br->id ? 'selected' : '' }}>
                                        {{ $br->branch_code ?? '' }} - {{ $br->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer shadow-sm bg-light rounded-bottom p-3" style="margin-top: 30px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Commit Schedule</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection