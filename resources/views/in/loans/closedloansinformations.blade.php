@extends('layouts.workingside')

@section('title', 'Closed Loans Informations')

@section('page-title', 'Closed Loans Informations')

@section('content')

<div class="arbif-page-header">

    <h3>

        <div class="page-icon">
            <i class="fas fa-hand-holding-usd"></i>
        </div>

        Closed Loans Informations

    </h3>


    <!-- <button class="arbif-btn-submit"
            data-bs-toggle="modal"
            data-bs-target="#addFormModal">

        <i class="fas fa-plus"></i>
        Register Loan

    </button> -->

</div>



<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table"
                   id="loanTable">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Loan Number</th>

                        <th>Client</th>

                        <th>Loan Category</th>

                        <th>Requested Amount</th>

                        <th>Total Repayable</th>

                        <th>Inst Frequency</th>

                        <th>Approval Status</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($data as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>

                            <span >
                                {{ $item->loan_number ?? 'N/A' }}
                            </span>

                        </td>

                        <td>
                            {{ optional($item->client)->client->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ optional($item->loanCategory)->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ number_format($item->loanCategory->amount_disbursed ?? 0, 2) }}
                        </td>

                        <td>
                            <strong>
                                {{ number_format($item->loanCategory->repayable_amount ?? 0, 2) }}
                            </strong>
                        </td>

                        <td>
                            {{ number_format($item->client_payable_frequency ?? 0, 2) }}
                        </td>

                        <td>
                            @if($item->ApprovalStatus == 'Approved')
                                <span class="arbif-badge arbif-badge-success">
                                    Approved
                                </span>
                            @elseif($item->ApprovalStatus == 'Pending')
                                <span class="arbif-badge arbif-badge-warning">
                                    Pending
                                </span>
                            @else
                                <span class="arbif-badge arbif-badge-danger">
                                    {{ $item->ApprovalStatus }}
                                </span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('viewloaninformation', encrypt($item->id)) }}"
                               class="arbif-btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                            <a href="{{ route('editloaninformation', encrypt($item->id)) }}"
                               class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i>
                                Edit
                            </a>
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="11"
                            class="text-center">

                            No Loan Informations Found

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
