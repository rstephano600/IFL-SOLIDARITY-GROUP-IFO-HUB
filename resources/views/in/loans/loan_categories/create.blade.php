@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4">Add Loan Category</h3>

    <form action="{{ route('loan_categories.store') }}" method="POST" class="row g-3">
        @csrf

        @include('in.loans.loan_categories.form')

        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Save Category
            </button>
            <a href="{{ route('loan_categories.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
