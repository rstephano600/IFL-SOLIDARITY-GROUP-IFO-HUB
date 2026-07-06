@extends('layouts.workingside')
@section('title', 'Salary Information')
@section('page-title', 'Salary Information')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-money-check-alt"></i></div>
        Salary Information Registry
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('registersalary') }}" class="arbif-btn-submit btn btn-primary btn-sm d-flex align-items-center text-decoration-none">
            <i class="fas fa-plus-circle me-1"></i> Generate Monthly Payroll
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="salaryTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Employee Details</th>
                        <th class="sortable">Month/Year</th>
                        <th class="sortable">Gross Salary</th>
                        <th class="sortable">Net Pay</th>
                        <th class="sortable">Allowances</th>
                        <th class="sortable">Deductions</th>
                        <th class="sortable">Approval</th>
                        <th class="sortable">Payment</th>
                        <th >Action</th>
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
                            <span class="arbif-badge arbif-badge-navy">
                                {{ \Carbon\Carbon::parse($data->PaidMonth)->format('F, Y') }}
                            </span>
                        </td>
                        <td><strong>{{ number_format($data->ActualGross, 2) }}</strong></td>
                        <td><strong class="text-success">{{ number_format($data->NetPay, 2) }}</strong></td>
                        <td>{{ number_format(($data->Allowance + $data->Overtime), 2) }}</td>
                        <td>
                            <span class="text-danger">
                                {{ number_format(($data->Advance + $data->Heslb + $data->Absent + $data->Paye + $data->NssfPay), 2) }}
                            </span>
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
                            <a href="{{ route('viewsalary', Crypt::encrypt($data->id)) }}" 
                            class="btn btn-sm btn-outline-primary" 
                            title="Edit Batch Breakdown">
                                <i class="fas fa-edit"></i> Edit Batch
                            </a>
                            @endif

                            @if(($data->ApprovalStatus ?? $data->AppStatus) == 'Pending')
                                <a href="{{ route('deletesalary', encrypt($item->id)) }}" class="arbif-btn-delete py-1 px-2" style="font-size: 0.8rem;" title="Drop Entry" onclick="return confirm('Permanently remove this financial record transaction?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            @endif

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="arbif-table-empty">
                            <i class="fas fa-folder-open d-block mb-2 fs-3"></i> No salary ledger records generated yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection