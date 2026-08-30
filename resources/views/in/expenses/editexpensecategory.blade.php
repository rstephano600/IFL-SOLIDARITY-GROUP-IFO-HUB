@extends('layouts.workingside')

@section('title','Edit Expense Category')
@section('page-title','Edit Expense Category')

@section('content')

<div class="arbif-card">

    <div class="arbif-card-body">

        <form method="POST"
              action="{{ route('updateexpensecategory', encrypt($data->id)) }}">

            @csrf

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Expense Code

                    </label>

                    <input type="text"
                           name="expense_code"
                           value="{{ $data->expense_code }}"
                           class="form-control"
                           required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Expense Name

                    </label>

                    <input type="text"
                           name="name"
                           value="{{ $data->name }}"
                           class="form-control"
                           required>

                </div>

                <div class="col-md-12 mb-3">

                    <label class="form-label">

                        Description

                    </label>

                    <textarea name="description"
                              rows="5"
                              class="form-control">{{ $data->description }}</textarea>

                </div>

            </div>

            <button type="submit"
                    class="arbif-btn-submit">

                Update Category

            </button>

        </form>

    </div>

</div>

@endsection