@extends('layouts.workingside')

@section('title', 'Deleted Money Requests')
@section('page-title', 'Deleted Service Money Requests')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-arrow-up text-danger"></i>
        </div>
        Deleted Service Money Requests
    </h3>

    <div>
        <a href="{{ route('serviceMoneyRequest') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Active Requests
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedMoneyRequestsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Request No</th>
                        <th>Service Req Ref</th>
                        <th>Payee Name</th>
                        <th>Amount Requested</th>
                        <th>Branch</th>
                        <th>Deleted Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->money_request_no }}</strong></td>
                        <td>{{ $item->serviceRequest->request_no ?? 'N/A' }}</td>
                        <td>{{ $item->payee_name }}</td>
                        <td class="fw-bold">{{ number_format($item->amount_requested, 2) }} {{ $item->currency_code }}</td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td class="small text-muted">{{ $item->deleted_at ? $item->deleted_at->format('d M, Y H:i') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restoreServiceMoneyRequest', encrypt($item->id)) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Restore this money request?')">
                                <i class="fas fa-rotate-left me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No Soft-Deleted Service Money Requests Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection