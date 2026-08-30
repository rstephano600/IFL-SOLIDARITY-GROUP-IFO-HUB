@extends('layouts.workingside')

@section('title','Expenses')
@section('page-title','Expenses Management')

@section('content')

<div class="arbif-page-header">

    <h3>

        <div class="page-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>

        Expenses Management

    </h3>


</div>

<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table"
                   id="expenseCategoryTable">

            <thead>

                <tr>

                    <th>#</th>

                    <th>Expense No</th>

                    <th>Category</th>

                    <th>Date</th>

                    <th>Amount</th>

                    <th>Status</th>

                    <th>Approval</th>

                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

                @foreach($data as $key => $expense)

                <tr>

                    <td>{{ $key + 1 }}</td>

                    <td>{{ $expense->expense_number }}</td>

                    <td>{{ $expense->category->name ?? '' }}</td>

                    <td>{{ $expense->expense_date }}</td>

                    <td>{{ number_format($expense->total_amount,2) }}</td>

                    <td>
                        <span class="badge bg-success">
                            {{ $expense->Status }}
                        </span>
                    </td>

                    <td>

                        @if($expense->AppStatus == 'Approved')

                            <span class="badge bg-success">

                                Approved

                            </span>

                        @elseif($expense->AppStatus == 'Rejected')

                            <span class="badge bg-danger">

                                Rejected

                            </span>

                        @else

                            <span class="badge bg-warning">

                                Pending

                            </span>

                        @endif

                    </td>
                    <td>

                        <a href="{{ route('viewexpense', encrypt($expense->id)) }}"
                           class="btn btn-info btn-sm">

                            <i class="fa fa-eye"></i>view

                        </a>

                        @can('approve-expenses')

                        <a href="{{ route('approveexpense', encrypt($expense->id)) }}"
                           class="btn btn-success btn-sm">

                            <i class="fa fa-check"></i> approve

                        </a>

                        @endcan

                    </td>



                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>


@endsection