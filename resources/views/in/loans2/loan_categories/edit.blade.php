@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">✏️ Edit Loan Category: {{ $loanCategory->name }}</h1>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('loan_categories.update', $loanCategory) }}">
                @csrf
                @method('PUT')

                @include('in.loans.loan_categories.form', ['loanCategory' => $loanCategory])

                <button type="submit" class="btn btn-primary mt-4">Update Category</button>
                <a href="{{ route('loan_categories.index') }}" class="btn btn-secondary mt-4">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection