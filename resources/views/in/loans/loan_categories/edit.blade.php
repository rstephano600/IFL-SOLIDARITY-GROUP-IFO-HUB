@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4">Edit Loan Category</h3>

    <form action="{{ route('loan_categories.update', $loanCategory) }}" method="POST" class="row g-3">
        @csrf
        @method('PUT')

        @include('in.loans.loan_categories.form', ['loanCategory' => $loanCategory])

        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Update
            </button>
            <a href="{{ route('loan_categories.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
