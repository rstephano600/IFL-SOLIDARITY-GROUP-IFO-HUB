@extends('layouts.workingside')

@section('title', 'New Expense')
@section('page-title', 'New Expense Requisition')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-file-invoice-dollar"></i></div>
        New Expense Requisition
    </h3>
    <a href="{{ route('expenses') }}" class="btn btn-secondary text-white">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('storeExpense') }}" enctype="multipart/form-data">
    @csrf
    @include('in.expenses.expenses.form', ['data' => null])

    <div class="text-end mt-3">
        <a href="{{ route('expenses') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="arbif-btn-submit">Submit Requisition</button>
    </div>
</form>

@endsection