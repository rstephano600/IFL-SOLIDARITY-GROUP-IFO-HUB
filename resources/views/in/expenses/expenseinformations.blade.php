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

    <button class="arbif-btn-submit"
            data-bs-toggle="modal"
            data-bs-target="#addExpenseModal">

        <i class="fas fa-plus-circle"></i>

        Register Expense

    </button>


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

                    <td>{{ number_format($expense->amount,2) }}</td>

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

                            <i class="fa fa-eye"></i>

                        </a>

                        @can('crud-expenses')

                        <a href="{{ route('editexpense', encrypt($expense->id)) }}"
                           class="btn btn-primary btn-sm">

                            <i class="arbif-btn-edit"></i>

                        </a>

                        @endcan

                        @can(('approve-expenses') && $expense->AppStatus == 'Pending')

                        <a href="{{ route('approveexpense', encrypt($expense->id)) }}"
                           class="btn btn-success btn-sm">

                            <i class="fa fa-check"></i>

                        </a>

                        <a href="{{ route('rejectexpense', encrypt($expense->id)) }}"
                           class="btn btn-danger btn-sm">

                            <i class="fa fa-times"></i>

                        </a>

                        @endcan

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

{{-- REGISTER EXPENSE MODAL --}}

<div class="modal fade"
     id="addExpenseModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">

        <div class="modal-content">

            <form method="POST"
                  action="{{ route('storeexpense') }}"
                  enctype="multipart/form-data">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">

                        Register Expense

                    </h5>

                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                            {{-- Corrected typo variable signature from $branchies to $branches --}}
                            <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branchies as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4">

                            <label>Expense Category</label>

                            <select name="expense_category_id"
                                    data-searchable data-placeholder="Search ..."
                                    required>

                                <option value="">
                                    Select Category
                                </option>

                                @foreach($categories as $category)

                                <option value="{{ $category->id }}">

                                    {{ $category->name }}

                                </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-4">

                            <label>Expense Date</label>

                            <input type="date"
                                   name="expense_date"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="col-md-4">

                            <label>Amount</label>

                            <input type="number"
                                   step="0.01"
                                   name="amount"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="col-md-4 mt-3">

                            <label>Payment Method</label>

                            <input type="text"
                                   name="payment_method"
                                   class="form-control">

                        </div>

                        <div class="col-md-4 mt-3">

                            <label>Reference Number</label>

                            <input type="text"
                                   name="reference_number"
                                   class="form-control">

                        </div>

                        <!-- <div class="col-md-4 mt-3">

                            <label>Attachment</label>

                            <input type="file"
                                   name="attachment"
                                   class="form-control">

                        </div> -->

                        <div class="col-md-12 mt-3">

                            <label>Description</label>

                            <textarea name="descriptions"
                                      rows="4"
                                      class="form-control"></textarea>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit"
                            class="btn btn-success">

                        Save Expense

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection