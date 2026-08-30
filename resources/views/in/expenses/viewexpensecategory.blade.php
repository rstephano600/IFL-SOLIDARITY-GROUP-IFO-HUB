@extends('layouts.workingside')

@section('title','View Expense Category')
@section('page-title','View Expense Category')

@section('content')

<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="row">

            <div class="col-md-6 mb-4">

                <strong>Expense Code</strong>

                <br>

                {{ $data->expense_code }}

            </div>

            <div class="col-md-6 mb-4">

                <strong>Expense Name</strong>

                <br>

                {{ $data->name }}

            </div>

            <div class="col-md-12 mb-4">

                <strong>Description</strong>

                <br>

                {{ $data->description }}

            </div>

            <div class="col-md-6 mb-4">

                <strong>Status</strong>

                <br>

                {{ $data->Status }}

            </div>

            <div class="col-md-6 mb-4">

                <strong>Created At</strong>

                <br>

                {{ $data->created_at }}

            </div>

        </div>

        <a href="{{ route('expensecategories') }}"
           class="arbif-btn-cancel">

            Back

        </a>

    </div>

</div>

@endsection