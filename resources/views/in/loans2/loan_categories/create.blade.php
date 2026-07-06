@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">➕ Create New Loan Category</h1>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('loan_categories.store') }}">
                @csrf

                @include('in.loans.loan_categories.form', ['loanCategory' => new \App\Models\LoanCategory()])

                <button type="submit" class="btn btn-success mt-4">Create Category</button>
                <a href="{{ route('loan_categories.index') }}" class="btn btn-secondary mt-4">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection