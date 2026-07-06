@extends('layouts.workingside')

@section('title', 'Rejected Loans Informations')

@section('page-title', 'Rejected Loans Informations')

@section('content')

<div class="arbif-page-header">

    <h3>

        <div class="page-icon">
            <i class="fas fa-hand-holding-usd"></i>
        </div>

        Rejected Loans Informations

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

                        <th class="sortable">#</th>

                        <th class="sortable">Loan Reference Number</th>

                        <th class="sortable">Client Full Name</th>

                        <th class="sortable">Loan Category</th>

                        <th class="sortable">Requested Amount</th>

                        <th class="sortable">Total Repayable</th>

                        <th class="sortable">Inst Frequency</th>

                        <th class="sortable">Current Paid</th>
                        <th class="sortable">Member Fee Paid</th>
                        <th class="sortable">Officer Fee Paid</th>
                        <th class="sortable">Insurance Fee Paid</th>
                        <th class="sortable">Preclosure Fee Paid</th>
                        <th class="sortable">Penalty Fee Paid</th>
                        <th class="sortable">Other Fee Paid</th>

                        <th class="sortable">Approval Status</th>

                        <!-- <th>Actions</th> -->

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
                            {{ number_format($item->amount_paid ?? 0, 2) }}
                        </td>
                        <td>
                            {{ number_format($item->membership_fee_paid ?? 0, 2) }}
                        </td>
                        <td>
                            {{ number_format($item->insurance_fee_paid ?? 0, 2) }}
                        </td>
                        <td>
                            {{ number_format($item->insurance_fee_paid ?? 0, 2) }}
                        </td>
                        <td>
                            {{ number_format($item->preclosure_fee_paid ?? 0, 2) }}
                        </td>
                        <td>
                            {{ number_format($item->penalty_fee_paid ?? 0, 2) }}
                        </td>
                        <td>
                            {{ number_format($item->other_fee_paid ?? 0, 2) }}
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

                        <!-- <td>
                            <a href="{{ route('approveloansinfo', encrypt($item->id)) }}"
                               class="arbif-btn-view"
                               onclick="return confirm('Are you sure you want to APPROVE this Loan Information?');">
                                <i class="fas fa-eye"></i>
                                Approve
                            </a>
                        </td> -->

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
