@extends('layouts.workingside')

@section('title', 'Edit Expense')
@section('page-title', 'Edit Expense Requisition')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-pencil"></i></div>
        Edit {{ $data->expense_number }}
    </h3>
    <a href="{{ route('viewExpense', encrypt($data->id)) }}" class="btn btn-secondary text-white">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('updateExpense', encrypt($data->id)) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('in.expenses.expenses.form', ['data' => $data])

    <div class="text-end mt-3">
        <a href="{{ route('viewExpense', encrypt($data->id)) }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="arbif-btn-submit">Update Expense</button>
    </div>
</form>

@endsection