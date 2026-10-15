@extends('layouts.configside')

@section('title', 'Expense Category Profile - ' . $data->expense_code)
@section('page-title', 'Expense Category Details')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-folder-open"></i>
        </div>
        Category: {{ $data->name }}
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('expensecategoryinformations') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Categories
        </a>
        <a href="{{ route('editexpensecategory', encrypt($data->id)) }}" class="arbif-btn-edit text-decoration-none">
            <i class="fas fa-pencil me-1"></i> Edit Category
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- CATEGORY DETAILS CARD --}}
    <div class="col-md-8">
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                    <i class="fas fa-circle-info me-1"></i> Category Overview
                </h6>

                <div class="row g-3 small">
                    <div class="col-md-6">
                        <strong>Expense Category Code:</strong> <br>
                        <span class="arbif-badge arbif-badge-navy mt-1 fs-6">{{ $data->expense_code }}</span>
                    </div>

                    <div class="col-md-6">
                        <strong>Category Name:</strong> <br>
                        <span class="fw-bold text-dark fs-6">{{ $data->name }}</span>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="p-3 bg-light border rounded">
                            <strong>Description / Notes:</strong> <br>
                            <p class="mb-0 text-muted mt-1">{{ $data->description ?? 'No specific description provided for this category.' }}</p>
                        </div>
                    </div>
                </div>

                {{-- EXPENSES LISTING UNDER THIS CATEGORY --}}
                <h6 class="fw-bold text-primary mt-4 mb-3 border-bottom pb-2">
                    <i class="fas fa-receipt me-1"></i> Expenses Under This Category ({{ $data->expenses ? $data->expenses->count() : 0 }})
                </h6>

                <div class="arbif-table-wrap">
                    <table class="arbif-table" id="categoryExpensesTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Expense Ref</th>
                                <th>Title / Description</th>
                                <th>Expense Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data->expenses as $index => $expense)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-bold text-primary">{{ $expense->expense_ref_no ?? $expense->ReferenceNo }}</span>
                                </td>
                                <td>{{ Str::limit($expense->title ?? $expense->description, 40, '...') }}</td>
                                <td>{{ $expense->expense_date ? $expense->expense_date->format('d M, Y') : ($expense->created_at ? $expense->created_at->format('d M, Y') : 'N/A') }}</td>
                                <td class="fw-bold text-success">{{ number_format($expense->amount ?? $expense->total_amount ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge {{ $expense->Status == 'Approved' ? 'bg-success' : ($expense->Status == 'Pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                        {{ $expense->Status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No expenses recorded under this category yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- SYSTEM & AUDIT SIDEBAR --}}
    <div class="col-md-4">
        <div class="arbif-card mb-3">
            <div class="arbif-card-body p-3">
                <h6 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="fas fa-shield-halved me-1"></i> Status Information</h6>
                
                <div class="mb-2">
                    <small class="text-muted d-block">System Status</small>
                    @if($data->Status === 'Active')
                        <span class="badge bg-success">Active</span>
                    @elseif($data->Status === 'Inactive')
                        <span class="badge bg-warning text-dark">Inactive</span>
                    @else
                        <span class="badge bg-danger">{{ $data->Status }}</span>
                    @endif
                </div>

                <div class="mb-2">
                    <small class="text-muted d-block">Auditing Status</small>
                    <span class="badge bg-info text-dark">{{ $data->AuditingStatus ?? 'Pending' }}</span>
                </div>

                <div>
                    <small class="text-muted d-block">Report Status</small>
                    <span class="badge bg-secondary">{{ $data->ReportStatus ?? 'Show' }}</span>
                </div>
            </div>
        </div>

        <div class="arbif-card">
            <div class="arbif-card-body p-3">
                <h6 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="fas fa-history me-1"></i> Audit Trail</h6>
                
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2">
                        <i class="fas fa-user-plus me-1 text-muted"></i>
                        <strong>Registered By:</strong> <br>
                        <span class="ps-3 text-muted">{{ $data->creator->name ?? $data->user->name ?? 'N/A' }}</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calendar-plus me-1 text-muted"></i>
                        <strong>Registered At:</strong> <br>
                        <span class="ps-3 text-muted">{{ $data->created_at ? $data->created_at->format('d M, Y H:i A') : 'N/A' }}</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-user-pen me-1 text-muted"></i>
                        <strong>Last Modified By:</strong> <br>
                        <span class="ps-3 text-muted">{{ $data->updater->name ?? 'N/A' }}</span>
                    </li>
                    <li>
                        <i class="fas fa-calendar-check me-1 text-muted"></i>
                        <strong>Last Modified At:</strong> <br>
                        <span class="ps-3 text-muted">{{ $data->updated_at ? $data->updated_at->format('d M, Y H:i A') : 'N/A' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof DataTable !== 'undefined') {
            new DataTable('#categoryExpensesTable', {
                perPage: 10,
                searchable: true,
                sortable: true
            });
        }
    });
</script>
@endpush