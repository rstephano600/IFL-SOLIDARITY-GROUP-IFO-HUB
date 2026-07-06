@extends('layouts.workingside')
@section('title', 'Weekly Allowances')
@section('page-title', 'Weekly Allowances')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-wallet"></i></div>
        Weekly Allowance Registry
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('registerweeklyallowance') }}" class="arbif-btn-submit btn btn-primary btn-sm d-flex align-items-center text-decoration-none">
            <i class="fas fa-plus-circle me-1"></i> Generate Weekly Batch
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="allowanceTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Employee Details</th>
                        <th class="sortable">Target Period</th>
                        <th class="sortable">Allowance Amount</th>
                        <th class="sortable">Amount Paid</th>
                        <th class="sortable">Generated Date</th>
                        <th class="sortable">Approval</th>
                        <th class="sortable">Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($datas as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong class="d-block text-dark">
                                {{ $data->employee->user->FirstName ?? '' }} 
                                {{ $data->employee->user->LastName ?? '' }}
                            </strong>
                            <small class="text-muted">{{ $data->employee->EmployeeID ?? 'No ID' }}</small>
                        </td>
                        <td>
                            <div class="mb-1">
                                <span class="arbif-badge arbif-badge-navy">
                                    Week {{ $data->WeekNumber }}
                                </span>
                            </div>
                            <small class="text-muted d-block">
                                {{ \Carbon\Carbon::parse($data->AllowanceMonth)->format('F, Y') }}
                            </small>
                        </td>
                        <td><strong>{{ number_format($data->AllowanceAmount, 2) }}</strong></td>
                        <td><strong class="text-success">{{ number_format($data->AmountPaid, 2) }}</strong></td>
                        <td>
                            <small class="text-dark">
                                {{ $data->GeneratedDate ? $data->GeneratedDate->format('d M, Y') : 'N/A' }}
                            </small>
                        </td>
                        <td>
                            @if($data->ApprovalStatus == 'Approved')
                                <span class="arbif-badge arbif-badge-success"><i class="fas fa-check-circle"></i> Approved</span>
                            @elseif($data->ApprovalStatus == 'Rejected')
                                <span class="arbif-badge arbif-badge-danger"><i class="fas fa-times-circle"></i> Rejected</span>
                            @else
                                <span class="arbif-badge arbif-badge-warning"><i class="fas fa-hourglass-half"></i> Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($data->PaymentStatus == 'Paid')
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-secondary">Unpaid</span>
                            @endif
                        </td>

                        <td>
                            @if($data->ApprovalStatus == 'Approved')
                                <span class="arbif-badge arbif-badge-success"><i class="fas fa-check-circle"></i> No Action</span>
                            @elseif($data->ApprovalStatus == 'Rejected')
                                <span class="arbif-badge arbif-badge-danger"><i class="fas fa-times-circle"></i> No Action</span>
                            @else
                            <a href="{{ route('viewweeklyallowance', Crypt::encrypt($data->id)) }}" 
                            class="btn btn-sm btn-outline-primary" 
                            title="Edit Batch Breakdown">
                                <i class="fas fa-edit"></i> Edit Batch
                            </a>
                            @endif

                            @if(($data->ApprovalStatus ?? $data->AppStatus) == 'Pending')
                                <a href="{{ route('deleteweeklyallowance', encrypt($item->id)) }}" class="arbif-btn-delete py-1 px-2" style="font-size: 0.8rem;" title="Drop Entry" onclick="return confirm('Permanently remove this financial record transaction?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="arbif-table-empty">
                            <i class="fas fa-folder-open d-block mb-2 fs-3"></i> No weekly allowance distributions generated for this period.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection