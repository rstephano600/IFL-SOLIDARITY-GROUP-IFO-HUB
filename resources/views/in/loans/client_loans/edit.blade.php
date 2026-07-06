@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Client Loan</h2>

    <form method="POST" action="{{ route('client_loans.update', $clientLoan) }}">
        @csrf
        @method('PUT')
        @include('in.loans.client_loans.form', ['mode' => 'edit'])
        <button type="submit" class="btn btn-primary">Update Loan</button>
        <a href="{{ route('client_loans.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection