@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">✏️ Edit Loan: {{ $loan->loan_number }}</h1>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ route('loans.update', $loan) }}">
                @csrf
                @method('PUT')

                @include('in.loans.loans.form', ['loan' => $loan])

                <button type="submit" class="btn btn-primary mt-4">Update Loan</button>
                <a href="{{ route('loans.show', $loan) }}" class="btn btn-secondary mt-4">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection