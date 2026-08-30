@extends('layouts.workingside')

@section('content')

<div class="wrapper wrapper-content">

    <div class="ibox">

        <div class="ibox-title">

            <h5>Edit Expense</h5>

        </div>

        <div class="ibox-content">

            <form method="POST"
                  action="{{ route('updateexpense', encrypt($data->id)) }}"
                  enctype="multipart/form-data">

                @csrf

                <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ $data->branch_id == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                    <div class="col-md-6">

                        <label>Expense Category</label>

                        <select name="expense_category_id"
                                class="form-control">

                            @foreach($categories as $category)

                            <option value="{{ $category->id }}"
                                {{ $data->expense_category_id == $category->id ? 'selected' : '' }}>

                                {{ $category->name }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6">

                        <label>Expense Date</label>

                        <input type="date"
                               name="expense_date"
                               value="{{ $data->expense_date }}"
                               class="form-control">

                    </div>

                    <div class="col-md-6 mt-3">

                        <label>Amount</label>

                        <input type="number"
                               step="0.01"
                               name="amount"
                               value="{{ $data->amount }}"
                               class="form-control">

                    </div>

                    <div class="col-md-6 mt-3">

                        <label>Payment Method</label>

                        <input type="text"
                               name="payment_method"
                               value="{{ $data->payment_method }}"
                               class="form-control">

                    </div>

                    <div class="col-md-6 mt-3">

                        <label>Reference Number</label>

                        <input type="text"
                               name="reference_number"
                               value="{{ $data->reference_number }}"
                               class="form-control">

                    </div>

                    <!-- <div class="col-md-6 mt-3">

                        <label>Replace Attachment</label>

                        <input type="file"
                               name="attachment"
                               class="form-control">

                    </div> -->

                    <div class="col-md-12 mt-3">

                        <label>Description</label>

                        <textarea name="descriptions"
                                  class="form-control"
                                  rows="4">{{ $data->descriptions }}</textarea>

                    </div>

                </div>

                <button class="btn btn-primary mt-3">

                    Update Expense

                </button>

            </form>

        </div>

    </div>

</div>

@endsection