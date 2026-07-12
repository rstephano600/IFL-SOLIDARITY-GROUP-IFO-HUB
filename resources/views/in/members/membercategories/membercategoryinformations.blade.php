@extends('layouts.configside')
@section('title', 'Member Categories')
@section('page-title', 'Member Categories')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-tags"></i></div>Member Categories</h3>
    <button class="arbif-btn-submit" onclick="openCreateForm()"><i class="fas fa-plus"></i> Add Category</button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="memberCategoryTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Category Code</th>
                        <th class="sortable">Category Name</th>
                        <th class="sortable">Company</th>
                        <th class="sortable">Branch</th>
                        <th class="sortable">Voting Right</th>
                        <th class="sortable">Loan Eligibility</th>
                        <th class="sortable">Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($membercategories as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->member_category_code }}</span></td>
                        <td>{{ $item->member_category_name }}</td>
                        <td>{{ $item->company->company_name ?? '—' }}</td>
                        <td>{{ $item->branch->branch_name ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $item->voting_right == 'Yes' || $item->voting_right == 1 ? 'bg-success' : 'bg-secondary' }}">
                                {{ $item->voting_right ?? 'No' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $item->loan_eligibility == 'Yes' || $item->loan_eligibility == 1 ? 'bg-success' : 'bg-secondary' }}">
                                {{ $item->loan_eligibility ?? 'No' }}
                            </span>
                        </td>
                        <td>{{ $item->createdBy->name ?? ($item->user->name ?? '—') }}</td>
                        <td>
                            <a href="{{ route('viewmembercategoryinformations', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-eye"></i> View </a>
                            <a href="{{ route('editmembercategoryinformations', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                            <a onclick="confirmDelete()" href="{{ route('deletemembercategoryinformations', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No member categories found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="memberCategoryTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="memberCategoryTable"></div>
        </div>
    </div>
</div>

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-tags"></i></div>
                <h5 class="modal-title">Create New Member Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storemembercategoryinformations') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Assign Company <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Corporate Context..." required>
                                <option></option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ old('company_id') == $comp->id ? 'selected' : '' }}>
                                        {{ $comp->company_code }} - {{ $comp->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Assign Branch <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Operational Branch Location..." required>
                                <option></option>
                                @foreach($branches as $br)
                                    <option value="{{ $br->id }}" {{ old('branch_id') == $br->id ? 'selected' : '' }}>
                                        {{ $br->branch_code ?? $br->company_code }} - {{ $br->branch_name ?? $br->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="member_category_code">Category Code <span class="text-danger">*</span></label>
                            <input type="text" id="member_category_code" name="member_category_code" class="form-control" placeholder="e.g. CAT-PREM" value="{{ old('member_category_code') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="member_category_name">Category Name <span class="text-danger">*</span></label>
                            <input type="text" id="member_category_name" name="member_category_name" class="form-control" placeholder="e.g. Premium Shareholder" value="{{ old('member_category_name') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="voting_right">Has Voting Rights? <span class="text-danger">*</span></label>
                            <select class="form-select" id="voting_right" name="voting_right" data-searchable data-placeholder="Select Voting Rights..." required>
                                <option></option>
                                <option value="Voting" >Voting</option>
                                <option value="Voting subject to constitution">Voting subject to constitution</option>
                                <option value="Voting through representative">Voting through representative</option>
                                <option value="Non voting">Non voting</option>
                                <option value="Suspended">Suspended</option>
                                <option value="None">None</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="loan_eligibility">Eligible for Loans? <span class="text-danger">*</span></label>
                            <select class="form-select" id="loan_eligibility" name="loan_eligibility" data-searchable data-placeholder="Select eligibility..." required>
                                <option></option>
                                <option value="Eligible">Eligible</option>
                                <option value="Not eligible unless approved">Not eligible unless approved</option>
                                <option value="Not eligible">Not eligible</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="description">Category Scope Privileges / Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Describe the constraints, requirements, and policy definitions unique to this category scope...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="margin-top: 20px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Save Category</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection